<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with(['user', 'branch.settings'])->latest();

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

        if ($request->has('month')) {
            $query->where('salary_month', 'like', $request->month . '%');
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $payrolls = $query->paginate(10);
        $branches = \App\Models\Branch::orderBy('name')->get();

        // Selected Branch for View Context
        $selectedBranch = null;
        if ($request->has('branch_id') && $request->branch_id) {
            $selectedBranch = \App\Models\Branch::find($request->branch_id);
        } elseif (!auth()->user()->isSuperAdmin()) {
            $selectedBranch = auth()->user()->branch;
        }

        // Helper to get currency totals
        $getCurrencyTotals = function ($statusFilter = null) use ($applyBranch, $selectedBranch, $request) {
            // If a specific branch is selected (or user is branch admin), we only need that branch's currency
            if ($selectedBranch) {
                $q = Payroll::query();
                $applyBranch($q);
                if ($request->has('month')) {
                    $q->where('salary_month', 'like', $request->month . '%');
                }
                if ($statusFilter) {
                    $statusFilter($q);
                }
                $total = $q->sum('net_salary');
                return [
                    (object)[
                        'currency' => $selectedBranch->currency,
                        'amount' => $total
                    ]
                ];
            }

            // Global view: Aggregate by branch_id first
            $q = Payroll::query();
            if ($request->has('month')) {
                $q->where('salary_month', 'like', $request->month . '%');
            }
            if ($statusFilter) {
                $statusFilter($q);
            }

            // Group by branch_id
            $results = $q->selectRaw('branch_id, SUM(net_salary) as total_amount')
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
        $totalPayrollValue = $getCurrencyTotals();
        $paidPayrollValue = $getCurrencyTotals(function($q) { $q->where('status', 'paid'); });
        $pendingPayrollValue = $getCurrencyTotals(function($q) { $q->where('status', 'pending'); });

        return view('payrolls.index', compact('payrolls', 'totalPayrollValue', 'paidPayrollValue', 'pendingPayrollValue', 'branches', 'selectedBranch'));
    }

    public function create()
    {
        $users = User::all();
        return view('payrolls.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary_month' => 'required|date',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,paid',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['deductions'] = $validated['deductions'] ?? 0;
        $validated['net_salary'] = $validated['base_salary'] + $validated['bonus'] - $validated['deductions'];

        // Assign branch based on user
        $user = User::find($validated['user_id']);
        if ($user && $user->branch_id) {
            $validated['branch_id'] = $user->branch_id;
        }

        Payroll::create($validated);

        return redirect()->route('payrolls.index')->with('success', 'Payroll created successfully.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('user');
        $settings = \App\Models\Setting::getAll($payroll->user->branch_id);
        return view('payrolls.show', compact('payroll', 'settings'));
    }

    public function edit(Payroll $payroll)
    {
        $users = User::all();
        return view('payrolls.edit', compact('payroll', 'users'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'salary_month' => 'required|date',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,paid',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['deductions'] = $validated['deductions'] ?? 0;
        $validated['net_salary'] = $validated['base_salary'] + $validated['bonus'] - $validated['deductions'];

        $payroll->update($validated);

        return redirect()->route('payrolls.index')->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('payrolls.index')->with('success', 'Payroll deleted successfully.');
    }

    public function downloadPdf(Payroll $payroll)
    {
        $payroll->load('user');
        $settings = \App\Models\Setting::getAll($payroll->user->branch_id);
        $pdf = Pdf::loadView('payrolls.pdf', compact('payroll', 'settings'));
        return $pdf->download('payslip-' . $payroll->id . '.pdf');
    }
}
