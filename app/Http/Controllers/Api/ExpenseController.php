<?php

namespace App\Http\Controllers\Api;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Resources\ExpenseResource;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Expense::query();
        
        if ($request->user()->branch_id) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        $expenses = $query->with(['category', 'payer'])->latest()->paginate(10);
        return $this->sendResponse(ExpenseResource::collection($expenses)->response()->getData(true), 'Expenses retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_payer_id' => 'nullable|exists:expense_payers,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $expense = Expense::create($request->all());
        return $this->sendResponse(new ExpenseResource($expense), 'Expense created successfully.', 201);
    }

    public function show($id)
    {
        $expense = Expense::with(['category', 'payer'])->find($id);
        if (is_null($expense)) {
            return $this->sendError('Expense not found.');
        }
        return $this->sendResponse(new ExpenseResource($expense), 'Expense retrieved successfully.');
    }

    public function update(Request $request, Expense $expense)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric',
            'date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $expense->update($request->all());
        return $this->sendResponse(new ExpenseResource($expense), 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return $this->sendResponse([], 'Expense deleted successfully.');
    }
}
