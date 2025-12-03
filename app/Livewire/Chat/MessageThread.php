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
    public $attachmentType = 'document'; // document, photo, voice, audio
    public $voiceNote = null;
    public $audioFile = null;

    protected $rules = [
        'newMessage' => 'nullable|string|max:5000',
        'attachments.*' => 'nullable|file|max:10240', // 10MB max
        'voiceNote' => 'nullable|file|mimes:mp3,wav,ogg,webm|max:5120', // 5MB max for voice
        'audioFile' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,flac|max:10240', // 10MB max for audio
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
        $this->conversation = Conversation::with(['messages.user', 'participants.client', 'project'])
            ->findOrFail($conversationId);
        
        // Mark as read
        $this->conversation->markAsReadFor(auth()->id());
        
        // Scroll to bottom (handled in view)
        $this->dispatch('messagesLoaded');
    }

    public function sendMessage()
    {
        // Validate that either message, attachments, voice note, or audio file are present
        if (empty(trim($this->newMessage)) && empty($this->attachments) && !$this->voiceNote && !$this->audioFile) {
            return;
        }

        $this->validate();

        if (!$this->conversationId) {
            return;
        }

        $attachmentPaths = [];
        
        // Handle voice note
        if ($this->voiceNote) {
            $path = $this->voiceNote->store('chat-attachments/voice', 'public');
            $attachmentPaths[] = [
                'name' => 'Voice Message',
                'path' => $path,
                'size' => $this->voiceNote->getSize(),
                'type' => $this->voiceNote->getMimeType(),
                'attachment_type' => 'voice',
            ];
        }
        
        // Handle audio file
        if ($this->audioFile) {
            $path = $this->audioFile->store('chat-attachments/audio', 'public');
            $attachmentPaths[] = [
                'name' => $this->audioFile->getClientOriginalName(),
                'path' => $path,
                'size' => $this->audioFile->getSize(),
                'type' => $this->audioFile->getMimeType(),
                'attachment_type' => 'audio',
            ];
        }
        
        // Handle file uploads
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $mimeType = $attachment->getMimeType();
                $isImage = str_starts_with($mimeType, 'image/');
                
                // Store in different folders based on type
                $folder = $this->attachmentType === 'photo' || $isImage 
                    ? 'chat-attachments/photos' 
                    : 'chat-attachments/documents';
                
                $path = $attachment->store($folder, 'public');
                
                $attachmentPaths[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'path' => $path,
                    'size' => $attachment->getSize(),
                    'type' => $mimeType,
                    'attachment_type' => $isImage && $this->attachmentType === 'photo' ? 'photo' : 'document',
                ];
            }
        }

        Message::create([
            'conversation_id' => $this->conversationId,
            'user_id' => auth()->id(),
            'message' => trim($this->newMessage) ?: null,
            'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
        ]);

        $this->reset(['newMessage', 'attachments', 'voiceNote', 'audioFile', 'attachmentType']);
        $this->conversation->refresh();
        $this->dispatch('messageSent');
        $this->dispatch('refreshConversations');
    }

    public function setAttachmentType($type)
    {
        $this->attachmentType = $type;
        $this->reset(['attachments', 'voiceNote', 'audioFile']);
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function removeVoiceNote()
    {
        $this->voiceNote = null;
    }

    public function removeAudioFile()
    {
        $this->audioFile = null;
    }

    public function getMessagesProperty()
    {
        if (!$this->conversation) {
            return collect();
        }

        return $this->conversation->messages()
            ->with('user')
            ->where(function ($query) {
                $query->whereNull('deleted_by')
                      ->orWhereJsonDoesntContain('deleted_by', auth()->id());
            })
            ->get();
    }

    public function deleteForMe($messageId)
    {
        $message = Message::find($messageId);
        if ($message) {
            $deletedBy = $message->deleted_by ?? [];
            if (!in_array(auth()->id(), $deletedBy)) {
                $deletedBy[] = auth()->id();
                $message->update(['deleted_by' => $deletedBy]);
            }
        }
        $this->dispatch('messagesLoaded'); // Refresh view
    }

    public function deleteForEveryone($messageId)
    {
        $message = Message::find($messageId);
        if ($message && $message->user_id === auth()->id()) {
            $message->delete();
        }
        $this->dispatch('messagesLoaded'); // Refresh view
    }

    public function getMessageInfo($messageId)
    {
        $message = Message::with('reads.user')->find($messageId);
        if ($message) {
            $this->dispatch('openMessageInfoModal', [
                'message' => $message,
                'delivered_at' => $message->delivered_at,
                'read_by' => $message->reads
            ]);
        }
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
