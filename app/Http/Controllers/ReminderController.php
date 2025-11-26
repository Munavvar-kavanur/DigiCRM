<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        $query = Reminder::where('user_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'completed');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $reminders = $query->orderBy('reminder_date', 'asc')->paginate(10);
        
        // Counts for tabs
        $upcomingCount = Reminder::where('user_id', Auth::id())->where('status', 'pending')->where('reminder_date', '>=', now())->count();
        $overdueCount = Reminder::where('user_id', Auth::id())->where('status', 'pending')->where('reminder_date', '<', now())->count();
        $completedCount = Reminder::where('user_id', Auth::id())->where('status', 'completed')->count();

        return view('reminders.index', compact('reminders', 'upcomingCount', 'overdueCount', 'completedCount'));
    }

    public function create()
    {
        return view('reminders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date',
            'type' => 'required|in:invoice,project,estimate,payroll,custom,expense',
            'priority' => 'required|in:high,medium,low',
            'is_recurring' => 'nullable|boolean',
            'frequency' => 'nullable|required_if:is_recurring,1|in:daily,weekly,monthly,yearly',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        Reminder::create($validated);

        return redirect()->route('reminders.index')->with('success', 'Reminder created successfully.');
    }

    public function edit(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }
        return view('reminders.edit', compact('reminder'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date',
            'type' => 'required|in:invoice,project,estimate,payroll,custom,expense',
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:pending,completed,dismissed',
            'is_recurring' => 'nullable|boolean',
            'frequency' => 'nullable|required_if:is_recurring,1|in:daily,weekly,monthly,yearly',
        ]);

        $reminder->update($validated);

        return redirect()->route('reminders.index')->with('success', 'Reminder updated successfully.');
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
