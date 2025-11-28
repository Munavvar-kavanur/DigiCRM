                            <div class="p-3 bg-teal-500 rounded-xl shadow-lg shadow-teal-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                                    {{ __('Add New Employee') }}
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('Register a new employee in the system.') }}
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

                <form method="POST" action="{{ route('employees.store') }}" class="p-8">
                    @csrf
                    
                    <div x-data="{ 
                        payrollTypes: {{ $payrollTypes->mapWithKeys(fn($t) => [$t->id => $t->name])->toJson() }},
                        selectedType: '{{ old('payroll_type_id') }}',
                        get salaryLabel() {
                            const typeName = this.payrollTypes[this.selectedType] || '';
                            if (typeName.toLowerCase().includes('hourly')) return 'Hourly Rate';
                            if (typeName.toLowerCase().includes('monthly')) return 'Monthly Salary';
                            if (typeName.toLowerCase().includes('project')) return 'Project Fee';
                            return 'Amount';
                        },
                        get showSalary() {
                            return !!this.selectedType;
                        }
                    }" class="space-y-10">
                    
                        <!-- Personal Information Section -->
                        <section class="relative">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Personal Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Name -->
                                <div class="group">
                                    <x-input-label for="name" :value="__('Full Name')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-teal-600 dark:group-focus-within:text-teal-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <x-text-input id="name" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-teal-500 focus:ring-teal-500 transition-all" type="text" name="name" :value="old('name')" required autofocus placeholder="John Doe" />
                                    </div>
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div class="group">
                                    <x-input-label for="email" :value="__('Email Address')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-teal-600 dark:group-focus-within:text-teal-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                            </svg>
                                        </div>
                                        <x-text-input id="email" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-teal-500 focus:ring-teal-500 transition-all" type="email" name="email" :value="old('email')" required placeholder="john@example.com" />
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>
                        </section>

                        <div class="border-t border-gray-100 dark:border-gray-700"></div>

                        <!-- Branch & Role Assignment (Super Admin Only) -->
                        @if(auth()->user()->isSuperAdmin())
                            <section class="relative">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Branch & Role</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Branch -->
                                    <div class="group">
                                        <x-input-label for="branch_id" :value="__('Assign Branch')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-teal-600 dark:group-focus-within:text-teal-400 transition-colors" />
                                        <div class="relative">
                                            <select id="branch_id" name="branch_id" class="block w-full pl-3 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm transition-all">
                                                <option value="">Select Branch</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <x-input-error :messages="$errors->get('branch_id')" class="mt-2" />
                                    </div>

                                    <!-- Role Selection Removed -->
                                </div>
                            </section>

                            <div class="border-t border-gray-100 dark:border-gray-700"></div>
                        @endif

                        <!-- Employment Details Section -->
                        <section class="relative">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Employment Details</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Job Title -->
                                <div class="group">
                                    <x-input-label for="job_title" :value="__('Job Title')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-teal-600 dark:group-focus-within:text-teal-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                            </svg>
                                        </div>
                                        <x-text-input id="job_title" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-teal-500 focus:ring-teal-500 transition-all" type="text" name="job_title" :value="old('job_title')" placeholder="Software Engineer" />
                                    </div>
                                    <x-input-error :messages="$errors->get('job_title')" class="mt-2" />
                                </div>

                                <!-- Joining Date -->
                                <div class="group">
                                    <x-input-label for="joining_date" :value="__('Joining Date')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-teal-600 dark:group-focus-within:text-teal-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <x-text-input id="joining_date" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-teal-500 focus:ring-teal-500 transition-all" type="date" name="joining_date" :value="old('joining_date')" />
                                    </div>
                                    <x-input-error :messages="$errors->get('joining_date')" class="mt-2" />
                                </div>

                                <!-- Employee Type -->
                                <div class="group">
                                    <x-input-label for="employee_type_id" :value="__('Employee Type')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-teal-600 dark:group-focus-within:text-teal-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select id="employee_type_id" name="employee_type_id" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm transition-all">
                                            <option value="">Select Type</option>
                                            @foreach($employeeTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('employee_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('employee_type_id')" class="mt-2" />
                                </div>

                                <!-- Payroll Type -->
                                <div class="group">
                                    <x-input-label for="payroll_type_id" :value="__('Payroll Type')" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-teal-600 dark:group-focus-within:text-teal-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select id="payroll_type_id" name="payroll_type_id" x-model="selectedType" class="block w-full pl-10 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm transition-all">
                                            <option value="">Select Type</option>
                                            @foreach($payrollTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('payroll_type_id')" class="mt-2" />
                                </div>

                                <!-- Salary (Dynamic) -->
                                <div class="group" x-show="showSalary" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     x-cloak>
                                    <x-input-label for="salary" x-text="salaryLabel" class="mb-2 text-gray-600 dark:text-gray-400 group-focus-within:text-teal-600 dark:group-focus-within:text-teal-400 transition-colors" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 font-bold">$</span>
                                        </div>
                                        <x-text-input id="salary" class="block w-full pl-8 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-900 focus:border-teal-500 focus:ring-teal-500 transition-all" type="number" step="0.01" name="salary" :value="old('salary')" placeholder="0.00" />
                                    </div>
                                    <x-input-error :messages="$errors->get('salary')" class="mt-2" />
                                </div>
                            </div>
                        </section>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('employees.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-teal-500 hover:bg-teal-600 focus:bg-teal-600 active:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Add Employee') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
