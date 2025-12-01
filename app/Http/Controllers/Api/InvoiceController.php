<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Resources\InvoiceResource;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Invoice::query();
        
        if ($request->user()->branch_id) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        $invoices = $query->with(['client', 'project'])->latest()->paginate(10);
        return $this->sendResponse(InvoiceResource::collection($invoices)->response()->getData(true), 'Invoices retrieved successfully.');
    }

    public function store(Request $request)
    {
        // Simplified validation - real world would need items validation
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'branch_id' => 'required|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $invoice = Invoice::create($request->all());
        
        // Handle items if present in request (assuming 'items' array)
        if ($request->has('items')) {
            $invoice->items()->createMany($request->items);
        }

        return $this->sendResponse(new InvoiceResource($invoice), 'Invoice created successfully.', 201);
    }

    public function show($id)
    {
        $invoice = Invoice::with(['client', 'project', 'items', 'payments'])->find($id);
        if (is_null($invoice)) {
            return $this->sendError('Invoice not found.');
        }
        return $this->sendResponse(new InvoiceResource($invoice), 'Invoice retrieved successfully.');
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|in:draft,sent,paid,overdue,cancelled',
            'total_amount' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $invoice->update($request->all());
        return $this->sendResponse(new InvoiceResource($invoice), 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return $this->sendResponse([], 'Invoice deleted successfully.');
    }
}
