<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = User::where('is_employee', true);
        
        if ($request->user()->branch_id) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        $employees = $query->latest()->paginate(10);
        return $this->sendResponse(UserResource::collection($employees)->response()->getData(true), 'Employees retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'branch_id' => 'required|exists:branches,id',
            'employee_type_id' => 'required|exists:employee_types,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['is_employee'] = true;

        $employee = User::create($input);
        return $this->sendResponse(new UserResource($employee), 'Employee created successfully.', 201);
    }

    public function show($id)
    {
        $employee = User::where('is_employee', true)->find($id);
        if (is_null($employee)) {
            return $this->sendError('Employee not found.');
        }
        return $this->sendResponse(new UserResource($employee), 'Employee retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('is_employee', true)->find($id);
        if (is_null($employee)) {
            return $this->sendError('Employee not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'branch_id' => 'sometimes|required|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $employee->update($request->all());
        return $this->sendResponse(new UserResource($employee), 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $employee = User::where('is_employee', true)->find($id);
        if (is_null($employee)) {
            return $this->sendError('Employee not found.');
        }
        $employee->delete();
        return $this->sendResponse([], 'Employee deleted successfully.');
    }
}
