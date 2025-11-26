<?php

namespace App\Http\Controllers;

use App\Models\PayrollType;
use Illuminate\Http\Request;

class PayrollTypeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payroll_types,name',
            'description' => 'nullable|string',
        ]);

        PayrollType::create($request->only('name', 'description'));

        return redirect()->route('settings.edit')->with('success', 'Payroll Type added successfully.');
    }

    public function destroy(PayrollType $payrollType)
    {
        $payrollType->delete();
        return redirect()->route('settings.edit')->with('success', 'Payroll Type deleted successfully.');
    }
}
