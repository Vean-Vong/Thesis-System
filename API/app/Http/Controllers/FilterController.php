<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = ['status' => 200];

        try {
            $filter = Filter::latest()->paginate(10);
            $result['data'] = $filter;
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
                'sediment' => 'required|integer|min:0',
                'pre_carbon' => 'required|integer|min:0',
                'uf_membrane' => 'required|integer|min:0',
                'post_carbon' => 'required|integer|min:0',
                'date' => 'sometimes|date',
            ]);

            $filter = Filter::create($validated);
            $result['data'] = $filter;
            $result['message'] = 'Filter created successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the filter.';
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Filter $filter)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $filter;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while retrieving the filter.';
        }

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filter $filter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filter $filter)
    {
        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'sediment' => 'sometimes|integer|min:0',
                'pre_carbon' => 'sometimes|integer|min:0',
                'uf_membrane' => 'sometimes|integer|min:0',
                'post_carbon' => 'sometimes|integer|min:0',
                'date' => 'sometimes|date',
            ]);

            $filter->update($validated);
            $result['data'] = $filter;
            $result['message'] = 'Filter updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while updating the filter.';
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filter $filter)
    {
        $result = ['status' => 200];

        try {
            $filter->delete();
            $result['message'] = 'Filter deleted successfully!';
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while deleting the filter.';
        }

        return response()->json($result);
    }
}
