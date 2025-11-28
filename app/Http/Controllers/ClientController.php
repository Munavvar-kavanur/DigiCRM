<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $applyBranch = function ($query) use ($request) {
            if (auth()->user()->isSuperAdmin()) {
                if ($request->has('branch_id') && $request->branch_id != '') {
                    $query->where('branch_id', $request->branch_id);
                }
            } else {
                $query->where('branch_id', auth()->user()->branch_id);
            }
        };

        $query = Client::query();
        $applyBranch($query);

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $clients = $query->withCount('projects')->with('branch')->latest()->paginate(10);
        $branches = \App\Models\Branch::orderBy('name')->get();

        // Stats
        $statsQuery = Client::query();
        $applyBranch($statsQuery);
        
        $totalClients = (clone $statsQuery)->count();
        $activeClients = (clone $statsQuery)->where('status', 'active')->count();
        $inactiveClients = (clone $statsQuery)->where('status', 'inactive')->count();
        
        // Calculate Total Revenue (Sum of paid invoices for all clients)
        $revenueQuery = \App\Models\Invoice::where('status', 'paid');
        $applyBranch($revenueQuery);

        // Fetch totals grouped by branch
        $branchTotals = $revenueQuery->select('branch_id', \DB::raw('SUM(total_amount) as total'))
            ->groupBy('branch_id')
            ->get();

        $currencyTotals = [];

        foreach ($branchTotals as $branchTotal) {
            $branchId = $branchTotal->branch_id;
            // Get currency code for this branch (or global if null)
            $currencyCode = \App\Models\Setting::get('currency_code', 'USD', $branchId);
            $currencySymbol = \App\Models\Setting::get('currency_symbol', '$', $branchId);

            if (!isset($currencyTotals[$currencyCode])) {
                $currencyTotals[$currencyCode] = [
                    'amount' => 0,
                    'symbol' => $currencySymbol,
                    'code' => $currencyCode
                ];
            }
            $currencyTotals[$currencyCode]['amount'] += $branchTotal->total;
        }

        $revenueStats = collect($currencyTotals)->map(function($data) {
            return [
                'amount' => $data['amount'],
                'formatted' => number_format($data['amount'], 2),
                'symbol' => $data['symbol'],
                'code' => $data['code']
            ];
        });

        $settings = \App\Models\Setting::getAll();
        
        $selectedBranch = null;
        if ($request->filled('branch_id')) {
            $selectedBranch = \App\Models\Branch::find($request->branch_id);
        }

        return view('clients.index', compact('clients', 'totalClients', 'activeClients', 'inactiveClients', 'revenueStats', 'settings', 'branches', 'selectedBranch'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        $client->load(['projects', 'invoices', 'estimates']);

        // Calculate Stats
        $totalProjects = $client->projects->count();
        $activeProjects = $client->projects->where('status', 'in_progress')->count();
        
        $totalInvoiced = $client->invoices->sum('total_amount');
        $paidInvoices = $client->invoices->where('status', 'paid')->sum('total_amount');
        $outstandingBalance = $totalInvoiced - $paidInvoices;

        $settings = \App\Models\Setting::getAll($client->branch_id);

        return view('clients.show', compact('client', 'totalProjects', 'activeProjects', 'totalInvoiced', 'outstandingBalance', 'settings'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    public function getProjects(Client $client)
    {
        return response()->json($client->projects()->select('id', 'title')->orderBy('title')->get());
    }
}
