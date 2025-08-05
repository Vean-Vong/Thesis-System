<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{


    public function combinedReport(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $groupBy = $request->input('group_by', 'day'); // day/month/year

        // Define date format for grouping
        switch ($groupBy) {
            case 'month':
                $dateFormat = '%Y-%m';
                break;
            case 'year':
                $dateFormat = '%Y';
                break;
            case 'day':
            default:
                $dateFormat = '%Y-%m-%d';
                break;
        }

        // Fetch sales grouped by date and customer
        $sales = DB::table('sales')
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period,
                         COUNT(*) as total_sales,
                         SUM(total_price) as total_sales_amount,
                         customer_id")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period', 'customer_id')
            ->get();

        // Fetch installments grouped by date and customer
        $installments = DB::table('installments')
            ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period,
                         SUM(monthly_fee) as total_installment_amount,
                         customer_id")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period', 'customer_id')
            ->get();

        // Index installments by period+customer_id for quick lookup
        $installmentIndex = [];
        foreach ($installments as $install) {
            $key = $install->period . '_' . $install->customer_id;
            $installmentIndex[$key] = $install->total_installment_amount;
        }

        // Fetch customer names once
        $customerIds = collect($sales)->pluck('customer_id')->merge(collect($installments)->pluck('customer_id'))->unique();
        $customers = Customer::whereIn('id', $customerIds)->pluck('name', 'id');

        $report = [];

        foreach ($sales as $sale) {
            $key = $sale->period . '_' . $sale->customer_id;
            $installmentAmount = $installmentIndex[$key] ?? 0;

            $report[] = [
                'period' => $sale->period,
                'customer_name' => $customers[$sale->customer_id] ?? 'Unknown',
                'total_sales' => $sale->total_sales,
                'total_sales_amount' => (float) $sale->total_sales_amount,
                'total_installment_amount' => (float) $installmentAmount,
                'total_amount' => (float) $sale->total_sales_amount + $installmentAmount,
            ];
        }

        return response()->json([
            'status' => 200,
            'data' => $report,
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = ['status' => 200];

        try {
            $query = Report::query();

            // ✅ Search filter
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%$search%")
                        ->orWhere('invoice_number', 'like', "%$search%")
                        ->orWhere('unit', 'like', "%$search%")
                        ->orWhere('remark', 'like', "%$search%");
                });
            }

            // ✅ Pagination
            $perPage = $request->input('limit', 10);
            $reports = $query->latest()->paginate($perPage);

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