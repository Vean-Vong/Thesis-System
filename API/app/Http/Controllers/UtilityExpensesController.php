<?php

namespace App\Http\Controllers;

use App\Models\Utility_expenses;
use Illuminate\Http\Request;

class UtilityExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = ['status' => 200];

        try {
            $query = Utility_expenses::query();

            // Search filter example (adjust fields as needed)
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('type', 'like', "%$search%")
                        ->orWhere('unit_rate', 'like', "%$search%");
                    // Add more fields if needed
                });
            }

            // Use limit parameter or default to 10
            $perPage = $request->input('limit', 10);

            // Pagination
            $utility_expenses = $query->latest()->paginate($perPage);

            $result['data'] = $utility_expenses;
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
                'type' => 'required|in:Water,Electricity,Garbage',
                'bill_date' => 'required|date',
                'usage_amount' => 'required|numeric|min:0',
                'cost' => 'required|numeric|min:0',
                'unit_rate' => 'nullable|numeric|min:0',
            ]);

            $utility_expense = Utility_expenses::create($validated);
            $result['data'] = $utility_expense;
            $result['message'] = 'Utility expense created successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the utility expense.';
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $result = ['status' => 200];

        try {
            $utility_expense = Utility_expenses::findOrFail($id);
            $result['data'] = $utility_expense;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Utility_expenses $utility_expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'type' => 'required|in:Water,Electricity,Garbage',
                'bill_date' => 'required|date',
                'usage_amount' => 'required|numeric|min:0',
                'cost' => 'required|numeric|min:0',
                'unit_rate' => 'nullable|numeric|min:0',
            ]);

            $utility_expenses = Utility_expenses::findOrFail($id);
            $utility_expenses->update($validated);

            $result['data'] = $utility_expenses;
            $result['message'] = 'Utility expense updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while updating the utility expense.';
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $result = ['status' => 200];

        try {
            $utility_expense = Utility_expenses::findOrFail($id);
            $utility_expense->delete();
            $result['message'] = 'Utility expense deleted successfully!';
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while deleting the utility expense.';
        }

        return response()->json($result);
    }
}
