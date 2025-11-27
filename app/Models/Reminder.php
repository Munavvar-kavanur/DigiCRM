<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Added for the user relationship
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBranch;

class Reminder extends Model
{
    use HasFactory, BelongsToBranch;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'reminder_date',
        'status',
        'related_model',
        'related_id',
        'expense_type',
        'branch_id',
        'type',
        'priority',
        'is_recurring',
        'frequency',
    ];

    protected $casts = [
        'reminder_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }
}
