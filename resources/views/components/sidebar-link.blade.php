@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-sm font-medium rounded-xl text-indigo-700 bg-indigo-50 dark:bg-indigo-900/50 dark:text-indigo-300 transition-all duration-200 group relative overflow-hidden shadow-sm ring-1 ring-indigo-200 dark:ring-indigo-800'
            : 'flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 group hover:scale-[1.02] hover:shadow-sm';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} :class="sidebarCollapsed ? 'justify-center px-2' : 'px-4 gap-3'">
    @if($active ?? false)
        <div class="absolute inset-y-0 left-0 w-1 bg-indigo-600 rounded-r-full" x-show="!sidebarCollapsed"></div>
    @endif

    <div class="shrink-0 transition-all duration-300 {{ ($active ?? false) ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }}">
        {{ $icon ?? '' }}
    </div>

    <span :class="sidebarCollapsed ? 'max-w-0 opacity-0' : 'max-w-[200px] opacity-100'" class="whitespace-nowrap overflow-hidden transition-all duration-300 ease-in-out origin-left">
        {{ $slot }}
    </span>
</a>
