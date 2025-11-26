<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->balance_due,
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $payment = $invoice->payments()->create($validated);

        // Update Invoice Balance and Status
        $invoice->balance_due -= $validated['amount'];
        if ($invoice->balance_due <= 0) {
            $invoice->status = 'paid';
            $invoice->balance_due = 0;
        } elseif ($invoice->status == 'draft' || $invoice->status == 'sent') {
            // Keep status as is or maybe move to 'partial' if we had that status, 
            // but for now let's just ensure it's not 'paid' if balance > 0
        }
        $invoice->save();

        return redirect()->route('invoices.show', $invoice)->with('success', 'Payment recorded successfully.');
    }
}
