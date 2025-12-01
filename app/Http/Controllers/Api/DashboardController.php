<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
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

        // Calculate KPIs
        $clientsQuery = Client::query();
        $applyBranch($clientsQuery);
        $totalClients = $clientsQuery->count();

        $projectsQuery = Project::where('status', 'in_progress');
        $applyBranch($projectsQuery);
        $ongoingProjects = $projectsQuery->count();
        
        // Revenue
        $revenueQuery = Invoice::where('status', 'paid');
        $applyBranch($revenueQuery);
        $totalRevenue = $revenueQuery->sum('total_amount');

        $outstandingQuery = Invoice::whereIn('status', ['pending', 'sent']);
        $applyBranch($outstandingQuery);
        $outstandingRevenue = $outstandingQuery->sum('total_amount');

        $overdueQuery = Invoice::where('status', 'overdue');
        $applyBranch($overdueQuery);
        $overdueInvoices = $overdueQuery->count();
        
        // Recent Projects
        $recentProjectsQuery = Project::with('client')->latest()->take(5);
        $applyBranch($recentProjectsQuery);
        $recentProjects = $recentProjectsQuery->get();
        
        // Recent Invoices
        $recentInvoicesQuery = Invoice::with('client')->latest()->take(5);
        $applyBranch($recentInvoicesQuery);
        $recentInvoices = $recentInvoicesQuery->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'kpis' => [
                    'totalClients' => $totalClients,
                    'ongoingProjects' => $ongoingProjects,
                    'totalRevenue' => $totalRevenue,
                    'outstandingRevenue' => $outstandingRevenue,
                    'overdueInvoices' => $overdueInvoices,
                ],
                'recentProjects' => $recentProjects,
                'recentInvoices' => $recentInvoices,
                'currency_symbol' => $settings['currency_symbol'] ?? '$',
            ],
            'message' => 'Dashboard data retrieved successfully',
        ]);
    }
}
