<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToBranch;

class Invoice extends Model
{
    use HasFactory, BelongsToBranch;

    protected $fillable = [
        'client_id',
        'project_id',
        'issue_date',
        'due_date',
        'subtotal',
        'total_amount',
        'status',
        'invoice_number',
        'is_recurring',
        'recurring_frequency',
        'next_invoice_date',
        'branch_id',
        'tax',
        'discount',
        'discount_type',
        'grand_total',
        'balance_due',
        'terms',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'next_invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'is_recurring' => 'boolean',
    ];

    protected $appends = ['currency_symbol'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getCurrencySymbolAttribute()
    {
        $symbols = [
            'INR' => '₹',
            'AED' => 'د.إ',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
        ];
        
        // Make sure to read the actual currency column
        $currency = $this->attributes['currency'] ?? 'INR';
        return $symbols[$currency] ?? $currency;
    }
}
