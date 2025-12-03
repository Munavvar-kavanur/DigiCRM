<div wire:poll.10s>
    @if($unreadCount > 0)
        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-3">{{ $unreadCount }}</span>
    @endif
</div>
