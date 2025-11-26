<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salary_month',
        'base_salary',
        'bonus',
        'deductions',
        'net_salary',
        'status',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'salary_month' => 'date',
        'payment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
