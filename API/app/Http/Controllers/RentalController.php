<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RentalController extends Controller
{
    /**
     * Display a listing of rentals.
     */
    public function index(Request $request)
    {
        $result = ['status' => 200];

        try {
            $query = Rental::with(['customer', 'products', 'sale']);

            // 🔍 Apply search filter
            if ($request->filled('search')) {
                $search = $request->input('search');
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
            $rentals = $query->latest()->paginate($perPage);

            // Add deposit from sale relation into each rental item
            $rentals->getCollection()->transform(function ($rental) {
                $rental->deposit = $rental->deposit ?? ($rental->sale->deposit ?? 0);
                return $rental;
            });


            $result['data'] = $rentals;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
            Log::error('Rental index error: ' . $e->getMessage());
        }

        return response()->json($result);
    }


    public function payments(Rental $rental)
    {
        $payments = $rental->payments()->latest()->get();

        return response()->json([
            'status' => 200,
            'data' => $payments,
        ]);
    }

    /**
     * Store a newly created rental.
     */
    public function store(Request $request)
    {
        $request->validate([
            'model'          => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'deposit'        => 'nullable|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0|max:100',
            'date'           => 'required|date',
            'duration'       => 'nullable|string|max:255',
            'warranty'       => 'nullable|string|max:255',
            'contract_type'  => 'required|string|in:rental',
            'seller'         => 'required|string|max:255',
            'customer_id'    => 'nullable|exists:customers,id',
            'customer'       => 'nullable|array',
            'customer.name'  => 'required_without:customer_id|string|max:255',
            'customer.phone' => 'nullable|string|max:50',
            'customer.address' => 'required_without:customer_id|string|max:500',
            'customer.job'     => 'required_without:customer_id|string|max:255',
            'products'       => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
            'products.*.price'      => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create or get customer
            $customerId = $request->input('customer_id');
            if (!$customerId) {
                $customerData = $request->input('customer');
                $customerData['date'] = $request->input('date');
                $customer = Customer::create($customerData);
                $customerId = $customer->id;
            }

            // ✅ Generate unique invoice number
            $datePrefix = now()->format('Ymd'); // e.g., 20250720
            $latestInvoice = Rental::where('invoice_number', 'like', $datePrefix . '%')
                ->orderBy('invoice_number', 'desc')
                ->lockForUpdate()
                ->first();

            $nextSequence = $latestInvoice
                ? ((int)substr($latestInvoice->invoice_number, -3) + 1)
                : 1;

            $invoiceNumber = $datePrefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            // Prepare Rental data
            $rentalData = [
                'invoice_number' => $invoiceNumber,
                'customer_id'    => $customerId,
                'model'          => $request->input('model'),
                'price'          => $request->input('price'),
                'deposit'        => $request->input('deposit', 0),
                'discount'       => $request->input('discount', 0),
                'date'           => $request->input('date'),
                'duration'       => $request->input('duration'),
                'warranty'       => $request->input('warranty'),
                'contract_type'  => $request->input('contract_type'),
                'seller'         => $request->input('seller'),
            ];

            // Create Rental
            $rental = Rental::create($rentalData);

            // Attach products to rental
            foreach ($request->input('products') as $product) {
                $rental->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'rental_price' => $product['rental_price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Rental created successfully',
                'data'    => $rental,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Rental creation error: ' . $e->getMessage());

            return response()->json([
                'status'  => 500,
                'message' => 'Error creating rental: ' . $e->getMessage(),
            ], 500);
        }
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
            $rentals = Rental::selectRaw("DATE_FORMAT(date, '{$dateFormat}') as period, SUM(price) as total_rentals")
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy('period')
                ->orderBy('period')
                ->get()
                ->keyBy('period');

            $result = [];

            foreach ($rentals as $period => $rental) {
                $result[$period] = [
                    'total_rentals' => $rental->total_rentals,
                ];
            }

            return response()->json([
                'status' => 200,
                'data' => $result,
            ]);
        } catch (\Throwable $e) {
            Log::error('Rental report error: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Failed to generate rental report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified rental.
     */
    public function show(Rental $rental)
    {
        $result = ['status' => 200];

        try {
            $rental->load(['customer', 'products', 'payments']);
            $result['data'] = $rental;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
            Log::error('Rental show error: ' . $e->getMessage());
        }

        return response()->json($result);
    }

    /**
     * Update the specified rental.
     */
    public function update(Request $request, Rental $rental)
    {
        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'model'         => 'sometimes|string|max:255',
                'price'         => 'sometimes|numeric|min:0',
                'deposit'       => 'nullable|numeric|min:0',
                'discount'      => 'sometimes|numeric|min:0|max:100',
                'date'          => 'sometimes|date',
                'duration'      => 'nullable|string|max:255',
                'warranty'      => 'nullable|string|max:255',
                'contract_type' => 'sometimes|string|in:rental',
                'seller'        => 'sometimes|string|max:255',
            ]);

            $rental->update($validated);
            $result['data'] = $rental;
            $result['message'] = 'Rental updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
            Log::error('Rental validation error: ', $e->errors());
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'Error updating rental: ' . $e->getMessage();
            Log::error('Rental update error: ' . $e->getMessage());
        }

        return response()->json($result);
    }

    /**
     * Remove the specified rental.
     */
    public function destroy(Rental $rental)
    {
        $result = ['status' => 200];

        try {
            $rental->delete();
            $result['message'] = 'Rental deleted successfully!';
        } catch (\Throwable $e) {
            Log::error('Rental delete error: ' . $e->getMessage());
            $result['status'] = 500;
            $result['message'] = 'An error occurred while deleting the rental.';
        }

        return response()->json($result);
    }
}
