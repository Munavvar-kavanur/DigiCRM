<x-app-layout>
    <div class="py-12">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Header -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                Expense Details
                                @if(auth()->user()->isSuperAdmin() && $expense->branch)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 align-middle">
                                        {{ $expense->branch->name }}
                                    </span>
                                @endif
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Submitted by {{ $expense->user->name }} on {{ $expense->date->format('F d, Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('expenses.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 flex items-center mr-2">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to List
                            </a>
                            <a href="{{ route('expenses.edit', $expense) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Main Details -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Status Banner -->
                            <div class="rounded-lg p-4 flex items-center justify-between {{ $expense->status === 'approved' ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : ($expense->status === 'rejected' ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800') }}">
                                <div class="flex items-center">
                                    @if($expense->status === 'approved')
                                        <div class="p-2 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 mr-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-green-800 dark:text-green-300">Approved</h3>
                                            <p class="text-sm text-green-600 dark:text-green-400">This expense has been approved.</p>
                                        </div>
                                    @elseif($expense->status === 'rejected')
                                        <div class="p-2 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 mr-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-red-800 dark:text-red-300">Rejected</h3>
                                            <p class="text-sm text-red-600 dark:text-red-400">This expense has been rejected.</p>
                                        </div>
                                    @else
                                        <div class="p-2 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-300 mr-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300">Pending Approval</h3>
                                            <p class="text-sm text-yellow-600 dark:text-yellow-400">This expense is waiting for review.</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Approval Actions -->
                                @if($expense->status === 'pending')
                                    <div class="flex gap-2">
                                        <form action="{{ route('expenses.update', $expense) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <!-- Hidden fields to satisfy validation -->
                                            <input type="hidden" name="description" value="{{ $expense->description }}">
                                            <input type="hidden" name="amount" value="{{ $expense->amount }}">
                                            <input type="hidden" name="date" value="{{ $expense->date->format('Y-m-d') }}">
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm transition">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('expenses.update', $expense) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <!-- Hidden fields to satisfy validation -->
                                            <input type="hidden" name="description" value="{{ $expense->description }}">
                                            <input type="hidden" name="amount" value="{{ $expense->amount }}">
                                            <input type="hidden" name="date" value="{{ $expense->date->format('Y-m-d') }}">
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm transition">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <!-- Info Grid -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $expense->title ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $expense->description ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                                        <dd class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $currency }}{{ number_format($expense->amount, 2) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</dt>
                                        <dd class="mt-1">
                                            @if($expense->category instanceof \App\Models\ExpenseCategory)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $expense->category->color }}20; color: {{ $expense->category->color }}">
                                                    {{ $expense->category->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-900 dark:text-white">{{ $expense->category ?? '-' }}</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Merchant / Vendor</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $expense->merchant ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Reference #</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $expense->reference ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Paid By</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $expense->payer ? $expense->payer->name : 'N/A' }}</dd>
                                    </div>
                                    @if($expense->is_recurring)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Recurring</dt>
                                            <dd class="mt-1 text-sm text-indigo-600 dark:text-indigo-400 font-medium">
                                                Yes ({{ ucfirst($expense->frequency) }})
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">End Date</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                                {{ $expense->end_date ? $expense->end_date->format('M d, Y') : 'No end date' }}
                                            </dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>

                        <!-- Receipt Preview -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-6 border border-gray-100 dark:border-gray-700 h-full">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Receipt</h3>
                                @if($expense->receipt_path)
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-800">
                                        @php
                                            $extension = pathinfo($expense->receipt_path, PATHINFO_EXTENSION);
                                        @endphp
                                        
                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                            <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $expense->receipt_path) }}" alt="Receipt" class="w-full h-auto rounded hover:opacity-90 transition">
                                            </a>
                                        @elseif(strtolower($extension) === 'pdf')
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <svg class="w-16 h-16 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white mb-2">PDF Document</span>
                                                <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-sm font-semibold hover:underline">
                                                    View PDF
                                                </a>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <svg class="w-16 h-16 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-sm font-semibold hover:underline">
                                                    Download File
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-4 text-center">
                                        <a href="{{ asset('storage/' . $expense->receipt_path) }}" download class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Download Original
                                        </a>
                                    </div>
                                @else
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm">No receipt attached</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
