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
        $query = Payroll::with('user')->latest();

        if ($request->has('month')) {
            $query->where('salary_month', 'like', $request->month . '%');
        }

        // Calculate stats based on the filtered query
        // We clone the query to avoid modifying the original query for pagination
        $statsQuery = clone $query;
        $payrollsCollection = $statsQuery->get();

        $totalPayroll = $payrollsCollection->sum('net_salary');
        $paidPayroll = $payrollsCollection->where('status', 'paid')->sum('net_salary');
        $pendingPayroll = $payrollsCollection->where('status', 'pending')->sum('net_salary');

        $payrolls = $query->paginate(10);

        return view('payrolls.index', compact('payrolls', 'totalPayroll', 'paidPayroll', 'pendingPayroll'));
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

        Payroll::create($validated);

        return redirect()->route('payrolls.index')->with('success', 'Payroll created successfully.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('user');
        return view('payrolls.show', compact('payroll'));
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
        $pdf = Pdf::loadView('payrolls.pdf', compact('payroll'));
        return $pdf->download('payslip-' . $payroll->id . '.pdf');
    }
}
