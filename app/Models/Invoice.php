<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'client_id',
        'project_id',
        'issue_date',
        'due_date',
        'status',
        'is_recurring',
        'recurring_frequency',
        'subtotal',
        'tax',
        'discount',
        'discount_type',
        'total_amount',
        'grand_total',
        'balance_due',
        'notes',
        'terms',
    ];

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
}
