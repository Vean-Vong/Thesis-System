<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result['status'] = 200;

        try {
            $rentals = Rental::latest()->paginate(10);
            $result['data'] = $rentals;
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
                'model' => 'required|string',
                'price' => 'required|string',
                'discount' => 'nullable|string',
                'date' => 'required|date',
                'duration' => 'nullable|string',
                'warranty' => 'nullable|string',
                'seller' => 'required|string',
                'contract_type' => 'required|string|in:rental',
            ]);


            $rental = Rental::create($validated);
            $result['data'] = $rental;
            $result['message'] = 'Rental created successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the rental.';
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        $result['status'] = 200;

        try {
            $result['data'] = $rental;
        } catch (\Throwable $e) {
            $result['status'] = 500; // Use 500 for server errors
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        $result['status'] = 200;

        try {
            $validated = $request->validate([
                'model' => 'required|string',
                'price' => 'required|string',
                'discount' => 'nullable|string',
                'date' => 'required|date',
                'duration' => 'nullable|string',
                'warranty' => 'nullable|string',
                'seller' => 'required|string',
                'contract_type' => 'required|string|in:rental',
            ]);


            $rental->update($validated);
            $result['data'] = $rental;
            $result['message'] = 'Rental updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the Rental.';
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        $result = ['status' => 200];

        try {
            $rental->delete();
            $result['message'] = 'Stock deleted successfully!';
        } catch (\Throwable $e) {
            Log::error('Stock delete error: ' . $e->getMessage());
            $result['status'] = 500;
            $result['message'] = 'An error occurred while deleting the Stock.';
        }

        return response()->json($result);
    }
}
