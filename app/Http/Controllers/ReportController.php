<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date') ? $request->input('start_date') : Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->input('end_date') : Carbon::now()->endOfMonth()->format('Y-m-d');
        $branchId = $request->input('branch_id');

        $user = auth()->user();
        $branches = [];
        if ($user->isSuperAdmin()) {
            $branches = \App\Models\Branch::all();
        } elseif ($user->branch_id) {
            $branchId = $user->branch_id;
        }

        // --- Fetch Data ---
        
        // Payments (Revenue)
        $paymentsQuery = Payment::with(['invoice.branch', 'invoice.client'])
            ->whereBetween('payment_date', [$startDate, $endDate]);

        if ($branchId) {
            $paymentsQuery->whereHas('invoice', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        $payments = $paymentsQuery->get();

        // Expenses
        $expensesQuery = Expense::with(['branch', 'category'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($branchId) {
            $expensesQuery->where('branch_id', $branchId);
        }
        $expenses = $expensesQuery->get();

        // --- Aggregation by Currency ---
        $financials = [];

        $initCurrency = function($currency) use (&$financials) {
            if (!isset($financials[$currency])) {
                $financials[$currency] = [
                    'revenue' => 0,
                    'expenses' => 0,
                    'net_profit' => 0,
                    'profit_margin' => 0,
                    'currency' => $currency
                ];
            }
        };

        // Process Payments
        foreach ($payments as $payment) {
            $currency = '$';
            if ($payment->invoice && $payment->invoice->branch) {
                $currency = $payment->invoice->branch->currency;
            } else {
                 $currency = \App\Models\Setting::get('currency_symbol', '$');
            }

            $initCurrency($currency);
            $financials[$currency]['revenue'] += $payment->amount;
        }

        // Process Expenses
        foreach ($expenses as $expense) {
            $currency = '$';
            if ($expense->branch) {
                $currency = $expense->branch->currency;
            } else {
                 $currency = \App\Models\Setting::get('currency_symbol', '$');
            }

            $initCurrency($currency);
            $financials[$currency]['expenses'] += $expense->amount;
        }

        // Calculate Net Profit & Margin
        foreach ($financials as &$data) {
            $data['net_profit'] = $data['revenue'] - $data['expenses'];
            $data['profit_margin'] = $data['revenue'] > 0 ? ($data['net_profit'] / $data['revenue']) * 100 : 0;
        }
        unset($data);

        // If no financials (empty data), add default currency with 0s
        if (empty($financials)) {
            $defaultCurrency = \App\Models\Setting::get('currency_symbol', '$');
            $financials[$defaultCurrency] = [
                'revenue' => 0,
                'expenses' => 0,
                'net_profit' => 0,
                'profit_margin' => 0,
                'currency' => $defaultCurrency
            ];
        }

        // --- Charts Data ---
        $chartCurrency = \App\Models\Setting::get('currency_symbol', '$');
        if ($branchId) {
            $branch = \App\Models\Branch::find($branchId);
            if ($branch) $chartCurrency = $branch->currency;
        }

        $cashFlowLabels = [];
        $revenueData = [];
        $expenseData = [];
        
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $cashFlowLabels[] = $date->format('M d');
            
            $dayPayments = $payments->filter(function($p) use ($day) {
                return $p->payment_date->format('Y-m-d') === $day;
            });
            $dayExpenses = $expenses->filter(function($e) use ($day) {
                return $e->date->format('Y-m-d') === $day;
            });
            
            $revenueData[] = $dayPayments->sum('amount');
            $expenseData[] = $dayExpenses->sum('amount');
        }

        // Expense Breakdown
        $expensesByCategory = $expenses->groupBy('expense_category_id')->map(function ($group) {
            return [
                'name' => $group->first()->category->name ?? 'Uncategorized',
                'total' => $group->sum('amount')
            ];
        });
        
        $expenseCategoryLabels = $expensesByCategory->pluck('name');
        $expenseCategoryData = $expensesByCategory->pluck('total');

        // --- Detailed Transactions ---
        $mappedPayments = $payments->toBase()->map(function ($payment) {
             $currency = $payment->invoice && $payment->invoice->branch ? $payment->invoice->branch->currency : \App\Models\Setting::get('currency_symbol', '$');
             return [
                'date' => $payment->payment_date,
                'description' => 'Payment for Invoice #' . ($payment->invoice->invoice_number ?? 'N/A'),
                'type' => 'Income',
                'category' => 'Sales',
                'amount' => $payment->amount,
                'currency' => $currency,
                'reference' => $payment->transaction_id,
            ];
        });

        $mappedExpenses = $expenses->toBase()->map(function ($expense) {
            $currency = $expense->branch ? $expense->branch->currency : \App\Models\Setting::get('currency_symbol', '$');
            return [
                'date' => $expense->date,
                'description' => $expense->description ?? $expense->title,
                'type' => 'Expense',
                'category' => $expense->category->name ?? 'Uncategorized',
                'amount' => -$expense->amount,
                'currency' => $currency,
                'reference' => null,
            ];
        });

        $transactions = $mappedPayments->merge($mappedExpenses)->sortByDesc('date');

        $selectedBranch = null;
        if ($branchId) {
            $selectedBranch = \App\Models\Branch::find($branchId);
        }

        return view('reports.index', compact(
            'startDate', 'endDate', 'branchId', 'branches', 'selectedBranch',
            'financials',
            'cashFlowLabels', 'revenueData', 'expenseData',
            'expenseCategoryLabels', 'expenseCategoryData',
            'transactions', 'chartCurrency'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'all'); // all, revenue, expense
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $filename = "report_{$type}_{$startDate}_to_{$endDate}.csv";
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Date', 'Type', 'Category', 'Description', 'Amount', 'Reference'];

        $callback = function() use ($type, $startDate, $endDate, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            if ($type === 'all' || $type === 'revenue') {
                $payments = Payment::with('invoice.client')
                    ->whereBetween('payment_date', [$startDate, $endDate])
                    ->get();
                
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->payment_date->format('Y-m-d'),
                        'Income',
                        'Sales',
                        'Payment for Invoice #' . ($payment->invoice->invoice_number ?? 'N/A') . ' (' . ($payment->invoice->client->name ?? 'Unknown') . ')',
                        $payment->amount,
                        $payment->transaction_id
                    ]);
                }
            }

            if ($type === 'all' || $type === 'expense') {
                $expenses = Expense::with('category')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();

                foreach ($expenses as $expense) {
                    fputcsv($file, [
                        $expense->date->format('Y-m-d'),
                        'Expense',
                        $expense->category->name ?? 'Uncategorized',
                        $expense->description ?? $expense->title,
                        $expense->amount, // Keep positive for CSV usually
                        ''
                    ]);
                }
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
