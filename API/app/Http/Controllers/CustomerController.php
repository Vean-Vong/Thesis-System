<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result['status'] = 200;

        try {
            $query = Customer::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('job', 'like', "%$search%");
                });
            }

            $perPage = $request->input('limit', 15);
            $customers = $query->latest()->paginate($perPage);

            $result['data'] = $customers;
        } catch (Throwable $e) {
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
        $result['status'] = 200;

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'required|string|max:255',
                'date' => 'required|date',
                'job' => 'required|string|max:255'
            ]);

            $result['data'] = Customer::create($validated);
            $result['message'] = 'Customer created successfully!';
        } catch (ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // Load sales with related products and seller
        $customer->load(
            'sales.products',
            'sales.seller',
            'rentals.products',

        );

        return response()->json([
            'status' => 200,
            'data' => $customer,
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $result['status'] = 200;

        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'required|string|max:255',
                'date' => 'required|date',
                'job' => 'required|string|max:255'
            ]);

            $customer->update($validated);
            $result['data'] = $customer;
            $result['message'] = 'Customer updated successfully!';
        } catch (ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $result['status'] = 200;

        try {
            $customer->delete();
            $result['message'] = 'Customer deleted successfully!';
        } catch (Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
