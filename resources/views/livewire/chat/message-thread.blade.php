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
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $conversation->participants->count() }} participant{{ $conversation->participants->count() > 1 ? 's' : '' }}
                            @if($conversation->project)
                                • {{ $conversation->project->title }}
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center space-x-2">
                    <button class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
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
                                <p class="text-sm whitespace-pre-wrap break-words">{{ $message->message }}</p>
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
            <form wire:submit.prevent="sendMessage" class="flex items-end space-x-3">
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
                        :disabled="!$wire.newMessage.trim()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    @endif
</div>
