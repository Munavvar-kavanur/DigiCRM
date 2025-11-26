<?php

namespace App\Observers;

use App\Models\Estimate;

class EstimateObserver
{
    /**
     * Handle the Estimate "created" event.
     */
    public function created(Estimate $estimate): void
    {
        if ($estimate->valid_until) {
            \App\Models\Reminder::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                'title' => 'Estimate Expiry: ' . $estimate->estimate_number,
                'description' => 'Estimate ' . $estimate->estimate_number . ' expires on ' . $estimate->valid_until->format('M d, Y'),
                'reminder_date' => $estimate->valid_until,
                'type' => 'estimate',
                'related_id' => $estimate->id,
                'related_type' => Estimate::class,
                'status' => 'pending',
                'priority' => 'medium',
            ]);
        }
    }

    /**
     * Handle the Estimate "updated" event.
     */
    public function updated(Estimate $estimate): void
    {
        //
    }

    /**
     * Handle the Estimate "deleted" event.
     */
    public function deleted(Estimate $estimate): void
    {
        //
    }

    /**
     * Handle the Estimate "restored" event.
     */
    public function restored(Estimate $estimate): void
    {
        //
    }

    /**
     * Handle the Estimate "force deleted" event.
     */
    public function forceDeleted(Estimate $estimate): void
    {
        //
    }
}
