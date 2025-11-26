<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Added for the user relationship

class Reminder extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'reminder_date',
        'type',
        'related_id',
        'related_type',
        'status',
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
