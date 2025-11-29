<?php

namespace App\Http\Controllers;

use App\Models\ExpensePayer;
use Illuminate\Http\Request;

class ExpensePayerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mobile_number' => 'nullable|string|max:20',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $branchId = null;
        if (auth()->user()->isSuperAdmin()) {
            $branchId = $request->branch_id;
        } else {
            $branchId = auth()->user()->branch_id;
        }

        ExpensePayer::create([
            'name' => $request->name,
            'description' => $request->description,
            'mobile_number' => $request->mobile_number,
            'branch_id' => $branchId,
        ]);

        return back()->with('success', 'Expense Payer added successfully.');
    }

    public function update(Request $request, ExpensePayer $expensePayer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mobile_number' => 'nullable|string|max:20',
        ]);

        $expensePayer->update([
            'name' => $request->name,
            'description' => $request->description,
            'mobile_number' => $request->mobile_number,
        ]);

        return back()->with('success', 'Expense Payer updated successfully.');
    }

    public function destroy(ExpensePayer $expensePayer)
    {
        $expensePayer->delete();
        return back()->with('success', 'Expense Payer deleted successfully.');
    }
}
