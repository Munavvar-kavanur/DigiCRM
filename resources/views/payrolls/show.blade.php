<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Payslip Details') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('payrolls.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Back
                </a>
                <a href="{{ route('payrolls.downloadPdf', $payroll) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Download PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    
                    <!-- Header -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6 flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">Payslip</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                For the month of {{ $payroll->salary_month->format('F Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($payroll->status) }}
                            </span>
                            @if($payroll->payment_date)
                                <p class="text-xs text-gray-500 mt-2">Paid on {{ $payroll->payment_date->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Employee Details -->
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Employee Details</h3>
                            <div class="flex items-center">
                                <img class="h-12 w-12 rounded-full object-cover mr-4" src="{{ $payroll->user->profile_photo_url }}" alt="{{ $payroll->user->name }}">
                                <div>
                                    <p class="text-lg font-bold">{{ $payroll->user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payroll->user->job_title ?? 'No Job Title' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payroll->user->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Company Details</h3>
                            <p class="font-bold">{{ config('app.name') }}</p>
                            <!-- Add company address/details from settings if available -->
                        </div>
                    </div>

                    <!-- Salary Breakdown -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-medium mb-4 border-b border-gray-200 dark:border-gray-600 pb-2">Salary Breakdown</h3>
                        
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600 dark:text-gray-300">Base Salary</span>
                            <span class="font-medium">{{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($payroll->base_salary, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600 dark:text-gray-300">Bonus</span>
                            <span class="font-medium text-green-600 dark:text-green-400">+ {{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($payroll->bonus, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600 dark:text-gray-300">Deductions</span>
                            <span class="font-medium text-red-600 dark:text-red-400">- {{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($payroll->deductions, 2) }}</span>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-600 my-4"></div>

                        <div class="flex justify-between items-center text-xl font-bold">
                            <span>Net Salary</span>
                            <span class="text-indigo-600 dark:text-indigo-400">{{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($payroll->net_salary, 2) }}</span>
                        </div>
                    </div>

                    @if($payroll->notes)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Notes</h3>
                            <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-4 rounded-md">{{ $payroll->notes }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
