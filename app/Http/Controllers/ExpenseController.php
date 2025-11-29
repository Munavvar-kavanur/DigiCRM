<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['user', 'category', 'branch.settings', 'paidBy']);

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

        // Filters
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('merchant', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('expense_category_id', $request->category_id);
        }

        if ($request->has('paid_by_id') && $request->paid_by_id != '') {
            $query->where('paid_by_id', $request->paid_by_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->latest('date')->paginate(10);
        
        $categories = \App\Models\ExpenseCategory::all();
        $branches = \App\Models\Branch::orderBy('name')->get();
        $expensePayers = \App\Models\ExpensePayer::all();

        // Selected Branch for View Context
        $selectedBranch = null;
        if ($request->has('branch_id') && $request->branch_id) {
            $selectedBranch = \App\Models\Branch::find($request->branch_id);
        } elseif (!auth()->user()->isSuperAdmin()) {
            $selectedBranch = auth()->user()->branch;
        }

        // Helper to get currency totals
        $getCurrencyTotals = function ($statusFilter = null) use ($applyBranch, $selectedBranch) {
            // If a specific branch is selected (or user is branch admin), we only need that branch's currency
            if ($selectedBranch) {
                $q = Expense::query();
                $applyBranch($q);
                if ($statusFilter) {
                    $statusFilter($q);
                }
                $total = $q->sum('amount');
                return collect([
                    (object)[
                        'currency' => $selectedBranch->currency,
                        'amount' => $total
                    ]
                ]);
            }

            // Global view: Aggregate by branch_id first
            $q = Expense::query();
            if ($statusFilter) {
                $statusFilter($q);
            }

            // Group by branch_id
            $results = $q->selectRaw('branch_id, SUM(amount) as total_amount')
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
        $totalExpensesValue = $getCurrencyTotals();

        $pendingExpenses = Expense::where('status', 'pending');
        $applyBranch($pendingExpenses);
        $pendingCount = $pendingExpenses->count();
        $pendingExpensesValue = $getCurrencyTotals(function($q) { $q->where('status', 'pending'); });

        $thisMonthExpensesValue = $getCurrencyTotals(function($q) {
            $q->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]);
        });

        // Also get approved expenses for stats
        $approvedExpensesValue = $getCurrencyTotals(function($q) { $q->where('status', 'approved'); });

        return view('expenses.index', compact(
            'expenses', 'categories', 'branches', 'expensePayers',
            'totalExpensesValue', 'pendingCount', 'pendingExpensesValue', 
            'thisMonthExpensesValue', 'approvedExpensesValue',
            'selectedBranch'
        ));
    }

    public function create()
    {
        $categories = \App\Models\ExpenseCategory::all();
        $currency = \App\Models\Setting::get('currency_symbol', '$');
        $branches = \App\Models\Branch::orderBy('name')->get();
        $expensePayers = \App\Models\ExpensePayer::all();
        return view('expenses.create', compact('categories', 'currency', 'branches', 'expensePayers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'category' => 'nullable|string|max:255', // Fallback
            'is_recurring' => 'boolean',
            'frequency' => 'nullable|in:daily,weekly,monthly,quarterly,half_yearly,yearly',
            'end_date' => 'nullable|date|after:date',
            'merchant' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'status' => 'required|in:pending,approved,rejected',
            'branch_id' => 'nullable|exists:branches,id',
            'paid_by_id' => 'nullable|exists:expense_payers,id',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_recurring'] = $request->has('is_recurring');

        if (auth()->user()->isSuperAdmin() && $request->filled('branch_id')) {
            $validated['branch_id'] = $request->branch_id;
        }

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $currency = \App\Models\Setting::get('currency_symbol', '$');
        return view('expenses.show', compact('expense', 'currency'));
    }

    public function edit(Expense $expense)
    {
        $categories = \App\Models\ExpenseCategory::all();
        $currency = \App\Models\Setting::get('currency_symbol', '$');
        $branches = \App\Models\Branch::orderBy('name')->get();
        $expensePayers = \App\Models\ExpensePayer::all();
        return view('expenses.edit', compact('expense', 'categories', 'currency', 'branches', 'expensePayers'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'category' => 'nullable|string|max:255', // Fallback
            'is_recurring' => 'boolean',
            'frequency' => 'nullable|in:daily,weekly,monthly,quarterly,half_yearly,yearly',
            'end_date' => 'nullable|date|after:date',
            'merchant' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'status' => 'required|in:pending,approved,rejected',
            'branch_id' => 'nullable|exists:branches,id',
            'paid_by_id' => 'nullable|exists:expense_payers,id',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        if (auth()->user()->isSuperAdmin() && $request->filled('branch_id')) {
            $validated['branch_id'] = $request->branch_id;
        }

        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($expense->receipt_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($expense->receipt_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->receipt_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($expense->receipt_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->receipt_path);
        }
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    public function approve(Expense $expense)
    {
        $expense->update(['status' => 'approved']);
        return back()->with('success', 'Expense approved successfully.');
    }

    public function reject(Expense $expense)
    {
        $expense->update(['status' => 'rejected']);
        return back()->with('success', 'Expense rejected.');
    }
}
