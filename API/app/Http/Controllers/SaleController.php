<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Stock;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = ['status' => 200];

        try {
            $query = Sale::query();

            //  Add search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('model', 'like', "%$search%")
                        ->orWhere('seller', 'like', "%$search%")
                        ->orWhere('contract_type', 'like', "%$search%")
                        ->orWhere('date', 'like', "%$search%")
                        ->orWhere('duration', 'like', "%$search%")
                        ->orWhere('warranty', 'like', "%$search%");
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = ['status' => 200];

        try {
            // Validate sale and products data
            $validatedSale = $request->validate([
                'model'         => 'required|string|max:255',
                'price'         => 'required|numeric|min:0',
                'deposit'       => 'nullable|numeric|min:0',
                'discount'      => 'nullable|numeric|min:0',
                'date'          => 'required|date',
                'duration'      => 'sometimes|string|max:255',
                'warranty'      => 'sometimes|string|max:255',
                'seller'        => 'required|string|max:255',
                'contract_type' => 'sometimes|string|in:purchase,installment',
                'customer_id'   => 'nullable|exists:customers,id',
                'customer'      => 'sometimes|array',
                'customer.name' => 'required_with:customer|string|max:255',
                'customer.phone' => 'nullable|string|max:20',
                'customer.address' => 'required_with:customer|string|max:255',
                'customer.date' => 'required_with:customer|date',
                'customer.job'  => 'required_with:customer|string|max:255',

                // New validation for products array
                'products'              => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity'   => 'required|integer|min:1',
                'products.*.price'      => 'required|numeric|min:0',
            ]);

            // Create customer if needed
            if (empty($validatedSale['customer_id']) && isset($validatedSale['customer'])) {
                $customer = Customer::create($validatedSale['customer']);
                $validatedSale['customer_id'] = $customer->id;
            }

            // Calculate sub_total based on price and discount
            $discount = $validatedSale['discount'] ?? 0;
            $subTotal = $validatedSale['price'] - $discount;
            $validatedSale['sub_total'] = $subTotal;

            // Create sale
            $sale = Sale::create($validatedSale);

            // Attach products to pivot table with quantity and price
            $products = [];
            foreach ($validatedSale['products'] as $product) {
                $products[$product['product_id']] = [
                    'quantity' => $product['quantity'],
                    'price'    => $product['price'],
                ];
            }
            $sale->products()->sync($products);

            $result['data'] = $sale->load('products', 'customer');
            $result['message'] = 'Sale created successfully with products!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            Log::error('Sale store error: ' . $e->getMessage());
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the sale.';
        }

        return response()->json($result);
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
}
