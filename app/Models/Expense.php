<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class Expense extends Model
{
    use HasFactory, BelongsToBranch;

    protected $fillable = [
        'title',
        'description',
        'amount',
        'date',
        'category', // Keeping for backward compatibility or fallback
        'expense_category_id',
        'user_id',
        'is_recurring',
        'frequency',
        'end_date',
        'receipt_path',
        'status',
        'merchant',
        'reference',
        'branch_id',
        'paid_by_id',
    ];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
    ];

    public function getReceiptUrlAttribute()
    {
        return $this->receipt_path ? asset('storage/' . $this->receipt_path) : null;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function paidBy()
    {
        return $this->belongsTo(ExpensePayer::class, 'paid_by_id');
    }
}
