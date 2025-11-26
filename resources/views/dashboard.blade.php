<x-app-layout>
<div class="space-y-6">
    
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="mb-4 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Welcome back, <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ Auth::user()->name }}</span>! Here's what's happening today.</p>
        </div>
        <!-- Quick Actions -->
        <div class="flex space-x-3">
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 rounded-lg font-medium text-sm hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Client
            </a>
            <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 dark:bg-green-900/50 dark:text-green-300 rounded-lg font-medium text-sm hover:bg-green-100 dark:hover:bg-green-900 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Invoice
            </a>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 rounded-lg font-medium text-sm hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Project
            </a>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Clients -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Clients</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $totalClients }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs font-medium text-green-600 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                            +{{ $newClientsThisMonth }} new
                        </span>
                        <span class="text-xs text-gray-400 ml-2">this month</span>
                    </div>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl text-indigo-600 dark:text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Projects -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Projects</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $ongoingProjects }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs font-medium text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 px-2 py-0.5 rounded-full">
                            {{ $projectsDueThisWeek }} due soon
                        </span>
                        <span class="text-xs text-gray-400 ml-2">this week</span>
                    </div>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
        </div>

        <!-- Invoices -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Outstanding</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($totalOutstandingInvoices, 0) }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs font-medium text-red-600 bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded-full">
                            {{ $overdueInvoicesCount }} overdue
                        </span>
                        <span class="text-xs text-gray-400 ml-2">invoices</span>
                    </div>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-xl text-green-600 dark:text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Expenses -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Expenses (Month)</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($expensesThisMonth, 0) }}</h3>
                    <p class="text-xs text-gray-500 mt-2">YTD: {{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($expensesThisYear, 0) }}</p>
                </div>
                <div class="p-3 bg-red-50 dark:bg-red-900/30 rounded-xl text-red-600 dark:text-red-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Action Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Charts Section (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Revenue Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Revenue Trend</h3>
                    <select class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option>Last 6 Months</option>
                        <option>Last Year</option>
                    </select>
                </div>
                <div class="h-72">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Invoice Status -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Invoice Status</h3>
                    <div class="h-56 relative">
                        <canvas id="invoiceStatusChart"></canvas>
                    </div>
                </div>
                <!-- Expense Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Expenses by Category</h3>
                    <div class="h-56">
                        <canvas id="expenseCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Panel (1/3 width) -->
        <div class="space-y-8">
            <!-- Notifications/Alerts -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                    <span class="relative flex h-3 w-3 mr-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                    Action Needed
                </h3>
                <div class="space-y-4">
                    @if($overdueReminders->count() > 0)
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 border border-red-100 dark:border-red-800/50">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-semibold text-red-800 dark:text-red-300">Overdue Reminders</p>
                                <span class="text-xs bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 px-2 py-0.5 rounded-full">{{ $overdueReminders->count() }}</span>
                            </div>
                            <ul class="space-y-2">
                                @foreach($overdueReminders as $reminder)
                                    <li class="flex justify-between items-center text-xs text-red-700 dark:text-red-400">
                                        <span class="truncate pr-2">{{ $reminder->title }}</span>
                                        <span class="whitespace-nowrap font-medium">{{ $reminder->reminder_date->format('M d') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($overdueInvoices->count() > 0)
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 border border-yellow-100 dark:border-yellow-800/50">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300">Overdue Invoices</p>
                                <span class="text-xs bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 px-2 py-0.5 rounded-full">{{ $overdueInvoices->count() }}</span>
                            </div>
                            <ul class="space-y-2">
                                @foreach($overdueInvoices as $invoice)
                                    <li class="flex justify-between items-center text-xs text-yellow-700 dark:text-yellow-400">
                                        <span class="font-medium">{{ $invoice->invoice_number }}</span>
                                        <span class="whitespace-nowrap">{{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($invoice->total_amount, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($projectsDueSoon->count() > 0)
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800/50">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Projects Due Soon</p>
                                <span class="text-xs bg-blue-200 dark:bg-blue-800 text-blue-800 dark:text-blue-200 px-2 py-0.5 rounded-full">{{ $projectsDueSoon->count() }}</span>
                            </div>
                            <ul class="space-y-2">
                                @foreach($projectsDueSoon as $project)
                                    <li class="flex justify-between items-center text-xs text-blue-700 dark:text-blue-400">
                                        <span class="truncate pr-2">{{ $project->title }}</span>
                                        <span class="whitespace-nowrap font-medium">{{ $project->deadline->format('M d') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if($overdueReminders->isEmpty() && $overdueInvoices->isEmpty() && $projectsDueSoon->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm">All caught up! No urgent actions needed.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payroll Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Payroll Snapshot</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Next Pay Date</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $upcomingPayroll ? $upcomingPayroll->payment_date->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total This Month</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($totalPayrollThisMonth, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Active Employees</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $totalEmployees }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Invoices -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Recent Invoices</h3>
                <a href="{{ route('invoices.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">View All &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentInvoices as $invoice)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $invoice->client->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">{{ $globalSettings['currency_symbol'] ?? '$' }}{{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
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
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Recent Projects</h3>
                <a href="{{ route('projects.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">View All &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentProjects as $project)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $project->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $project->client->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                color: '#f3f4f6',
                                borderDash: [5, 5]
                            },
                            ticks: {
                                callback: function(value) {
                                    return '{{ $globalSettings['currency_symbol'] ?? '$' }}' + value;
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
                                padding: 20
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
                        borderRadius: 4,
                        barThickness: 20
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
                                color: '#f3f4f6',
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
        });
    </script>
</x-app-layout>
