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
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // --- KPIs ---
        $totalRevenue = Payment::whereBetween('payment_date', [$startDate, $endDate])->sum('amount');
        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // --- Charts Data ---
        
        // Cash Flow (Revenue vs Expenses) - Grouped by Day
        $cashFlowLabels = [];
        $revenueData = [];
        $expenseData = [];
        
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $cashFlowLabels[] = $date->format('M d');
            $revenueData[] = Payment::whereDate('payment_date', $day)->sum('amount');
            $expenseData[] = Expense::whereDate('date', $day)->sum('amount');
        }

        // Expense Breakdown by Category
        $expensesByCategory = Expense::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('expense_category_id, sum(amount) as total')
            ->groupBy('expense_category_id')
            ->with('category')
            ->get();
            
        $expenseCategoryLabels = $expensesByCategory->pluck('category.name');
        $expenseCategoryData = $expensesByCategory->pluck('total');

        // --- Detailed Transactions ---
        $payments = Payment::with('invoice.client')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get()
            ->map(function ($payment) {
                return [
                    'date' => $payment->payment_date,
                    'description' => 'Payment for Invoice #' . ($payment->invoice->invoice_number ?? 'N/A'),
                    'type' => 'Income',
                    'category' => 'Sales',
                    'amount' => $payment->amount,
                    'reference' => $payment->transaction_id,
                ];
            });

        $expenses = Expense::with('category')
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->map(function ($expense) {
                return [
                    'date' => $expense->date,
                    'description' => $expense->description ?? $expense->title,
                    'type' => 'Expense',
                    'category' => $expense->category->name ?? 'Uncategorized',
                    'amount' => -$expense->amount, // Negative for display logic if needed, but usually kept positive for tables
                    'reference' => null,
                ];
            });

        $transactions = $payments->merge($expenses)->sortByDesc('date');

        $currency = \App\Models\Setting::get('currency_symbol', '$');

        return view('reports.index', compact(
            'startDate', 'endDate',
            'totalRevenue', 'totalExpenses', 'netProfit', 'profitMargin',
            'cashFlowLabels', 'revenueData', 'expenseData',
            'expenseCategoryLabels', 'expenseCategoryData',
            'transactions', 'currency'
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
