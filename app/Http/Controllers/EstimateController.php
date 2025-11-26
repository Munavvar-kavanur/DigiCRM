<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class EstimateController extends Controller
{
    public function index(Request $request)
    {
        $query = Estimate::with(['client', 'project']);

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('estimate_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status Filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Client Filter
        if ($request->has('client_id') && $request->client_id != '') {
            $query->where('client_id', $request->client_id);
        }

        // Date Filter
        if ($request->has('date_from')) {
            $query->whereDate('valid_until', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('valid_until', '<=', $request->date_to);
        }

        $estimates = $query->latest()->paginate(10);
        $clients = Client::orderBy('name')->get();

        // Stats
        $totalEstimates = Estimate::count();
        $acceptedEstimates = Estimate::where('status', 'accepted')->count();
        $pendingEstimates = Estimate::whereIn('status', ['sent', 'draft'])->count();
        $declinedEstimates = Estimate::where('status', 'rejected')->count();

        return view('estimates.index', compact('estimates', 'clients', 'totalEstimates', 'acceptedEstimates', 'pendingEstimates', 'declinedEstimates'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $projects = Project::where('status', '!=', 'completed')->get();
        return view('estimates.create', compact('clients', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'valid_until' => 'required|date',
            'status' => 'required|in:draft,sent,accepted,rejected',
            'items' => 'required|array|min:1',
            'items.*.title' => 'nullable|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:fixed,percent',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        $estimateNumber = $request->input('estimate_number');
        if (empty($estimateNumber)) {
            $latestEstimate = Estimate::latest()->first();
            $nextId = $latestEstimate ? $latestEstimate->id + 1 : 1;
            $estimateNumber = 'EST-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        }

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $taxRate = $request->input('tax', 0);
        $discountValue = $request->input('discount', 0);
        $discountType = $request->input('discount_type', 'percent');
        
        $taxAmount = ($subtotal * $taxRate) / 100;
        
        if ($discountType === 'fixed') {
            $discountAmount = $discountValue;
        } else {
            $discountAmount = ($subtotal * $discountValue) / 100;
        }

        $totalAmount = $subtotal + $taxAmount - $discountAmount;

        $estimate = Estimate::create([
            'estimate_number' => $estimateNumber,
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'],
            'valid_until' => $validated['valid_until'],
            'status' => $validated['status'],
            'subtotal' => $subtotal,
            'tax' => $taxRate,
            'discount' => $discountValue,
            'discount_type' => $discountType,
            'total_amount' => $totalAmount,
            'notes' => $request->input('notes'),
            'terms' => $request->input('terms'),
        ]);

        foreach ($request->items as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            
            $estimate->items()->create([
                'title' => $item['title'] ?? null,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $lineTotal,
            ]);
        }

        return redirect()->route('estimates.index')->with('success', 'Estimate created successfully.');
    }

    public function show(Estimate $estimate)
    {
        $estimate->load('items', 'client', 'project');
        return view('estimates.show', compact('estimate'));
    }

    public function edit(Estimate $estimate)
    {
        $estimate->load('items');
        $clients = Client::where('status', 'active')->get();
        $projects = Project::where('status', '!=', 'completed')->get();
        return view('estimates.edit', compact('estimate', 'clients', 'projects'));
    }

    public function update(Request $request, Estimate $estimate)
    {
        $validated = $request->validate([
            'estimate_number' => 'required|string|unique:estimates,estimate_number,' . $estimate->id,
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'valid_until' => 'required|date',
            'status' => 'required|in:draft,sent,accepted,rejected',
            'items' => 'required|array|min:1',
            'items.*.title' => 'nullable|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:fixed,percent',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $taxRate = $request->input('tax', 0);
        $discountValue = $request->input('discount', 0);
        $discountType = $request->input('discount_type', 'percent');
        
        $taxAmount = ($subtotal * $taxRate) / 100;
        
        if ($discountType === 'fixed') {
            $discountAmount = $discountValue;
        } else {
            $discountAmount = ($subtotal * $discountValue) / 100;
        }

        $totalAmount = $subtotal + $taxAmount - $discountAmount;

        $estimate->update([
            'estimate_number' => $validated['estimate_number'],
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'],
            'valid_until' => $validated['valid_until'],
            'status' => $validated['status'],
            'subtotal' => $subtotal,
            'tax' => $taxRate,
            'discount' => $discountValue,
            'discount_type' => $discountType,
            'total_amount' => $totalAmount,
            'notes' => $request->input('notes'),
            'terms' => $request->input('terms'),
        ]);

        // Delete old items and re-create
        $estimate->items()->delete();

        foreach ($request->items as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            
            $estimate->items()->create([
                'title' => $item['title'] ?? null,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $lineTotal,
            ]);
        }

        return redirect()->route('estimates.index')->with('success', 'Estimate updated successfully.');
    }

    public function destroy(Estimate $estimate)
    {
        $estimate->delete();
        return redirect()->route('estimates.index')->with('success', 'Estimate deleted successfully.');
    }

    public function downloadPdf(Estimate $estimate)
    {
        $estimate->load(['client', 'project', 'items']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('estimates.pdf', compact('estimate'));
        return $pdf->download('estimate-' . $estimate->estimate_number . '.pdf');
    }
}
