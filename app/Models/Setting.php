<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'branch_id'];

    /**
     * Get a setting value by key.
     */
    /**
     * Get a setting value by key.
     */
    public static function get($key, $default = null, $branchId = null)
    {
        // Determine target branch ID
        $targetBranchId = $branchId;
        
        if (is_null($targetBranchId) && auth()->check()) {
             $targetBranchId = auth()->user()->branch_id;
        }

        // If we have a branch ID, try to find branch-specific setting
        if ($targetBranchId) {
            $branchSetting = self::where('key', $key)
                ->where('branch_id', $targetBranchId)
                ->first();
            
            if ($branchSetting) {
                return $branchSetting->value;
            }
        }

        // For Super Admin (or no branch), OR if branch setting was missing, return global setting
        $globalSetting = self::where('key', $key)->whereNull('branch_id')->first();
        
        return $globalSetting ? $globalSetting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set($key, $value, $branchId = null)
    {
        $data = ['value' => $value];
        $conditions = ['key' => $key];

        // Use provided branchId if available
        if (!is_null($branchId) && $branchId !== '') {
            $conditions['branch_id'] = $branchId;
        } elseif (auth()->check() && auth()->user()->branch_id) {
            $conditions['branch_id'] = auth()->user()->branch_id;
        } else {
            $conditions['branch_id'] = null;
        }

        return self::updateOrCreate($conditions, $data);
    }

    /**
     * Get all settings as a key-value array.
     */
    public static function getAll($branchId = null)
    {
        // Determine target branch ID
        $targetBranchId = $branchId;
        
        if (is_null($targetBranchId) && auth()->check()) {
             $targetBranchId = auth()->user()->branch_id;
        }

        // If we have a branch ID, ONLY return branch settings
        if ($targetBranchId) {
            return self::where('branch_id', $targetBranchId)
                ->pluck('value', 'key')
                ->toArray();
        }

        // For Super Admin, return global settings
        return self::whereNull('branch_id')->pluck('value', 'key')->toArray();
    }
    /**
     * Format currency based on settings.
     */
    public static function formatCurrency($amount, $settings = [])
    {
        $symbol = $settings['currency_symbol'] ?? '$';
        $position = $settings['currency_symbol_position'] ?? 'prefix';
        $formattedAmount = number_format((float)$amount, 2);

        if ($position === 'suffix') {
            return $formattedAmount . ' ' . $symbol;
        }

        return $symbol . $formattedAmount;
    }
}
