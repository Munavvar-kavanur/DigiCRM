<?php

namespace App\Http\Controllers\Api;

use App\Models\Payroll;
use Illuminate\Http\Request;
use App\Http\Resources\PayrollResource;
use Illuminate\Support\Facades\Validator;

class PayrollController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Payroll::query();
        
        // Filter logic if needed (e.g. by employee)
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $payrolls = $query->with(['user', 'type'])->latest()->paginate(10);
        // Using generic response if resource not created yet, but ideally use resource
        return $this->sendResponse(PayrollResource::collection($payrolls)->response()->getData(true), 'Payrolls retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'payroll_type_id' => 'required|exists:payroll_types,id',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,paid',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $payroll = Payroll::create($request->all());
        return $this->sendResponse(new PayrollResource($payroll), 'Payroll record created successfully.', 201);
    }

    public function show($id)
    {
        $payroll = Payroll::with(['user', 'type'])->find($id);
        if (is_null($payroll)) {
            return $this->sendError('Payroll record not found.');
        }
        return $this->sendResponse(new PayrollResource($payroll), 'Payroll record retrieved successfully.');
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|in:pending,paid',
            'amount' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $payroll->update($request->all());
        return $this->sendResponse(new PayrollResource($payroll), 'Payroll record updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return $this->sendResponse([], 'Payroll record deleted successfully.');
    }
}
