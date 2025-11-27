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
        $query = Estimate::with(['client', 'project', 'branch.settings']);

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
        
        // Branch Filter (Super Admin)
        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->where('branch_id', $request->branch_id);
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
        $branches = \App\Models\Branch::orderBy('name')->get();

        // Stats
        $totalEstimates = Estimate::count();
        $acceptedEstimates = Estimate::where('status', 'accepted')->count();
        $pendingEstimates = Estimate::whereIn('status', ['sent', 'draft'])->count();
        $declinedEstimates = Estimate::where('status', 'rejected')->count();

        return view('estimates.index', compact('estimates', 'clients', 'branches', 'totalEstimates', 'acceptedEstimates', 'pendingEstimates', 'declinedEstimates'));
    }

    public function create()
    {
        if (auth()->user()->isSuperAdmin()) {
            $clients = request()->old('branch_id') 
                ? Client::where('status', 'active')->where('branch_id', request()->old('branch_id'))->get()
                : collect();
        } else {
            $clients = Client::where('status', 'active')->where('branch_id', auth()->user()->branch_id)->get();
        }
        $projects = request()->old('client_id')
            ? Project::where('status', '!=', 'completed')->where('client_id', request()->old('client_id'))->get()
            : collect();
        $branches = \App\Models\Branch::all();
        return view('estimates.create', compact('clients', 'projects', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'branch_id' => 'nullable|exists:branches,id',
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
            'estimate_number' => 'nullable|string|unique:estimates,estimate_number',
        ]);

        // Generate Estimate Number if not provided
        $estimateNumber = $request->input('estimate_number');
        if (empty($estimateNumber)) {
            $branchId = $request->input('branch_id');
            if (!$branchId && !auth()->user()->isSuperAdmin()) {
                 $branchId = auth()->user()->branch_id;
            }
            
            $branchCode = 'GEN'; // Default if no branch
            if ($branchId) {
                $branch = \App\Models\Branch::find($branchId);
                if ($branch && $branch->code) {
                    $branchCode = $branch->code;
                }
            }

            // Format: EST-{BRANCH_CODE}-{SEQUENCE}
            $prefix = 'EST-' . $branchCode . '-';
            
            // Find latest estimate for this branch to get sequence
            $latestEstimate = \App\Models\Estimate::where('estimate_number', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();
            
            $nextSequence = 1;
            if ($latestEstimate) {
                // Extract sequence
                $parts = explode('-', $latestEstimate->estimate_number);
                $lastSeq = end($parts);
                if (is_numeric($lastSeq)) {
                    $nextSequence = (int)$lastSeq + 1;
                }
            }
            
            $estimateNumber = $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
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

        $estimateData = [
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
        ];

        if (auth()->user()->isSuperAdmin() && $request->has('branch_id')) {
            $estimateData['branch_id'] = $request->branch_id;
        }

        $estimate = Estimate::create($estimateData);

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
        $estimate->load('items', 'client', 'project', 'branch');
        $settings = \App\Models\Setting::getAll($estimate->branch_id);
        return view('estimates.show', compact('estimate', 'settings'));
    }

    public function edit(Estimate $estimate)
    {
        $estimate->load('items');
        $clients = Client::where('status', 'active')->where('branch_id', $estimate->branch_id)->get();
        $projects = Project::where('status', '!=', 'completed')->where('client_id', $estimate->client_id)->get();
        $branches = \App\Models\Branch::all();
        $settings = \App\Models\Setting::getAll($estimate->branch_id);
        return view('estimates.edit', compact('estimate', 'clients', 'projects', 'branches', 'settings'));
    }

    public function update(Request $request, Estimate $estimate)
    {
        $validated = $request->validate([
            'estimate_number' => 'required|string|unique:estimates,estimate_number,' . $estimate->id,
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'branch_id' => 'nullable|exists:branches,id',
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

        $updateData = [
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
        ];

        if (auth()->user()->isSuperAdmin() && $request->has('branch_id')) {
            $updateData['branch_id'] = $request->branch_id;
        }

        $estimate->update($updateData);

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
        $settings = \App\Models\Setting::getAll($estimate->branch_id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('estimates.pdf', compact('estimate', 'settings'));
        return $pdf->download('estimate-' . $estimate->estimate_number . '.pdf');
    }
}
