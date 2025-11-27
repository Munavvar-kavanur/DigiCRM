<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function estimates()
    {
        return $this->hasMany(Estimate::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function getCurrencyAttribute()
    {
        // If settings relation is loaded, use it to avoid N+1
        if ($this->relationLoaded('settings')) {
            $setting = $this->settings->firstWhere('key', 'currency_symbol');
            return $setting ? $setting->value : '$';
        }

        // Fallback to direct query (cached in Setting::get if possible, but here direct)
        return \App\Models\Setting::get('currency_symbol', '$', $this->id);
    }
}
