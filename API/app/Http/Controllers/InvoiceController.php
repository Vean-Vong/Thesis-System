<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Utility_expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Get all invoices with related utility expenses (paginated).
     */
    public function index(Request $request)
    {
        $result = ['status' => 200];

        try {
            $invoices = Invoice::with('utilityExpenses')->latest()->paginate($request->get('limit', 10));
            $result['data'] = $invoices;
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * Store a new invoice with utility expenses.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'utility_expense_ids' => 'required|array|min:1',
            'utility_expense_ids.*' => 'exists:utility_expenses,id',
        ]);

        DB::beginTransaction();

        try {
            $expenses = Utility_expenses::whereIn('id', $request->utility_expense_ids)->get();

            $subtotal = $expenses->sum(fn($expense) => (float) $expense->cost);
            $tax = 0; // or $subtotal * 0.10;
            $total = $subtotal + $tax;

            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . now()->format('YmdHis'),
                'invoice_date' => $request->invoice_date,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            $invoice->utilityExpenses()->attach($request->utility_expense_ids);

            DB::commit();

            return response()->json([
                'status' => 200,
                'data' => $invoice->load('utilityExpenses'),
                'message' => 'Invoice created successfully!',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Error creating invoice: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show a specific invoice with its utility expenses.
     */
    public function show($id)
    {
        try {
            $invoice = Invoice::with('utilityExpenses')->findOrFail($id);
            return response()->json(['status' => 200, 'data' => $invoice]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete an invoice and detach related utility expenses.
     */
    public function destroy($id)
    {
        $result = ['status' => 200];

        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->utilityExpenses()->detach();
            $invoice->delete();
            $result['message'] = 'Invoice deleted successfully.';
        } catch (\Throwable $e) {
            $result['status'] = 500;
            $result['message'] = 'Failed to delete invoice.';
        }

        return response()->json($result);
    }
}