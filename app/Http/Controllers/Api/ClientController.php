<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::latest();
        
        if ($request->user()->branch_id) {
            $query->where('branch_id', $request->user()->branch_id);
        }
        
        $clients = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $clients,
            'message' => 'Clients retrieved successfully',
        ]);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $client,
            'message' => 'Client retrieved successfully',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'nullable|string',
            'company' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Auto-assign branch_id if user has one
        if ($request->user()->branch_id) {
            $validated['branch_id'] = $request->user()->branch_id;
        }

        $client = Client::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $client,
            'message' => 'Client created successfully',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:clients,email,' . $id,
            'phone' => 'nullable|string',
            'company' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $client->update($validated);
        
        return response()->json([
            'success' => true,
            'data' => $client,
            'message' => 'Client updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully',
        ]);
    }
}
