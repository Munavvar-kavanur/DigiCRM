<?php

namespace App\Observers;

use App\Models\Expense;

class ExpenseObserver
{
    /**
     * Handle the Expense "created" event.
     */
    public function created(Expense $expense): void
    {
        if ($expense->is_recurring) {
            \App\Models\Reminder::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id() ?? $expense->user_id ?? 1,
                'title' => 'Expense: ' . $expense->title,
                'description' => 'Recurring expense ' . $expense->title . ' (' . $expense->amount . ')',
                'reminder_date' => $expense->date,
                'type' => 'expense',
                'related_id' => $expense->id,
                'related_type' => Expense::class,
                'status' => 'pending',
                'priority' => 'medium',
                'is_recurring' => true,
                'frequency' => $expense->frequency,
            ]);
        }
    }

    /**
     * Handle the Expense "updated" event.
     */
    public function updated(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "deleted" event.
     */
    public function deleted(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "restored" event.
     */
    public function restored(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "force deleted" event.
     */
    public function forceDeleted(Expense $expense): void
    {
        //
    }
}
