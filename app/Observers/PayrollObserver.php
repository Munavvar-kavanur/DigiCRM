<?php

namespace App\Observers;

use App\Models\Payroll;

class PayrollObserver
{
    /**
     * Handle the Payroll "created" event.
     */
    public function created(Payroll $payroll): void
    {
        if ($payroll->payment_date) {
            \App\Models\Reminder::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                'title' => 'Payroll Payment: ' . $payroll->user->name,
                'description' => 'Payroll payment for ' . $payroll->user->name . ' due on ' . $payroll->payment_date->format('M d, Y'),
                'reminder_date' => $payroll->payment_date,
                'type' => 'payroll',
                'related_id' => $payroll->id,
                'related_type' => Payroll::class,
                'status' => 'pending',
                'priority' => 'high',
            ]);
        }
    }

    /**
     * Handle the Payroll "updated" event.
     */
    public function updated(Payroll $payroll): void
    {
        //
    }

    /**
     * Handle the Payroll "deleted" event.
     */
    public function deleted(Payroll $payroll): void
    {
        //
    }

    /**
     * Handle the Payroll "restored" event.
     */
    public function restored(Payroll $payroll): void
    {
        //
    }

    /**
     * Handle the Payroll "force deleted" event.
     */
    public function forceDeleted(Payroll $payroll): void
    {
        //
    }
}
