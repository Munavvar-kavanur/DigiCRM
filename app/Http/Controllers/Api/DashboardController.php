<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Reminder;
use App\Models\Branch;
use App\Models\Setting;

class DashboardController extends BaseApiController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $settings = Setting::getAll();
        
        // Determine Branch Context
        $branchId = null;
        if ($user->isSuperAdmin()) {
            if ($request->has('branch_id') && $request->branch_id != '') {
                $branchId = $request->branch_id;
            }
        } else {
            $branchId = $user->branch_id;
        }

        // Helper to apply branch filter
        $applyBranch = function($query) use ($branchId) {
            if ($branchId) {
                $query->where('branch_id', $branchId);
            }
        };

        // --- KPIs ---
        $clientsQuery = Client::query();
        $applyBranch($clientsQuery);
        $totalClients = $clientsQuery->count();

        $projectsQuery = Project::where('status', 'in_progress');
        $applyBranch($projectsQuery);
        $ongoingProjects = $projectsQuery->count();

        $outstandingQuery = Invoice::where('status', '!=', 'paid');
        $applyBranch($outstandingQuery);
        $totalOutstanding = $outstandingQuery->sum('total_amount');

        $overdueQuery = Invoice::where('status', 'overdue');
        $applyBranch($overdueQuery);
        $overdueInvoices = $overdueQuery->count();

        // Recent Activity
        $recentProjects = Project::latest()->take(5);
        $applyBranch($recentProjects);
        
        $recentInvoices = Invoice::latest()->take(5);
        $applyBranch($recentInvoices);

        $data = [
            'kpis' => [
                'total_clients' => $totalClients,
                'ongoing_projects' => $ongoingProjects,
                'outstanding_revenue' => $totalOutstanding,
                'overdue_invoices' => $overdueInvoices,
            ],
            'recent_projects' => $recentProjects->get(),
            'recent_invoices' => $recentInvoices->get(),
            'currency_symbol' => $settings['currency_symbol'] ?? '$',
        ];

        return $this->sendResponse($data, 'Dashboard data retrieved successfully.');
    }
}
