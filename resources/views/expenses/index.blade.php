<x-app-layout>
    <div class="space-y-8">
        
        <!-- Header Section -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-rose-600 to-pink-600 dark:from-rose-900 dark:to-pink-900 shadow-xl">
            <div class="absolute inset-0 bg-white/10 dark:bg-black/10 backdrop-blur-[1px]"></div>
            <div class="relative p-8 md:p-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-white">
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight">
                        {{ __('Expenses') }}
                    </h2>
                    <p class="mt-2 text-rose-100 text-lg">
                        Track and manage company expenditures.
                        @if($selectedBranch)
                            Viewing <span class="font-bold text-white">{{ $selectedBranch->name }}</span>
                        @endif
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-rose-600 hover:bg-rose-50 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Expense
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Expenses -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Expenses</p>
                        <div class="mt-2 space-y-1">
                            @foreach($totalExpensesValue as $val)
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                    {{ $val->currency }}{{ number_format($val->amount, 2) }}
                                </div>
                            @endforeach
                            @if($totalExpensesValue->isEmpty())
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">0.00</div>
                            @endif
                        </div>
                    </div>
                    <div class="p-3.5 bg-rose-50 dark:bg-rose-900/30 rounded-2xl text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">This Month</p>
                        <div class="mt-2 space-y-1">
                            @foreach($thisMonthExpensesValue as $val)
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $val->currency }}{{ number_format($val->amount, 2) }}
                                </div>
                            @endforeach
                            @if($thisMonthExpensesValue->isEmpty())
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">0.00</div>
                            @endif
                        </div>
                    </div>
                    <div class="p-3.5 bg-blue-50 dark:bg-blue-900/30 rounded-2xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Approval</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2 group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors">{{ $pendingCount }}</h3>
                        <div class="mt-1 space-y-0.5">
                             @foreach($pendingExpensesValue as $val)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $val->currency }}{{ number_format($val->amount, 2) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="p-3.5 bg-yellow-50 dark:bg-yellow-900/30 rounded-2xl text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved</p>
                        <div class="mt-2 space-y-1">
                            @foreach($approvedExpensesValue as $val)
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                    {{ $val->currency }}{{ number_format($val->amount, 2) }}
                                </div>
                            @endforeach
                            @if($approvedExpensesValue->isEmpty())
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">0.00</div>
                            @endif
                        </div>
                    </div>
                    <div class="p-3.5 bg-green-50 dark:bg-green-900/30 rounded-2xl text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Content -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                <form action="{{ route('expenses.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 justify-between">
                    <!-- Search -->
                    <div class="relative w-full lg:w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl leading-5 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent sm:text-sm transition-all" 
                            placeholder="Description, Merchant...">
                    </div>

                    <!-- Filters Group -->
                    <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                        <select name="status" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 py-2.5 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-rose-500 text-sm font-medium cursor-pointer">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>

                        <select name="category_id" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 py-2.5 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-rose-500 text-sm font-medium cursor-pointer max-w-[200px]">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <select name="paid_by_id" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 py-2.5 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-rose-500 text-sm font-medium cursor-pointer max-w-[200px]">
                            <option value="">All Payers</option>
                            @foreach($expensePayers as $payer)
                                <option value="{{ $payer->id }}" {{ request('paid_by_id') == $payer->id ? 'selected' : '' }}>{{ $payer->name }}</option>
                            @endforeach
                        </select>

                        @if(auth()->user()->isSuperAdmin())
                            <select name="branch_id" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 py-2.5 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-rose-500 text-sm font-medium cursor-pointer">
                                <option value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        <div class="flex items-center gap-2">
                            <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 py-2.5 px-3 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-rose-500 text-sm font-medium" placeholder="From">
                            <span class="text-gray-400">-</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()" class="appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 py-2.5 px-3 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-rose-500 text-sm font-medium" placeholder="To">
                        </div>

                        @if(request('search') || request('status') || request('category_id') || request('branch_id') || request('date_from') || request('date_to'))
                            <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 m-6 mb-0 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Details</th>
                            @if(auth()->user()->isSuperAdmin())
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Branch</th>
                            @endif
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paid By</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($expenses as $expense)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $expense->date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $expense->title ?? $expense->description }}</div>
                                    @if($expense->title && $expense->description)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate max-w-xs" title="{{ $expense->description }}">
                                            {{ $expense->description }}
                                        </div>
                                    @endif
                                    @if($expense->merchant)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $expense->merchant }}
                                        </div>
                                    @endif
                                </td>
                                @if(auth()->user()->isSuperAdmin())
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $expense->branch ? $expense->branch->name : 'N/A' }}
                                        </span>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($expense->category instanceof \App\Models\ExpenseCategory)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $expense->category->color }}20; color: {{ $expense->category->color }}">
                                            {{ $expense->category->name }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $expense->category ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $expense->paidBy->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border-green-200 dark:border-green-800',
                                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 border-red-200 dark:border-red-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800',
                                        ];
                                        $statusClass = $statusClasses[$expense->status] ?? $statusClasses['pending'];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $statusClass }}">
                                        {{ ucfirst($expense->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $expense->branch ? $expense->branch->currency : '$' }}{{ number_format($expense->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('expenses.show', $expense) }}" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20" title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('expenses.edit', $expense) }}" class="p-2 text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 transition-colors rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <button type="button" @click="$dispatch('open-delete-modal', { actionUrl: '{{ route('expenses.destroy', $expense) }}' })" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-full mb-4">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No expenses found</h3>
                                        <p class="text-sm mb-6 max-w-sm mx-auto">Track your spending by adding a new expense.</p>
                                        <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Add New Expense
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($expenses->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
