<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_employee', true)->with('branch');

        // Branch Filter (Super Admin)
        if (auth()->user()->isSuperAdmin() && $request->has('branch_id') && $request->branch_id != '') {
            $query->where('branch_id', $request->branch_id);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $totalEmployees = $query->count();
        $totalMonthlyCost = $query->sum('salary');
        $averageSalary = $totalEmployees > 0 ? $query->avg('salary') : 0;
        
        $employees = $query->latest()->paginate(10);
        $branches = \App\Models\Branch::orderBy('name')->get();
        
        $settings = \App\Models\Setting::getAll();

        return view('employees.index', compact('employees', 'totalEmployees', 'totalMonthlyCost', 'averageSalary', 'settings', 'branches'));
    }

    public function create()
    {
        $employeeTypes = \App\Models\EmployeeType::all();
        $payrollTypes = \App\Models\PayrollType::all();
        $branches = auth()->user()->isSuperAdmin() ? \App\Models\Branch::all() : collect();
        
        return view('employees.create', compact('employeeTypes', 'payrollTypes', 'branches'));
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
            'branch_id' => ['nullable', 'exists:branches,id'],
            'role' => ['nullable', 'in:super_admin,branch_admin,employee'],
        ]);

        $branchId = auth()->user()->isSuperAdmin() ? $request->branch_id : auth()->user()->branch_id;
        $role = auth()->user()->isSuperAdmin() ? ($request->role ?? 'employee') : 'employee';

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
            'branch_id' => $branchId,
            'role' => $role,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully. Default password is "password".');
    }

    public function edit(User $employee)
    {
        $employeeTypes = \App\Models\EmployeeType::all();
        $payrollTypes = \App\Models\PayrollType::all();
        $branches = auth()->user()->isSuperAdmin() ? \App\Models\Branch::all() : collect();
        
        return view('employees.edit', compact('employee', 'employeeTypes', 'payrollTypes', 'branches'));
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
            'branch_id' => ['nullable', 'exists:branches,id'],
            'role' => ['nullable', 'in:super_admin,branch_admin,employee'],
        ]);

        $branchId = auth()->user()->isSuperAdmin() ? $request->branch_id : auth()->user()->branch_id;
        $role = auth()->user()->isSuperAdmin() ? ($request->role ?? 'employee') : 'employee';

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'job_title' => $request->job_title,
            'salary' => $request->salary,
            'joining_date' => $request->joining_date,
            'employee_type_id' => $request->employee_type_id,
            'payroll_type_id' => $request->payroll_type_id,
            'branch_id' => $branchId,
            'role' => $role,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
