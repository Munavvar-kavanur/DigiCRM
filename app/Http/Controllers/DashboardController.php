<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Estimate;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\Reminder;
use App\Models\ExpenseCategory;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $settings = Setting::getAll();
        
        // Determine Branch Context
        $branchId = null;
        $selectedBranch = null;

        if ($user->isSuperAdmin()) {
            if ($request->has('branch_id') && $request->branch_id != '') {
                $branchId = $request->branch_id;
                $selectedBranch = Branch::find($branchId);
            }
        } else {
            $branchId = $user->branch_id;
            $selectedBranch = $user->branch;
        }

        // Helper to apply branch filter
        $applyBranch = function($query) use ($branchId) {
            if ($branchId) {
                $query->where('branch_id', $branchId);
            }
        };

        // --- KPIs ---
        
        // Clients
        $clientsQuery = Client::query();
        $applyBranch($clientsQuery);
        $totalClients = $clientsQuery->count();
        
        $newClientsQuery = Client::whereMonth('created_at', now()->month);
        $applyBranch($newClientsQuery);
        $newClientsThisMonth = $newClientsQuery->count();

        // Projects
        $projectsQuery = Project::where('status', 'in_progress');
        $applyBranch($projectsQuery);
        $ongoingProjects = $projectsQuery->count();

        $projectsDueQuery = Project::whereBetween('deadline', [now()->startOfWeek(), now()->endOfWeek()]);
        $applyBranch($projectsDueQuery);
        $projectsDueThisWeek = $projectsDueQuery->count();

        // Financials (Multi-Currency Support)
        
        // Helper to get totals grouped by currency
        $getCurrencyTotals = function($query, $amountColumn = 'total_amount') use ($branchId, $selectedBranch, $settings) {
            // If a specific branch is selected, we just return that single value with its currency
            if ($branchId) {
                $total = $query->sum($amountColumn);
                $currency = $selectedBranch ? $selectedBranch->currency : ($settings['currency_symbol'] ?? '$');
                return [['currency' => $currency, 'amount' => $total]];
            }

            // If Global View (Super Admin), we need to group by branch currency
            // This is tricky because currency is on the Branch model, or Settings table.
            // For simplicity and performance, we will iterate branches and sum up.
            
            $totals = [];
            $branches = Branch::all();
            
            // Add "No Branch" / Global items if any (usually none in strict mode, but good to handle)
            $globalTotal = (clone $query)->whereNull('branch_id')->sum($amountColumn);
            if ($globalTotal > 0) {
                $currency = $settings['currency_symbol'] ?? '$';
                $totals[$currency] = ($totals[$currency] ?? 0) + $globalTotal;
            }

            foreach ($branches as $branch) {
                $branchTotal = (clone $query)->where('branch_id', $branch->id)->sum($amountColumn);
                if ($branchTotal > 0) {
                    $currency = $branch->currency;
                    $totals[$currency] = ($totals[$currency] ?? 0) + $branchTotal;
                }
            }

            $formatted = [];
            foreach ($totals as $curr => $amount) {
                $formatted[] = ['currency' => $curr, 'amount' => $amount];
            }
            
            return empty($formatted) ? [['currency' => $settings['currency_symbol'] ?? '$', 'amount' => 0]] : $formatted;
        };

        // Outstanding Invoices
        $outstandingQuery = Invoice::where('status', '!=', 'paid');
        $totalOutstandingInvoices = $getCurrencyTotals($outstandingQuery);

        $overdueInvoicesQuery = Invoice::where(function($q) {
            $q->where('status', 'overdue')->orWhere(function($sub) {
                $sub->where('status', '!=', 'paid')->where('due_date', '<', now());
            });
        });
        $applyBranch($overdueInvoicesQuery);
        $overdueInvoicesCount = $overdueInvoicesQuery->count();

        // Expenses
        $expensesMonthQuery = Expense::whereMonth('date', now()->month);
        $expensesThisMonth = $getCurrencyTotals($expensesMonthQuery, 'amount');
        
        $expensesYearQuery = Expense::whereYear('date', now()->year);
        $expensesThisYear = $getCurrencyTotals($expensesYearQuery, 'amount');

        // Payroll
        $payrollQuery = Payroll::whereMonth('payment_date', now()->month);
        $totalPayrollThisMonth = $getCurrencyTotals($payrollQuery, 'net_salary');

        $upcomingPayrollQuery = Payroll::where('payment_date', '>=', now())->orderBy('payment_date');
        $applyBranch($upcomingPayrollQuery);
        $upcomingPayroll = $upcomingPayrollQuery->first();

        // Employees
        $employeesQuery = User::where('is_employee', true);
        $applyBranch($employeesQuery);
        $totalEmployees = $employeesQuery->count();


        // --- Charts (Simplified for now - using Base Currency or just Sum) ---
        // Note: Mixing currencies in charts is bad practice. 
        // If Branch Selected: Accurate.
        // If Global: We will just sum them up (assuming 1:1 for visual trend) OR just show count.
        // Let's stick to Sum for now but maybe we should disable charts in global view if multiple currencies exist?
        // For now, let's keep it simple and just sum.
        
        // Revenue Trend (Last 6 Months)
        $revenueLabels = [];
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenueLabels[] = $date->format('M Y');
            
            $revQuery = Invoice::where('status', 'paid')
                ->whereMonth('issue_date', $date->month)
                ->whereYear('issue_date', $date->year);
            $applyBranch($revQuery);
            $revenueData[] = $revQuery->sum('total_amount');
        }

        // Invoice Status
        $paidQuery = Invoice::where('status', 'paid'); $applyBranch($paidQuery);
        $sentQuery = Invoice::where('status', 'sent'); $applyBranch($sentQuery);
        
        $invoiceStatusData = [
            $paidQuery->count(),
            $sentQuery->count(),
            $overdueInvoicesCount
        ];

        // Expense Categories
        $expenseCategories = ExpenseCategory::withSum(['expenses' => function($q) use ($branchId) {
            if ($branchId) $q->where('branch_id', $branchId);
        }], 'amount')->get();
        
        $expenseCategoryLabels = $expenseCategories->pluck('name');
        $expenseCategoryData = $expenseCategories->pluck('expenses_sum_amount');


        // --- Action Needed ---
        $remindersQuery = Reminder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereBetween('reminder_date', [now(), now()->addDays(7)])
            ->orderBy('reminder_date')
            ->take(5);
        // Reminders are user-specific, so branch filter is implicit via user, but if Super Admin wants to see all reminders for a branch?
        // Usually reminders are personal. Let's keep them personal to the logged-in user.
        $upcomingReminders = $remindersQuery->get();
            
        $overdueRemindersQuery = Reminder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('reminder_date', '<', now())
            ->orderBy('reminder_date')
            ->take(5);
        $overdueReminders = $overdueRemindersQuery->get();

        $overdueInvoicesListQuery = Invoice::where(function($q) {
                $q->where('status', 'overdue')
                  ->orWhere(function($sub) {
                      $sub->where('status', '!=', 'paid')->where('due_date', '<', now());
                  });
            })
            ->orderBy('due_date')
            ->take(5);
        $applyBranch($overdueInvoicesListQuery);
        $overdueInvoices = $overdueInvoicesListQuery->get();

        $projectsDueSoonQuery = Project::where('status', '!=', 'completed')
            ->whereBetween('deadline', [now(), now()->addDays(7)])
            ->orderBy('deadline')
            ->take(5);
        $applyBranch($projectsDueSoonQuery);
        $projectsDueSoon = $projectsDueSoonQuery->get();

        // --- Recent Activity ---
        $recentClientsQuery = Client::latest()->take(5); $applyBranch($recentClientsQuery);
        $recentClients = $recentClientsQuery->get();
        
        $recentProjectsQuery = Project::latest()->take(5); $applyBranch($recentProjectsQuery);
        $recentProjects = $recentProjectsQuery->get();
        
        $recentInvoicesQuery = Invoice::latest()->take(5); $applyBranch($recentInvoicesQuery);
        $recentInvoices = $recentInvoicesQuery->get();
        
        $recentExpensesQuery = Expense::latest()->take(5); $applyBranch($recentExpensesQuery);
        $recentExpenses = $recentExpensesQuery->get();

        // --- Branch Analytics (Super Admin Only) ---
        $branchAnalytics = [];
        $branchRevenueLabels = [];
        $branchRevenueData = [];
        $branches = collect();

        if ($user->isSuperAdmin()) {
            $branches = Branch::all(); // For the dropdown
            
            // Only calculate analytics if NO specific branch is selected (Global View)
            if (!$branchId) {
                $branchesWithStats = Branch::withCount(['clients', 'projects' => function($q) {
                    $q->where('status', 'in_progress');
                }])->get();

                foreach ($branchesWithStats as $branch) {
                    // Calculate Revenue (Paid Invoices)
                    $revenue = Invoice::withoutGlobalScope(\App\Models\Scopes\BranchScope::class)
                        ->where('branch_id', $branch->id)
                        ->where('status', 'paid')
                        ->sum('total_amount');

                    // Calculate Outstanding (Unpaid/Overdue)
                    $outstanding = Invoice::withoutGlobalScope(\App\Models\Scopes\BranchScope::class)
                        ->where('branch_id', $branch->id)
                        ->where('status', '!=', 'paid')
                        ->sum('total_amount');

                    $branchAnalytics[] = [
                        'name' => $branch->name,
                        'code' => $branch->code,
                        'clients_count' => $branch->clients_count,
                        'active_projects_count' => $branch->projects_count,
                        'revenue' => $revenue,
                        'outstanding' => $outstanding,
                        'currency' => $branch->currency, // Pass currency for display
                    ];

                    $branchRevenueLabels[] = $branch->code ?? $branch->name;
                    $branchRevenueData[] = $revenue;
                }
            }
        }

        return view('dashboard', compact(
            'totalClients', 'newClientsThisMonth',
            'ongoingProjects', 'projectsDueThisWeek',
            'totalOutstandingInvoices', 'overdueInvoicesCount',
            'expensesThisMonth', 'expensesThisYear',
            'upcomingPayroll', 'totalPayrollThisMonth',
            'totalEmployees',
            'revenueLabels', 'revenueData',
            'invoiceStatusData',
            'expenseCategoryLabels', 'expenseCategoryData',
            'upcomingReminders', 'overdueReminders',
            'overdueInvoices', 'projectsDueSoon',
            'recentClients', 'recentProjects', 'recentInvoices', 'recentExpenses',
            'branchAnalytics', 'branchRevenueLabels', 'branchRevenueData',
            'settings', 'branches', 'selectedBranch'
        ));
    }
}
