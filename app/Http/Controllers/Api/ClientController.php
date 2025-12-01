<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Validator;

class ClientController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Client::query();
        
        if ($request->user()->branch_id) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        $clients = $query->latest()->paginate(10);
        return $this->sendResponse(ClientResource::collection($clients)->response()->getData(true), 'Clients retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $client = Client::create($request->all());
        return $this->sendResponse(new ClientResource($client), 'Client created successfully.', 201);
    }

    public function show($id)
    {
        $client = Client::find($id);
        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }
        return $this->sendResponse(new ClientResource($client), 'Client retrieved successfully.');
    }

    public function update(Request $request, Client $client)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:clients,email,' . $client->id,
            'branch_id' => 'sometimes|required|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $client->update($request->all());
        return $this->sendResponse(new ClientResource($client), 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return $this->sendResponse([], 'Client deleted successfully.');
    }
}
