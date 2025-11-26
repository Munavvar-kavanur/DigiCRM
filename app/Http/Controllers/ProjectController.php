<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('client');

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
        $clients = Client::orderBy('name')->get();

        // Stats
        $totalProjects = Project::count();
        $inProgressProjects = Project::where('status', 'in_progress')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $onHoldProjects = Project::where('status', 'on_hold')->count();

        return view('projects.index', compact('projects', 'clients', 'totalProjects', 'inProgressProjects', 'completedProjects', 'onHoldProjects'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        return view('projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
            'deadline' => 'nullable|date',
            'budget' => 'nullable|numeric',
        ]);

        Project::create($validated);

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
        $clients = Client::where('status', 'active')->get();
        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
            'deadline' => 'nullable|date',
            'budget' => 'nullable|numeric',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
