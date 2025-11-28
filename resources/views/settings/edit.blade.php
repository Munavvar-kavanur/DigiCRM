<x-app-layout>
    <div class="py-12" x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || localStorage.getItem('settings_active_tab') || 'details',
        updateTab(tab) {
            this.activeTab = tab;
            localStorage.setItem('settings_active_tab', tab);
            
            // Update URL without reloading
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        }
    }">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Sidebar Navigation -->
                <div class="lg:col-span-3">
                    <div class="sticky top-6 space-y-4">
                        <div class="px-4 mb-2">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Settings</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your application preferences</p>
                        </div>
                        
                        <nav class="flex flex-col space-y-1">
                            <!-- Company Details -->
                            <button @click="updateTab('details')" 
                                :class="{ 'bg-white dark:bg-gray-800 shadow-sm border-l-4 border-indigo-500': activeTab === 'details', 'hover:bg-gray-50 dark:hover:bg-gray-800/50 border-l-4 border-transparent': activeTab !== 'details' }" 
                                class="group flex items-center px-4 py-4 text-sm font-medium rounded-r-xl transition-all duration-200 w-full text-left">
                                <div :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400': activeTab === 'details', 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-600': activeTab !== 'details' }" class="flex-shrink-0 p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <span :class="{ 'text-gray-900 dark:text-white': activeTab === 'details', 'text-gray-600 dark:text-gray-400': activeTab !== 'details' }" class="block text-base font-semibold">Company Details</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-500 font-normal mt-0.5 block">Address, contact info & tax ID</span>
                                </div>
                            </button>

                            <!-- Branding -->
                            <button @click="updateTab('branding')" 
                                :class="{ 'bg-white dark:bg-gray-800 shadow-sm border-l-4 border-indigo-500': activeTab === 'branding', 'hover:bg-gray-50 dark:hover:bg-gray-800/50 border-l-4 border-transparent': activeTab !== 'branding' }" 
                                class="group flex items-center px-4 py-4 text-sm font-medium rounded-r-xl transition-all duration-200 w-full text-left">
                                <div :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400': activeTab === 'branding', 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-600': activeTab !== 'branding' }" class="flex-shrink-0 p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span :class="{ 'text-gray-900 dark:text-white': activeTab === 'branding', 'text-gray-600 dark:text-gray-400': activeTab !== 'branding' }" class="block text-base font-semibold">Branding & Logos</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-500 font-normal mt-0.5 block">Logos, favicons & themes</span>
                                </div>
                            </button>

                            <!-- Localization -->
                            <button @click="updateTab('localization')" 
                                :class="{ 'bg-white dark:bg-gray-800 shadow-sm border-l-4 border-indigo-500': activeTab === 'localization', 'hover:bg-gray-50 dark:hover:bg-gray-800/50 border-l-4 border-transparent': activeTab !== 'localization' }" 
                                class="group flex items-center px-4 py-4 text-sm font-medium rounded-r-xl transition-all duration-200 w-full text-left">
                                <div :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400': activeTab === 'localization', 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-600': activeTab !== 'localization' }" class="flex-shrink-0 p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span :class="{ 'text-gray-900 dark:text-white': activeTab === 'localization', 'text-gray-600 dark:text-gray-400': activeTab !== 'localization' }" class="block text-base font-semibold">Localization</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-500 font-normal mt-0.5 block">Currency & regional formats</span>
                                </div>
                            </button>

                            <!-- Expense Categories -->
                            <button @click="updateTab('expense_categories')" 
                                :class="{ 'bg-white dark:bg-gray-800 shadow-sm border-l-4 border-indigo-500': activeTab === 'expense_categories', 'hover:bg-gray-50 dark:hover:bg-gray-800/50 border-l-4 border-transparent': activeTab !== 'expense_categories' }" 
                                class="group flex items-center px-4 py-4 text-sm font-medium rounded-r-xl transition-all duration-200 w-full text-left">
                                <div :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400': activeTab === 'expense_categories', 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-600': activeTab !== 'expense_categories' }" class="flex-shrink-0 p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <span :class="{ 'text-gray-900 dark:text-white': activeTab === 'expense_categories', 'text-gray-600 dark:text-gray-400': activeTab !== 'expense_categories' }" class="block text-base font-semibold">Expense Categories</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-500 font-normal mt-0.5 block">Manage expense types</span>
                                </div>
                            </button>

                            <!-- Employee Types -->
                            <button @click="updateTab('employee_types')" 
                                :class="{ 'bg-white dark:bg-gray-800 shadow-sm border-l-4 border-indigo-500': activeTab === 'employee_types', 'hover:bg-gray-50 dark:hover:bg-gray-800/50 border-l-4 border-transparent': activeTab !== 'employee_types' }" 
                                class="group flex items-center px-4 py-4 text-sm font-medium rounded-r-xl transition-all duration-200 w-full text-left">
                                <div :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400': activeTab === 'employee_types', 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-600': activeTab !== 'employee_types' }" class="flex-shrink-0 p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span :class="{ 'text-gray-900 dark:text-white': activeTab === 'employee_types', 'text-gray-600 dark:text-gray-400': activeTab !== 'employee_types' }" class="block text-base font-semibold">Employee Types</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-500 font-normal mt-0.5 block">Employment classifications</span>
                                </div>
                            </button>

                            <!-- Payroll Types -->
                            <button @click="updateTab('payroll_types')" 
                                :class="{ 'bg-white dark:bg-gray-800 shadow-sm border-l-4 border-indigo-500': activeTab === 'payroll_types', 'hover:bg-gray-50 dark:hover:bg-gray-800/50 border-l-4 border-transparent': activeTab !== 'payroll_types' }" 
                                class="group flex items-center px-4 py-4 text-sm font-medium rounded-r-xl transition-all duration-200 w-full text-left">
                                <div :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400': activeTab === 'payroll_types', 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-600': activeTab !== 'payroll_types' }" class="flex-shrink-0 p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span :class="{ 'text-gray-900 dark:text-white': activeTab === 'payroll_types', 'text-gray-600 dark:text-gray-400': activeTab !== 'payroll_types' }" class="block text-base font-semibold">Payroll Types</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-500 font-normal mt-0.5 block">Salary structures</span>
                                </div>
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="lg:col-span-9">
                    
                    @if(auth()->user()->isSuperAdmin())
                    <!-- Super Admin Settings Section -->
                    <div x-data="{ expanded: false }" class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-md">
                        <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-white dark:from-indigo-900/20 dark:to-gray-800 hover:from-indigo-100 dark:hover:from-indigo-900/30 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg mr-4 text-indigo-600 dark:text-indigo-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Super Admin Configuration</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage global application settings and branding</p>
                                </div>
                            </div>
                            <div class="flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                <span x-text="expanded ? 'Collapse' : 'Expand'" class="mr-2"></span>
                                <svg class="w-5 h-5 transform transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>

                        <div x-show="expanded" x-collapse x-cloak class="border-t border-gray-100 dark:border-gray-700">
                            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="branch_id" value="">

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                                    <!-- Company Details -->
                                    <div class="space-y-6">
                                        <div class="flex items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">Global Company Details</h4>
                                        </div>
                                        
                                        <div class="space-y-5">
                                            <div>
                                                <x-input-label for="sa_company_name" :value="__('Company Name')" />
                                                <x-text-input id="sa_company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $superAdminSettings['company_name'] ?? '')" />
                                            </div>
                                            <div>
                                                <x-input-label for="sa_company_email" :value="__('Company Email')" />
                                                <x-text-input id="sa_company_email" name="company_email" type="email" class="mt-1 block w-full" :value="old('company_email', $superAdminSettings['company_email'] ?? '')" />
                                            </div>
                                            <div>
                                                <x-input-label for="sa_company_phone" :value="__('Company Phone')" />
                                                <x-text-input id="sa_company_phone" name="company_phone" type="text" class="mt-1 block w-full" :value="old('company_phone', $superAdminSettings['company_phone'] ?? '')" />
                                            </div>
                                            <div>
                                                <x-input-label for="sa_company_website" :value="__('Company Website')" />
                                                <x-text-input id="sa_company_website" name="company_website" type="url" class="mt-1 block w-full" :value="old('company_website', $superAdminSettings['company_website'] ?? '')" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Branding -->
                                    <div class="space-y-6">
                                        <div class="flex items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">Global Branding</h4>
                                        </div>
                                        
                                        <div class="space-y-6">
                                            <!-- Favicon -->
                                            <div>
                                                <x-input-label for="sa_favicon" :value="__('Favicon (ICO/PNG, 32x32px)')" />
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        @if(isset($superAdminSettings['favicon']))
                                                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 w-12 h-12 flex items-center justify-center">
                                                                <img src="{{ asset('storage/' . $superAdminSettings['favicon']) }}" alt="Favicon" class="h-8 w-8 object-contain">
                                                            </div>
                                                        @else
                                                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <input type="file" id="sa_favicon" name="favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300">
                                                </div>
                                            </div>

                                            <!-- Light Logo -->
                                            <div>
                                                <x-input-label for="sa_crm_logo_light" :value="__('Light Mode Logo')" />
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        @if(isset($superAdminSettings['crm_logo_light']))
                                                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 w-32 h-12 flex items-center justify-center">
                                                                <img src="{{ asset('storage/' . $superAdminSettings['crm_logo_light']) }}" alt="Light Logo" class="max-h-8 max-w-full object-contain">
                                                            </div>
                                                        @else
                                                            <div class="w-32 h-12 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400">
                                                                <span class="text-xs">No Logo</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <input type="file" id="sa_crm_logo_light" name="crm_logo_light" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300">
                                                </div>
                                            </div>

                                            <!-- Dark Logo -->
                                            <div>
                                                <x-input-label for="sa_crm_logo_dark" :value="__('Dark Mode Logo')" />
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        @if(isset($superAdminSettings['crm_logo_dark']))
                                                            <div class="p-2 bg-gray-900 dark:bg-gray-800 rounded-lg border border-gray-700 dark:border-gray-600 w-32 h-12 flex items-center justify-center">
                                                                <img src="{{ asset('storage/' . $superAdminSettings['crm_logo_dark']) }}" alt="Dark Logo" class="max-h-8 max-w-full object-contain">
                                                            </div>
                                                        @else
                                                            <div class="w-32 h-12 bg-gray-900 dark:bg-gray-800 rounded-lg border border-gray-700 dark:border-gray-600 flex items-center justify-center text-gray-500">
                                                                <span class="text-xs">No Logo</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <input type="file" id="sa_crm_logo_dark" name="crm_logo_dark" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <x-primary-button class="px-6 py-2.5 text-sm">{{ __('Save Super Admin Settings') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-8 text-gray-900 dark:text-gray-100">
                            
                            @if(auth()->user()->isSuperAdmin())
                                <div class="mb-8 p-5 bg-blue-50 dark:bg-blue-900/20 rounded-xl border-l-4 border-blue-500 dark:border-blue-400">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div>
                                            <h4 class="text-lg font-bold text-blue-900 dark:text-blue-100">Branch Settings Context</h4>
                                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Select a branch to configure its specific settings.</p>
                                        </div>
                                        <div class="w-full sm:w-72">
                                            <form id="branch-context-form" onsubmit="return false;">
                                                <input type="hidden" name="tab" :value="activeTab">
                                                <div class="relative">
                                                    <select name="branch_id" 
                                                        @change="
                                                            let branchId = $event.target.value;
                                                            let url = new URL(window.location.href);
                                                            url.searchParams.set('branch_id', branchId);
                                                            url.searchParams.set('tab', activeTab);
                                                            
                                                            // Update URL
                                                            window.history.pushState({}, '', url);
                                                            
                                                            // Show loading state (optional)
                                                            document.getElementById('main-settings-container').style.opacity = '0.5';
                                                            
                                                            // Fetch new content
                                                            fetch(url)
                                                                .then(response => response.text())
                                                                .then(html => {
                                                                    let parser = new DOMParser();
                                                                    let doc = parser.parseFromString(html, 'text/html');
                                                                    let newContent = doc.getElementById('main-settings-container').innerHTML;
                                                                    
                                                                    document.getElementById('main-settings-container').innerHTML = newContent;
                                                                    document.getElementById('main-settings-container').style.opacity = '1';
                                                                    
                                                                    // Re-initialize Alpine if necessary (usually automatic for new DOM in x-data scope, 
                                                                    // but since we are replacing innerHTML of a child, we might need to be careful.
                                                                    // Actually, since the x-data is on the parent, replacing child HTML should be fine 
                                                                    // as long as we don't destroy the x-data root.)
                                                                })
                                                                .catch(err => {
                                                                    console.error('Error fetching settings:', err);
                                                                    window.location.reload(); // Fallback
                                                                });
                                                        "
                                                        class="block w-full pl-4 pr-10 py-2.5 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg dark:bg-gray-800 dark:text-white shadow-sm">
                                                        @foreach($branches as $branch)
                                                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="main-settings-container">
                                <!-- Hidden input for the main form to persist branch_id on save -->
                                <input type="hidden" name="branch_id" value="{{ $branchId }}" form="main-settings-form">
                            @elseif(auth()->user()->branch_id)
                                <div id="main-settings-container">
                                <div class="mb-8 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border-l-4 border-green-500 dark:border-green-400 flex items-center">
                                    <div class="flex-shrink-0 mr-3">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-green-900 dark:text-green-100">Branch Settings Active</h4>
                                        <p class="text-sm text-green-700 dark:text-green-300">You are editing settings for <strong>{{ auth()->user()->branch->name }}</strong>. These will override global defaults.</p>
                                    </div>
                                </div>
                            @else
                                <div id="main-settings-container">
                            @endif
                            
                            <form id="main-settings-form" method="post" action="{{ route('settings.update') }}" class="space-y-8" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="tab" :value="activeTab">

                                <!-- Company Details Tab -->
                                <div x-show="activeTab === 'details'" class="space-y-8">
                                    <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Company Information</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update your company's core details and contact information.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-6">
                                            <div>
                                                <x-input-label for="company_name" :value="__('Company Name')" />
                                                <div class="relative mt-2">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    </div>
                                                    <x-text-input id="company_name" name="company_name" type="text" class="block w-full pl-10" :value="old('company_name', $settings['company_name'] ?? '')" />
                                                </div>
                                            </div>
                                            <div>
                                                <x-input-label for="company_email" :value="__('Company Email')" />
                                                <div class="relative mt-2">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                    <x-text-input id="company_email" name="company_email" type="email" class="block w-full pl-10" :value="old('company_email', $settings['company_email'] ?? '')" />
                                                </div>
                                            </div>
                                            <div>
                                                <x-input-label for="company_phone" :value="__('Company Phone')" />
                                                <div class="relative mt-2">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                        </svg>
                                                    </div>
                                                    <x-text-input id="company_phone" name="company_phone" type="text" class="block w-full pl-10" :value="old('company_phone', $settings['company_phone'] ?? '')" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-6">
                                            <div>
                                                <x-input-label for="company_website" :value="__('Company Website')" />
                                                <div class="relative mt-2">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                        </svg>
                                                    </div>
                                                    <x-text-input id="company_website" name="company_website" type="url" class="block w-full pl-10" :value="old('company_website', $settings['company_website'] ?? '')" placeholder="https://example.com" />
                                                </div>
                                            </div>
                                            <div>
                                                <x-input-label for="tax_id" :value="__('Tax ID / VAT')" />
                                                <div class="relative mt-2">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <x-text-input id="tax_id" name="tax_id" type="text" class="block w-full pl-10" :value="old('tax_id', $settings['tax_id'] ?? '')" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="md:col-span-2">
                                            <x-input-label for="company_address" :value="__('Address')" />
                                            <textarea id="company_address" name="company_address" rows="3" class="block mt-2 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm transition-colors">{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                                        </div>
                                        
                                        <!-- Address Details -->
                                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                            <div>
                                                <x-input-label for="company_city" :value="__('City')" />
                                                <x-text-input id="company_city" name="company_city" type="text" class="mt-2 block w-full" :value="old('company_city', $settings['company_city'] ?? '')" />
                                            </div>
                                            <div>
                                                <x-input-label for="company_state" :value="__('State / Province')" />
                                                <x-text-input id="company_state" name="company_state" type="text" class="mt-2 block w-full" :value="old('company_state', $settings['company_state'] ?? '')" />
                                            </div>
                                            <div>
                                                <x-input-label for="company_zip" :value="__('Zip / Postal Code')" />
                                                <x-text-input id="company_zip" name="company_zip" type="text" class="mt-2 block w-full" :value="old('company_zip', $settings['company_zip'] ?? '')" />
                                            </div>
                                            <div>
                                                <x-input-label for="company_country" :value="__('Country')" />
                                                <x-text-input id="company_country" name="company_country" type="text" class="mt-2 block w-full" :value="old('company_country', $settings['company_country'] ?? '')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Branding Tab -->
                                <div x-show="activeTab === 'branding'" class="space-y-8" x-cloak>
                                    <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Branding & Logos</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Customize the look and feel of your CRM and documents.</p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-8">
                                        
                                        <!-- CRM Logos -->
                                        <div class="bg-gray-50 dark:bg-gray-700/30 p-8 rounded-2xl border border-gray-100 dark:border-gray-700">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                                                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg mr-3 text-indigo-600 dark:text-indigo-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                CRM Logos
                                            </h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                                <!-- Favicon -->
                                                <div class="md:col-span-2">
                                                    <x-input-label for="favicon" :value="__('Favicon (ICO/PNG, 32x32px)')" />
                                                    <div class="mt-2 flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            @if(isset($settings['favicon']))
                                                                <div class="p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 w-12 h-12 flex items-center justify-center">
                                                                    <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon" class="h-8 w-8 object-contain">
                                                                </div>
                                                            @else
                                                                <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-400">
                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" id="favicon" name="favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300">
                                                    </div>
                                                    <x-input-error :messages="$errors->get('favicon')" class="mt-2" />
                                                </div>

                                                <!-- Full Logos -->
                                                <div>
                                                    <x-input-label for="crm_logo_light" :value="__('Light Mode Logo (Rec: 200x50px)')" />
                                                    <div class="mt-2 flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            @if(isset($settings['crm_logo_light']))
                                                                <div class="p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 w-32 h-12 flex items-center justify-center">
                                                                    <img src="{{ asset('storage/' . $settings['crm_logo_light']) }}" alt="Light Logo" class="max-h-8 max-w-full object-contain">
                                                                </div>
                                                            @else
                                                                <div class="w-32 h-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-400">
                                                                    <span class="text-xs">No Logo</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" id="crm_logo_light" name="crm_logo_light" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300">
                                                    </div>
                                                    <x-input-error :messages="$errors->get('crm_logo_light')" class="mt-2" />
                                                </div>
                                                <div>
                                                    <x-input-label for="crm_logo_dark" :value="__('Dark Mode Logo (Rec: 200x50px)')" />
                                                    <div class="mt-2 flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            @if(isset($settings['crm_logo_dark']))
                                                                <div class="p-2 bg-gray-900 dark:bg-gray-800 rounded-lg border border-gray-700 dark:border-gray-600 w-32 h-12 flex items-center justify-center">
                                                                    <img src="{{ asset('storage/' . $settings['crm_logo_dark']) }}" alt="Dark Logo" class="max-h-8 max-w-full object-contain">
                                                                </div>
                                                            @else
                                                                <div class="w-32 h-12 bg-gray-900 dark:bg-gray-800 rounded-lg border border-gray-700 dark:border-gray-600 flex items-center justify-center text-gray-500">
                                                                    <span class="text-xs">No Logo</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" id="crm_logo_dark" name="crm_logo_dark" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300">
                                                    </div>
                                                    <x-input-error :messages="$errors->get('crm_logo_dark')" class="mt-2" />
                                                </div>

                                                <!-- Collapsed Logos -->
                                                <div>
                                                    <x-input-label for="crm_logo_collapsed_light" :value="__('Collapsed Menu Logo Light (Rec: 32x32px)')" />
                                                    <div class="mt-2 flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            @if(isset($settings['crm_logo_collapsed_light']))
                                                                <div class="p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 w-12 h-12 flex items-center justify-center">
                                                                    <img src="{{ asset('storage/' . $settings['crm_logo_collapsed_light']) }}" alt="Collapsed Light Logo" class="h-8 w-8 object-contain">
                                                                </div>
                                                            @else
                                                                <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-400">
                                                                    <span class="text-xs">No Logo</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" id="crm_logo_collapsed_light" name="crm_logo_collapsed_light" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300">
                                                    </div>
                                                    <x-input-error :messages="$errors->get('crm_logo_collapsed_light')" class="mt-2" />
                                                </div>
                                                <div>
                                                    <x-input-label for="crm_logo_collapsed_dark" :value="__('Collapsed Menu Logo Dark (Rec: 32x32px)')" />
                                                    <div class="mt-2 flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            @if(isset($settings['crm_logo_collapsed_dark']))
                                                                <div class="p-2 bg-gray-900 dark:bg-gray-800 rounded-lg border border-gray-700 dark:border-gray-600 w-12 h-12 flex items-center justify-center">
                                                                    <img src="{{ asset('storage/' . $settings['crm_logo_collapsed_dark']) }}" alt="Collapsed Dark Logo" class="h-8 w-8 object-contain">
                                                                </div>
                                                            @else
                                                                <div class="w-12 h-12 bg-gray-900 dark:bg-gray-800 rounded-lg border border-gray-700 dark:border-gray-600 flex items-center justify-center text-gray-500">
                                                                    <span class="text-xs">No Logo</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" id="crm_logo_collapsed_dark" name="crm_logo_collapsed_dark" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300">
                                                    </div>
                                                    <x-input-error :messages="$errors->get('crm_logo_collapsed_dark')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Localization Tab -->
                                <div x-show="activeTab === 'localization'" class="space-y-8" x-cloak>
                                    <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Localization</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Set your preferred currency, date format, and regional settings.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-6">
                                            <div>
                                                <x-input-label for="currency_symbol" :value="__('Currency Symbol')" />
                                                <x-text-input id="currency_symbol" name="currency_symbol" type="text" class="mt-2 block w-full" :value="old('currency_symbol', $settings['currency_symbol'] ?? '$')" placeholder="$" />
                                            </div>
                                            <div>
                                                <x-input-label for="currency_code" :value="__('Currency Code')" />
                                                <x-text-input id="currency_code" name="currency_code" type="text" class="mt-2 block w-full" :value="old('currency_code', $settings['currency_code'] ?? 'USD')" placeholder="USD" />
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-6">
                                            <div>
                                                <x-input-label for="date_format" :value="__('Date Format')" />
                                                <select id="date_format" name="date_format" class="mt-2 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                    <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2024-03-15)</option>
                                                    <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (15/03/2024)</option>
                                                    <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (03/15/2024)</option>
                                                    <option value="d-M-Y" {{ ($settings['date_format'] ?? '') == 'd-M-Y' ? 'selected' : '' }}>DD-MMM-YYYY (15-Mar-2024)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label for="timezone" :value="__('Timezone')" />
                                                <select id="timezone" name="timezone" class="mt-2 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                    @foreach(timezone_identifiers_list() as $timezone)
                                                        <option value="{{ $timezone }}" {{ ($settings['timezone'] ?? config('app.timezone')) == $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4" x-show="['details', 'branding', 'localization'].includes(activeTab)">
                                    <x-primary-button>{{ __('Save Settings') }}</x-primary-button>

                                    @if (session('status') === 'settings-updated')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                        >{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                                </form>

                            <!-- Expense Categories Tab -->
                            <div x-show="activeTab === 'expense_categories'" class="space-y-8" x-cloak>
                                <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Expense Categories</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage categories for expense tracking (e.g., Travel, Office Supplies).</p>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <!-- Add New Category -->
                                    <div class="lg:col-span-1">
                                        <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 sticky top-6">
                                            <h4 class="text-base font-bold text-gray-900 dark:text-white mb-4">Add New Category</h4>
                                            <form action="{{ route('expense-categories.store') }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <x-input-label for="new_expense_category" :value="__('Category Name')" />
                                                    <x-text-input id="new_expense_category" name="name" type="text" class="mt-2 block w-full" required placeholder="e.g., Travel" />
                                                </div>
                                                <div>
                                                    <x-input-label for="new_expense_category_description" :value="__('Description')" />
                                                    <x-text-input id="new_expense_category_description" name="description" type="text" class="mt-2 block w-full" placeholder="Optional description..." />
                                                </div>
                                                <x-primary-button class="w-full justify-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    {{ __('Add Category') }}
                                                </x-primary-button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- List Categories -->
                                    <div class="lg:col-span-2">
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @forelse($expenseCategories as $category)
                                                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                                                    {{ $category->name }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $category->description ?? '-' }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <form action="{{ route('expense-categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                                                No expense categories found. Add one to get started.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Employee Types Tab -->
                            <div x-show="activeTab === 'employee_types'" class="space-y-8" x-cloak>
                                <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Employee Types</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Define employment classifications (e.g., Full-time, Contract).</p>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <!-- Add New Type -->
                                    <div class="lg:col-span-1">
                                        <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 sticky top-6">
                                            <h4 class="text-base font-bold text-gray-900 dark:text-white mb-4">Add New Employee Type</h4>
                                            <form action="{{ route('employee-types.store') }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <x-input-label for="employee_type_name" :value="__('Type Name')" />
                                                    <x-text-input id="employee_type_name" name="name" type="text" class="mt-2 block w-full" required placeholder="e.g., Permanent" />
                                                </div>
                                                <div>
                                                    <x-input-label for="employee_type_description" :value="__('Description')" />
                                                    <x-text-input id="employee_type_description" name="description" type="text" class="mt-2 block w-full" placeholder="Optional description..." />
                                                </div>
                                                <x-primary-button class="w-full justify-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    {{ __('Add Type') }}
                                                </x-primary-button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- List Types -->
                                    <div class="lg:col-span-2">
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @forelse($employeeTypes as $type)
                                                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                                    {{ $type->name }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $type->description ?? '-' }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <form action="{{ route('employee-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                                                No employee types found. Add one to get started.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payroll Types Tab -->
                            <div x-show="activeTab === 'payroll_types'" class="space-y-8" x-cloak>
                                <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Payroll Types</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Define salary structures (e.g., Monthly Salary, Hourly Rate).</p>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <!-- Add New Type -->
                                    <div class="lg:col-span-1">
                                        <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 sticky top-6">
                                            <h4 class="text-base font-bold text-gray-900 dark:text-white mb-4">Add New Payroll Type</h4>
                                            <form action="{{ route('payroll-types.store') }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <x-input-label for="payroll_type_name" :value="__('Type Name')" />
                                                    <x-text-input id="payroll_type_name" name="name" type="text" class="mt-2 block w-full" required placeholder="e.g., Monthly Salary" />
                                                </div>
                                                <div>
                                                    <x-input-label for="payroll_type_description" :value="__('Description')" />
                                                    <x-text-input id="payroll_type_description" name="description" type="text" class="mt-2 block w-full" placeholder="Optional description..." />
                                                </div>
                                                <x-primary-button class="w-full justify-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    {{ __('Add Type') }}
                                                </x-primary-button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- List Types -->
                                    <div class="lg:col-span-2">
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @forelse($payrollTypes as $type)
                                                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                                    {{ $type->name }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $type->description ?? '-' }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <form action="{{ route('payroll-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                                                No payroll types found. Add one to get started.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cropper.js Modal -->
    <div x-data="imageCropper()" x-show="isOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                    </button>
                    <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropper.js Styles & Scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        function imageCropper() {
            return {
                isOpen: false,
                cropper: null,
                targetInputId: null,
                aspectRatio: 1,

                init() {
                    // Listen for file input changes
                    const fileInputs = document.querySelectorAll('input[type="file"]');
                    fileInputs.forEach(input => {
                        input.addEventListener('change', (e) => {
                            if (e.target.files && e.target.files.length > 0) {
                                this.targetInputId = e.target.id;
                                
                                // Determine aspect ratio based on input ID
                                if (this.targetInputId.includes('collapsed')) {
                                    this.aspectRatio = 1; // Square for collapsed/favicon
                                } else {
                                    this.aspectRatio = NaN; // Free crop for main logos (or set to 4 if strictly rectangular)
                                }

                                const file = e.target.files[0];
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    document.getElementById('cropper-image').src = e.target.result;
                                    this.openModal();
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    });
                },

                openModal() {
                    this.isOpen = true;
                    this.$nextTick(() => {
                        if (this.cropper) {
                            this.cropper.destroy();
                        }
                        const image = document.getElementById('cropper-image');
                        this.cropper = new Cropper(image, {
                            aspectRatio: this.aspectRatio,
                            viewMode: 1,
                            autoCropArea: 1,
                        });
                    });
                },

                closeModal() {
                    this.isOpen = false;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                    // Reset input if cancelled so change event fires again for same file
                    if (this.targetInputId) {
                        document.getElementById(this.targetInputId).value = '';
                    }
                },

                cropImage() {
                    if (!this.cropper) return;

                    const canvas = this.cropper.getCroppedCanvas();
                    
                    canvas.toBlob((blob) => {
                        // Create a new File object
                        const fileInput = document.getElementById(this.targetInputId);
                        const fileName = fileInput.files[0].name;
                        const newFile = new File([blob], fileName, { type: blob.type });

                        // Update the file input with the cropped file
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(newFile);
                        fileInput.files = dataTransfer.files;

                        // Update preview if it exists
                        // Find the preview container (sibling div with img)
                        const previewContainer = fileInput.nextElementSibling?.nextElementSibling; 
                        // Note: In our blade, the error message is next, then the preview div. 
                        // Structure: input -> error -> div(img)
                        
                        // Let's try to find the img tag in the parent container to be safe
                        const parent = fileInput.parentElement;
                        const img = parent.querySelector('img');
                        
                        if (img) {
                            img.src = URL.createObjectURL(blob);
                        } else {
                            // If no preview exists yet, we could create one, but for now let's just rely on the fact that the input is updated.
                            // The user will see the new file selected.
                        }

                        this.isOpen = false;
                        if (this.cropper) {
                            this.cropper.destroy();
                            this.cropper = null;
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>

