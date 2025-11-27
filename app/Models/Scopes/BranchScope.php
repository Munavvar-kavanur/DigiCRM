<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BranchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Super Admins can see everything (no scope applied)
            if ($user->role === 'super_admin') {
                return;
            }

            // Branch Admins and Employees see only their branch data
            if ($user->branch_id) {
                $builder->where($model->getTable() . '.branch_id', $user->branch_id);
            }
        }
    }
}
