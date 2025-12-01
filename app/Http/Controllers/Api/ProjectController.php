<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Validator;

class ProjectController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Project::query();
        
        if ($request->user()->branch_id) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        $projects = $query->with('client')->latest()->paginate(10);
        return $this->sendResponse(ProjectResource::collection($projects)->response()->getData(true), 'Projects retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:not_started,in_progress,on_hold,completed,cancelled',
            'branch_id' => 'required|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $project = Project::create($request->all());
        return $this->sendResponse(new ProjectResource($project), 'Project created successfully.', 201);
    }

    public function show($id)
    {
        $project = Project::with(['client', 'tasks', 'invoices'])->find($id);
        if (is_null($project)) {
            return $this->sendError('Project not found.');
        }
        return $this->sendResponse(new ProjectResource($project), 'Project retrieved successfully.');
    }

    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'client_id' => 'sometimes|required|exists:clients,id',
            'status' => 'sometimes|required|in:not_started,in_progress,on_hold,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $project->update($request->all());
        return $this->sendResponse(new ProjectResource($project), 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return $this->sendResponse([], 'Project deleted successfully.');
    }
}
