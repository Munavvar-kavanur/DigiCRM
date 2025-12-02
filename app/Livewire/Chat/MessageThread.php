<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;

class MessageThread extends Component
{
    public $conversationId;
    public $newMessage = '';
    public $conversation;

    protected $rules = [
        'newMessage' => 'required|string|max:5000',
    ];

    public function mount($conversationId = null)
    {
        if ($conversationId) {
            $this->loadConversation($conversationId);
        }
    }

    #[On('conversationSelected')]
    public function loadConversation($conversationId)
    {
        $this->conversationId = $conversationId;
        $this->conversation = Conversation::with(['messages.user', 'participants'])
            ->findOrFail($conversationId);
        
        // Mark as read
        $this->conversation->markAsReadFor(auth()->id());
        
        // Scroll to bottom (handled in view)
        $this->dispatch('messagesLoaded');
    }

    public function sendMessage()
    {
        $this->validate();

        if (!$this->conversationId) {
            return;
        }

        Message::create([
            'conversation_id' => $this->conversationId,
            'user_id' => auth()->id(),
            'message' => trim($this->newMessage),
        ]);

        $this->newMessage = '';
        $this->conversation->refresh();
        $this->dispatch('messageSent');
        $this->dispatch('refreshConversations');
    }

    public function getMessagesProperty()
    {
        if (!$this->conversation) {
            return collect();
        }

        return $this->conversation->messages()->with('user')->get();
    }

    public function pollMessages()
    {
        if ($this->conversationId) {
            $this->conversation->refresh();
            $this->conversation->markAsReadFor(auth()->id());
        }
    }

    public function render()
    {
        return view('livewire.chat.message-thread', [
            'messages' => $this->messages,
        ]);
    }
}
