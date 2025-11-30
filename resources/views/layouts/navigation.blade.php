<div class="h-full flex flex-col justify-between">
    <div>
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 px-4 transition-all duration-300">
            <!-- Logo -->
            <div class="shrink-0 flex items-center transform transition-transform duration-300 hover:scale-105">
                <a href="{{ route('dashboard') }}" wire:navigate>
                    @php
                        // Get settings for the current context (Branch or Global)
                        $contextSettings = \App\Models\Setting::getAll();
                        
                        // Get Global Settings explicitly for fallback
                        $globalSettings = \App\Models\Setting::whereNull('branch_id')->pluck('value', 'key')->toArray();
                        
                        // Determine if we are in a branch context
                        $isBranchContext = auth()->check() && auth()->user()->branch_id;
                        
                        // Helper function to resolve settings with priority:
                        // 1. Branch Specific Setting
                        // 2. Branch Fallback Setting (e.g., favicon)
                        // 3. Global Specific Setting
                        // 4. Global Fallback Setting
                        $resolveSetting = function($key, $fallbackKey = null) use ($contextSettings, $globalSettings, $isBranchContext) {
                            if ($isBranchContext) {
                                if (!empty($contextSettings[$key])) return $contextSettings[$key];
                                if ($fallbackKey && !empty($contextSettings[$fallbackKey])) return $contextSettings[$fallbackKey];
                            }
                            
                            if (!empty($globalSettings[$key])) return $globalSettings[$key];
                            if ($fallbackKey && !empty($globalSettings[$fallbackKey])) return $globalSettings[$fallbackKey];
                            
                            return null;
                        };
                        
                        // Resolve Logos
                        $lightLogo = $resolveSetting('crm_logo_light', 'crm_logo_dark');
                        $darkLogo = $resolveSetting('crm_logo_dark', 'crm_logo_light');
                        
                        // Resolve Collapsed Logos (prioritizing favicon as fallback)
                        $collapsedLightLogo = $resolveSetting('crm_logo_collapsed_light', 'favicon');
                        $collapsedDarkLogo = $resolveSetting('crm_logo_collapsed_dark', 'favicon');
                        
                        // Final safety fallback to main logos
                        $collapsedLightLogo = $collapsedLightLogo ?: $lightLogo;
                        $collapsedDarkLogo = $collapsedDarkLogo ?: $darkLogo;
                    @endphp

                    <!-- Light Mode Logo -->
                    <div class="dark:hidden flex items-center justify-center h-9">
                        <!-- Full Logo -->
                        <div x-show="!sidebarCollapsed" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100">
                            <img src="{{ $lightLogo ? asset('storage/' . $lightLogo) : '' }}" 
                                 class="h-9 w-auto max-w-[10rem] object-contain" 
                                 alt="Logo"
                                 @if(!$lightLogo) style="display: none;" @endif>
                            @if(!$lightLogo)
                                 <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            @endif
                        </div>

                        <!-- Collapsed Logo -->
                        <div x-show="sidebarCollapsed"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100">
                            <img src="{{ $collapsedLightLogo ? asset('storage/' . $collapsedLightLogo) : '' }}" 
                                 class="h-8 w-8 object-contain" 
                                 alt="Logo"
                                 @if(!$collapsedLightLogo) style="display: none;" @endif>
                            @if(!$collapsedLightLogo)
                                 <x-application-logo class="block h-8 w-8 fill-current text-gray-800" />
                            @endif
                        </div>
                    </div>

                    <!-- Dark Mode Logo -->
                    <div class="hidden dark:flex items-center justify-center h-9">
                        <!-- Full Logo -->
                        <div x-show="!sidebarCollapsed"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100">
                            <img src="{{ $darkLogo ? asset('storage/' . $darkLogo) : '' }}" 
                                 class="h-9 w-auto max-w-[10rem] object-contain" 
                                 alt="Logo"
                                 @if(!$darkLogo) style="display: none;" @endif>
                            @if(!$darkLogo)
                                 <x-application-logo class="block h-9 w-auto fill-current text-gray-200" />
                            @endif
                        </div>

                        <!-- Collapsed Logo -->
                        <div x-show="sidebarCollapsed"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100">
                            <img src="{{ $collapsedDarkLogo ? asset('storage/' . $collapsedDarkLogo) : '' }}" 
                                 class="h-8 w-8 object-contain" 
                                 alt="Logo"
                                 @if(!$collapsedDarkLogo) style="display: none;" @endif>
                            @if(!$collapsedDarkLogo)
                                 <x-application-logo class="block h-8 w-8 fill-current text-gray-200" />
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="px-4 py-6 space-y-2 flex-1 overflow-y-auto scrollbar-hide">
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </x-slot>
                {{ __('Dashboard') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('clients.index')" :active="request()->routeIs('clients.*')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </x-slot>
                {{ __('Clients') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('projects.index')" :active="request()->routeIs('projects.*')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                </x-slot>
                {{ __('Projects') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </x-slot>
                {{ __('Invoices') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('estimates.index')" :active="request()->routeIs('estimates.*')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </x-slot>
                {{ __('Estimates') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </x-slot>
                {{ __('Expenses') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.*')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </x-slot>
                {{ __('Payroll') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </x-slot>
                {{ __('Employees') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </x-slot>
                {{ __('Reports') }}
            </x-sidebar-link>

            @if(auth()->user()->isSuperAdmin())
                <x-sidebar-link :href="route('branches.index')" :active="request()->routeIs('branches.*')" @click="sidebarOpen = false">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </x-slot>
                    {{ __('Branches') }}
                </x-sidebar-link>

                <x-sidebar-link :href="route('settings.users.index')" :active="request()->routeIs('settings.users.*')" @click="sidebarOpen = false">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </x-slot>
                    {{ __('Users & Roles') }}
                </x-sidebar-link>
            @endif
            <x-sidebar-link :href="route('reminders.index')" :active="request()->routeIs('reminders.*')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </x-slot>
                {{ __('Reminders') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('settings.edit')" :active="request()->routeIs('settings.edit')" @click="sidebarOpen = false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </x-slot>
                {{ __('Settings') }}
            </x-sidebar-link>
        </div>
    </div>

    <!-- Collapse Toggle -->
    <div class="p-4 border-t border-gray-100 dark:border-gray-700 hidden md:flex justify-end">
        <button @click="toggleSidebar()" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors border border-gray-200 dark:border-gray-700 shadow-sm">
            <svg class="w-6 h-6 transform transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
        </button>
    </div>
</div>
