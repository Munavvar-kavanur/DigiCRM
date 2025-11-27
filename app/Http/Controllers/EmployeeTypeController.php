<?php

namespace App\Http\Controllers;

use App\Models\EmployeeType;
use Illuminate\Http\Request;

class EmployeeTypeController extends Controller
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
        $exists = EmployeeType::where('name', $request->name)
            ->where('branch_id', $branchId)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['name' => 'The name has already been taken for this branch.']);
        }

        EmployeeType::create([
            'name' => $request->name,
            'description' => $request->description,
            'branch_id' => $branchId,
        ]);

        return redirect()->route('settings.edit')->with('success', 'Employee Type added successfully.');
    }

    public function destroy(EmployeeType $employeeType)
    {
        $employeeType->delete();
        return redirect()->route('settings.edit')->with('success', 'Employee Type deleted successfully.');
    }
}
