<div class="flex flex-col h-full" wire:poll.5s="pollMessages">
    @if($conversation)
        {{-- Header --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    {{-- Avatar/Icon --}}
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            @if($conversation->type === 'direct')
                                @php
                                    $otherUser = $conversation->participants->where('id', '!=', auth()->id())->first();
                                @endphp
                                {{ substr($otherUser->name ?? 'U', 0, 2) }}
                            @else
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            @if($conversation->type === 'direct')
                                {{ $otherUser->name ?? 'Unknown User' }}
                            @else
                                {{ $conversation->title }}
                            @endif
                        </h3>
                        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <span>{{ $conversation->participants->count() }} participant{{ $conversation->participants->count() > 1 ? 's' : '' }}</span>
                            @if($conversation->project)
                                <span>•</span>
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                    {{ $conversation->project->title }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-10"
                         style="display: none;">
                        <button wire:click="$parent.clearConversation({{ $conversation->id }})" 
                                onclick="confirm('Clear all messages in this conversation?') || event.stopImmediatePropagation()"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-t-xl">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Clear History
                        </button>
                        <button wire:click="$parent.deleteConversation({{ $conversation->id }})"
                                onclick="confirm('Delete this conversation permanently?') || event.stopImmediatePropagation()"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-b-xl">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Delete Conversation
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages Area --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900" 
             id="messages-container"
             x-data="{ 
                 scrollToBottom() { 
                     this.$el.scrollTop = this.$el.scrollHeight; 
                 } 
             }"
             x-init="scrollToBottom()"
             @messages-loaded.window="scrollToBottom()"
             @message-sent.window="scrollToBottom()">
            
            @forelse($messages as $message)
                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-end space-x-2 max-w-[70%] {{ $message->user_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                        {{-- Avatar --}}
                        @if($message->user_id !== auth()->id())
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($message->user->name, 0, 2) }}
                            </div>
                        @endif

                        {{-- Message Bubble --}}
                        <div>
                            @if($message->user_id !== auth()->id())
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 ml-1">{{ $message->user->name }}</p>
                            @endif
                            
                            <div class="px-4 py-2 rounded-2xl shadow-sm {{ $message->user_id === auth()->id() 
                                ? 'bg-indigo-600 text-white rounded-br-none' 
                                : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-bl-none' }}">
                                
                                @if($message->message)
                                    <p class="text-sm whitespace-pre-wrap break-words">{{ $message->message }}</p>
                                @endif

                                {{-- Attachments --}}
                                @if($message->attachments)
                                    <div class="mt-2 space-y-2">
                                        @foreach($message->attachments as $attachment)
                                            <a href="{{ Storage::url($attachment['path']) }}" target="_blank" download
                                               class="flex items-center space-x-2 p-2 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-indigo-500 hover:bg-indigo-400' : 'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600' }} transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-medium truncate">{{ $attachment['name'] }}</p>
                                                    <p class="text-xs opacity-75">{{ number_format($attachment['size'] / 1024, 1) }} KB</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 {{ $message->user_id === auth()->id() ? 'text-right mr-1' : 'ml-1' }}">
                                {{ $message->created_at->format('g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        {{-- Message Input --}}
        <div class="p-6 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            {{-- Attachment Preview --}}
            @if(!empty($attachments))
                <div class="mb-3 flex flex-wrap gap-2">
                    @foreach($attachments as $index => $attachment)
                        <div class="relative inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <svg class="w-4 h-4 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $attachment->getClientOriginalName() }}</span>
                            <button wire:click="removeAttachment({{ $index }})" type="button" class="ml-2 text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <form wire:submit.prevent="sendMessage" class="flex items-end space-x-3">
                {{-- Attachment Button --}}
                <label for="file-upload" class="flex-shrink-0 p-3 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl cursor-pointer transition-colors">
                    <input id="file-upload" type="file" wire:model="attachments" multiple class="hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                </label>

                <div class="flex-1">
                    <textarea wire:model="newMessage" 
                              rows="1"
                              placeholder="Type your message..."
                              class="w-full resize-none rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 px-4 py-3"
                              @keydown.enter.prevent="if (!$event.shiftKey) { $wire.sendMessage(); $event.target.value = ''; }"
                              x-data
                              x-init="$el.style.height = '52px'; $el.addEventListener('input', function() { this.style.height = '52px'; this.style.height = this.scrollHeight + 'px'; })"></textarea>
                    @error('newMessage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" 
                        class="flex-shrink-0 p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24" wire:loading>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
        </div>
    @endif
</div>
