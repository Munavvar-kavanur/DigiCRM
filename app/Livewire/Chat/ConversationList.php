<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Component;
use Livewire\Attributes\On;

class ConversationList extends Component
{
    public $selectedConversationId;
    public $search = '';
    public $filterType = 'all'; // all, direct, group, project

    #[On('conversationSelected')]
    public function updateSelectedConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
    }

    #[On('refreshConversations')]
    public function refreshConversations()
    {
        // This will trigger a re-render
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
        $this->dispatch('conversationSelected', conversationId: $conversationId);
    }

    public function getConversationsProperty()
    {
        $query = auth()->user()->conversations();

        // Apply search filter
        if ($this->search) {
            $searchTerm = $this->search;
            $query->where(function ($q) use ($searchTerm) {
                // Search in conversation title
                $q->where('conversations.title', 'like', '%' . $searchTerm . '%')
                  // Search in participant names (excluding current user)
                  ->orWhereHas('participants', function ($pq) use ($searchTerm) {
                      $pq->where('users.id', '!=', auth()->id())
                         ->where('users.name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Apply type filter
        if ($this->filterType !== 'all') {
            $query->where('conversations.type', $this->filterType);
        }

        return $query->with(['latestMessage.user', 'participants.client', 'project'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) {
                $conversation->unread_count = $conversation->getUnreadCountForUser(auth()->id());
                return $conversation;
            });
    }

    public function render()
    {
        return view('livewire.chat.conversation-list', [
            'conversations' => $this->conversations,
        ]);
    }
}
