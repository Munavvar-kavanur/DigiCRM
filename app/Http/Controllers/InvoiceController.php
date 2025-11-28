<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'project', 'branch.settings']);

        // Helper to apply branch filter
        $applyBranch = function ($q) use ($request) {
            if (auth()->user()->isSuperAdmin()) {
                if ($request->has('branch_id') && $request->branch_id != '') {
                    $q->where('branch_id', $request->branch_id);
                }
            } else {
                $q->where('branch_id', auth()->user()->branch_id);
            }
        };

        // Apply branch filter to main query
        $applyBranch($query);

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
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
            $query->whereDate('issue_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }

        $invoices = $query->latest('issue_date')->paginate(10);
        
        // Clients & Branches for filters
        $clientsQuery = Client::orderBy('name');
        $applyBranch($clientsQuery);
        $clients = $clientsQuery->get();
        
        $branches = \App\Models\Branch::orderBy('name')->get();

        // Stats Logic with Multi-Currency Support
        $getCurrencyTotals = function ($status = null) use ($request, $applyBranch) {
            // If a specific branch is selected (or user is not super admin), use that branch's currency
            $targetBranchId = null;
            if (!auth()->user()->isSuperAdmin()) {
                $targetBranchId = auth()->user()->branch_id;
            } elseif ($request->has('branch_id') && $request->branch_id != '') {
                $targetBranchId = $request->branch_id;
            }

            if ($targetBranchId) {
                $branch = \App\Models\Branch::find($targetBranchId);
                $currency = $branch ? $branch->currency : '$';
                
                $query = Invoice::where('branch_id', $targetBranchId);
                if ($status === 'paid') {
                    $query->where('status', 'paid');
                } elseif ($status === 'unpaid') {
                    $query->whereIn('status', ['sent', 'overdue', 'draft']);
                } elseif ($status === 'outstanding') {
                    // For outstanding, we want total - paid. 
                    // But here we'll just sum total_amount for non-paid for simplicity or use logic below
                    // Actually, simpler to just sum total_amount of all invoices - sum of paid? 
                    // Let's stick to the requested stats: Total, Received, Outstanding.
                    // Outstanding = Total - Received.
                }

                $amount = 0;
                if ($status === 'paid') {
                    $amount = Invoice::where('branch_id', $targetBranchId)->where('status', 'paid')->sum('total_amount');
                } elseif ($status === 'total') {
                    $amount = Invoice::where('branch_id', $targetBranchId)->sum('total_amount');
                }
                
                return [['currency' => $currency, 'amount' => $amount]];
            }

            // Global View (Super Admin) - Aggregate by currency
            $totals = [];
            $allBranches = \App\Models\Branch::all();
            
            // Initialize totals for each currency found
            $currencyMap = []; // currency_symbol => amount

            foreach ($allBranches as $branch) {
                $currency = $branch->currency;
                
                $branchQuery = Invoice::where('branch_id', $branch->id);
                if ($status === 'paid') {
                    $branchQuery->where('status', 'paid');
                }
                
                $amount = $branchQuery->sum('total_amount');

                if (!isset($currencyMap[$currency])) {
                    $currencyMap[$currency] = 0;
                }
                $currencyMap[$currency] += $amount;
            }

            foreach ($currencyMap as $curr => $amt) {
                if ($amt > 0) {
                    $totals[] = ['currency' => $curr, 'amount' => $amt];
                }
            }
            
            return empty($totals) ? [['currency' => '$', 'amount' => 0]] : $totals;
        };

        // Calculate Stats
        $totalInvoices = Invoice::query();
        $applyBranch($totalInvoices);
        $totalInvoices = $totalInvoices->count();

        $unpaidInvoices = Invoice::whereIn('status', ['sent', 'overdue', 'draft']);
        $applyBranch($unpaidInvoices);
        $unpaidInvoices = $unpaidInvoices->count();

        $totalAmounts = $getCurrencyTotals('total');
        $receivedAmounts = $getCurrencyTotals('paid');
        
        // Calculate Outstanding (Total - Received) per currency
        $outstandingAmounts = [];
        // Helper to map currency arrays to key-value
        $mapToKeyVal = function($arr) {
            $res = [];
            foreach($arr as $item) $res[$item['currency']] = $item['amount'];
            return $res;
        };
        
        $totalMap = $mapToKeyVal($totalAmounts);
        $receivedMap = $mapToKeyVal($receivedAmounts);
        
        $allCurrencies = array_unique(array_merge(array_keys($totalMap), array_keys($receivedMap)));
        
        foreach ($allCurrencies as $curr) {
            $tot = $totalMap[$curr] ?? 0;
            $rec = $receivedMap[$curr] ?? 0;
            $out = $tot - $rec;
            if ($out > 0) { // Only show if there is outstanding amount
                 $outstandingAmounts[] = ['currency' => $curr, 'amount' => $out];
            }
        }
        if (empty($outstandingAmounts)) $outstandingAmounts[] = ['currency' => '$', 'amount' => 0];

        // Pass selected branch for view context
        $selectedBranch = null;
        if ($request->has('branch_id') && $request->branch_id) {
            $selectedBranch = \App\Models\Branch::find($request->branch_id);
        }

        return view('invoices.index', compact(
            'invoices', 'clients', 'branches', 
            'totalInvoices', 'unpaidInvoices', 
            'totalAmounts', 'receivedAmounts', 'outstandingAmounts',
            'selectedBranch'
        ));
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
        
        $settings = \App\Models\Setting::getAll();

        return view('invoices.create', compact('clients', 'projects', 'branches', 'settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'nullable|string|unique:invoices,invoice_number',
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'branch_id' => 'nullable|exists:branches,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,sent,paid,overdue',
            'items' => 'required|array|min:1',
            'items.*.title' => 'nullable|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:fixed,percent',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_frequency' => 'nullable|required_if:is_recurring,1|in:monthly,quarterly,semi_annually,yearly',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }
        $taxRate = $request->tax ?? 0;
        $discountValue = $request->discount ?? 0;
        $discountType = $request->discount_type ?? 'percent';

        $taxAmount = ($subtotal * $taxRate) / 100;

        if ($discountType === 'fixed') {
            $discountAmount = $discountValue;
        } else {
            $discountAmount = ($subtotal * $discountValue) / 100;
        }

        $grand_total = $subtotal + $taxAmount - $discountAmount;
        $balance_due = $grand_total; // Initially full amount is due

        // Generate Invoice Number if not provided
        $invoiceNumber = $request->input('invoice_number');
        if (empty($invoiceNumber)) {
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

            // Format: INV-{BRANCH_CODE}-{SEQUENCE}
            $prefix = 'INV-' . $branchCode . '-';
            
            // Find latest invoice for this branch to get sequence
            $latestInvoice = \App\Models\Invoice::where('invoice_number', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();
            
            $nextSequence = 1;
            if ($latestInvoice) {
                // Extract sequence
                $parts = explode('-', $latestInvoice->invoice_number);
                $lastSeq = end($parts);
                if (is_numeric($lastSeq)) {
                    $nextSequence = (int)$lastSeq + 1;
                }
            }
            
            $invoiceNumber = $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
        }

        $invoiceData = [
            'invoice_number' => $invoiceNumber,
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'],
            'branch_id' => $request->branch_id,
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'subtotal' => $subtotal,
            'tax' => $taxRate,
            'discount' => $discountValue,
            'discount_type' => $discountType,
            'grand_total' => $grand_total,
            'total_amount' => $grand_total, // Keep for backward compatibility if needed
            'balance_due' => $grand_total, // Assuming full amount is due
            'notes' => $request->input('notes'),
            'terms' => $request->input('terms'),
            'is_recurring' => $request->has('is_recurring'),
            'recurring_frequency' => $request->input('recurring_frequency'),
        ];

        if (auth()->user()->isSuperAdmin() && $request->has('branch_id')) {
            $invoiceData['branch_id'] = $request->branch_id;
        }

        $invoice = Invoice::create($invoiceData);

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'title' => $item['title'] ?? null,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'items', 'payments', 'branch']);
        $settings = \App\Models\Setting::getAll($invoice->branch_id);
        return view('invoices.show', compact('invoice', 'settings'));
    }

    public function edit(Invoice $invoice)
    {
        $clients = Client::where('status', 'active')->where('branch_id', $invoice->branch_id)->get();
        $projects = Project::where('status', '!=', 'completed')->where('client_id', $invoice->client_id)->get();
        $branches = \App\Models\Branch::all();
        $settings = \App\Models\Setting::getAll($invoice->branch_id);
        return view('invoices.edit', compact('invoice', 'clients', 'projects', 'branches', 'settings'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'branch_id' => 'nullable|exists:branches,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,sent,paid,overdue',
            'items' => 'required|array|min:1',
            'items.*.title' => 'nullable|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:fixed,percent',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }
        $taxRate = $request->tax ?? 0;
        $discountValue = $request->discount ?? 0;
        $discountType = $request->discount_type ?? 'percent';

        $taxAmount = ($subtotal * $taxRate) / 100;

        if ($discountType === 'fixed') {
            $discountAmount = $discountValue;
        } else {
            $discountAmount = ($subtotal * $discountValue) / 100;
        }

        $grand_total = $subtotal + $taxAmount - $discountAmount;
        
        // Recalculate balance due based on existing payments
        $total_paid = $invoice->payments()->sum('amount');
        $balance_due = $grand_total - $total_paid;

        $updateData = [
            'invoice_number' => $request->invoice_number,
            'client_id' => $request->client_id,
            'project_id' => $request->project_id,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'subtotal' => $subtotal,
            'tax' => $taxRate,
            'discount' => $discountValue,
            'discount_type' => $discountType,
            'grand_total' => $grand_total,
            'total_amount' => $grand_total,
            'balance_due' => $balance_due,
            'notes' => $request->notes,
            'terms' => $request->terms,
        ];

        if (auth()->user()->isSuperAdmin() && $request->has('branch_id')) {
            $updateData['branch_id'] = $request->branch_id;
        }

        $invoice->update($updateData);

        // Sync items (delete all and recreate for simplicity, or update smart)
        $invoice->items()->delete();
        foreach ($request->items as $item) {
            $invoice->items()->create([
                'title' => $item['title'] ?? null,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'items']);
        $settings = \App\Models\Setting::getAll($invoice->branch_id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice', 'settings'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
