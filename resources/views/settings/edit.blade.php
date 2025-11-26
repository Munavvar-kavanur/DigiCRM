<x-app-layout>
    <div class="py-12" x-data="{ 
        activeTab: 'details',
        updateTab(tab) {
            this.activeTab = tab;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Sidebar Navigation -->
                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <nav class="flex flex-col p-4 space-y-2">
                            <button @click="updateTab('details')" :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300': activeTab === 'details', 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700': activeTab !== 'details' }" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 font-medium text-sm">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Company Details
                            </button>
                            <button @click="updateTab('branding')" :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300': activeTab === 'branding', 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700': activeTab !== 'branding' }" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 font-medium text-sm">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Branding & Logos
                            </button>
                            <button @click="updateTab('localization')" :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300': activeTab === 'localization', 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700': activeTab !== 'localization' }" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 font-medium text-sm">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Localization
                            </button>
                            <button @click="updateTab('categories')" :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300': activeTab === 'categories', 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700': activeTab !== 'categories' }" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 font-medium text-sm">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Expense Categories
                            </button>
                            <button @click="updateTab('employee_types')" :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300': activeTab === 'employee_types', 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700': activeTab !== 'employee_types' }" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 font-medium text-sm">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Employee Types
                            </button>
                            <button @click="updateTab('payroll_types')" :class="{ 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300': activeTab === 'payroll_types', 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700': activeTab !== 'payroll_types' }" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 font-medium text-sm">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Payroll Types
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="md:col-span-3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            
                            <!-- Main Settings Form -->
                            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" x-show="!['categories', 'employee_types', 'payroll_types'].includes(activeTab)">
                                @csrf
                                @method('PATCH')

                                <!-- Company Details Tab -->
                                <div x-show="activeTab === 'details'" class="space-y-6">
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Company Information</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Update your company's core details and contact information.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <x-input-label for="company_name" :value="__('Company Name')" />
                                            <div class="relative mt-1">
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
                                            <div class="relative mt-1">
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
                                            <div class="relative mt-1">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                </div>
                                                <x-text-input id="company_phone" name="company_phone" type="text" class="block w-full pl-10" :value="old('company_phone', $settings['company_phone'] ?? '')" />
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label for="company_website" :value="__('Company Website')" />
                                            <div class="relative mt-1">
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
                                            <div class="relative mt-1">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <x-text-input id="tax_id" name="tax_id" type="text" class="block w-full pl-10" :value="old('tax_id', $settings['tax_id'] ?? '')" />
                                            </div>
                                        </div>
                                        <div class="md:col-span-2">
                                            <x-input-label for="company_address" :value="__('Address')" />
                                            <textarea id="company_address" name="company_address" rows="2" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                                        </div>
                                        
                                        <!-- Address Details -->
                                        <div>
                                            <x-input-label for="company_city" :value="__('City')" />
                                            <x-text-input id="company_city" name="company_city" type="text" class="mt-1 block w-full" :value="old('company_city', $settings['company_city'] ?? '')" />
                                        </div>
                                        <div>
                                            <x-input-label for="company_state" :value="__('State / Province')" />
                                            <x-text-input id="company_state" name="company_state" type="text" class="mt-1 block w-full" :value="old('company_state', $settings['company_state'] ?? '')" />
                                        </div>
                                        <div>
                                            <x-input-label for="company_zip" :value="__('Zip / Postal Code')" />
                                            <x-text-input id="company_zip" name="company_zip" type="text" class="mt-1 block w-full" :value="old('company_zip', $settings['company_zip'] ?? '')" />
                                        </div>
                                        <div>
                                            <x-input-label for="company_country" :value="__('Country')" />
                                            <x-text-input id="company_country" name="company_country" type="text" class="mt-1 block w-full" :value="old('company_country', $settings['company_country'] ?? '')" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Branding Tab -->
                                <div x-show="activeTab === 'branding'" class="space-y-6" x-cloak>
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Branding & Logos</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Customize the look and feel of your CRM and documents.</p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-6">
                                        <!-- CRM Logos -->
                                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                Application Branding
                                            </h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <!-- Full Logos -->
                                                <div>
                                                    <x-input-label for="crm_logo_light" :value="__('Light Mode Logo (Rec: 200x50px)')" />
                                                    <input type="file" id="crm_logo_light" name="crm_logo_light" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:text-gray-400 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400">
                                                    <x-input-error :messages="$errors->get('crm_logo_light')" class="mt-2" />
                                                    @if(isset($settings['crm_logo_light']))
                                                        <div class="mt-2 p-3 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                                            <img src="{{ asset('storage/' . $settings['crm_logo_light']) }}" alt="Light Logo" class="h-10 object-contain">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <x-input-label for="crm_logo_dark" :value="__('Dark Mode Logo (Rec: 200x50px)')" />
                                                    <input type="file" id="crm_logo_dark" name="crm_logo_dark" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:text-gray-400 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400">
                                                    <x-input-error :messages="$errors->get('crm_logo_dark')" class="mt-2" />
                                                    @if(isset($settings['crm_logo_dark']))
                                                        <div class="mt-2 p-3 bg-gray-900 rounded-lg border border-gray-700 flex items-center justify-center">
                                                            <img src="{{ asset('storage/' . $settings['crm_logo_dark']) }}" alt="Dark Logo" class="h-10 object-contain">
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Collapsed Logos -->
                                                <div>
                                                    <x-input-label for="crm_logo_collapsed_light" :value="__('Collapsed Menu Logo Light (Rec: 32x32px)')" />
                                                    <input type="file" id="crm_logo_collapsed_light" name="crm_logo_collapsed_light" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:text-gray-400 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400">
                                                    <x-input-error :messages="$errors->get('crm_logo_collapsed_light')" class="mt-2" />
                                                    @if(isset($settings['crm_logo_collapsed_light']))
                                                        <div class="mt-2 p-3 bg-gray-100 rounded-lg border border-gray-200 w-16 h-16 flex items-center justify-center">
                                                            <img src="{{ asset('storage/' . $settings['crm_logo_collapsed_light']) }}" alt="Collapsed Light Logo" class="h-8 w-8 object-contain">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <x-input-label for="crm_logo_collapsed_dark" :value="__('Collapsed Menu Logo Dark (Rec: 32x32px)')" />
                                                    <input type="file" id="crm_logo_collapsed_dark" name="crm_logo_collapsed_dark" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:text-gray-400 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400">
                                                    <x-input-error :messages="$errors->get('crm_logo_collapsed_dark')" class="mt-2" />
                                                    @if(isset($settings['crm_logo_collapsed_dark']))
                                                        <div class="mt-2 p-3 bg-gray-900 rounded-lg border border-gray-700 w-16 h-16 flex items-center justify-center">
                                                            <img src="{{ asset('storage/' . $settings['crm_logo_collapsed_dark']) }}" alt="Collapsed Dark Logo" class="h-8 w-8 object-contain">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Invoice Logos -->
                                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Document Branding (Invoices & Estimates)
                                            </h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <x-input-label for="invoice_logo_light" :value="__('Document Logo (Light)')" />
                                                    <input type="file" id="invoice_logo_light" name="invoice_logo_light" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:text-gray-400 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400">
                                                    <x-input-error :messages="$errors->get('invoice_logo_light')" class="mt-2" />
                                                    @if(isset($settings['invoice_logo_light']))
                                                        <div class="mt-2 p-3 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                                            <img src="{{ asset('storage/' . $settings['invoice_logo_light']) }}" alt="Invoice Logo" class="h-12 object-contain">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <x-input-label for="invoice_logo_dark" :value="__('Document Logo (Dark)')" />
                                                    <input type="file" id="invoice_logo_dark" name="invoice_logo_dark" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:text-gray-400 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400">
                                                    <x-input-error :messages="$errors->get('invoice_logo_dark')" class="mt-2" />
                                                    @if(isset($settings['invoice_logo_dark']))
                                                        <div class="mt-2 p-3 bg-gray-900 rounded-lg border border-gray-700 flex items-center justify-center">
                                                            <img src="{{ asset('storage/' . $settings['invoice_logo_dark']) }}" alt="Invoice Logo" class="h-12 object-contain">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Localization Tab -->
                                <div x-show="activeTab === 'localization'" class="space-y-6" x-cloak>
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Localization</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Set your preferred currency and regional settings.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <x-input-label for="currency_code" :value="__('Currency Code')" />
                                            <div class="relative mt-1">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <select id="currency_code" name="currency_code" class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                    <option value="USD" {{ ($settings['currency_code'] ?? '') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                                    <option value="EUR" {{ ($settings['currency_code'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                                    <option value="GBP" {{ ($settings['currency_code'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                                    <option value="AED" {{ ($settings['currency_code'] ?? '') == 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                                                    <option value="INR" {{ ($settings['currency_code'] ?? '') == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                                                    <option value="AUD" {{ ($settings['currency_code'] ?? '') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                                                    <option value="CAD" {{ ($settings['currency_code'] ?? '') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label for="currency_symbol" :value="__('Currency Symbol')" />
                                            <div class="relative mt-1">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-400 font-bold text-lg">$</span>
                                                </div>
                                                <x-text-input id="currency_symbol" name="currency_symbol" type="text" class="block w-full pl-10" :value="old('currency_symbol', $settings['currency_symbol'] ?? '$')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <x-primary-button>
                                        {{ __('Save Changes') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            <!-- Expense Categories Tab (Separate Form) -->
                            <div x-show="activeTab === 'categories'" class="space-y-6" x-cloak>
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Expense Categories</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage categories for organizing company expenses.</p>
                                </div>
                                
                                <!-- Add New Category -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Category</h4>
                                    <form action="{{ route('expense-categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                                        @csrf
                                        <div class="flex-grow w-full">
                                            <x-input-label for="category_name" :value="__('Category Name')" />
                                            <x-text-input id="category_name" name="name" type="text" class="mt-1 block w-full" required placeholder="e.g., Travel, Office Supplies" />
                                        </div>
                                        <div class="w-full sm:w-auto">
                                            <x-input-label for="category_color" :value="__('Color Label')" />
                                            <div class="flex items-center mt-1">
                                                <input type="color" id="category_color" name="color" class="h-10 w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer" value="#3b82f6">
                                            </div>
                                        </div>
                                        <x-primary-button class="w-full sm:w-auto justify-center">
                                            {{ __('Add Category') }}
                                        </x-primary-button>
                                    </form>
                                </div>

                                <!-- List Categories -->
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Name</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Color</th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                    <span class="sr-only">Actions</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                            @foreach($expenseCategories as $category)
                                                <tr>
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $category->name }}</td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                        <div class="flex items-center">
                                                            <span class="inline-block w-4 h-4 rounded-full mr-2" style="background-color: {{ $category->color ?? '#ccc' }}"></span>
                                                            <span class="uppercase">{{ $category->color }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <form action="{{ route('expense-categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Employee Types Tab -->
                            <div x-show="activeTab === 'employee_types'" class="space-y-6" x-cloak>
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Employee Types</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Define employment classifications (e.g., Full-time, Contract).</p>
                                </div>
                                
                                <!-- Add New Type -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Employee Type</h4>
                                    <form action="{{ route('employee-types.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                                        @csrf
                                        <div class="flex-grow w-full sm:w-1/3">
                                            <x-input-label for="employee_type_name" :value="__('Type Name')" />
                                            <x-text-input id="employee_type_name" name="name" type="text" class="mt-1 block w-full" required placeholder="e.g., Permanent" />
                                        </div>
                                        <div class="flex-grow w-full sm:w-2/3">
                                            <x-input-label for="employee_type_description" :value="__('Description')" />
                                            <x-text-input id="employee_type_description" name="description" type="text" class="mt-1 block w-full" placeholder="Optional description..." />
                                        </div>
                                        <x-primary-button class="w-full sm:w-auto justify-center">
                                            {{ __('Add') }}
                                        </x-primary-button>
                                    </form>
                                </div>

                                <!-- List Types -->
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Name</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Description</th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                    <span class="sr-only">Actions</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                            @foreach($employeeTypes as $type)
                                                <tr>
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                            {{ $type->name }}
                                                        </span>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $type->description ?? '-' }}</td>
                                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <form action="{{ route('employee-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Payroll Types Tab -->
                            <div x-show="activeTab === 'payroll_types'" class="space-y-6" x-cloak>
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Payroll Types</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Define salary structures (e.g., Monthly Salary, Hourly Rate).</p>
                                </div>
                                
                                <!-- Add New Type -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Payroll Type</h4>
                                    <form action="{{ route('payroll-types.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                                        @csrf
                                        <div class="flex-grow w-full sm:w-1/3">
                                            <x-input-label for="payroll_type_name" :value="__('Type Name')" />
                                            <x-text-input id="payroll_type_name" name="name" type="text" class="mt-1 block w-full" required placeholder="e.g., Monthly Salary" />
                                        </div>
                                        <div class="flex-grow w-full sm:w-2/3">
                                            <x-input-label for="payroll_type_description" :value="__('Description')" />
                                            <x-text-input id="payroll_type_description" name="description" type="text" class="mt-1 block w-full" placeholder="Optional description..." />
                                        </div>
                                        <x-primary-button class="w-full sm:w-auto justify-center">
                                            {{ __('Add') }}
                                        </x-primary-button>
                                    </form>
                                </div>

                                <!-- List Types -->
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Name</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Description</th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                    <span class="sr-only">Actions</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                            @foreach($payrollTypes as $type)
                                                <tr>
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                            {{ $type->name }}
                                                        </span>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $type->description ?? '-' }}</td>
                                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <form action="{{ route('payroll-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
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
                                Crop Image
                            </h3>
                            <div class="mt-4 w-full h-96 bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden">
                                <img id="cropper-image" src="" alt="Image to crop" class="max-w-full h-full object-contain">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="cropImage()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Crop & Save
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

