<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Client Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'projects', pdfPreviewModal: false, pdfPreviewUrl: '' }">
            
            <!-- Welcome Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Here is an overview of your account. You can manage your projects, invoices, and communicate with us directly from here.
                    </p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Due -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Due</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($totalDue, 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Active Projects -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Projects</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $projects->where('status', '!=', 'completed')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Unpaid Invoices -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unpaid Invoices</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $invoices->where('status', '!=', 'paid')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Tabs -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button @click="activeTab = 'projects'" 
                            :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'projects', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'projects' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Projects
                        </button>
                        <button @click="activeTab = 'invoices'" 
                            :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'invoices', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'invoices' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Invoices
                        </button>
                        <button @click="activeTab = 'estimates'" 
                            :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'estimates', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'estimates' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Estimates
                        </button>
                        <button @click="activeTab = 'payments'" 
                            :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'payments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'payments' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Payments
                        </button>
                        <button @click="activeTab = 'team'" 
                            :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'team', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'team' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Team
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <!-- Projects Tab -->
                    <div x-show="activeTab === 'projects'" class="space-y-4">
                        @if($projects->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400">No projects found.</p>
                        @else
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($projects as $project)
                                    <div class="border dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $project->name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($project->description, 100) }}</p>
                                                <div class="mt-2 flex items-center gap-2">
                                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ ucfirst($project->status) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        Due: {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Progress</div>
                                                <div class="w-32 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-1">
                                                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $project->progress ?? 0 }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">{{ $project->progress ?? 0 }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Invoices Tab -->
                    <div x-show="activeTab === 'invoices'" style="display: none;">
                         @if($invoices->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400">No invoices found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice #</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($invoices as $invoice)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $invoice->invoice_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($invoice->total, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                                           ($invoice->status === 'unpaid' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                                        {{ ucfirst($invoice->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                    <button @click="pdfPreviewModal = true; pdfPreviewUrl = '{{ route('invoices.preview', $invoice) }}'" 
                                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Preview
                                                    </button>
                                                    
                                                    <a href="{{ route('invoices.pdf', $invoice) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded-full shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Download
                                                    </a>

                                                    @if($invoice->status !== 'paid')
                                                        <button onclick="payNow({{ $invoice->id }})" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                            Pay Now
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Estimates Tab -->
                    <div x-show="activeTab === 'estimates'" style="display: none;">
                        @if($estimates->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400">No estimates found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estimate #</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($estimates as $estimate)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $estimate->estimate_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($estimate->estimate_date)->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($estimate->total, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $estimate->status === 'accepted' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                                           ($estimate->status === 'declined' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200') }}">
                                                        {{ ucfirst($estimate->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('estimates.pdf', $estimate) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Download</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Payments Tab -->
                    <div x-show="activeTab === 'payments'" style="display: none;">
                        @if($payments->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400">No payments found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Method</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($payment->amount, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ ucfirst($payment->payment_method) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $payment->transaction_id ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Team Tab -->
                    <div x-show="activeTab === 'team'" style="display: none;">
                        <livewire:client.team-manager />
                    </div>
                </div>
            </div>
            <!-- PDF Preview Modal -->
            <div x-show="pdfPreviewModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="pdfPreviewModal" @click="pdfPreviewModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="pdfPreviewModal" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                        Invoice Preview
                                    </h3>
                                    <div class="mt-4 h-[70vh] bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden">
                                        <iframe :src="pdfPreviewUrl" class="w-full h-full border-0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <a :href="pdfPreviewUrl && pdfPreviewUrl.replace('/preview', '/pdf')" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Download PDF
                            </a>
                            <button type="button" @click="pdfPreviewModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function payNow(invoiceId) {
            fetch(`/invoices/${invoiceId}/razorpay/order`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                var options = {
                    "key": data.key,
                    "amount": data.amount,
                    "currency": data.currency,
                    "name": data.name,
                    "description": data.description,
                    "order_id": data.order_id, 
                    "handler": function (response){
                        // Verify Payment
                        fetch(`/invoices/${invoiceId}/razorpay/verify`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Success', data.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                    },
                    "prefill": data.prefill,
                    "theme": data.theme
                };
                var rzp1 = new Razorpay(options);
                rzp1.on('payment.failed', function (response){
                    Swal.fire('Error', response.error.description, 'error');
                });
                rzp1.open();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong!', 'error');
            });
        }
    </script>
</x-app-layout>
