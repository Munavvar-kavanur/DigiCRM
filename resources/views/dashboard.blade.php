<x-app-layout>
    <div class="space-y-8">
        
        <!-- Welcome Section & Filter -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-900 dark:to-purple-900 shadow-xl">
            <div class="absolute inset-0 bg-white/10 dark:bg-black/10 backdrop-blur-[1px]"></div>
            <div class="relative p-8 md:p-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-white">
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
                        {{ __('Dashboard') }}
                    </h2>
                    <p class="mt-2 text-indigo-100 text-lg">
                        Welcome back, <span class="font-semibold text-white">{{ Auth::user()->name }}</span>!
                        @if($selectedBranch)
                            Viewing <span class="font-bold text-white">{{ $selectedBranch->name }}</span>
                        @else
                            Here's your global overview.
                        @endif
                    </p>
                </div>
                
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    @if(auth()->user()->isSuperAdmin())
                        <form method="GET" action="{{ route('dashboard') }}" class="relative">
                            <select name="branch_id" onchange="this.form.submit()" class="appearance-none bg-white/20 hover:bg-white/30 text-white border border-white/30 rounded-xl py-2.5 pl-4 pr-10 focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer backdrop-blur-sm transition-all">
                                <option value="" class="text-gray-900">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }} class="text-gray-900">
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </form>
                    @endif

                    <!-- Quick Actions -->
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl font-medium text-sm backdrop-blur-sm transition-all duration-200 border border-white/20 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Client
                        </a>
                        <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl font-medium text-sm backdrop-blur-sm transition-all duration-200 border border-white/20 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Invoice
                        </a>
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2.5 bg-white text-indigo-600 hover:bg-indigo-50 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Project
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->isSuperAdmin() && !empty($branchAnalytics) && !$selectedBranch)
            <!-- Branch Analytics Section (Super Admin Only - Global View) -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Branch Performance
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Branch Revenue Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-300">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Revenue Distribution</h4>
                        <div class="h-64 relative">
                            <canvas id="branchRevenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Branch Details Table -->
                    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100">Branch Details</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Branch</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Clients</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Projects</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Revenue</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Outstanding</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($branchAnalytics as $branch)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xs mr-3">
                                                        {{ substr($branch['name'], 0, 2) }}
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $branch['name'] }}</span>
                                                        <span class="text-xs text-gray-500">{{ $branch['code'] ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 font-medium">{{ $branch['clients_count'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 font-medium">{{ $branch['active_projects_count'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 dark:text-green-400">
                                                {{ $branch['currency'] }}{{ number_format($branch['revenue'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-500 dark:text-red-400">
                                                {{ $branch['currency'] }}{{ number_format($branch['outstanding'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Clients -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Clients</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $totalClients }}</h3>
                        <div class="flex items-center mt-3">
                            <span class="text-xs font-bold text-green-600 bg-green-100 dark:bg-green-900/30 px-2.5 py-1 rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                +{{ $newClientsThisMonth }}
                            </span>
                            <span class="text-xs text-gray-400 ml-2">this month</span>
                        </div>
                    </div>
                    <div class="p-3.5 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Projects</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $ongoingProjects }}</h3>
                        <div class="flex items-center mt-3">
                            <span class="text-xs font-bold text-yellow-600 bg-yellow-100 dark:bg-yellow-900/30 px-2.5 py-1 rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $projectsDueThisWeek }} due soon
                            </span>
                        </div>
                    </div>
                    <div class="p-3.5 bg-blue-50 dark:bg-blue-900/30 rounded-2xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Invoices (Multi-Currency) -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div class="w-full">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Outstanding</p>
                        <div class="mt-2 space-y-1">
                            @foreach($totalOutstandingInvoices as $total)
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                    {{ $total['currency'] }}{{ number_format($total['amount'], 0) }}
                                </h3>
                            @endforeach
                        </div>
                        <div class="flex items-center mt-3">
                            <span class="text-xs font-bold text-red-600 bg-red-100 dark:bg-red-900/30 px-2.5 py-1 rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $overdueInvoicesCount }} overdue
                            </span>
                        </div>
                    </div>
                    <div class="p-3.5 bg-green-50 dark:bg-green-900/30 rounded-2xl text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Expenses (Multi-Currency) -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div class="w-full">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Expenses (Month)</p>
                        <div class="mt-2 space-y-1">
                            @foreach($expensesThisMonth as $total)
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                    {{ $total['currency'] }}{{ number_format($total['amount'], 0) }}
                                </h3>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <p class="text-xs text-gray-400 font-medium">YTD:</p>
                            @foreach($expensesThisYear as $total)
                                <p class="text-xs text-gray-500 dark:text-gray-300 font-semibold">
                                    {{ $total['currency'] }}{{ number_format($total['amount'], 0) }}
                                </p>
                            @endforeach
                        </div>
                    </div>
                    <div class="p-3.5 bg-red-50 dark:bg-red-900/30 rounded-2xl text-red-600 dark:text-red-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Action Panel -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Charts Section (2/3 width) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Revenue Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Revenue Trend</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                @if($selectedBranch)
                                    Financial performance for {{ $selectedBranch->name }}
                                @else
                                    Global financial performance (Aggregated)
                                @endif
                            </p>
                        </div>
                        <select class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                            <option>Last 6 Months</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    <div class="h-80 w-full">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Invoice Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 hover:shadow-md transition-shadow duration-300">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Invoice Status</h3>
                        <div class="h-64 relative">
                            <canvas id="invoiceStatusChart"></canvas>
                        </div>
                    </div>
                    <!-- Expense Categories -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 hover:shadow-md transition-shadow duration-300">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Expenses by Category</h3>
                        <div class="h-64">
                            <canvas id="expenseCategoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Panel (1/3 width) -->
            <div class="space-y-8">
                <!-- Action Needed -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <span class="relative flex h-3 w-3 mr-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                            Action Needed
                        </h3>
                    </div>
                    
                    <div class="space-y-4">
                        @if($overdueReminders->count() > 0)
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-4 border border-red-100 dark:border-red-800/50">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-bold text-red-800 dark:text-red-300">Overdue Reminders</p>
                                    <span class="text-xs bg-white dark:bg-red-900 text-red-800 dark:text-red-200 px-2.5 py-1 rounded-full font-bold shadow-sm">{{ $overdueReminders->count() }}</span>
                                </div>
                                <ul class="space-y-3">
                                    @foreach($overdueReminders as $reminder)
                                        <li class="flex justify-between items-center text-xs text-red-700 dark:text-red-400">
                                            <span class="truncate pr-2 font-medium">{{ $reminder->title }}</span>
                                            <span class="whitespace-nowrap opacity-80">{{ $reminder->reminder_date->format('M d') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($overdueInvoices->count() > 0)
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-2xl p-4 border border-yellow-100 dark:border-yellow-800/50">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-bold text-yellow-800 dark:text-yellow-300">Overdue Invoices</p>
                                    <span class="text-xs bg-white dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-2.5 py-1 rounded-full font-bold shadow-sm">{{ $overdueInvoices->count() }}</span>
                                </div>
                                <ul class="space-y-3">
                                    @foreach($overdueInvoices as $invoice)
                                        <li class="flex justify-between items-center text-xs text-yellow-700 dark:text-yellow-400">
                                            <span class="font-medium">{{ $invoice->invoice_number }}</span>
                                            <span class="whitespace-nowrap font-bold">
                                                {{ $invoice->branch ? $invoice->branch->currency : ($settings['currency_symbol'] ?? '$') }}{{ number_format($invoice->total_amount, 2) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($projectsDueSoon->count() > 0)
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4 border border-blue-100 dark:border-blue-800/50">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-bold text-blue-800 dark:text-blue-300">Projects Due Soon</p>
                                    <span class="text-xs bg-white dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2.5 py-1 rounded-full font-bold shadow-sm">{{ $projectsDueSoon->count() }}</span>
                                </div>
                                <ul class="space-y-3">
                                    @foreach($projectsDueSoon as $project)
                                        <li class="flex justify-between items-center text-xs text-blue-700 dark:text-blue-400">
                                            <span class="truncate pr-2 font-medium">{{ $project->title }}</span>
                                            <span class="whitespace-nowrap opacity-80">{{ $project->deadline->format('M d') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if($overdueReminders->isEmpty() && $overdueInvoices->isEmpty() && $projectsDueSoon->isEmpty())
                            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                <div class="bg-green-50 dark:bg-green-900/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-sm font-medium">All caught up!</p>
                                <p class="text-xs mt-1 opacity-75">No urgent actions needed.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payroll Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-300">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Payroll Snapshot</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Next Pay Date</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $upcomingPayroll ? $upcomingPayroll->payment_date->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total This Month</span>
                            <div class="text-right">
                                @foreach($totalPayrollThisMonth as $total)
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ $total['currency'] }}{{ number_format($total['amount'], 2) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Active Employees</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $totalEmployees }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Invoices -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Recent Invoices</h3>
                    <a href="{{ route('invoices.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">View All &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentInvoices as $invoice)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $invoice->client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-200">
                                        {{ $invoice->branch ? $invoice->branch->currency : ($settings['currency_symbol'] ?? '$') }}{{ number_format($invoice->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 
                                               ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Recent Projects</h3>
                    <a href="{{ route('projects.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">View All &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentProjects as $project)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $project->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $project->client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                            {{ $project->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 
                                               ($project->status === 'in_progress' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#6b7280';
            
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($revenueLabels) !!},
                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($revenueData) !!},
                        borderColor: '#4f46e5',
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
                            gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
                            return gradient;
                        },
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#f3f4f6',
                            bodyColor: '#f3f4f6',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(243, 244, 246, 0.6)',
                                borderDash: [5, 5]
                            },
                            ticks: {
                                callback: function(value) {
                                    // Use default currency for chart y-axis or empty if mixed
                                    return value; 
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Invoice Status Chart
            const invoiceStatusCtx = document.getElementById('invoiceStatusChart').getContext('2d');
            new Chart(invoiceStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Unpaid', 'Overdue'],
                    datasets: [{
                        data: {!! json_encode($invoiceStatusData) !!},
                        backgroundColor: ['#10b981', '#9ca3af', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });

            // Expense Category Chart
            const expenseCategoryCtx = document.getElementById('expenseCategoryChart').getContext('2d');
            new Chart(expenseCategoryCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($expenseCategoryLabels) !!},
                    datasets: [{
                        label: 'Expenses',
                        data: {!! json_encode($expenseCategoryData) !!},
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(243, 244, 246, 0.6)',
                                borderDash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            @if(auth()->user()->isSuperAdmin() && !empty($branchRevenueLabels))
                // Branch Revenue Chart
                const branchRevenueCtx = document.getElementById('branchRevenueChart').getContext('2d');
                new Chart(branchRevenueCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($branchRevenueLabels) !!},
                        datasets: [{
                            data: {!! json_encode($branchRevenueData) !!},
                            backgroundColor: [
                                '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    }
                                }
                            }
                        }
                    }
                });
            @endif
        });
    </script>
</x-app-layout>
