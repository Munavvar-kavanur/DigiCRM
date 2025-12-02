<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'attachments',
        'is_system_message',
        'deleted_by',
        'delivered_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_system_message' => 'boolean',
        'deleted_by' => 'array',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the conversation this message belongs to
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user who sent the message
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reads for the message
     */
    public function reads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MessageRead::class);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Update conversation's last_message_at when a message is created
        static::created(function ($message) {
            $message->conversation->update([
                'last_message_at' => $message->created_at,
            ]);
        });
    }
}
