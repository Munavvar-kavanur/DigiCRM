<div class="flex h-[calc(100vh-8rem)] bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
    {{-- Conversation List Sidebar --}}
    <div class="w-full md:w-96 border-r border-gray-200 dark:border-gray-700 flex flex-col">
        {{-- Header --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Messages</h2>
                <button wire:click="openNewConversationModal" 
                        class="p-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition-colors shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Conversation List Component --}}
        <livewire:chat.conversation-list :selected-conversation-id="$selectedConversationId" />
    </div>

    {{-- Message Thread Area --}}
    <div class="flex-1 flex flex-col bg-gray-50 dark:bg-gray-900">
        @if($selectedConversationId)
            <livewire:chat.message-thread :conversation-id="$selectedConversationId" :key="'message-thread-' . $selectedConversationId" />
        @else
            {{-- Empty State --}}
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Conversation Selected</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Select a conversation to start messaging</p>
                    <button wire:click="openNewConversationModal"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Start New Conversation
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- New Conversation Modal --}}
    @if($showNewConversationModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showNewConversationModal', false)"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="createConversation">
                        <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">New Conversation</h3>
                            
                            {{-- Conversation Type --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                                <select wire:model.live="newConversationType" 
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="direct">Direct Message</option>
                                    <option value="group">Group Chat</option>
                                    <option value="project">Project Discussion</option>
                                </select>
                            </div>

                            {{-- Title (for group/project) --}}
                            @if(in_array($newConversationType, ['group', 'project']))
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                                    <input type="text" wire:model="conversationTitle"
                                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Enter conversation title">
                                    @error('conversationTitle') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            {{-- Project Selection --}}
                            @if($newConversationType === 'project')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project</label>
                                    <select wire:model="selectedProjectId"
                                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Project</option>
                                        @foreach($this->projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Participants --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Participants
                                </label>
                                <div class="max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-xl p-3 space-y-2">
                                    @foreach($this->availableUsers as $user)
                                        <label class="flex items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg cursor-pointer">
                                            <input type="checkbox" wire:model="selectedUsers" value="{{ $user->id }}"
                                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $user->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selectedUsers') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showNewConversationModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
