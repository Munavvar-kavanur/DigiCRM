<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversationId = null;
    public $showNewConversationModal = false;
    public $newConversationType = 'direct';
    public $selectedUsers = [];
    public $conversationTitle = '';
    public $selectedProjectId = null;

    protected $listeners = [
        'conversationSelected' => 'selectConversation',
        'refreshConversations' => '$refresh',
    ];

    public function mount()
    {
        // Auto-select first conversation if exists
        $firstConversation = auth()->user()->conversations()->first();
        if ($firstConversation) {
            $this->selectedConversationId = $firstConversation->id;
        }
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
    }

    public function openNewConversationModal()
    {
        $this->showNewConversationModal = true;
        $this->reset(['selectedUsers', 'conversationTitle', 'newConversationType', 'selectedProjectId']);
    }

    public function createConversation()
    {
        $this->validate([
            'selectedUsers' => 'required|array|min:1',
            'newConversationType' => 'required|in:direct,group,project,support',
            'conversationTitle' => 'required_if:newConversationType,group,project',
        ]);

        $conversation = Conversation::create([
            'title' => $this->conversationTitle,
            'type' => $this->newConversationType,
            'project_id' => $this->selectedProjectId,
            'branch_id' => auth()->user()->branch_id,
            'created_by' => auth()->id(),
            'last_message_at' => now(),
        ]);

        // Add creator as participant
        $conversation->participants()->attach(auth()->id(), [
            'last_read_at' => now(),
        ]);

        // Add selected users as participants
        foreach ($this->selectedUsers as $userId) {
            $conversation->participants()->attach($userId);
        }

        $this->showNewConversationModal = false;
        $this->selectedConversationId = $conversation->id;
        $this->dispatch('refreshConversations');
    }

    public function getAvailableUsersProperty()
    {
        $query = User::where('id', '!=', auth()->id());
        
        if (!auth()->user()->isSuperAdmin()) {
            $query->where('branch_id', auth()->user()->branch_id);
        }
        
        return $query->orderBy('name')->get();
    }

    public function getProjectsProperty()
    {
        $query = \App\Models\Project::query();
        
        if (!auth()->user()->isSuperAdmin()) {
            $query->where('branch_id', auth()->user()->branch_id);
        }
        
        return $query->orderBy('title')->get();
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
