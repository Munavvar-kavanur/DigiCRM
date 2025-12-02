<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class MessageThread extends Component
{
    use WithFileUploads;

    public $conversationId;
    public $newMessage = '';
    public $conversation;
    public $attachments = [];

    protected $rules = [
        'newMessage' => 'nullable|string|max:5000',
        'attachments.*' => 'nullable|file|max:10240', // 10MB max
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
        $this->conversation = Conversation::with(['messages.user', 'participants', 'project'])
            ->findOrFail($conversationId);
        
        // Mark as read
        $this->conversation->markAsReadFor(auth()->id());
        
        // Scroll to bottom (handled in view)
        $this->dispatch('messagesLoaded');
    }

    public function sendMessage()
    {
        // Validate that either message or attachments are present
        if (empty(trim($this->newMessage)) && empty($this->attachments)) {
            return;
        }

        $this->validate();

        if (!$this->conversationId) {
            return;
        }

        $attachmentPaths = [];
        
        // Handle file uploads
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $path = $attachment->store('chat-attachments', 'public');
                $attachmentPaths[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'path' => $path,
                    'size' => $attachment->getSize(),
                    'type' => $attachment->getMimeType(),
                ];
            }
        }

        Message::create([
            'conversation_id' => $this->conversationId,
            'user_id' => auth()->id(),
            'message' => trim($this->newMessage) ?: null,
            'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
        ]);

        $this->reset(['newMessage', 'attachments']);
        $this->conversation->refresh();
        $this->dispatch('messageSent');
        $this->dispatch('refreshConversations');
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
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
