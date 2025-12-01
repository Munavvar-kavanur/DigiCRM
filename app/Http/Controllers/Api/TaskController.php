<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Validator;

class TaskController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Task::query();
        
        // Filter by project if provided
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->with('project')->latest()->paginate(10);
        return $this->sendResponse(TaskResource::collection($tasks)->response()->getData(true), 'Tasks retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $task = Task::create($request->all());
        return $this->sendResponse(new TaskResource($task), 'Task created successfully.', 201);
    }

    public function show($id)
    {
        $task = Task::with('project')->find($id);
        if (is_null($task)) {
            return $this->sendError('Task not found.');
        }
        return $this->sendResponse(new TaskResource($task), 'Task retrieved successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:pending,in_progress,completed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $task->update($request->all());
        return $this->sendResponse(new TaskResource($task), 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return $this->sendResponse([], 'Task deleted successfully.');
    }
}
