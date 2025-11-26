<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl">
                
                <!-- Header -->
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                                    {{ __('Create New Estimate') }}
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('Create and send a new estimate to your client.') }}
                                </p>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                Creation Mode
                            </span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('estimates.store') }}" method="POST" class="p-8" x-data="{
                    items: [{ title: '', description: '', quantity: 1, unit_price: 0, total: 0 }],
                    taxRate: 0,
                    discountRate: 0,
                    discountType: 'percent',
                    currencySymbol: '{{ $globalSettings['currency_symbol'] ?? '$' }}',
                    addItem() {
                        this.items.push({ title: '', description: '', quantity: 1, unit_price: 0, total: 0 });
                    },
                    removeItem(index) {
                        this.items.splice(index, 1);
                    },
                    calculateTotal(item) {
                        item.total = (item.quantity * item.unit_price).toFixed(2);
                        return item.total;
                    },
                    get subtotal() {
                        return this.items.reduce((sum, item) => sum + parseFloat(item.total || 0), 0).toFixed(2);
                    },
                    get taxAmount() {
                        return (this.subtotal * this.taxRate / 100).toFixed(2);
                    },
                    get discountAmount() {
                        if (this.discountType === 'fixed') {
                            return parseFloat(this.discountRate).toFixed(2);
                        }
                        return (this.subtotal * this.discountRate / 100).toFixed(2);
                    },
                    get grandTotal() {
                        return (parseFloat(this.subtotal) + parseFloat(this.taxAmount) - parseFloat(this.discountAmount)).toFixed(2);
                    }
                }">
                    @csrf
                    <div class="space-y-10">
                        
                        <!-- Estimate Details Section -->
                        <section class="relative">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Estimate Details</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <!-- Estimate Number -->
                                <div class="group">
                                    <x-input-label for="estimate_number" :value="__('Estimate Number')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <x-text-input id="estimate_number" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:bg-white dark:focus:bg-gray-900 transition-all" type="text" name="estimate_number" :value="old('estimate_number')" placeholder="Auto-generated" />
                                    </div>
                                    <x-input-error :messages="$errors->get('estimate_number')" class="mt-2" />
                                </div>

                                <!-- Client -->
                                <div class="group">
                                    <x-input-label for="client_id" :value="__('Client')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select id="client_id" name="client_id" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all" required>
                                            <option value="">Select Client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                                </div>

                                <!-- Project -->
                                <div class="group">
                                    <x-input-label for="project_id" :value="__('Project (Optional)')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd" />
                                                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z" />
                                            </svg>
                                        </div>
                                        <select id="project_id" name="project_id" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                                </div>

                                <!-- Valid Until -->
                                <div class="group">
                                    <x-input-label for="valid_until" :value="__('Valid Until')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <x-text-input id="valid_until" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:bg-white dark:focus:bg-gray-900 transition-all" type="date" name="valid_until" :value="old('valid_until')" required />
                                    </div>
                                    <x-input-error :messages="$errors->get('valid_until')" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div class="group">
                                    <x-input-label for="status" :value="__('Status')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select id="status" name="status" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all">
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                            <option value="accepted" {{ old('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            </div>
                        </section>

                        <div class="border-t border-gray-100 dark:border-gray-700"></div>

                        <!-- Items Section -->
                        <section class="relative">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Items</h3>
                                </div>
                                <button type="button" @click="addItem()" class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-medium rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add Item
                                </button>
                            </div>

                            <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                            <th class="px-6 py-4 w-1/2">Item Details</th>
                                            <th class="px-6 py-4 w-24 text-center">Qty</th>
                                            <th class="px-6 py-4 w-32 text-right">Rate</th>
                                            <th class="px-6 py-4 w-32 text-right">Amount</th>
                                            <th class="px-6 py-4 w-10 text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td class="px-6 py-4 align-top">
                                                    <x-text-input x-model="item.title" x-bind:name="'items['+index+'][title]'" type="text" class="block w-full mb-2 font-bold text-gray-900 dark:text-gray-100 bg-transparent border-0 border-b border-gray-200 dark:border-gray-700 focus:border-indigo-500 focus:ring-0 px-0" placeholder="Item Title" />
                                                    <textarea x-model="item.description" x-bind:name="'items['+index+'][description]'" rows="2" class="block w-full bg-transparent border-0 text-sm text-gray-600 dark:text-gray-400 focus:ring-0 px-0 resize-none" placeholder="Description" required></textarea>
                                                </td>
                                                <td class="px-6 py-4 align-top">
                                                    <x-text-input x-model="item.quantity" x-bind:name="'items['+index+'][quantity]'" type="number" step="1" min="1" class="block w-full text-center bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 rounded-md focus:border-indigo-500 focus:ring-indigo-500" @input="calculateTotal(item)" required />
                                                </td>
                                                <td class="px-6 py-4 align-top">
                                                    <x-text-input x-model="item.unit_price" x-bind:name="'items['+index+'][unit_price]'" type="number" step="0.01" min="0" class="block w-full text-right bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 rounded-md focus:border-indigo-500 focus:ring-indigo-500" @input="calculateTotal(item)" required />
                                                </td>
                                                <td class="px-6 py-4 align-top text-right font-medium text-gray-900 dark:text-gray-100 pt-6">
                                                    <span x-text="currencySymbol + calculateTotal(item)"></span>
                                                </td>
                                                <td class="px-6 py-4 align-top text-center pt-5">
                                                    <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-500 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                <button type="button" @click="addItem()" class="flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add Line Item
                                </button>
                            </div>
                        </div>

                        <!-- Totals & Notes -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <div class="space-y-8">
                                <div class="group">
                                    <x-input-label for="notes" :value="__('Notes')" class="mb-2 text-gray-600 dark:text-gray-400" />
                                    <textarea id="notes" name="notes" rows="4" class="block w-full bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all" placeholder="Add any notes for the client...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-700/20 p-8 rounded-2xl border border-gray-100 dark:border-gray-700 h-fit">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Summary</h4>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600 dark:text-gray-400 font-medium">Subtotal</span>
                                        <span class="font-bold text-gray-900 dark:text-gray-100" x-text="currencySymbol + subtotal"></span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-sm">
                                        <div class="flex items-center">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium mr-2">Tax</span>
                                            <div class="relative rounded-md shadow-sm">
                                                <input x-model="taxRate" name="tax" type="number" step="0.01" min="0" class="block w-20 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm text-right pr-8 transition-colors">
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                    <span class="text-gray-500 sm:text-xs">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="font-medium text-gray-900 dark:text-gray-100" x-text="currencySymbol + taxAmount"></span>
                                    </div>

                                    <div class="flex justify-between items-center text-sm">
                                        <div class="flex items-center">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium mr-2">Discount</span>
                                            <div class="flex rounded-md shadow-sm">
                                                <select x-model="discountType" name="discount_type" class="rounded-l-md border-r-0 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm py-1 px-2 transition-colors">
                                                    <option value="percent">%</option>
                                                    <option value="fixed" x-text="currencySymbol"></option>
                                                </select>
                                                <input x-model="discountRate" name="discount" type="number" step="0.01" min="0" class="block w-20 rounded-r-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm text-right transition-colors">
                                            </div>
                                        </div>
                                        <span class="font-medium text-red-500 dark:text-red-400" x-text="'-' + currencySymbol + discountAmount"></span>
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900 dark:text-white">Total</span>
                                        <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400" x-text="currencySymbol + grandTotal"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('estimates.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                {{ __('Create Estimate') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
