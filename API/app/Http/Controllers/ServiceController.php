<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = ['status' => 200];

        try {
            $services = Service::latest()->paginate(10);
            $result['data'] = $services;
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
                'price' => 'required|numeric|min:0',
                'date' => 'required|date',
                'duration' => 'sometimes|string|max:255',
                'warranty' => 'sometimes|string|max:255',
                'seller' => 'required|string|max:255',
                'contract_type' => 'sometimes|string|in:Monthly Fees,Yearly Fees',

            ]);

            $service = Service::create($validated);
            $result['data'] = $service;
            $result['message'] = 'Service created successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the service.';
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $result['status'] = 200;

        try {
            $result['data'] = $service;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $result = ['status' => 200];

        try {
            $validated = $request->validate([
                'model' => 'sometimes|string|max:255',
                'price' => 'sometimes|numeric|min:0',
                'date' => 'sometimes|date',
                'duration' => 'sometimes|string|max:255',
                'warranty' => 'sometimes|string|max:255',
                'seller' => 'sometimes|string|max:255',
                'contract_type' => 'sometimes|string|in:Monthly Fees,Yearly Fees',

            ]);

            $service->update($validated);
            $result['data'] = $service;
            $result['message'] = 'Service updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while updating the service.';
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $result = ['status' => 200];

        try {
            $service->delete();
            $result['message'] = 'Service deleted successfully!';
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while deleting the service.';
        }

        return response()->json($result);
    }
}
