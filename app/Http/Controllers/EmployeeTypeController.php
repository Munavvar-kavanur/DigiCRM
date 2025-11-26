<?php

namespace App\Http\Controllers;

use App\Models\EmployeeType;
use Illuminate\Http\Request;

class EmployeeTypeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:employee_types,name',
            'description' => 'nullable|string',
        ]);

        EmployeeType::create($request->only('name', 'description'));

        return redirect()->route('settings.edit')->with('success', 'Employee Type added successfully.');
    }

    public function destroy(EmployeeType $employeeType)
    {
        $employeeType->delete();
        return redirect()->route('settings.edit')->with('success', 'Employee Type deleted successfully.');
    }
}
