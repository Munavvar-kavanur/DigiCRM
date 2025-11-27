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
        
        // Branch Filter (Super Admin)
        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->where('branch_id', $request->branch_id);
        }

        // Date Filter
        if ($request->has('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }

        $invoices = $query->latest('issue_date')->paginate(10);
        $clients = Client::orderBy('name')->get();
        $branches = \App\Models\Branch::orderBy('name')->get();

        // Stats
        $totalInvoices = Invoice::count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $unpaidInvoices = Invoice::whereIn('status', ['sent', 'overdue', 'draft'])->count();
        
        $totalAmount = Invoice::sum('total_amount');
        $receivedAmount = Invoice::where('status', 'paid')->sum('total_amount');
        $outstandingAmount = $totalAmount - $receivedAmount;

        $currency = \App\Models\Setting::get('currency_symbol', '$');

        return view('invoices.index', compact('invoices', 'clients', 'branches', 'totalInvoices', 'paidInvoices', 'unpaidInvoices', 'totalAmount', 'receivedAmount', 'outstandingAmount', 'currency'));
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
