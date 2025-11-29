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

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'color' => 'required|string|max:7', // Hex color
        ]);

        // Check uniqueness within branch, excluding current category
        $exists = ExpenseCategory::where('name', $request->name)
            ->where('branch_id', $expenseCategory->branch_id)
            ->where('id', '!=', $expenseCategory->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['name' => 'The name has already been taken for this branch.']);
        }

        $expenseCategory->update($validated);

        return redirect()->route('settings.edit', ['tab' => 'categories'])->with('success', 'Category updated successfully.');
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
