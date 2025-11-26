<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

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

        $clients = $query->withCount('projects')->latest()->paginate(10);

        // Stats
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'active')->count();
        $inactiveClients = Client::where('status', 'inactive')->count();
        
        // Calculate Total Revenue (Sum of paid invoices for all clients)
        // Assuming Invoice model has 'total_amount' and 'status'
        $totalRevenue = \App\Models\Invoice::where('status', 'paid')->sum('total_amount');

        return view('clients.index', compact('clients', 'totalClients', 'activeClients', 'inactiveClients', 'totalRevenue'));
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

        return view('clients.show', compact('client', 'totalProjects', 'activeProjects', 'totalInvoiced', 'outstandingBalance'));
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
}
