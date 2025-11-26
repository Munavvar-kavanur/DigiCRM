<header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 h-16 flex items-center justify-between px-6 transition-all duration-200 sticky top-0 z-30">
    <!-- Left Side: Sidebar Toggle & Search -->
    <div class="flex items-center flex-1 gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Search Bar (Hidden on small screens) -->
        <div class="relative hidden md:block w-full max-w-md" 
             x-data="{ 
                query: '', 
                results: [], 
                showResults: false,
                performSearch() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.showResults = false;
                        return;
                    }
                    fetch('{{ route('global.search') }}?query=' + this.query)
                        .then(response => response.json())
                        .then(data => {
                            this.results = data;
                            this.showResults = true;
                        });
                }
             }" 
             @click.away="showResults = false">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" 
                x-model="query"
                @input.debounce.300ms="performSearch()"
                class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-xl leading-5 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-all duration-200" 
                placeholder="Search Clients, Projects, Invoices, Estimates..."
            >
            
            <!-- Search Results Dropdown -->
            <div x-show="showResults && query.length >= 2" 
                 class="absolute mt-2 w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                
                <ul class="max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                    <template x-for="result in results" :key="result.id + result.type">
                        <li>
                            <a :href="result.url" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="result.title"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="result.subtitle"></p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300" x-text="result.type"></span>
                                </div>
                            </a>
                        </li>
                    </template>
                    <li x-show="results.length === 0" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                        No results found.
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Side: Actions & Profile -->
    <div class="flex items-center space-x-3 md:space-x-4">
        
        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="relative p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-200">
                @if($unreadNotificationsCount > 0)
                    <span class="absolute top-2 right-2.5 block h-2 w-2 rounded-full ring-2 ring-white dark:ring-gray-800 bg-red-500"></span>
                @endif
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open" @click.away="open = false"
                 class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg py-2 z-50 ring-1 ring-black ring-opacity-5 border border-gray-100 dark:border-gray-700 transform origin-top-right"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 style="display: none;">
                
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
                    @if($unreadNotificationsCount > 0)
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{ $unreadNotificationsCount }} New</span>
                    @endif
                </div>

                <div class="max-h-64 overflow-y-auto">
                    @forelse($latestNotifications as $notification)
                        <a href="{{ route('reminders.index') }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $notification->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Due: {{ $notification->reminder_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            <p class="text-sm">No new notifications</p>
                        </div>
                    @endforelse
                </div>

                <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 rounded-b-xl">
                    <a href="{{ route('reminders.index') }}" class="block text-center text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors">
                        View All Reminders
                    </a>
                </div>
            </div>
        </div>

        <!-- Theme Toggle -->
        <button @click="toggleTheme()" class="relative p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-200">
            <svg x-show="!darkMode" class="w-6 h-6 transform transition-transform duration-500 rotate-0 hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
            <svg x-show="darkMode" class="w-6 h-6 transform transition-transform duration-500 rotate-0 hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </button>

        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none group">
                <div class="flex flex-col items-end hidden md:block">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 leading-tight">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Admin</span>
                </div>
                <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 group-hover:border-indigo-500 transition-colors duration-200" 
                     src="{{ Auth::user()->profile_photo_url }}" 
                     alt="{{ Auth::user()->name }}" />
            </button>

            <div x-show="open" @click.away="open = false" 
                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg py-2 z-50 ring-1 ring-black ring-opacity-5 border border-gray-100 dark:border-gray-700 transform origin-top-right"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 style="display: none;">
                
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 mb-2 md:hidden">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" wire:navigate>
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Profile') }}
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</header>
