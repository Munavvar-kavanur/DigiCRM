<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7', // Hex color
        ]);

        $branchId = null;
        if (auth()->user()->isSuperAdmin()) {
            $branchId = session('settings_branch_context') ?: null;
        } else {
            $branchId = auth()->user()->branch_id;
        }
        
        // Check uniqueness within branch
        $exists = ExpenseCategory::where('name', $request->name)
            ->where('branch_id', $branchId)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['name' => 'The name has already been taken for this branch.']);
        }

        $validated['branch_id'] = $branchId;

        ExpenseCategory::create($validated);

        return redirect()->route('settings.edit', ['tab' => 'categories'])->with('success', 'Category added successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        // Check if category is in use
        if ($expenseCategory->expenses()->exists()) {
            return redirect()->route('settings.edit', ['tab' => 'categories'])->with('error', 'Cannot delete category because it is in use.');
        }

        $expenseCategory->delete();

        return redirect()->route('settings.edit', ['tab' => 'categories'])->with('success', 'Category deleted successfully.');
    }
}
