<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBranch;

class Project extends Model
{
    use HasFactory, BelongsToBranch;

    protected $fillable = [
        'title',
        'description',
        'status',
        'deadline',
        'client_id',
        'user_id',
        'branch_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function estimates()
    {
        return $this->hasMany(Estimate::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
