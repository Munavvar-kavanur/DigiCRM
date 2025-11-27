<?php

namespace App\Http\Controllers;

use App\Models\PayrollType;
use Illuminate\Http\Request;

class PayrollTypeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $branchId = null;
        if (auth()->user()->isSuperAdmin()) {
            $branchId = session('settings_branch_context') ?: null;
        } else {
            $branchId = auth()->user()->branch_id;
        }

        // Check uniqueness within branch
        $exists = PayrollType::where('name', $request->name)
            ->where('branch_id', $branchId)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['name' => 'The name has already been taken for this branch.']);
        }

        PayrollType::create([
            'name' => $request->name,
            'description' => $request->description,
            'branch_id' => $branchId,
        ]);

        return redirect()->route('settings.edit')->with('success', 'Payroll Type added successfully.');
    }

    public function destroy(PayrollType $payrollType)
    {
        $payrollType->delete();
        return redirect()->route('settings.edit')->with('success', 'Payroll Type deleted successfully.');
    }
}
