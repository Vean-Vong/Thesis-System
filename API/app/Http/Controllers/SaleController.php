<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = ['status' => 200];

        try {
            $sales = Sale::latest()->paginate(10);
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
            $validated = $request->validate([
                'model' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'date' => 'required|date',
                'duration' => 'sometimes|string|max:255',
                'warranty' => 'sometimes|string|max:255',
                'seller' => 'required|string|max:255',
                'contract_type' => 'sometimes|string|in:purchase,installment',

            ]);

            $sale = Sale::create($validated);
            $result['data'] = $sale;
            $result['message'] = 'Sale created successfully!';
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
        $result['status'] = 200;

        try {
            $result['data'] = $sale;
        } catch (\Throwable $e) {
            $result['status'] = 500; // Use 500 for server errors
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
                'discount' => 'sometimes|numeric|min:0',
                'date' => 'sometimes|date',
                'duration' => 'sometimes|string|max:255',
                'warranty' => 'sometimes|string|max:255',
                'seller' => 'sometimes|string|max:255',
                'contract_type' => 'sometimes|string|in:purchase,installment',
            ]);


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
