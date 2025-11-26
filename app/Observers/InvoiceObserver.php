<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        if ($invoice->due_date) {
            \App\Models\Reminder::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id() ?? $invoice->client->user_id ?? 1, // Fallback to admin if no auth
                'title' => 'Invoice Due: ' . $invoice->invoice_number,
                'description' => 'Reminder for invoice ' . $invoice->invoice_number . ' due on ' . $invoice->due_date->format('M d, Y'),
                'reminder_date' => $invoice->due_date,
                'type' => 'invoice',
                'related_id' => $invoice->id,
                'related_type' => Invoice::class,
                'status' => 'pending',
                'priority' => 'high',
            ]);
        }
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
