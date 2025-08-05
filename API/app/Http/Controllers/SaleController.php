<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = ['status' => 200];

        try {
            $query = Sale::with(['customer', 'products', 'seller']);

            if ($request->filled('search')) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('model', 'like', "%{$search}%")
                        ->orWhere('seller', 'like', "%{$search}%")
                        ->orWhere('contract_type', 'like', "%{$search}%")
                        ->orWhere('invoice_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            }

            $perPage = $request->input('limit', 15);
            $sales = $query->latest()->paginate($perPage);

            $result['data'] = $sales;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }


    public function report(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $groupBy = $request->group_by ?? 'day';

        $dateFormat = match ($groupBy) {
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m-%d',
        };

        try {
            // 📊 Total sales
            $sales = \App\Models\Sale::selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period, SUM(price) as total_sales")
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('period')
                ->get()
                ->keyBy('period');

            // 💰 Total installments
            $installments = \App\Models\Installment::selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period, SUM(monthly_fee) as total_installment")
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('period')
                ->get()
                ->keyBy('period');

            // 📦 Total rentals
            $rentals = \App\Models\Rental::selectRaw("DATE_FORMAT(date, '{$dateFormat}') as period, SUM(price) as total_rentals")
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy('period')
                ->get()
                ->keyBy('period');

            // Combine all data
            $result = [];

            // Merge sales and installments
            foreach (
                array_unique(array_merge(
                    $sales->keys()->toArray(),
                    $installments->keys()->toArray(),
                    $rentals->keys()->toArray()
                )) as $period
            ) {
                $saleAmount = $sales[$period]->total_sales ?? 0;
                $installmentAmount = $installments[$period]->total_installment ?? 0;
                $rentalAmount = $rentals[$period]->total_rentals ?? 0;

                $result[$period] = [
                    'total_sales'        => $saleAmount,
                    'total_installment'  => $installmentAmount,
                    'total_rentals'      => $rentalAmount,
                    'grand_total'        => $saleAmount + $installmentAmount + $rentalAmount,
                ];
            }

            return response()->json([
                'status' => 200,
                'data'   => $result,
            ]);
        } catch (\Throwable $e) {
            Log::error('Report generation error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Failed to generate report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function installReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        try {
            $installments = \App\Models\Installment::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as period, SUM(monthly_fee) as total_installment")
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('period')
                ->get()
                ->keyBy('period');

            $result = [];

            foreach ($installments as $period => $row) {
                $result[$period] = [
                    'total_installment' => $row->total_installment,
                ];
            }

            return response()->json([
                'status' => 200,
                'data' => collect($result)->sortKeys(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Installment Report generation error: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Failed to generate installment report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function rentalReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        try {
            $rentals = \App\Models\Rental::selectRaw("DATE_FORMAT(date, '%Y-%m-%d') as period, SUM(price) as total_rentals")
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy('period')
                ->get()
                ->keyBy('period');

            $result = [];

            foreach ($rentals as $period => $row) {
                $result[$period] = [
                    'total_rentals' => $row->total_rentals,
                ];
            }

            return response()->json([
                'status' => 200,
                'data' => collect($result)->sortKeys(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Rental Report generation error: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Failed to generate rental report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function newSetupDetails(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $contractType = $request->query('contract_type'); // <- NEW

        if ($startDate) $startDate = Carbon::parse($startDate)->startOfDay();
        if ($endDate) $endDate = Carbon::parse($endDate)->endOfDay();

        $salesQuery = \App\Models\Sale::with(['customer', 'products']);
        $rentalsQuery = \App\Models\Rental::with('customer');
        $installmentsQuery = \App\Models\Installment::with(['sale.customer', 'sale.products']);

        if ($startDate && $endDate) {
            $salesQuery->whereBetween('created_at', [$startDate, $endDate]);
            $rentalsQuery->whereBetween('date', [$startDate, $endDate]);
            $installmentsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // 🔍 Filter by contract_type
        if ($contractType) {
            $salesQuery->where('contract_type', $contractType);
            $installmentsQuery->whereHas('sale', function ($query) use ($contractType) {
                $query->where('contract_type', $contractType);
            });
        }

        $sales = $salesQuery->get()->map(function ($sale) {
            return [
                'type' => 'Purchase',
                'invoice_number' => $sale->invoice_number,
                'customer_name' => $sale->customer->name ?? '-',
                'model' => $sale->model,
                'price' => $sale->price,
                'quantity' => $sale->products->sum('pivot.quantity'),
                'date' => $sale->created_at->format('Y-m-d'),
            ];
        });

        $rentals = $rentalsQuery->get()->map(function ($rental) {
            return [
                'type' => 'Rental',
                'invoice_number' => $rental->invoice_number,
                'customer_name' => $rental->customer->name ?? '-',
                'model' => $rental->model,
                'price' => $rental->price,
                'quantity' => $rental->quantity,
                'date' => $rental->date,
            ];
        });

        $installments = $installmentsQuery->get()->map(function ($inst) {
            return [
                'type' => 'Installment',
                'invoice_number' => $inst->sale->invoice_number ?? '-',
                'customer_name' => $inst->sale->customer->name ?? '-',
                'model' => $inst->sale->model ?? '-',
                'price' => $inst->total_price,
                'quantity' => $inst->sale->products->sum('pivot.quantity') ?? 1,
                'date' => $inst->created_at->format('Y-m-d'),
            ];
        });

        $result = $sales->concat($rentals)->concat($installments);

        return response()->json([
            'status' => 200,
            'data' => $result->sortByDesc('date')->values(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $request->validate([
            'model'         => 'nullable|string|max:255',
            'price'         => 'nullable|numeric|min:0',
            'deposit'       => 'nullable|numeric|min:0',
            'discount'      => 'nullable|numeric|min:0|max:100',
            'date'          => 'nullable|date',
            'duration'      => 'nullable|string|max:255',
            'warranty'      => 'nullable|string|max:255',
            'contract_type' => 'required|string|in:purchase,installment',
            'seller'        => 'nullable|string|max:255',
            'customer_id'   => 'nullable|exists:customers,id',
            'customer'      => 'nullable|array',
            'customer.name' => 'required_without:customer_id|string|max:255',
            'customer.phone' => 'nullable|string|max:50',
            'customer.address' => 'required_without:customer_id|string|max:500',
            'customer.job'   => 'nullable|string|max:255',
            'products'      => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create or get customer
            $customerId = $request->input('customer_id');
            if (!$customerId) {
                $customerData = $request->input('customer');
                $customerData['date'] = $request->input('date'); // Add date
                $customer = Customer::create($customerData);
                $customerId = $customer->id;
            }

            // ✅ Generate unique invoice number
            $datePrefix = now()->format('Ymd'); // e.g., 20240716
            $latestInvoice = Sale::where('invoice_number', 'like', $datePrefix . '%')
                ->orderBy('invoice_number', 'desc')
                ->lockForUpdate() // prevent duplicates on concurrent requests
                ->first();

            if ($latestInvoice) {
                // Extract last 3 digits and increment
                $lastSequence = (int)substr($latestInvoice->invoice_number, -3);
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }

            $invoiceNumber =  $datePrefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            // Prepare Sale data
            $saleData = [
                'invoice_number' => $invoiceNumber,
                'customer_id'    => $customerId,
                'model'          => $request->input('model'),
                'price'          => $request->input('price'),
                'deposit'        => $request->input('deposit'),
                'discount'       => $request->input('discount', 0),
                'date'           => $request->input('date'),
                'duration'       => $request->input('duration'),
                'warranty'       => $request->input('warranty'),
                'contract_type'  => $request->input('contract_type'),
                'seller'         => $request->input('seller'),
            ];

            // Create Sale
            $sale = Sale::create($saleData);

            // Attach products to sale
            foreach ($request->input('products') as $product) {
                $sale->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price'    => $product['price'],
                ]);
            }

            // Automatically create Installment if contract_type is installment
            if ($sale->contract_type === 'installment') {
                // Calculate monthly fee
                $durationInMonths = 1;
                if (!empty($sale->duration)) {
                    preg_match('/\d+/', $sale->duration, $matches);
                    if (isset($matches[0])) {
                        $durationInMonths = max(1, (int)$matches[0]); // avoid div by 0
                    }
                }

                $monthlyFee = round(
                    ($sale->price - $sale->deposit) / $durationInMonths,
                    2
                );

                // Create Installment
                Installment::create([
                    'sale_id'      => $sale->id,
                    'customer_id'  => $sale->customer_id,
                    'deposit'      => $sale->deposit,
                    'sale_date'    => $sale->date,
                    'total_price'  => $sale->price,
                    'monthly_fee'  => $monthlyFee,
                    'seller_id'    => auth()->id() ?? 1, // fallback if no auth
                    'paid_amount'  => 0,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Sale created successfully',
                'data'    => $sale,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 500,
                'message' => 'Error creating sale: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $result = ['status' => 200];
        try {
            // Eager load customer, stock, and products (pivot)
            $sale->load(['customer', 'stock', 'products']);
            if ($sale->products->isEmpty()) {
                $sale->setRelation('products', []);
            }


            // Return sale including products pivot data
            $result['data'] = $sale;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {

        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'model' => 'sometimes|string|max:255',
                'price' => 'sometimes|numeric|min:0',
                'deposit' => 'nullable|numeric|min:0',
                'discount' => 'sometimes|numeric|min:0',
                'sub_total' => 'required|numeric|min:0',
                'date' => 'sometimes|date',
                'duration' => 'sometimes|string|max:255',
                'warranty' => 'sometimes|string|max:255',
                'seller' => 'sometimes|string|max:255',
                'contract_type' => 'sometimes|string|in:purchase,installment',
            ]);

            // 🔥 Recalculate sub_total if price or discount changes
            if (isset($validated['price']) || isset($validated['discount'])) {
                $price = $validated['price'] ?? $sale->price;
                $discount = $validated['discount'] ?? $sale->discount;
                $validated['sub_total'] = $price - $discount;
            }

            $sale->update($validated);
            $result['data'] = $sale;
            $result['message'] = 'Sale updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
            Log::error('Validation Error:', $e->errors()); // Log validation errors
        } catch (\Throwable $e) {
            Log::error('Sale update error: ' . $e->getMessage());
            $result['status'] = 500;
            $result['message'] = 'An error occurred while updating the sale.';
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $result = ['status' => 200];

        try {
            $sale->delete();
            $result['message'] = 'Sale deleted successfully!';
        } catch (\Throwable $e) {
            Log::error('Sale delete error: ' . $e->getMessage());
            $result['status'] = 500;
            $result['message'] = 'An error occurred while deleting the sale.';
        }

        return response()->json($result);
    }


    public function salesWithInstallments(Request $request)
    {
        try {
            $limit = $request->input('limit', 15);

            $query = \App\Models\Sale::with(['products', 'customer', 'installments'])
                ->whereHas('installments')
                ->where('contract_type', 'installment'); // add this line to filter

            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('model', 'like', "%{$search}%");
            }

            $sales = $query->paginate($limit);

            return response()->json([
                'status' => 200,
                'data' => $sales,
            ]);
        } catch (\Throwable $e) {
            Log::error('SalesWithInstallments Error: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Failed to fetch sales with installments.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
