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
    public function createRazorpayOrder(Invoice $invoice)
    {
        $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        // Ensure we're using the correct currency. Defaulting to INR if not set.
        // In a real app, you might want to handle currency conversion if the invoice is not in INR
        // but Razorpay account is INR, or ensure Razorpay supports the invoice currency.
        $currency = 'INR'; 

        $orderData = [
            'receipt'         => (string) $invoice->id,
            'amount'          => (int) ($invoice->balance_due * 100), // Amount in paise/cents
            'currency'        => $currency,
            'payment_capture' => 1 // Auto capture
        ];

        $razorpayOrder = $api->order->create($orderData);

        return response()->json([
            'order_id' => $razorpayOrder['id'],
            'amount' => $orderData['amount'],
            'key' => env('RAZORPAY_KEY_ID'),
            'currency' => $orderData['currency'],
            'name' => config('app.name'),
            'description' => 'Payment for Invoice ' . $invoice->invoice_number,
            'prefill' => [
                'name' => $invoice->client->name,
                'email' => $invoice->client->email,
                'contact' => $invoice->client->phone,
            ],
            'theme' => [
                'color' => '#4F46E5'
            ]
        ]);
    }

    public function verifyRazorpayPayment(Request $request, Invoice $invoice)
    {
        $input = $request->all();
        $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        $success = true;
        $error = "Payment Failed";

        if (empty($input['razorpay_payment_id']) === false)
        {
            try
            {
                $attributes = array(
                    'razorpay_order_id' => $input['razorpay_order_id'],
                    'razorpay_payment_id' => $input['razorpay_payment_id'],
                    'razorpay_signature' => $input['razorpay_signature']
                );

                $api->utility->verifyPaymentSignature($attributes);
            }
            catch(\Exception $e)
            {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        if ($success === true)
        {
            // Record Payment
            // Note: We are assuming the full balance is paid here as per the order creation.
            // If partial payments are allowed via Razorpay, logic needs adjustment.
            $amountPaid = $invoice->balance_due; 

            $payment = $invoice->payments()->create([
                'amount' => $amountPaid,
                'payment_date' => now(),
                'payment_method' => 'Razorpay',
                'transaction_id' => $input['razorpay_payment_id'],
                'notes' => 'Razorpay Payment ID: ' . $input['razorpay_payment_id'],
            ]);

            // Update Invoice
            $invoice->balance_due = 0;
            $invoice->status = 'paid';
            $invoice->save();

            return response()->json(['success' => true, 'message' => 'Payment successful']);
        }
        else
        {
            return response()->json(['success' => false, 'message' => $error], 400);
        }
    }
}
