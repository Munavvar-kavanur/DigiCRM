<div class="flex flex-col flex-1 overflow-hidden" wire:poll.10s="refreshConversations">
    {{-- Search and Filter --}}
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="relative mb-3">
            <input type="text" wire:model.live.debounce.300ms="search" 
                   placeholder="Search conversations..."
                   class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        {{-- Type Filters --}}
        <div class="flex space-x-2">
            <button wire:click="$set('filterType', 'all')" 
                    class="px-3 py-1 rounded-lg text-xs font-medium transition-colors {{ $filterType === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                All
            </button>
            <button wire:click="$set('filterType', 'direct')"
                    class="px-3 py-1 rounded-lg text-xs font-medium transition-colors {{ $filterType === 'direct' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Direct
            </button>
            <button wire:click="$set('filterType', 'group')"
                    class="px-3 py-1 rounded-lg text-xs font-medium transition-colors {{ $filterType === 'group' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Groups
            </button>
            <button wire:click="$set('filterType', 'project')"
                    class="px-3 py-1 rounded-lg text-xs font-medium transition-colors {{ $filterType === 'project' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Projects
            </button>
        </div>
    </div>

    {{-- Conversation List --}}
    <div class="flex-1 overflow-y-auto">
        @forelse($conversations as $conversation)
            <div wire:click="selectConversation({{ $conversation->id }})"
                 class="p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors {{ $selectedConversationId == $conversation->id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-l-indigo-600' : '' }}">
                
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        {{-- Conversation Title --}}
                        <div class="flex items-center mb-1">
                            @if($conversation->type === 'direct')
                                @php
                                    $otherUser = $conversation->participants->where('id', '!=', auth()->id())->first();
                                @endphp
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $otherUser->name ?? 'Unknown' }}
                                </h4>
                            @else
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $conversation->title }}
                                </h4>
                            @endif

                            {{-- Type Badge --}}
                            @if($conversation->type !== 'direct')
                                <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full
                                    {{ $conversation->type === 'group' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                    {{ $conversation->type === 'project' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : '' }}">
                                    {{ ucfirst($conversation->type) }}
                                </span>
                            @elseif($conversation->type === 'direct' && isset($otherUser) && $otherUser->client)
                                <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300">
                                    Client
                                </span>
                            @endif
                        </div>

                        {{-- Last Message Preview --}}
                        @if($conversation->latestMessage->first())
                            <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                <span class="font-medium">{{ $conversation->latestMessage->first()->user->name }}:</span>
                                {{ $conversation->latestMessage->first()->message }}
                            </p>
                        @else
                            <p class="text-xs text-gray-400 dark:text-gray-500 italic">No messages yet</p>
                        @endif

                        {{-- Timestamp --}}
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : $conversation->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Unread Badge --}}
                    @if($conversation->unread_count > 0)
                        <div class="ml-2 flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-5 min-w-[1.25rem] px-1.5 rounded-full bg-indigo-600 text-white text-xs font-bold">
                                {{ $conversation->unread_count > 99 ? '99+' : $conversation->unread_count }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">No conversations yet</p>
            </div>
        @endforelse
    </div>
</div>
