<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // --- KPIs ---
        $totalClients = \App\Models\Client::count();
        $newClientsThisMonth = \App\Models\Client::whereMonth('created_at', now()->month)->count();

        $ongoingProjects = \App\Models\Project::where('status', 'in_progress')->count();
        $projectsDueThisWeek = \App\Models\Project::whereBetween('deadline', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $totalOutstandingInvoices = \App\Models\Invoice::where('status', '!=', 'paid')->sum('total_amount');
        $overdueInvoicesCount = \App\Models\Invoice::where('status', 'overdue')->orWhere(function($q) {
            $q->where('status', '!=', 'paid')->where('due_date', '<', now());
        })->count();

        $pendingEstimates = \App\Models\Estimate::where('status', 'sent')->count();
        $convertedEstimates = \App\Models\Estimate::where('status', 'accepted')->count(); // Assuming accepted means converted or close to it

        $expensesThisMonth = \App\Models\Expense::whereMonth('date', now()->month)->sum('amount');
        $expensesThisYear = \App\Models\Expense::whereYear('date', now()->year)->sum('amount');

        $upcomingPayroll = \App\Models\Payroll::where('payment_date', '>=', now())->orderBy('payment_date')->first();
        $totalPayrollThisMonth = \App\Models\Payroll::whereMonth('payment_date', now()->month)->sum('net_salary');

        $totalEmployees = \App\Models\User::where('is_employee', true)->count();
        // Attendance logic would go here if implemented

        // --- Charts ---
        // Revenue Trend (Last 6 Months)
        $revenueLabels = [];
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenueLabels[] = $date->format('M Y');
            $revenueData[] = \App\Models\Invoice::where('status', 'paid')
                ->whereMonth('issue_date', $date->month)
                ->whereYear('issue_date', $date->year)
                ->sum('total_amount');
        }

        // Invoice Status
        $invoiceStatusData = [
            \App\Models\Invoice::where('status', 'paid')->count(),
            \App\Models\Invoice::where('status', 'sent')->count(), // Unpaid/Sent
            $overdueInvoicesCount
        ];

        // Project Status
        $projectStatusData = [
            \App\Models\Project::where('status', 'in_progress')->count(),
            \App\Models\Project::where('status', 'completed')->count(),
            \App\Models\Project::where('status', 'on_hold')->count(),
        ];

        // Expenses by Category
        $expenseCategories = \App\Models\ExpenseCategory::withSum('expenses', 'amount')->get();
        $expenseCategoryLabels = $expenseCategories->pluck('name');
        $expenseCategoryData = $expenseCategories->pluck('expenses_sum_amount');

        // --- Action Needed ---
        $upcomingReminders = \App\Models\Reminder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereBetween('reminder_date', [now(), now()->addDays(7)])
            ->orderBy('reminder_date')
            ->take(5)
            ->get();
            
        $overdueReminders = \App\Models\Reminder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('reminder_date', '<', now())
            ->orderBy('reminder_date')
            ->take(5)
            ->get();

        $overdueInvoices = \App\Models\Invoice::where('status', 'overdue')
            ->orWhere(function($q) {
                $q->where('status', '!=', 'paid')->where('due_date', '<', now());
            })
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $projectsDueSoon = \App\Models\Project::where('status', '!=', 'completed')
            ->whereBetween('deadline', [now(), now()->addDays(7)])
            ->orderBy('deadline')
            ->take(5)
            ->get();

        // --- Recent Activity ---
        $recentClients = \App\Models\Client::latest()->take(5)->get();
        $recentProjects = \App\Models\Project::latest()->take(5)->get();
        $recentInvoices = \App\Models\Invoice::latest()->take(5)->get();
        $recentExpenses = \App\Models\Expense::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalClients', 'newClientsThisMonth',
            'ongoingProjects', 'projectsDueThisWeek',
            'totalOutstandingInvoices', 'overdueInvoicesCount',
            'pendingEstimates', 'convertedEstimates',
            'expensesThisMonth', 'expensesThisYear',
            'upcomingPayroll', 'totalPayrollThisMonth',
            'totalEmployees',
            'revenueLabels', 'revenueData',
            'invoiceStatusData',
            'projectStatusData',
            'expenseCategoryLabels', 'expenseCategoryData',
            'upcomingReminders', 'overdueReminders',
            'overdueInvoices', 'projectsDueSoon',
            'recentClients', 'recentProjects', 'recentInvoices', 'recentExpenses'
        ));
    }
}
