<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmployeeController extends Controller
{
    public function index()
    {
        $query = User::where('is_employee', true);
        
        $totalEmployees = $query->count();
        $totalMonthlyCost = $query->sum('salary');
        $averageSalary = $totalEmployees > 0 ? $query->avg('salary') : 0;
        
        $employees = $query->latest()->paginate(10);
        
        return view('employees.index', compact('employees', 'totalEmployees', 'totalMonthlyCost', 'averageSalary'));
    }

    public function create()
    {
        $employeeTypes = \App\Models\EmployeeType::all();
        $payrollTypes = \App\Models\PayrollType::all();
        return view('employees.create', compact('employeeTypes', 'payrollTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'job_title' => ['nullable', 'string', 'max:255'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'joining_date' => ['nullable', 'date'],
            'employee_type_id' => ['nullable', 'exists:employee_types,id'],
            'payroll_type_id' => ['nullable', 'exists:payroll_types,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // Default password
            'job_title' => $request->job_title,
            'salary' => $request->salary,
            'joining_date' => $request->joining_date,
            'employee_type_id' => $request->employee_type_id,
            'payroll_type_id' => $request->payroll_type_id,
            'is_employee' => true,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully. Default password is "password".');
    }

    public function edit(User $employee)
    {
        $employeeTypes = \App\Models\EmployeeType::all();
        $payrollTypes = \App\Models\PayrollType::all();
        return view('employees.edit', compact('employee', 'employeeTypes', 'payrollTypes'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$employee->id],
            'job_title' => ['nullable', 'string', 'max:255'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'joining_date' => ['nullable', 'date'],
            'employee_type_id' => ['nullable', 'exists:employee_types,id'],
            'payroll_type_id' => ['nullable', 'exists:payroll_types,id'],
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'job_title' => $request->job_title,
            'salary' => $request->salary,
            'joining_date' => $request->joining_date,
            'employee_type_id' => $request->employee_type_id,
            'payroll_type_id' => $request->payroll_type_id,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        if ($employee->id === auth()->id()) {
            return redirect()->route('employees.index')->with('error', 'You cannot delete your own account.');
        }

        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
