<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['client', 'branch']);

        // Helper to apply branch filter
        $applyBranch = function ($q) use ($request) {
            if (auth()->user()->isSuperAdmin()) {
                if ($request->has('branch_id') && $request->branch_id != '') {
                    $q->where('branch_id', $request->branch_id);
                }
            } else {
                $q->where('branch_id', auth()->user()->branch_id);
            }
        };

        // Apply branch filter to main query
        $applyBranch($query);

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status Filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Client Filter
        if ($request->has('client_id') && $request->client_id != '') {
            $query->where('client_id', $request->client_id);
        }

        $projects = $query->latest()->paginate(10);
        
        // Clients & Branches for filters
        $clientsQuery = Client::orderBy('name');
        $applyBranch($clientsQuery);
        $clients = $clientsQuery->get();
        
        $branches = \App\Models\Branch::orderBy('name')->get();

        // Stats (Filtered by Branch)
        $totalProjects = Project::query();
        $applyBranch($totalProjects);
        $totalProjects = $totalProjects->count();

        $inProgressProjects = Project::where('status', 'in_progress');
        $applyBranch($inProgressProjects);
        $inProgressProjects = $inProgressProjects->count();

        $completedProjects = Project::where('status', 'completed');
        $applyBranch($completedProjects);
        $completedProjects = $completedProjects->count();

        $onHoldProjects = Project::where('status', 'on_hold');
        $applyBranch($onHoldProjects);
        $onHoldProjects = $onHoldProjects->count();

        // Pass selected branch for view context
        $selectedBranch = null;
        if ($request->has('branch_id') && $request->branch_id) {
            $selectedBranch = \App\Models\Branch::find($request->branch_id);
        }

        return view('projects.index', compact(
            'projects', 'clients', 'branches', 
            'totalProjects', 'inProgressProjects', 'completedProjects', 'onHoldProjects',
            'selectedBranch'
        ));
    }

    public function create()
    {
        $clients = collect();
        if (!auth()->user()->isSuperAdmin()) {
            $clients = Client::where('status', 'active')->where('branch_id', auth()->user()->branch_id)->get();
        } else {
            // For super admin, load all active clients or clients from old branch_id if validation failed
            if (request()->old('branch_id')) {
                $clients = Client::where('status', 'active')->where('branch_id', request()->old('branch_id'))->get();
            } else {
                $clients = Client::where('status', 'active')->orderBy('name')->get();
            }
        }
        
        $branches = \App\Models\Branch::orderBy('name')->get();
        return view('projects.create', compact('clients', 'branches'));
    }

    public function store(Request $request)
    {
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
            'deadline' => 'nullable|date',
            'budget' => 'nullable|numeric',
        ];

        if (auth()->user()->isSuperAdmin()) {
            $rules['branch_id'] = 'nullable|exists:branches,id';
        }

        $validated = $request->validate($rules);

        $projectData = $validated;
        if (auth()->user()->isSuperAdmin() && isset($validated['branch_id'])) {
            $projectData['branch_id'] = $validated['branch_id'];
        }

        Project::create($projectData);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'tasks.assignee']);
        $users = \App\Models\User::all();

        // Calculate Stats
        $totalTasks = $project->tasks->count();
        $completedTasks = $project->tasks->where('status', 'done')->count();
        $pendingTasks = $totalTasks - $completedTasks;
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $daysLeft = 0;
        $isOverdue = false;
        if ($project->deadline) {
            $deadline = \Carbon\Carbon::parse($project->deadline);
            $daysLeft = now()->diffInDays($deadline, false);
            if ($daysLeft < 0) {
                $isOverdue = true;
                $daysLeft = abs($daysLeft);
            }
        }

        return view('projects.show', compact('project', 'users', 'totalTasks', 'completedTasks', 'pendingTasks', 'progress', 'daysLeft', 'isOverdue'));
    }

    public function edit(Project $project)
    {
        $clients = Client::where('status', 'active')->where('branch_id', $project->branch_id)->get();
        $branches = \App\Models\Branch::orderBy('name')->get();
        return view('projects.edit', compact('project', 'clients', 'branches'));
    }

    public function update(Request $request, Project $project)
    {
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
            'deadline' => 'nullable|date',
            'budget' => 'nullable|numeric',
        ];

        if (auth()->user()->isSuperAdmin()) {
            $rules['branch_id'] = 'nullable|exists:branches,id';
        }

        $validated = $request->validate($rules);

        $updateData = $validated;
        if (auth()->user()->isSuperAdmin() && isset($validated['branch_id'])) {
            $updateData['branch_id'] = $validated['branch_id'];
        }

        $project->update($updateData);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
