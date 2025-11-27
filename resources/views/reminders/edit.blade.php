<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl">
                
                <!-- Header -->
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-amber-500 rounded-xl shadow-lg shadow-amber-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                                    {{ __('Edit Reminder') }}
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('Update the reminder details below.') }}
                                </p>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                                Editing Mode
                            </span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('reminders.update', $reminder) }}" class="p-8">
                    @csrf
                    @method('PUT')
                    <div class="space-y-8">
                        
                        <!-- Reminder Details Section -->
                        <section class="relative">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reminder Details</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @if(auth()->user()->isSuperAdmin())
                                    <div class="group md:col-span-2">
                                        <x-input-label for="branch_id" :value="__('Branch')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" />
                                        <div class="relative">
                                            <select id="branch_id" name="branch_id" class="block w-full pl-3 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all">
                                                <option value="">Select Branch</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" {{ old('branch_id', $reminder->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <x-input-error :messages="$errors->get('branch_id')" class="mt-2" />
                                    </div>
                                @endif

                                <!-- Title -->
                                <div class="group md:col-span-2">
                                    <x-input-label for="title" :value="__('Title')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-amber-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <x-text-input id="title" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:bg-white dark:focus:bg-gray-900 focus:border-amber-500 focus:ring-amber-500 transition-all" type="text" name="title" :value="old('title', $reminder->title)" required autofocus />
                                    </div>
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                <!-- Type -->
                                <div class="group">
                                    <x-input-label for="type" :value="__('Type')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-amber-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select id="type" name="type" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:bg-white dark:focus:bg-gray-900 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm transition-all">
                                            <option value="custom" {{ old('type', $reminder->type) == 'custom' ? 'selected' : '' }}>Custom</option>
                                            <option value="invoice" {{ old('type', $reminder->type) == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                            <option value="project" {{ old('type', $reminder->type) == 'project' ? 'selected' : '' }}>Project</option>
                                            <option value="estimate" {{ old('type', $reminder->type) == 'estimate' ? 'selected' : '' }}>Estimate</option>
                                            <option value="payroll" {{ old('type', $reminder->type) == 'payroll' ? 'selected' : '' }}>Payroll</option>
                                            <option value="expense" {{ old('type', $reminder->type) == 'expense' ? 'selected' : '' }}>Expense</option>
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                </div>

                                <!-- Priority -->
                                <div class="group">
                                    <x-input-label for="priority" :value="__('Priority')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-amber-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select id="priority" name="priority" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:bg-white dark:focus:bg-gray-900 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm transition-all">
                                            <option value="low" {{ old('priority', $reminder->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority', $reminder->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ old('priority', $reminder->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                                </div>

                                <!-- Date -->
                                <div class="group">
                                    <x-input-label for="reminder_date" :value="__('Reminder Date')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-amber-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <x-text-input id="reminder_date" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:bg-white dark:focus:bg-gray-900 focus:border-amber-500 focus:ring-amber-500 transition-all" type="date" name="reminder_date" :value="old('reminder_date', $reminder->reminder_date->format('Y-m-d'))" required />
                                    </div>
                                    <x-input-error :messages="$errors->get('reminder_date')" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div class="group">
                                    <x-input-label for="status" :value="__('Status')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-amber-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select id="status" name="status" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:bg-white dark:focus:bg-gray-900 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm transition-all">
                                            <option value="pending" {{ old('status', $reminder->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="completed" {{ old('status', $reminder->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="dismissed" {{ old('status', $reminder->status) == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>

                                <!-- Description -->
                                <div class="group md:col-span-2">
                                    <x-input-label for="description" :value="__('Description')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400 transition-colors" />
                                    <textarea id="description" name="description" rows="3" class="block w-full bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:bg-white dark:focus:bg-gray-900 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm transition-all">{{ old('description', $reminder->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>
                            </div>
                        </section>

                        <!-- Recurring Options -->
                        <div x-data="{ isRecurring: {{ old('is_recurring', $reminder->is_recurring) ? 'true' : 'false' }} }" class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center">
                                <input id="is_recurring" type="checkbox" name="is_recurring" value="1" class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500 dark:focus:ring-amber-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600" x-model="isRecurring" {{ old('is_recurring', $reminder->is_recurring) ? 'checked' : '' }}>
                                <label for="is_recurring" class="ml-2 block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Repeat this reminder') }}
                                </label>
                            </div>

                            <div x-show="isRecurring" x-transition class="mt-4">
                                <x-input-label for="frequency" :value="__('Frequency')" class="mb-2 text-gray-600 dark:text-gray-400" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <select id="frequency" name="frequency" class="block w-full pl-10 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm transition-all">
                                        <option value="">Select Frequency</option>
                                        <option value="daily" {{ old('frequency', $reminder->frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ old('frequency', $reminder->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ old('frequency', $reminder->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="yearly" {{ old('frequency', $reminder->frequency) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('frequency')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('reminders.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-amber-500 hover:bg-amber-600 focus:bg-amber-600 active:bg-amber-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                {{ __('Update Reminder') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
