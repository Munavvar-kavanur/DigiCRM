<?php

namespace App\Http\Controllers\Api;

use App\Models\Estimate;
use Illuminate\Http\Request;
use App\Http\Resources\EstimateResource;
use Illuminate\Support\Facades\Validator;

class EstimateController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Estimate::query();
        
        if ($request->user()->branch_id) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        $estimates = $query->with(['client', 'project'])->latest()->paginate(10);
        return $this->sendResponse(EstimateResource::collection($estimates)->response()->getData(true), 'Estimates retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'estimate_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:estimate_date',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:draft,sent,accepted,declined',
            'branch_id' => 'required|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $estimate = Estimate::create($request->all());

        if ($request->has('items')) {
            $estimate->items()->createMany($request->items);
        }

        return $this->sendResponse(new EstimateResource($estimate), 'Estimate created successfully.', 201);
    }

    public function show($id)
    {
        $estimate = Estimate::with(['client', 'project', 'items'])->find($id);
        if (is_null($estimate)) {
            return $this->sendError('Estimate not found.');
        }
        return $this->sendResponse(new EstimateResource($estimate), 'Estimate retrieved successfully.');
    }

    public function update(Request $request, Estimate $estimate)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|in:draft,sent,accepted,declined',
            'total_amount' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $estimate->update($request->all());
        return $this->sendResponse(new EstimateResource($estimate), 'Estimate updated successfully.');
    }

    public function destroy(Estimate $estimate)
    {
        $estimate->delete();
        return $this->sendResponse([], 'Estimate deleted successfully.');
    }
}
