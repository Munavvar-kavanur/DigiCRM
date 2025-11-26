<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'color' => 'nullable|string|max:7', // Hex color
        ]);

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
