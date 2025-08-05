<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstallmentController extends Controller
{

    public function syncInstallments()
    {
        $result = ['status' => 200];

        try {
            $sales = Sale::where('contract_type', 'installment')->get();

            $created = 0;
            $skipped = 0;

            foreach ($sales as $sale) {
                if ($sale->installments()->exists()) {
                    $skipped++;
                    continue;
                }

                // Duration in months (default to 1 if missing)
                $durationInMonths = 1;
                if (!empty($sale->duration)) {
                    preg_match('/\d+/', $sale->duration, $matches);
                    if (isset($matches[0])) {
                        $durationInMonths = max(1, (int)$matches[0]);
                    }
                }

                // Get predefined monthly install price
                $monthlyFee = $sale->install_price ?? 0;

                // Calculate total installment amount
                $totalInstallmentAmount = $monthlyFee * $durationInMonths;

                // Create installment
                Installment::create([
                    'sale_id'        => $sale->id,
                    'customer_id'    => $sale->customer_id,
                    'deposit'        => $sale->deposit ?? 0,
                    'sale_date'      => $sale->date,
                    'total_price'    => $sale->price,
                    'monthly_fee'    => $monthlyFee,
                    'duration'       => $durationInMonths,
                    'seller_id'      => auth()->id() ?? 1,
                    'paid_amount'    => 0,
                    'total_installment_amount' => $totalInstallmentAmount, // optional
                ]);

                $created++;
            }

            $result['message'] = "✅ Installments synced: {$created} created, {$skipped} skipped.";
        } catch (\Throwable $e) {
            Log::error('Sync Installments Error: ' . $e->getMessage());
            $result['status'] = 500;
            $result['message'] = '❌ Error syncing installments.';
            $result['error'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit', 15);

            // Fetch installments with related sale, customer, and seller
            $query = Installment::with(['sale.products', 'customer', 'seller'])
                ->whereHas('sale', function ($q) {
                    $q->where('contract_type', 'installment'); // Only fetch installment sales
                });

            // Search filter
            if ($request->filled('search')) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->whereHas('sale', function ($q2) use ($search) {
                        $q2->where('model', 'like', "%{$search}%");
                    })->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
                });
            }

            // Paginate results
            $installments = $query->paginate($limit);

            return response()->json([
                'status' => 200,
                'data' => [
                    'data' => $installments->items(),
                    'current_page' => $installments->currentPage(),
                    'per_page' => $installments->perPage(),
                    'last_page' => $installments->lastPage(),
                    'total' => $installments->total(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Fetch Installments Error: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Failed to fetch installments',
            ], 500);
        }
    }


    public function payments($id)
    {
        try {
            $installment = Installment::with(['payments' => function ($query) {
                $query->orderBy('created_at', 'desc'); // newest first
            }])->findOrFail($id);

            return response()->json([
                'status' => 200,
                'data' => $installment->payments,
            ]);
        } catch (\Throwable $e) {
            Log::error('Fetch Payment History Error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Failed to fetch payment history',
            ], 500);
        }
    }


    /**
     * Store a newly created installment.
     */
    public function store(Request $request)
    {
        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'sale_id'      => 'required|exists:sales,id',
                'customer_id'  => 'required|exists:customers,id',
                'deposit'      => 'required|numeric|min:0',
                'sale_date'    => 'required|date',
                'total_price'  => 'required|numeric|min:0',
                'monthly_fee'  => 'required|numeric|min:0',
                'seller_id'    => 'required|exists:users,id',
                'paid_amount'  => 'nullable|numeric|min:0',
            ]);

            $sale = Sale::findOrFail($validated['sale_id']);

            // Check contract type
            if ($sale->contract_type !== 'installment') {
                return response()->json([
                    'status' => 400,
                    'message' => 'Installments can only be created for sales with contract_type "installment".',
                ], 400);
            }

            // Prevent duplicate installment for the same sale
            if ($sale->installments()->exists()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Installment already exists for this sale.',
                ], 400);
            }

            $installment = Installment::create($validated);

            $result['message'] = 'Installment created successfully';
            $result['data'] = $installment->load('sale.products', 'customer', 'seller');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            Log::error('Installment store error: ' . $e->getMessage());
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the installment.';
        }

        return response()->json($result);
    }

    /**
     * Display a specific installment.
     */
    public function show(Installment $installment)
    {
        try {
            $installment->load('sale.products', 'customer', 'seller');

            return response()->json([
                'status' => 200,
                'data' => $installment,
            ]);
        } catch (\Throwable $e) {
            Log::error('Show Installment error: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Failed to fetch the installment',
            ], 500);
        }
    }

    /**
     * Update an installment.
     */
    public function update(Request $request, Installment $installment)
    {
        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'deposit'      => 'sometimes|required|numeric|min:0',
                'sale_date'    => 'sometimes|required|date',
                'total_price'  => 'sometimes|required|numeric|min:0',
                'monthly_fee'  => 'sometimes|required|numeric|min:0',
                'seller_id'    => 'sometimes|required|exists:users,id',
                'paid_amount'  => 'nullable|numeric|min:0',
            ]);

            $installment->update($validated);

            $result['message'] = 'Installment updated successfully';
            $result['data'] = $installment->load('sale.products', 'customer', 'seller');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            Log::error('Installment update error: ' . $e->getMessage());
            $result['status'] = 500;
            $result['message'] = 'An error occurred while updating the installment.';
        }

        return response()->json($result);
    }

    /**
     * Delete an installment.
     */
    public function destroy(Installment $installment)
    {
        try {
            $installment->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Installment deleted successfully',
            ]);
        } catch (\Throwable $e) {
            Log::error('Installment delete error: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Failed to delete the installment',
            ], 500);
        }
    }
}
