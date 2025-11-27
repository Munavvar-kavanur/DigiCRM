<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class Estimate extends Model
{
    use HasFactory, BelongsToBranch;

    protected $fillable = [
        'client_id',
        'project_id',
        'valid_until',
        'total_amount',
        'status',
        'estimate_number',
        'notes',
        'discount_type',
        'discount',
        'subtotal',
        'tax',
        'branch_id',
        'terms',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
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
        return $this->hasMany(EstimateItem::class);
    }
}
