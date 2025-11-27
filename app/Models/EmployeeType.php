<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    use HasFactory, \App\Traits\BelongsToBranch;

    protected $fillable = ['name', 'description', 'branch_id'];
}
