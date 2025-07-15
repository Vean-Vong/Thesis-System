<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result['status'] = 200;

        try {
            $query = Employee::query();

            // Apply search filter
            if ($request->has('search') && $request->search !== null) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('position', 'like', "%$search%");
                });
            }

            $perPage = $request->input('limit', 15);
            $employees = $query->latest()->paginate($perPage);

            $result['data'] = $employees;
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
        $result['status'] = 200;

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'gender' => 'required|in:Male,Female,Other',
                'position' => 'required|string|max:255',
                'email' => 'nullable|email|unique:employees,email',
                'phone' => 'nullable|string|max:20',
                'address' => 'required|string|max:255',
                'hire_date' => 'required|date',
                'date_of_birth' => 'required|date',
            ]);

            $employee = Employee::create($validated);
            $result['data'] = $employee;
            $result['message'] = 'Employee created successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500; // Use 500 for server errors
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $result['status'] = 200;

        try {
            $result['data'] = $employee;
        } catch (\Throwable $e) {
            $result['status'] = 500; // Use 500 for server errors
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $result['status'] = 200;

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'gender' => 'required|in:Male,Female,Other',
                'position' => 'required|string|max:255',
                'email' => 'nullable|email|unique:employees,email,' . $employee->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'required|string|max:255',
                'hire_date' => 'required|date',
                'date_of_birth' => 'required|date',
            ]);

            $employee->update($validated);
            $result['data'] = $employee;
            $result['message'] = 'Employee updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $result['status'] = 200;

        try {
            $employee->delete();
            $result['message'] = 'Employee deleted successfully!';
        } catch (\Throwable $e) {
            $result['status'] = 500; // Use 500 for server errors
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }
}
