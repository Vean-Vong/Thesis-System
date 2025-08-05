<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function invoice($paymentId)
    {
        // Load payment with related installment and customer data
        $payment = Payment::with(['installment', 'customer'])->find($paymentId);

        if (!$payment) {
            return response()->json([
                'status' => 404,
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'payment' => $payment,
                'installment' => $payment->installment,
                'customer' => $payment->customer,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Installment $installment)
    {
        try {
            $validated = $request->validate([
                'amount'       => 'required|numeric|min:1',
                'payment_date' => 'required|date',
                'note'         => 'nullable|string|max:255',
                'quantity_paid'     => 'required|integer|min:1',
            ]);


            // 🛑 Prevent payments if already marked as paid
            if ($installment->status === 'paid') {
                return response()->json([
                    'status'  => 400,
                    'message' => '⚠️ This installment has already been fully paid.',
                ], 400);
            }

            // Calculate balance
            $balance = $installment->total_price - $installment->deposit - $installment->paid_amount;
            if ($validated['amount'] > $balance) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Payment amount exceeds balance.',
                ], 400);
            }

            // Create payment
            $payment = Payment::create([
                'installment_id' => $installment->id,
                'customer_id'    => $installment->customer_id,
                'seller_id'      => auth()->id() ?? 1, // fallback for now
                'amount'         => $validated['amount'],
                'payment_date'   => $validated['payment_date'],
                'note'           => $validated['note'] ?? null,
                'quantity_paid'     => $validated['quantity_paid'],

            ]);

            // Update installment paid amount
            $installment->increment('paid_amount', $validated['amount']);


            // 🟢 Check if fully paid
            $totalPaid = $installment->deposit + $installment->paid_amount;

            if ($totalPaid >= $installment->total_price) {
                $installment->status = 'paid';
                $installment->paid_at = now();
                $installment->save();
            }


            return response()->json([
                'status'  => 200,
                'message' => '✅ Payment recorded successfully.',
                'data'    => $payment,
            ]);
        } catch (\Throwable $e) {
            Log::error('💥 Payment Store Error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => '❌ Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeRentalPayment(Request $request, $rentalId)
    {
        try {
            $rental = Rental::find($rentalId);

            if (!$rental) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Rental not found.'
                ], 404);
            }

            $validated = $request->validate([
                'amount'       => 'required|numeric|min:1',
                'payment_date' => 'required|date',
                'note'         => 'nullable|string|max:255',
                'quantity_paid'     => 'required|integer|min:1',

            ]);

            // Record payment
            $payment = Payment::create([
                'installment_id' => null, // Force null for rental payments
                'amount'         => $validated['amount'],
                'payment_date'   => $validated['payment_date'],
                'note'           => $validated['note'] ?? null,
                'rental_id'      => $rental->id,
                'customer_id'    => $rental->customer_id,
                'seller_id'      => auth()->id() ?? 1,
                'quantity_paid'     => $validated['quantity_paid'],

            ]);

            // Update rental paid amount (keep adding forever)
            $rental->paid_amount += $validated['amount'];
            $rental->save();

            return response()->json([
                'status'  => 200,
                'message' => 'Payment recorded successfully.',
                'data'    => $payment,
            ]);
        } catch (\Throwable $e) {
            Log::error('💥 storeRentalPayment Error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }


    public function rentalShow($id)
    {
        $rental = Rental::with(['customer', 'payments'])->find($id);

        if (!$rental) {
            return response()->json([
                'status' => 404,
                'message' => 'Rental not found'
            ], 404);
        }

        // Calculate total paid and count payments
        $totalPaid = $rental->payments->sum('amount');
        $paymentCount = $rental->payments->count();

        $rental->paid_amount = $totalPaid; // overwrite
        $rental->payments_count = $paymentCount;

        return response()->json([
            'status' => 200,
            'data' => $rental
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Eager load 'payments' relationship along with other relations if needed
        $installment = Installment::with(['payments', 'customer', 'sale', 'seller'])->find($id);

        if (!$installment) {
            return response()->json([
                'status' => 404,
                'message' => 'Installment not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $installment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
