<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = ['status' => 200];

        try {
            $query = Stock::query();

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('model', 'like', "%$search%")
                    ->orWhere('supplier', 'like', "%$search%");
                // Add other searchable fields as necessary
            }

            $perPage = $request->input('limit', 10);

            $stocks = $query->latest()->paginate($perPage);

            $result['data'] = $stocks;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                'colors' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'date' => 'required|date',
                'supplier' => 'required|string|max:255',
            ]);

            $stock = Stock::create($validated);
            $result['data'] = $stock;
            $result['message'] = 'Stock created successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the stock.';
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        $result['status'] = 200;

        try {
            $result['data'] = $stock;
        } catch (\Throwable $e) {
            $result['status'] = 500; // Use 500 for server errors
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $result['status'] = 200;

        try {
            $validated = $request->validate([
                'model' => 'required|string|max:255',
                'colors' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'date' => 'required|date',
                'supplier' => 'required|string|max:255',
            ]);

            $stock->update($validated);
            $result['data'] = $stock;
            $result['message'] = 'Stock updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the stock.';
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        $result = ['status' => 200];

        try {
            $stock->delete();
            $result['message'] = 'Stock deleted successfully!';
        } catch (\Throwable $e) {
            Log::error('Stock delete error: ' . $e->getMessage());
            $result['status'] = 500;
            $result['message'] = 'An error occurred while deleting the Stock.';
        }

        return response()->json($result);
    }
}
