<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('user', 'category');

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

        if ($request->has('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->latest('date')->paginate(10);
        $categories = \App\Models\ExpenseCategory::all();

        // Stats
        $totalExpenses = Expense::sum('amount');
        $pendingCount = Expense::where('status', 'pending')->count();
        $thisMonthExpenses = Expense::whereMonth('date', now()->month)->sum('amount');

        $currency = \App\Models\Setting::get('currency_symbol', '$');

        return view('expenses.index', compact('expenses', 'categories', 'totalExpenses', 'pendingCount', 'thisMonthExpenses', 'currency'));
    }

    public function create()
    {
        $categories = \App\Models\ExpenseCategory::all();
        $currency = \App\Models\Setting::get('currency_symbol', '$');
        return view('expenses.create', compact('categories', 'currency'));
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
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_recurring'] = $request->has('is_recurring');

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
        return view('expenses.edit', compact('expense', 'categories', 'currency'));
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
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

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
