<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = ['status' => 200];

        try {
            $query = Setup::query();

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%$search%")
                        ->orWhere('model', 'like', "%$search%")
                        ->orWhere('invoice_number', 'like', "%$search%")
                        ->orWhere('unit', 'like', "%$search%")
                        ->orWhere('remark', 'like', "%$search%");
                });
            }

            $perPage = $request->input('per_page', 15);
            $setups = $query->latest()->paginate($perPage);

            $result['data'] = $setups;
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
                'customer_name' => 'required|string|max:255',
                'model' => 'nullable|string|max:255',
                'invoice_number' => 'required|string|max:255|unique:reports',
                'unit' => 'required|numeric:min:0',
                'cash_on_hand' => 'required|numeric:min:0',
                'cash_on_bank' => 'required|numeric:min:0',
                'date' => 'required|date',
                'remark' => 'nullable|string|max:255',
            ]);

            $setup = Setup::create($validated);
            $result['data'] = $setup;
            $result['message'] = 'Setup created successfully!';
        } catch (\Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Setup $setup)
    {
        $result = ['status' => 200];
        try {
            $result['data'] = $setup;
        } catch (\Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setup $setup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setup $setup)
    {
        $result = ['status' => 200];
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'model' => 'nullable|string|max:255',
                'invoice_number' => 'required|string|max:255|unique:reports',
                'unit' => 'required|numeric|min:0',
                'cash_on_hand' => 'required|numeric|min:0',
                'cash_on_bank' => 'required|numeric|min:0',
                'date' => 'required|date',
                'remark' => 'nullable|string|max:255',
            ]);

            $setup->update($validated);
            $result['data'] = $setup;
            $result['message'] = 'Setup updated successfully!';
        } catch (\Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setup $setup)
    {
        $result = ['status' => 200];
        try {
            $setup->delete();
            $result['message'] = 'Setup deleted successfully!';
        } catch (\Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}