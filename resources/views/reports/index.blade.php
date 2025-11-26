<x-app-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header & Filters -->
            <div class="flex flex-col md:flex-row justify-between items-center bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 leading-tight">
                        {{ __('Financial Reports') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Analyze your revenue, expenses, and cash flow.</p>
                </div>
                
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <div>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex items-center text-gray-400">to</div>
                    <div>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Export Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('reports.export', ['type' => 'revenue', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 dark:bg-green-900/50 dark:text-green-300 rounded-lg font-medium text-sm hover:bg-green-100 dark:hover:bg-green-900 transition-colors duration-200 border border-green-200 dark:border-green-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Revenue
                </a>
                <a href="{{ route('reports.export', ['type' => 'expense', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 dark:bg-red-900/50 dark:text-red-300 rounded-lg font-medium text-sm hover:bg-red-100 dark:hover:bg-red-900 transition-colors duration-200 border border-red-200 dark:border-red-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Expenses
                </a>
                <a href="{{ route('reports.export', ['type' => 'all', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 rounded-lg font-medium text-sm hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-colors duration-200 border border-indigo-200 dark:border-indigo-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export All
                </a>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Revenue -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $currency }}{{ number_format($totalRevenue, 2) }}</h3>
                </div>

                <!-- Expenses -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Expenses</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $currency }}{{ number_format($totalExpenses, 2) }}</h3>
                </div>

                <!-- Net Profit -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Net Profit</p>
                    <h3 class="text-3xl font-bold {{ $netProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mt-2">
                        {{ $currency }}{{ number_format($netProfit, 2) }}
                    </h3>
                </div>

                <!-- Profit Margin -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Profit Margin</p>
                    <h3 class="text-3xl font-bold {{ $profitMargin >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-red-600 dark:text-red-400' }} mt-2">
                        {{ number_format($profitMargin, 1) }}%
                    </h3>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cash Flow Chart -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Cash Flow (Revenue vs Expenses)</h3>
                    <div class="h-80">
                        <canvas id="cashFlowChart"></canvas>
                    </div>
                </div>

                <!-- Expense Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Expense Breakdown</h3>
                    <div class="h-64">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detailed Transactions Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Detailed Transactions</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $transaction['date']->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $transaction['type'] === 'Income' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' }}">
                                            {{ $transaction['type'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $transaction['category'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">{{ $transaction['description'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right {{ $transaction['type'] === 'Income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction['type'] === 'Income' ? '+' : '-' }}{{ $currency }}{{ number_format(abs($transaction['amount']), 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No transactions found for this period.</td>
                                </tr>
                            @endforelse
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

            // Cash Flow Chart
            const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
            new Chart(cashFlowCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($cashFlowLabels) !!},
                    datasets: [
                        {
                            label: 'Revenue',
                            data: {!! json_encode($revenueData) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Expenses',
                            data: {!! json_encode($expenseData) !!},
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '{{ $currency }}' + value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Expense Breakdown Chart
            const expenseCtx = document.getElementById('expenseChart').getContext('2d');
            new Chart(expenseCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($expenseCategoryLabels) !!},
                    datasets: [{
                        data: {!! json_encode($expenseCategoryData) !!},
                        backgroundColor: [
                            '#f59e0b', '#3b82f6', '#ec4899', '#8b5cf6', '#10b981', '#6b7280'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        });
    </script>
</x-app-layout>
