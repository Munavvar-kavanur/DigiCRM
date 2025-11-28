<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function create(Request $request)
    {
        $branches = [];
        if (Auth::user()->isSuperAdmin()) {
            $branches = Branch::all();
        }
        
        $type = $request->query('type');
        $branch_id = $request->query('branch_id');
        $related_id = $request->query('related_id');
        $related_type = $request->query('related_type');
        
        return view('reminders.create', compact('branches', 'type', 'branch_id', 'related_id', 'related_type'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date',
            'type' => 'required|string|in:custom,invoice,project,estimate,payroll,expense',
            'priority' => 'required|string|in:low,medium,high',
            'status' => 'required|string|in:pending,completed,dismissed',
            'is_recurring' => 'boolean',
            'frequency' => 'nullable|required_if:is_recurring,true|string|in:daily,weekly,monthly,yearly',
            'branch_id' => 'nullable|exists:branches,id',
            'related_id' => 'nullable|integer',
            'related_type' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        
        if (!Auth::user()->isSuperAdmin() && Auth::user()->branch_id) {
            $validated['branch_id'] = Auth::user()->branch_id;
        }
        
        if (!$request->has('is_recurring')) {
            $validated['is_recurring'] = false;
            $validated['frequency'] = null;
        }
        
        Reminder::create($validated);

        return redirect()->route('reminders.index')->with('success', 'Reminder created successfully.');
    }

    public function show(Reminder $reminder)
    {
        return redirect()->route('reminders.edit', $reminder);
    }

    public function edit(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $branches = [];
        if (Auth::user()->isSuperAdmin()) {
            $branches = Branch::all();
        }

        return view('reminders.edit', compact('reminder', 'branches'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date',
            'type' => 'required|string|in:custom,invoice,project,estimate,payroll,expense',
            'priority' => 'required|string|in:low,medium,high',
            'status' => 'required|string|in:pending,completed,dismissed',
            'is_recurring' => 'boolean',
            'frequency' => 'nullable|required_if:is_recurring,true|string|in:daily,weekly,monthly,yearly',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        if (!$request->has('is_recurring')) {
            $validated['is_recurring'] = false;
            $validated['frequency'] = null;
        }

        $reminder->update($validated);

        return redirect()->route('reminders.index')->with('success', 'Reminder updated successfully.');
    }

    public function index(Request $request)
    {
        $query = Reminder::with('branch');

        // Restrict to current user if not Super Admin
        if (!Auth::user()->isSuperAdmin()) {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('branch_id') && Auth::user()->isSuperAdmin()) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('status')) {
            if ($request->status !== 'all') {
                $query->where('status', $request->status);
            }
        } else {
            $query->where('status', '!=', 'completed');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $reminders = $query->orderBy('reminder_date', 'asc')->paginate(10);
        
        // Counts for tabs - Respecting the same scope as the main list (User vs All)
        // We create a new query instance for stats to avoid modifying the main query or being affected by its pagination
        $statsQuery = Reminder::query();
        if (!Auth::user()->isSuperAdmin()) {
            $statsQuery->where('user_id', Auth::id());
        }
        // We do NOT apply the other filters (status, type, search) to the stats, 
        // because usually these tabs act as "Quick Filters" themselves or high-level summaries.
        // However, if the user filters by Branch, the stats *should* probably reflect that branch?
        // For now, let's keep stats global for the user (or global for company if Super Admin).
        // If we want stats to filter by branch, we should add that check.
        if ($request->filled('branch_id') && Auth::user()->isSuperAdmin()) {
            $statsQuery->where('branch_id', $request->branch_id);
        }

        $upcomingCount = (clone $statsQuery)->where('status', 'pending')->where('reminder_date', '>=', now())->count();
        $overdueCount = (clone $statsQuery)->where('status', 'pending')->where('reminder_date', '<', now())->count();
        $completedCount = (clone $statsQuery)->where('status', 'completed')->count();

        $branches = [];
        if (Auth::user()->isSuperAdmin()) {
            $branches = Branch::all();
        }

        return view('reminders.index', compact('reminders', 'upcomingCount', 'overdueCount', 'completedCount', 'branches'));
    }

    public function destroy(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $reminder->delete();

        return redirect()->route('reminders.index')->with('success', 'Reminder deleted successfully.');
    }

    public function markAsComplete(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $reminder->update(['status' => 'completed']);

        if ($reminder->is_recurring && $reminder->frequency) {
            $nextDate = $reminder->reminder_date->copy();
            
            switch ($reminder->frequency) {
                case 'daily':
                    $nextDate->addDay();
                    break;
                case 'weekly':
                    $nextDate->addWeek();
                    break;
                case 'monthly':
                    $nextDate->addMonth();
                    break;
                case 'yearly':
                    $nextDate->addYear();
                    break;
            }

            Reminder::create([
                'user_id' => $reminder->user_id,
                'title' => $reminder->title,
                'description' => $reminder->description,
                'reminder_date' => $nextDate,
                'type' => $reminder->type,
                'related_id' => $reminder->related_id,
                'related_type' => $reminder->related_type,
                'status' => 'pending',
                'priority' => $reminder->priority,
                'is_recurring' => true,
                'frequency' => $reminder->frequency,
            ]);
        }

        return redirect()->back()->with('success', 'Reminder marked as complete.');
    }
}
