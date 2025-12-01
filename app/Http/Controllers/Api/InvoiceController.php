<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('client')->latest();
        
        if ($request->user()->branch_id) {
            $query->where('branch_id', $request->user()->branch_id);
        }
        
        $invoices = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $invoices,
            'message' => 'Invoices retrieved successfully',
        ]);
    }

    public function show($id)
    {
        $invoice = Invoice::with('client')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $invoice,
            'message' => 'Invoice retrieved successfully',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|unique:invoices',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Auto-assign branch_id if user has one
        if ($request->user()->branch_id) {
            $validated['branch_id'] = $request->user()->branch_id;
        }

        $invoice = Invoice::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $invoice->load('client'),
            'message' => 'Invoice created successfully',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        $validated = $request->validate([
            'client_id' => 'exists:clients,id',
            'invoice_number' => 'string|unique:invoices,invoice_number,' . $id,
            'issue_date' => 'date',
            'due_date' => 'date',
            'total_amount' => 'numeric',
            'status' => 'in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        $invoice->update($validated);
        
        return response()->json([
            'success' => true,
            'data' => $invoice->load('client'),
            'message' => 'Invoice updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Invoice deleted successfully',
        ]);
    }
}
