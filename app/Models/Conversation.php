<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'title',
        'type',
        'project_id',
        'branch_id',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the project associated with the conversation
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the branch associated with the conversation
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who created the conversation
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all messages for the conversation
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message
     */
    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    /**
     * Get all participants in the conversation
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    /**
     * Get participant records
     */
    public function participantRecords(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    /**
     * Get unread count for a specific user
     */
    public function getUnreadCountForUser($userId): int
    {
        $participant = $this->participantRecords()->where('user_id', $userId)->first();
        
        if (!$participant) {
            return 0;
        }

        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('created_at', '>', $participant->last_read_at ?? $this->created_at)
            ->count();
    }

    /**
     * Mark as read for a user
     */
    public function markAsReadFor($userId): void
    {
        $this->participantRecords()
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);

        // Mark individual messages as read
        $this->messages()
            ->where('user_id', '!=', $userId)
            ->whereDoesntHave('reads', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get()
            ->each(function ($message) use ($userId) {
                $message->reads()->create([
                    'user_id' => $userId,
                    'read_at' => now(),
                ]);
            });
    }

    /**
     * Scope to filter by user participation
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }

    /**
     * Scope to filter by branch
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
}
