<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('client')->latest();
        
        if ($request->user()->branch_id) {
            $query->where('branch_id', $request->user()->branch_id);
        }
        
        $projects = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $projects,
            'message' => 'Projects retrieved successfully',
        ]);
    }

    public function show($id)
    {
        $project = Project::with('client')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $project,
            'message' => 'Project retrieved successfully',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
        ]);

        // Auto-assign branch_id if user has one
        if ($request->user()->branch_id) {
            $validated['branch_id'] = $request->user()->branch_id;
        }

        $project = Project::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $project->load('client'),
            'message' => 'Project created successfully',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'client_id' => 'exists:clients,id',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed,on_hold',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
        ]);

        $project->update($validated);
        
        return response()->json([
            'success' => true,
            'data' => $project->load('client'),
            'message' => 'Project updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ]);
    }
}
