<?php

namespace App\Livewire\Layout\Navigation;

use Livewire\Component;

class ChatBadge extends Component
{
    public function render()
    {
        $unreadChatCount = auth()->user()->conversations()
            ->where(function($q) {
                $q->whereColumn('conversations.last_message_at', '>', 'conversation_participants.last_read_at')
                  ->orWhereNull('conversation_participants.last_read_at');
            })
            ->count();

        return view('livewire.layout.navigation.chat-badge', [
            'unreadCount' => $unreadChatCount
        ]);
    }
}
