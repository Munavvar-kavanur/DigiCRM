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
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            @if($conversation->type === 'direct')
                                {{ $otherUser->name ?? 'Unknown User' }}
                                @if(isset($otherUser) && $otherUser->client)
                                    <span class="text-xs font-normal text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full border border-indigo-100 dark:border-indigo-800">
                                        {{ $otherUser->client->company_name }}
                                    </span>
                                @endif
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
                            
                            <div class="relative group" x-data="{ showMenu: false }">
                            <div @contextmenu.prevent="showMenu = true" 
                                 @click.away="showMenu = false"
                                 class="px-4 py-2 rounded-2xl shadow-sm {{ $message->user_id === auth()->id() 
                                ? 'bg-indigo-600 text-white rounded-br-none' 
                                : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-bl-none' }}">
                                
                                @if($message->message)
                                    <p class="text-sm whitespace-pre-wrap break-words">{{ $message->message }}</p>
                                @endif

                                {{-- Attachments --}}
                                @if($message->attachments)
                                    <div class="mt-2 space-y-2">
                                        @foreach($message->attachments as $attachment)
                                            @php
                                                $attachmentType = $attachment['attachment_type'] ?? 'document';
                                                $isImage = str_starts_with($attachment['type'], 'image/');
                                            @endphp

                                            {{-- Photo Preview --}}
                                            @if($attachmentType === 'photo' || $isImage)
                                                <a href="{{ Storage::url($attachment['path']) }}" target="_blank" 
                                                   class="block rounded-lg overflow-hidden group relative">
                                                    <img src="{{ Storage::url($attachment['path']) }}" 
                                                         alt="{{ $attachment['name'] }}"
                                                         class="max-w-xs rounded-lg hover:opacity-90 transition-opacity cursor-pointer">
                                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                        </svg>
                                                    </div>
                                                </a>

                                            {{-- Voice Note --}}
                                            @elseif($attachmentType === 'voice')
                                                <div class="flex items-center space-x-3 p-3 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-indigo-500' : 'bg-gray-100 dark:bg-gray-700' }}">
                                                    <button class="flex-shrink-0 p-2 rounded-full hover:bg-black/10 transition-colors" 
                                                           x-data="{ playing: false }"
                                                           @click="
                                                               const audio = $el.nextElementSibling;
                                                               if (playing) {
                                                                   audio.pause();
                                                                   playing = false;
                                                               } else {
                                                                   audio.play();
                                                                   playing = true;
                                                               }
                                                               audio.onended = () => playing = false;
                                                           ">
                                                        <svg x-show="!playing" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                                                        </svg>
                                                        <svg x-show="playing" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                                                            <path d="M5.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75A.75.75 0 007.25 3h-1.5zM12.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75a.75.75 0 00-.75-.75h-1.5z"></path>
                                                        </svg>
                                                    </button>
                                                    <audio src="{{ Storage::url($attachment['path']) }}" class="hidden"></audio>
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M7 4a3 3 0 016 0v6a3 3 0 11-6 0V4z"></path>
                                                                <path d="M5.5 9.643a.75.75 0 00-1.5 0V10c0 3.06 2.29 5.585 5.25 5.954V17.5h-1.5a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-1.5v-1.546A6.001 6.001 0 0016 10v-.357a.75.75 0 00-1.5 0V10a4.5 4.5 0 01-9 0v-.357z"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium">Voice Message</span>
                                                        </div>
                                                        <p class="text-xs opacity-75">{{ number_format($attachment['size'] / 1024, 1) }} KB</p>
                                                    </div>
                                                </div>

                                            {{-- Audio File --}}
                                            @elseif($attachmentType === 'audio')
                                                <div class="flex items-center space-x-3 p-3 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-indigo-500' : 'bg-gray-100 dark:bg-gray-700' }}">
                                                    <button class="flex-shrink-0 p-2 rounded-full hover:bg-black/10 transition-colors" 
                                                           x-data="{ playing: false }"
                                                           @click="
                                                               const audio = $el.nextElementSibling;
                                                               if (playing) {
                                                                   audio.pause();
                                                                   playing = false;
                                                               } else {
                                                                   audio.play();
                                                                   playing = true;
                                                               }
                                                               audio.onended = () => playing = false;
                                                           ">
                                                        <svg x-show="!playing" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                                                        </svg>
                                                        <svg x-show="playing" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                                                            <path d="M5.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75A.75.75 0 007.25 3h-1.5zM12.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75a.75.75 0 00-.75-.75h-1.5z"></path>
                                                        </svg>
                                                    </button>
                                                    <audio src="{{ Storage::url($attachment['path']) }}" class="hidden"></audio>
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium">{{ $attachment['name'] }}</span>
                                                        </div>
                                                        <p class="text-xs opacity-75">{{ number_format($attachment['size'] / 1024, 1) }} KB</p>
                                                    </div>
                                                </div>

                                            {{-- Document --}}
                                            @else
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
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Context Menu --}}
                            <div x-show="showMenu" 
                                 x-transition
                                 class="absolute z-20 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-1"
                                 :class="{{ $message->user_id === auth()->id() }} ? 'right-0' : 'left-0'"
                                 style="top: 100%; display: none;">
                                
                                <button @click="showMenu = false; $wire.getMessageInfo({{ $message->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Info
                                </button>
                                
                                <button @click="showMenu = false; $wire.deleteForMe({{ $message->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Delete for me
                                </button>

                                @if($message->user_id === auth()->id())
                                    <button @click="showMenu = false; $wire.deleteForEveryone({{ $message->id }})" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Delete for everyone
                                    </button>
                                @endif
                            </div>
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
            {{-- Attachment/Voice/Audio Preview --}}
            @if(!empty($attachments) || $voiceNote || $audioFile)
                <div class="mb-3 flex flex-wrap gap-2">
                    @if($voiceNote)
                        <div class="relative inline-flex items-center px-3 py-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 4a3 3 0 016 0v6a3 3 0 11-6 0V4z"></path>
                            </svg>
                            <span class="text-sm text-indigo-700 dark:text-indigo-300">Voice Message</span>
                            <button wire:click="removeVoiceNote" type="button" class="ml-2 text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if($audioFile)
                        <div class="relative inline-flex items-center px-3 py-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                            <svg class="w-4 h-4 mr-2 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-orange-700 dark:text-orange-300">Audio File</span>
                            <button wire:click="removeAudioFile" type="button" class="ml-2 text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                    
                    @foreach($attachments as $index => $attachment)
                        <div class="relative inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            @if(str_starts_with($attachment->getMimeType(), 'image/') && $attachmentType === 'photo')
                                <svg class="w-4 h-4 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            @endif
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
                {{-- Attachment Menu --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="flex-shrink-0 p-3 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>

                    {{-- Attachment Options Dropdown --}}
                    <div x-show="open" @click.away="open = false" 
                         class="absolute bottom-full left-0 mb-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700"
                         style="display: none;">
                        <label wire:click="setAttachmentType('photo')" class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer rounded-t-xl">
                            <input type="file" wire:model="attachments" accept="image/*" multiple class="hidden">
                            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Photo</span>
                        </label>
                        
                        <label wire:click="setAttachmentType('document')" class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                            <input type="file" wire:model="attachments" multiple class="hidden">
                            <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Document</span>
                        </label>

                        <label wire:click="setAttachmentType('audio')" class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                            <input type="file" wire:model="audioFile" accept="audio/*" class="hidden">
                            <svg class="w-5 h-5 mr-3 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Audio File</span>
                        </label>
                        
                        <div class="relative">
                            <input type="file" id="voice-input" wire:model="voiceNote" class="hidden" accept="audio/*">
                            
                            <label class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer rounded-b-xl"
                                   x-data="{ 
                                       recording: false, 
                                       mediaRecorder: null,
                                       startRecording() {
                                           navigator.mediaDevices.getUserMedia({ audio: true })
                                               .then(stream => {
                                                   this.mediaRecorder = new MediaRecorder(stream);
                                                   const chunks = [];
                                                   
                                                   this.mediaRecorder.ondataavailable = (e) => chunks.push(e.data);
                                                   this.mediaRecorder.onstop = () => {
                                                       const blob = new Blob(chunks, { type: 'audio/webm' });
                                                       const file = new File([blob], 'voice-message-' + Date.now() + '.webm', { type: 'audio/webm' });
                                                       
                                                       // Create a DataTransfer to set the file input value
                                                       const dataTransfer = new DataTransfer();
                                                       dataTransfer.items.add(file);
                                                       document.getElementById('voice-input').files = dataTransfer.files;
                                                       
                                                       // Trigger Livewire to detect the file
                                                       document.getElementById('voice-input').dispatchEvent(new Event('change', { bubbles: true }));
                                                       
                                                       stream.getTracks().forEach(track => track.stop());
                                                   };
                                                   
                                                   this.mediaRecorder.start();
                                                   this.recording = true;
                                               })
                                               .catch(err => {
                                                   console.error('Microphone error:', err);
                                                   alert('Microphone access denied. Please allow microphone access in your browser settings.');
                                               });
                                       },
                                       stopRecording() {
                                           if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
                                               this.mediaRecorder.stop();
                                               this.recording = false;
                                           }
                                       }
                                   }">
                                <div @click="recording ? stopRecording() : startRecording()" class="flex items-center w-full">
                                    <svg class="w-5 h-5 mr-3" :class="recording ? 'text-red-500 animate-pulse' : 'text-purple-500'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 4a3 3 0 016 0v6a3 3 0 11-6 0V4z"></path>
                                        <path d="M5.5 9.643a.75.75 0 00-1.5 0V10c0 3.06 2.29 5.585 5.25 5.954V17.5h-1.5a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-1.5v-1.546A6.001 6.001 0 0016 10v-.357a.75.75 0 00-1.5 0V10a4.5 4.5 0 01-9 0v-.357z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300" x-text="recording ? 'Stop Recording' : 'Voice Message'"></span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

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

    {{-- Message Info Modal --}}
    <div x-data="{ open: false, message: null, deliveredAt: null, readBy: [] }"
         @open-message-info-modal.window="
            open = true; 
            message = $event.detail.message; 
            deliveredAt = $event.detail.delivered_at; 
            readBy = $event.detail.read_by;
         "
         x-show="open" 
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="open = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Message Info
                    </h3>
                    <div class="mt-4 space-y-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-bold">Sent:</span> <span x-text="message ? new Date(message.created_at).toLocaleString() : ''"></span>
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-bold">Delivered:</span> <span x-text="deliveredAt ? new Date(deliveredAt).toLocaleString() : 'Pending'"></span>
                        </p>
                        <div>
                            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-2">Read By:</p>
                            <ul class="text-sm text-gray-500 dark:text-gray-400 max-h-40 overflow-y-auto">
                                <template x-for="read in readBy" :key="read.id">
                                    <li class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                        <span x-text="read.user.name"></span>
                                        <span x-text="new Date(read.read_at).toLocaleString()" class="text-xs text-gray-400"></span>
                                    </li>
                                </template>
                                <li x-show="readBy.length === 0" class="text-gray-400 italic">Not read yet</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="open = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
