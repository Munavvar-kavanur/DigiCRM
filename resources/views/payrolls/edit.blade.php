<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl">
                
                <!-- Header -->
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-emerald-500 rounded-xl shadow-lg shadow-emerald-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                                    {{ __('Edit Payroll') }}
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('Update payroll details below.') }}
                                </p>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                                Editing Mode
                            </span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('payrolls.update', $payroll) }}" class="p-8" x-data="{ 
                    baseSalary: {{ $payroll->base_salary }},
                    bonus: {{ $payroll->bonus }},
                    deductions: {{ $payroll->deductions }},
                    get netSalary() {
                        return (parseFloat(this.baseSalary) || 0) + (parseFloat(this.bonus) || 0) - (parseFloat(this.deductions) || 0);
                    }
                }">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-10">
                        
                        <!-- Payroll Details Section -->
                        <section class="relative">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Payroll Details</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Employee (Read Only) -->
                                <div class="group">
                                    <x-input-label :value="__('Employee')" class="mb-2 text-gray-600 dark:text-gray-400" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="block w-full pl-10 py-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md text-gray-700 dark:text-gray-300">
                                            {{ $payroll->user->name }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Salary Month -->
                                <div class="group">
                                    <x-input-label for="salary_month" :value="__('Salary Month')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="date" id="salary_month" name="salary_month" value="{{ $payroll->salary_month->format('Y-m-d') }}" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm transition-all" required>
                                    </div>
                                    <x-input-error :messages="$errors->get('salary_month')" class="mt-2" />
                                </div>

                                <!-- Base Salary -->
                                <div class="group">
                                    <x-input-label for="base_salary" :value="__('Base Salary')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 font-bold">$</span>
                                        </div>
                                        <x-text-input id="base_salary" class="block w-full pl-8 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-emerald-500 focus:ring-emerald-500 transition-all" type="number" step="0.01" name="base_salary" x-model="baseSalary" required />
                                    </div>
                                    <x-input-error :messages="$errors->get('base_salary')" class="mt-2" />
                                </div>

                                <!-- Bonus -->
                                <div class="group">
                                    <x-input-label for="bonus" :value="__('Bonus')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.536 5.879a1 1 0 001.415 0 3 3 0 014.242 0 1 1 0 001.415-1.415 5 5 0 00-7.072 0 1 1 0 000 1.415z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <x-text-input id="bonus" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-emerald-500 focus:ring-emerald-500 transition-all" type="number" step="0.01" name="bonus" x-model="bonus" />
                                    </div>
                                    <x-input-error :messages="$errors->get('bonus')" class="mt-2" />
                                </div>

                                <!-- Deductions -->
                                <div class="group">
                                    <x-input-label for="deductions" :value="__('Deductions')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <x-text-input id="deductions" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-emerald-500 focus:ring-emerald-500 transition-all" type="number" step="0.01" name="deductions" x-model="deductions" />
                                    </div>
                                    <x-input-error :messages="$errors->get('deductions')" class="mt-2" />
                                </div>

                                <!-- Net Salary (Read Only) -->
                                <div class="group">
                                    <x-input-label for="net_salary" :value="__('Net Salary')" class="mb-2 text-gray-600 dark:text-gray-400" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">$</span>
                                        </div>
                                        <div class="block w-full pl-8 py-2.5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-md text-emerald-700 dark:text-emerald-300 font-bold text-lg" x-text="netSalary.toFixed(2)"></div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="border-t border-gray-100 dark:border-gray-700"></div>

                        <!-- Status & Payment Section -->
                        <section class="relative">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Status & Payment</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Status -->
                                <div class="group">
                                    <x-input-label for="status" :value="__('Status')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select id="status" name="status" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm transition-all" required>
                                            <option value="pending" {{ $payroll->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $payroll->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>

                                <!-- Payment Date -->
                                <div class="group">
                                    <x-input-label for="payment_date" :value="__('Payment Date')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="date" id="payment_date" name="payment_date" value="{{ $payroll->payment_date ? $payroll->payment_date->format('Y-m-d') : '' }}" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm transition-all">
                                    </div>
                                    <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                                </div>

                                <!-- Notes -->
                                <div class="group md:col-span-2">
                                    <x-input-label for="notes" :value="__('Notes')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-emerald-600 dark:group-focus-within:text-emerald-400 transition-colors" />
                                    <textarea id="notes" name="notes" rows="3" class="block w-full bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm transition-all">{{ $payroll->notes }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>
                            </div>
                        </section>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('payrolls.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 focus:bg-emerald-600 active:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Update Payroll') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
