<x-app-layout>
    <div class="space-y-8" x-data="{ 
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
        
        <!-- Header Section -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-cyan-600 to-teal-600 dark:from-cyan-900 dark:to-teal-900 shadow-xl">
            <div class="absolute inset-0 bg-white/10 dark:bg-black/10 backdrop-blur-[1px]"></div>
            <div class="relative p-8 md:p-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-white">
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
                        {{ __('Settings') }}
                    </h2>
                    <p class="mt-2 text-cyan-100 text-lg">
                        Manage your application preferences and configurations.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3">
                <div class="sticky top-6 space-y-4">
                    <nav class="flex flex-col space-y-2">
                        <!-- Company Details -->
                        <button @click="updateTab('details')" 
                            :class="{ 'bg-white dark:bg-gray-800 shadow-md text-cyan-600 dark:text-cyan-400': activeTab === 'details', 'hover:bg-white/50 dark:hover:bg-gray-800/50 text-gray-600 dark:text-gray-400': activeTab !== 'details' }" 
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 w-full text-left">
                            <svg class="w-5 h-5 mr-3" :class="{ 'text-cyan-600 dark:text-cyan-400': activeTab === 'details', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'details' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Company Details
                        </button>

                        <!-- Branding -->
                        <button @click="updateTab('branding')" 
                            :class="{ 'bg-white dark:bg-gray-800 shadow-md text-cyan-600 dark:text-cyan-400': activeTab === 'branding', 'hover:bg-white/50 dark:hover:bg-gray-800/50 text-gray-600 dark:text-gray-400': activeTab !== 'branding' }" 
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 w-full text-left">
                            <svg class="w-5 h-5 mr-3" :class="{ 'text-cyan-600 dark:text-cyan-400': activeTab === 'branding', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'branding' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Branding & Logos
                        </button>

                        <!-- Localization -->
                        <button @click="updateTab('localization')" 
                            :class="{ 'bg-white dark:bg-gray-800 shadow-md text-cyan-600 dark:text-cyan-400': activeTab === 'localization', 'hover:bg-white/50 dark:hover:bg-gray-800/50 text-gray-600 dark:text-gray-400': activeTab !== 'localization' }" 
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 w-full text-left">
                            <svg class="w-5 h-5 mr-3" :class="{ 'text-cyan-600 dark:text-cyan-400': activeTab === 'localization', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'localization' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Localization
                        </button>

                        <!-- Expense Categories -->
                        <button @click="updateTab('expense_categories')" 
                            :class="{ 'bg-white dark:bg-gray-800 shadow-md text-cyan-600 dark:text-cyan-400': activeTab === 'expense_categories', 'hover:bg-white/50 dark:hover:bg-gray-800/50 text-gray-600 dark:text-gray-400': activeTab !== 'expense_categories' }" 
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 w-full text-left">
                            <svg class="w-5 h-5 mr-3" :class="{ 'text-cyan-600 dark:text-cyan-400': activeTab === 'expense_categories', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'expense_categories' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            Expense Categories
                        </button>

                        <!-- Employee Types -->
                        <button @click="updateTab('employee_types')" 
                            :class="{ 'bg-white dark:bg-gray-800 shadow-md text-cyan-600 dark:text-cyan-400': activeTab === 'employee_types', 'hover:bg-white/50 dark:hover:bg-gray-800/50 text-gray-600 dark:text-gray-400': activeTab !== 'employee_types' }" 
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 w-full text-left">
                            <svg class="w-5 h-5 mr-3" :class="{ 'text-cyan-600 dark:text-cyan-400': activeTab === 'employee_types', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'employee_types' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Employee Types
                        </button>

                        <!-- Expense Payers -->
                        <button @click="updateTab('expense_payers')" 
                            :class="{ 'bg-white dark:bg-gray-800 shadow-md text-cyan-600 dark:text-cyan-400': activeTab === 'expense_payers', 'hover:bg-white/50 dark:hover:bg-gray-800/50 text-gray-600 dark:text-gray-400': activeTab !== 'expense_payers' }" 
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 w-full text-left">
                            <svg class="w-5 h-5 mr-3" :class="{ 'text-cyan-600 dark:text-cyan-400': activeTab === 'expense_payers', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'expense_payers' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Expense Payers
                        </button>

                        <!-- Payroll Types -->
                        <button @click="updateTab('payroll_types')" 
                            :class="{ 'bg-white dark:bg-gray-800 shadow-md text-cyan-600 dark:text-cyan-400': activeTab === 'payroll_types', 'hover:bg-white/50 dark:hover:bg-gray-800/50 text-gray-600 dark:text-gray-400': activeTab !== 'payroll_types' }" 
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 w-full text-left">
                            <svg class="w-5 h-5 mr-3" :class="{ 'text-cyan-600 dark:text-cyan-400': activeTab === 'payroll_types', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'payroll_types' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Payroll Types
                        </button>

                        @if(auth()->user()->isSuperAdmin())
                        <!-- Backup & Restore -->
                        <button @click="updateTab('backup')" 
                            :class="{ 'bg-white dark:bg-gray-800 shadow-md text-cyan-600 dark:text-cyan-400': activeTab === 'backup', 'hover:bg-white/50 dark:hover:bg-gray-800/50 text-gray-600 dark:text-gray-400': activeTab !== 'backup' }" 
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 w-full text-left">
                            <svg class="w-5 h-5 mr-3" :class="{ 'text-cyan-600 dark:text-cyan-400': activeTab === 'backup', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'backup' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Backup & Restore
                        </button>
                        @endif
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-9 space-y-6">
                
                @if(auth()->user()->isSuperAdmin())
                <!-- Super Admin Settings Section -->
                <div x-data="{ expanded: false }" class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-md">
                    <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 hover:from-gray-100 dark:hover:from-gray-700 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-gray-900 dark:bg-gray-700 rounded-xl mr-4 text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Super Admin Configuration</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Global application settings and branding</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400">
                            <span x-text="expanded ? 'Collapse' : 'Expand'" class="mr-2"></span>
                            <svg class="w-5 h-5 transform transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </button>

                    <div x-show="expanded" x-collapse x-cloak class="border-t border-gray-100 dark:border-gray-700">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="branch_id" value="">

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                                <!-- Company Details -->
                                <div class="space-y-6">
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Global Company Details</h4>
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
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Global Branding</h4>
                                    <div class="space-y-6">
                                        <!-- Favicon -->
                                        <div>
                                            <x-input-label for="sa_favicon" :value="__('Favicon')" />
                                            <div class="mt-2 flex items-center space-x-4">
                                                <div class="flex-shrink-0 w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                    @if(isset($superAdminSettings['favicon']))
                                                        <img src="{{ asset('storage/' . $superAdminSettings['favicon']) }}" alt="Favicon" class="h-8 w-8 object-contain">
                                                    @else
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    @endif
                                                </div>
                                                <input type="file" id="sa_favicon" name="favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
                                            </div>
                                        </div>

                                        <!-- Light Logo -->
                                        <div>
                                            <x-input-label for="sa_crm_logo_light" :value="__('Light Mode Logo')" />
                                            <div class="mt-2 flex items-center space-x-4">
                                                <div class="flex-shrink-0 w-32 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                    @if(isset($superAdminSettings['crm_logo_light']))
                                                        <img src="{{ asset('storage/' . $superAdminSettings['crm_logo_light']) }}" alt="Light Logo" class="max-h-8 max-w-full object-contain">
                                                    @else
                                                        <span class="text-xs text-gray-400">No Logo</span>
                                                    @endif
                                                </div>
                                                <input type="file" id="sa_crm_logo_light" name="crm_logo_light" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
                                            </div>
                                        </div>

                                        <!-- Dark Logo -->
                                        <div>
                                            <x-input-label for="sa_crm_logo_dark" :value="__('Dark Mode Logo')" />
                                            <div class="mt-2 flex items-center space-x-4">
                                                <div class="flex-shrink-0 w-32 h-12 bg-gray-900 dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-700 dark:border-gray-600">
                                                    @if(isset($superAdminSettings['crm_logo_dark']))
                                                        <img src="{{ asset('storage/' . $superAdminSettings['crm_logo_dark']) }}" alt="Dark Logo" class="max-h-8 max-w-full object-contain">
                                                    @else
                                                        <span class="text-xs text-gray-500">No Logo</span>
                                                    @endif
                                                </div>
                                                <input type="file" id="sa_crm_logo_dark" name="crm_logo_dark" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mt-6">Invoice Branding</h4>
                                    <div class="space-y-6 mt-4">
                                        <!-- Invoice Logo Light -->
                                        <div>
                                            <x-input-label for="sa_invoice_logo_light" :value="__('Invoice Logo (Light Mode)')" />
                                            <div class="mt-2 flex items-center space-x-4">
                                                <div class="flex-shrink-0 w-32 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                    @if(isset($superAdminSettings['invoice_logo_light']))
                                                        <img src="{{ asset('storage/' . $superAdminSettings['invoice_logo_light']) }}" alt="Invoice Logo Light" class="max-h-8 max-w-full object-contain">
                                                    @else
                                                        <span class="text-xs text-gray-400">No Logo</span>
                                                    @endif
                                                </div>
                                                <input type="file" id="sa_invoice_logo_light" name="invoice_logo_light" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
                                            </div>
                                        </div>

                                        <!-- Invoice Logo Dark -->
                                        <div>
                                            <x-input-label for="sa_invoice_logo_dark" :value="__('Invoice Logo (Dark Mode)')" />
                                            <div class="mt-2 flex items-center space-x-4">
                                                <div class="flex-shrink-0 w-32 h-12 bg-gray-900 dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-700 dark:border-gray-600">
                                                    @if(isset($superAdminSettings['invoice_logo_dark']))
                                                        <img src="{{ asset('storage/' . $superAdminSettings['invoice_logo_dark']) }}" alt="Invoice Logo Dark" class="max-h-8 max-w-full object-contain">
                                                    @else
                                                        <span class="text-xs text-gray-500">No Logo</span>
                                                    @endif
                                                </div>
                                                <input type="file" id="sa_invoice_logo_dark" name="invoice_logo_dark" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
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

                <!-- Main Settings Card -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-8 text-gray-900 dark:text-gray-100">
                        
                        <!-- Branch Context Switcher -->
                        @if(auth()->user()->isSuperAdmin())
                            <div class="mb-8 p-6 bg-cyan-50 dark:bg-cyan-900/20 rounded-2xl border border-cyan-100 dark:border-cyan-800/50">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-cyan-900 dark:text-cyan-100">Branch Settings Context</h4>
                                        <p class="text-sm text-cyan-700 dark:text-cyan-300 mt-1">Select a branch to configure its specific settings.</p>
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
                                                        window.location.href = url.toString();
                                                    "
                                                    class="block w-full pl-4 pr-10 py-2.5 text-base border-cyan-200 dark:border-cyan-700 focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm rounded-xl dark:bg-gray-800 dark:text-white shadow-sm">
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
                            <input type="hidden" name="branch_id" value="{{ $branchId }}" form="main-settings-form">
                        @elseif(auth()->user()->branch_id)
                            <div id="main-settings-container">
                            <div class="mb-8 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border-l-4 border-green-500 dark:border-green-400 flex items-center">
                                <div class="flex-shrink-0 mr-3">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-green-900 dark:text-green-100">Branch Settings Active</h4>
                                    <p class="text-sm text-green-700 dark:text-green-300">You are editing settings for <strong>{{ auth()->user()->branch->name }}</strong>.</p>
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
                                            <x-text-input id="company_name" name="company_name" type="text" class="mt-2 block w-full" :value="old('company_name', $settings['company_name'] ?? '')" />
                                        </div>
                                        <div>
                                            <x-input-label for="company_email" :value="__('Company Email')" />
                                            <x-text-input id="company_email" name="company_email" type="email" class="mt-2 block w-full" :value="old('company_email', $settings['company_email'] ?? '')" />
                                        </div>
                                        <div>
                                            <x-input-label for="company_phone" :value="__('Company Phone')" />
                                            <x-text-input id="company_phone" name="company_phone" type="text" class="mt-2 block w-full" :value="old('company_phone', $settings['company_phone'] ?? '')" />
                                        </div>
                                    </div>

                                    <div class="space-y-6">
                                        <div>
                                            <x-input-label for="company_website" :value="__('Company Website')" />
                                            <x-text-input id="company_website" name="company_website" type="url" class="mt-2 block w-full" :value="old('company_website', $settings['company_website'] ?? '')" placeholder="https://example.com" />
                                        </div>
                                        <div>
                                            <x-input-label for="tax_id" :value="__('Tax ID / VAT')" />
                                            <x-text-input id="tax_id" name="tax_id" type="text" class="mt-2 block w-full" :value="old('tax_id', $settings['tax_id'] ?? '')" />
                                        </div>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <x-input-label for="company_address" :value="__('Address')" />
                                        <textarea id="company_address" name="company_address" rows="3" class="block mt-2 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-cyan-500 dark:focus:border-cyan-600 focus:ring-cyan-500 dark:focus:ring-cyan-600 rounded-xl shadow-sm transition-colors">{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                                    </div>
                                    
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
                                    <div class="bg-gray-50 dark:bg-gray-700/30 p-8 rounded-2xl border border-gray-100 dark:border-gray-700">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">CRM Logos</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <div class="md:col-span-2">
                                                <x-input-label for="favicon" :value="__('Favicon')" />
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <div class="flex-shrink-0 w-12 h-12 bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                        @if(isset($settings['favicon']))
                                                            <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon" class="h-8 w-8 object-contain">
                                                        @else
                                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        @endif
                                                    </div>
                                                    <input type="file" id="favicon" name="favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
                                                </div>
                                            </div>

                                            <div>
                                                <x-input-label for="crm_logo_light" :value="__('Light Mode Logo')" />
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <div class="flex-shrink-0 w-32 h-12 bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                        @if(isset($settings['crm_logo_light']))
                                                            <img src="{{ asset('storage/' . $settings['crm_logo_light']) }}" alt="Light Logo" class="max-h-8 max-w-full object-contain">
                                                        @else
                                                            <span class="text-xs text-gray-400">No Logo</span>
                                                        @endif
                                                    </div>
                                                    <input type="file" id="crm_logo_light" name="crm_logo_light" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
                                                </div>
                                            </div>
                                            <div>
                                                <x-input-label for="crm_logo_dark" :value="__('Dark Mode Logo')" />
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <div class="flex-shrink-0 w-32 h-12 bg-gray-900 dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-700 dark:border-gray-600">
                                                        @if(isset($settings['crm_logo_dark']))
                                                            <img src="{{ asset('storage/' . $settings['crm_logo_dark']) }}" alt="Dark Logo" class="max-h-8 max-w-full object-contain">
                                                        @else
                                                            <span class="text-xs text-gray-500">No Logo</span>
                                                        @endif
                                                    </div>
                                                    <input type="file" id="crm_logo_dark" name="crm_logo_dark" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 dark:bg-gray-700/30 p-8 rounded-2xl border border-gray-100 dark:border-gray-700">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Invoice Branding</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <div>
                                                <x-input-label for="invoice_logo_light" :value="__('Invoice Logo (Light Mode)')" />
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <div class="flex-shrink-0 w-32 h-12 bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                        @if(isset($settings['invoice_logo_light']))
                                                            <img src="{{ asset('storage/' . $settings['invoice_logo_light']) }}" alt="Invoice Logo Light" class="max-h-8 max-w-full object-contain">
                                                        @else
                                                            <span class="text-xs text-gray-400">No Logo</span>
                                                        @endif
                                                    </div>
                                                    <input type="file" id="invoice_logo_light" name="invoice_logo_light" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
                                                </div>
                                            </div>
                                            <div>
                                                <x-input-label for="invoice_logo_dark" :value="__('Invoice Logo (Dark Mode)')" />
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <div class="flex-shrink-0 w-32 h-12 bg-gray-900 dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-700 dark:border-gray-600">
                                                        @if(isset($settings['invoice_logo_dark']))
                                                            <img src="{{ asset('storage/' . $settings['invoice_logo_dark']) }}" alt="Invoice Logo Dark" class="max-h-8 max-w-full object-contain">
                                                        @else
                                                            <span class="text-xs text-gray-500">No Logo</span>
                                                        @endif
                                                    </div>
                                                    <input type="file" id="invoice_logo_dark" name="invoice_logo_dark" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/50 dark:file:text-cyan-300">
                                                </div>
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
                                        <div class="mb-6">
                                            <x-input-label for="currency_symbol_position" :value="__('Currency Symbol Position')" />
                                            <select id="currency_symbol_position" name="currency_symbol_position" class="mt-2 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-cyan-500 dark:focus:border-cyan-600 focus:ring-cyan-500 dark:focus:ring-cyan-600 rounded-xl shadow-sm">
                                                <option value="prefix" {{ ($settings['currency_symbol_position'] ?? 'prefix') == 'prefix' ? 'selected' : '' }}>Prefix (e.g. $100)</option>
                                                <option value="suffix" {{ ($settings['currency_symbol_position'] ?? '') == 'suffix' ? 'selected' : '' }}>Suffix (e.g. 100 AED)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-6">
                                        <div>
                                            <x-input-label for="date_format" :value="__('Date Format')" />
                                            <select id="date_format" name="date_format" class="mt-2 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-cyan-500 dark:focus:border-cyan-600 focus:ring-cyan-500 dark:focus:ring-cyan-600 rounded-xl shadow-sm">
                                                <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2024-03-15)</option>
                                                <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (15/03/2024)</option>
                                                <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (03/15/2024)</option>
                                                <option value="d-M-Y" {{ ($settings['date_format'] ?? '') == 'd-M-Y' ? 'selected' : '' }}>DD-MMM-YYYY (15-Mar-2024)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="timezone" :value="__('Timezone')" />
                                            <select id="timezone" name="timezone" class="mt-2 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-cyan-500 dark:focus:border-cyan-600 focus:ring-cyan-500 dark:focus:ring-cyan-600 rounded-xl shadow-sm">
                                                @foreach(timezone_identifiers_list() as $timezone)
                                                    <option value="{{ $timezone }}" {{ ($settings['timezone'] ?? config('app.timezone')) == $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mt-6" x-show="['details', 'branding', 'localization'].includes(activeTab)">
                                <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                                @if (session('status') === 'settings-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 dark:text-green-400">{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>

                        <!-- Expense Categories Tab -->
                        <div x-show="activeTab === 'expense_categories'" class="space-y-8" x-cloak>
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Expense Categories</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage categories for expense tracking.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
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
                                            <x-primary-button class="w-full justify-center">{{ __('Add Category') }}</x-primary-button>
                                        </form>
                                    </div>
                                </div>

                                <div class="lg:col-span-2">
                                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @forelse($expenseCategories as $category)
                                                    <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">{{ $category->name }}</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $category->description ?? '-' }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <div class="flex justify-end space-x-2">
                                                                <button type="button" @click="$dispatch('open-view-category-modal', { category: {{ $category }} })" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors" title="View Details">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                                </button>
                                                                <button type="button" @click="$dispatch('open-edit-category-modal', { category: {{ $category }} })" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 p-2 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors" title="Edit">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                                </button>
                                                                <button type="button" @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('expense-categories.destroy', $category) }}' })" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Delete">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No expense categories found.</td></tr>
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
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Define employment classifications.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <div class="lg:col-span-1">
                                    <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 sticky top-6">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white mb-4">Add New Type</h4>
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
                                            <x-primary-button class="w-full justify-center">{{ __('Add Type') }}</x-primary-button>
                                        </form>
                                    </div>
                                </div>

                                <div class="lg:col-span-2">
                                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @forelse($employeeTypes as $type)
                                                    <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">{{ $type->name }}</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $type->description ?? '-' }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <button type="button" @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('employee-types.destroy', $type) }}' })" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No employee types found.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Expense Payers Tab -->
                        <div x-show="activeTab === 'expense_payers'" class="space-y-8" x-cloak>
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Expense Payers</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage people who can pay for expenses.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <div class="lg:col-span-1">
                                    <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 sticky top-6">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white mb-4">Add New Payer</h4>
                                        <form action="{{ route('expense-payers.store') }}" method="POST" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="branch_id" value="{{ $branchId }}">
                                            <div>
                                                <x-input-label for="payer_name" :value="__('Name')" />
                                                <x-text-input id="payer_name" name="name" type="text" class="mt-2 block w-full" required placeholder="e.g., John Doe" />
                                            </div>
                                            <div>
                                                <x-input-label for="payer_mobile" :value="__('Mobile Number')" />
                                                <x-text-input id="payer_mobile" name="mobile_number" type="text" class="mt-2 block w-full" placeholder="e.g., +1234567890" />
                                            </div>
                                            <div>
                                                <x-input-label for="payer_description" :value="__('Description')" />
                                                <x-text-input id="payer_description" name="description" type="text" class="mt-2 block w-full" placeholder="Optional description..." />
                                            </div>
                                            <x-primary-button class="w-full justify-center">{{ __('Add Payer') }}</x-primary-button>
                                        </form>
                                    </div>
                                </div>

                                <div class="lg:col-span-2">
                                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mobile</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @forelse($expensePayers as $payer)
                                                    <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">{{ $payer->name }}</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $payer->mobile_number ?? '-' }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $payer->description ?? '-' }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <div class="flex items-center justify-end space-x-2">
                                                                <!-- View Button -->
                                                                <button @click="$dispatch('open-view-payer-modal', { payer: {{ $payer }} })" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" title="View">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                                </button>

                                                                <!-- Edit Button -->
                                                                <button @click="$dispatch('open-edit-payer-modal', { payer: {{ $payer }} })" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-2 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors" title="Edit">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                                </button>

                                                                <!-- Delete Button -->
                                                                <button type="button" @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('expense-payers.destroy', $payer) }}' })" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Delete">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No expense payers found.</td></tr>
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
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Define salary structures.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <div class="lg:col-span-1">
                                    <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 sticky top-6">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white mb-4">Add New Type</h4>
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
                                            <x-primary-button class="w-full justify-center">{{ __('Add Type') }}</x-primary-button>
                                        </form>
                                    </div>
                                </div>

                                <div class="lg:col-span-2">
                                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @forelse($payrollTypes as $type)
                                                    <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">{{ $type->name }}</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $type->description ?? '-' }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <button type="button" @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('payroll-types.destroy', $type) }}' })" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No payroll types found.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->isSuperAdmin())
                        <!-- Backup & Restore Tab -->
                        <div x-show="activeTab === 'backup'" class="space-y-8" x-cloak>
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Backup & Restore</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Secure your data by creating backups or restoring from a previous state.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Backup Section -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 p-8 rounded-2xl border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center mb-6">
                                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl mr-4 text-blue-600 dark:text-blue-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Download Backup</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Export your entire database as a SQL file.</p>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            This will generate a complete backup of your database, including all clients, projects, invoices, and settings.
                                            Please store this file in a secure location.
                                        </p>
                                        <a href="{{ route('settings.backup') }}" class="inline-flex items-center justify-center w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors shadow-sm hover:shadow-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Download Database Backup
                                        </a>
                                    </div>
                                </div>

                                <!-- Restore Section -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 p-8 rounded-2xl border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center mb-6">
                                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-xl mr-4 text-red-600 dark:text-red-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Restore Database</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Restore your system from a backup file.</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('settings.restore') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return confirm('WARNING: This will overwrite all current data! Are you sure you want to proceed?');">
                                        @csrf
                                        <div class="space-y-2">
                                            <p class="text-sm text-red-600 dark:text-red-400 font-medium">
                                                Warning: This action is irreversible. All current data will be replaced.
                                            </p>
                                            <input type="file" name="backup_file" accept=".sql" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 dark:file:bg-red-900/30 dark:file:text-red-300">
                                        </div>
                                        <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-3 bg-white dark:bg-gray-800 border-2 border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 font-medium rounded-xl transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Restore from Backup
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Payer Modal -->
    <div x-data="{ show: false, payer: {} }" 
         @open-edit-payer-modal.window="show = true; payer = $event.detail.payer" 
         x-show="show" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'/expense-payers/' + payer.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Edit Expense Payer
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <x-input-label for="edit_payer_name" :value="__('Name')" />
                                <x-text-input id="edit_payer_name" name="name" type="text" class="mt-1 block w-full" x-model="payer.name" required />
                            </div>
                            <div>
                                <x-input-label for="edit_payer_mobile" :value="__('Mobile Number')" />
                                <x-text-input id="edit_payer_mobile" name="mobile_number" type="text" class="mt-1 block w-full" x-model="payer.mobile_number" />
                            </div>
                            <div>
                                <x-input-label for="edit_payer_description" :value="__('Description')" />
                                <x-text-input id="edit_payer_description" name="description" type="text" class="mt-1 block w-full" x-model="payer.description" />
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button class="w-full sm:w-auto sm:ml-3 justify-center">
                            {{ __('Update Payer') }}
                        </x-primary-button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="show = false">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Payer Modal -->
    <div x-data="{ show: false, payer: {} }" 
         @open-view-payer-modal.window="show = true; payer = $event.detail.payer" 
         x-show="show" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Payer Details
                    </h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="payer.name"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Mobile Number</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="payer.mobile_number || '-'"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="payer.description || '-'"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="show = false">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div x-data="{ show: false, category: {} }"
         x-on:open-edit-category-modal.window="show = true; category = $event.detail.category"
         x-on:close-modal.window="show = false"
         x-show="show"
         class="fixed z-50 inset-0 overflow-y-auto" 
         style="display: none;"
         x-cloak
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'{{ route('expense-categories.update', 'PLACEHOLDER') }}'.replace('PLACEHOLDER', category.id)" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Edit Expense Category
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="edit_category_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" name="name" id="edit_category_name" x-model="category.name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="edit_category_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <input type="text" name="description" id="edit_category_description" x-model="category.description" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="edit_category_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                                <input type="color" name="color" id="edit_category_color" x-model="category.color" class="mt-1 block w-full h-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Save Changes') }}
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="show = false">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Category Modal -->
    <div x-data="{ show: false, category: {} }"
         x-on:open-view-category-modal.window="show = true; category = $event.detail.category"
         x-on:close-modal.window="show = false"
         x-show="show"
         class="fixed z-50 inset-0 overflow-y-auto" 
         style="display: none;"
         x-cloak
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Category Details
                    </h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="category.name"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="category.description || '-'"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Color</label>
                            <div class="mt-1 flex items-center">
                                <div class="w-6 h-6 rounded border border-gray-200 dark:border-gray-600" :style="'background-color: ' + category.color"></div>
                                <span class="ml-2 text-sm text-gray-900 dark:text-white" x-text="category.color"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="show = false">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
