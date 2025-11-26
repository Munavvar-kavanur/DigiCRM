<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        if ($project->deadline) {
            \App\Models\Reminder::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                'title' => 'Project Deadline: ' . $project->title,
                'description' => 'Deadline for project ' . $project->title,
                'reminder_date' => $project->deadline,
                'type' => 'project',
                'related_id' => $project->id,
                'related_type' => Project::class,
                'status' => 'pending',
                'priority' => 'high',
            ]);
        }
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
