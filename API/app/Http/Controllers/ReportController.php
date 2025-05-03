<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = ['status' => 200];
        try {
            $reports = Report::latest()->paginate(10);
            $result['data'] = $reports;
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
                'invoice_number' => 'required|string|max:255|unique:reports',
                'unit' => 'required|numeric|min:0',
                'cash_on_hand' => 'required|numeric|min:0',
                'cash_on_bank' => 'required|numeric|min:0',
                'date' => 'required|date',
                'remark' => 'nullable|string|max:255',
            ]);

            $report = Report::create($validated);
            $result['data'] = $report;
            $result['message'] = 'Report created successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while creating the report.';
        }
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        $result = ['status' => 200];
        try {
            $result['data'] = $report;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        $result = ['status' => 200];
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'invoice_number' => 'required|string|max:255|unique:reports,invoice_number,' . $report->id,
                'unit' => 'required|numeric|min:0',
                'cash_on_hand' => 'required|numeric|min:0',
                'cash_on_bank' => 'required|numeric|min:0',
                'date' => 'required|date',
                'remark' => 'nullable|string|max:255',
            ]);

            $report->update($validated);
            $result['data'] = $report;
            $result['message'] = 'Report updated successfully!';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $result['status'] = 422;
            $result['errors'] = $e->errors();
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while updating the report.';
        }
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $result = ['status' => 200];
        try {
            $report->delete();
            $result['message'] = 'Report deleted successfully!';
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'An error occurred while deleting the report.';
        }
        return response()->json($result);
    }
}
