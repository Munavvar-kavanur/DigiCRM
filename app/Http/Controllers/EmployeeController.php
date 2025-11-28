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
        $query = User::where('is_employee', true)->with('branch.settings');

        // Helper to apply branch filter
        $applyBranch = function ($q) use ($request) {
            if (auth()->user()->isSuperAdmin()) {
                if ($request->has('branch_id') && $request->branch_id != '') {
                    $q->where('branch_id', $request->branch_id);
                }
            } else {
                $q->where('branch_id', auth()->user()->branch_id);
            }
        };

        // Apply branch filter to main query
        $applyBranch($query);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('job_title', 'like', "%{$search}%");
            });
        }
        
        $employees = $query->latest()->paginate(10);
        $branches = \App\Models\Branch::orderBy('name')->get();

        // Selected Branch for View Context
        $selectedBranch = null;
        if ($request->has('branch_id') && $request->branch_id) {
            $selectedBranch = \App\Models\Branch::find($request->branch_id);
        } elseif (!auth()->user()->isSuperAdmin()) {
            $selectedBranch = auth()->user()->branch;
        }

        // Helper to get currency totals
        $getCurrencyTotals = function ($column = 'salary') use ($applyBranch, $selectedBranch, $request) {
            // If a specific branch is selected (or user is branch admin), we only need that branch's currency
            if ($selectedBranch) {
                $q = User::where('is_employee', true);
                $applyBranch($q);
                
                $total = $q->sum($column);
                return [
                    (object)[
                        'currency' => $selectedBranch->currency,
                        'amount' => $total
                    ]
                ];
            }

            // Global view: Aggregate by branch_id first
            $q = User::where('is_employee', true);
            
            // Group by branch_id
            $results = $q->selectRaw("branch_id, SUM($column) as total_amount")
                         ->groupBy('branch_id')
                         ->get();

            // Map to currencies and aggregate
            $currencyTotals = [];
            $branches = \App\Models\Branch::with('settings')->get()->keyBy('id');

            foreach ($results as $result) {
                $branch = $branches->get($result->branch_id);
                // Use branch currency or default '$' if branch not found (e.g. deleted branch or null)
                $currency = $branch ? $branch->currency : '$';
                
                if (!isset($currencyTotals[$currency])) {
                    $currencyTotals[$currency] = 0;
                }
                $currencyTotals[$currency] += $result->total_amount;
            }

            // Convert to array of objects
            $final = [];
            foreach ($currencyTotals as $currency => $amount) {
                $final[] = (object)[
                    'currency' => $currency,
                    'amount' => $amount
                ];
            }
            
            return collect($final);
        };

        // Stats
        $totalEmployees = User::where('is_employee', true);
        $applyBranch($totalEmployees);
        $totalEmployees = $totalEmployees->count();

        $totalMonthlyCostValue = $getCurrencyTotals('salary');

        return view('employees.index', compact('employees', 'totalEmployees', 'totalMonthlyCostValue', 'branches', 'selectedBranch'));
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
