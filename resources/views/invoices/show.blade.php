<x-app-layout>


    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Action Bar -->
            <div class="flex justify-between items-center mb-6 no-print">
                <div class="flex space-x-2">
                    <a href="{{ route('invoices.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors">
                        &larr; Back
                    </a>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('invoices.edit', $invoice) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors">
                        Edit
                    </a>
                    <a href="{{ route('reminders.create', ['type' => 'invoice', 'related_id' => $invoice->id, 'related_type' => 'App\Models\Invoice', 'branch_id' => $invoice->branch_id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors">
                        Create Reminder
                    </a>
                    <a href="{{ route('invoices.pdf', $invoice) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download PDF
                    </a>
                </div>
            </div>

            <!-- Digital Paper Container -->
            <div class="bg-white shadow-2xl rounded-sm overflow-hidden relative print:shadow-none print:w-full">
                <!-- Header Bar -->
                <div class="bg-gray-800 text-white px-8 py-6 flex justify-between items-center">
                    <div class="flex items-center">
                        @if(isset($settings['invoice_logo_light']))
                            <img src="{{ asset('storage/' . $settings['invoice_logo_light']) }}" alt="Logo" class="h-12 mr-4">
                        @else
                            <h2 class="text-2xl font-bold tracking-wider">{{ $settings['company_name'] ?? 'COMPANY' }}</h2>
                        @endif
                    </div>
                    <div class="text-right">
                        <h1 class="text-3xl font-bold tracking-widest uppercase">INVOICE</h1>
                        <p class="text-gray-400 text-sm mt-1">#{{ $invoice->invoice_number }}</p>
                        <div class="mt-2">
                            <span class="px-2 py-1 text-xs font-bold uppercase rounded bg-white text-gray-800">
                                {{ $invoice->status }}
                            </span>
                            @if($invoice->is_recurring)
                                <span class="px-2 py-1 text-xs font-bold uppercase rounded bg-blue-100 text-blue-800 ml-2">
                                    Recurring: {{ ucfirst(str_replace('_', ' ', $invoice->recurring_frequency)) }}
                                </span>
                            @endif
                            @if(auth()->user()->isSuperAdmin() && $invoice->branch)
                                <span class="px-2 py-1 text-xs font-bold uppercase rounded bg-purple-100 text-purple-800 ml-2">
                                    {{ $invoice->branch->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-10 text-gray-800">
                    <!-- Info Section -->
                    <div class="flex justify-between mb-10">
                        <div class="w-1/2 pr-4">
                            <h3 class="text-xs font-bold text-gray-500 uppercase border-b border-gray-200 pb-1 mb-2">From</h3>
                            <div class="text-sm leading-relaxed">
                                <strong class="text-gray-900 text-base">{{ $settings['company_name'] ?? 'My Company' }}</strong><br>
                                {!! nl2br(e($settings['company_address'] ?? '')) !!}<br>
                                {{ $settings['company_email'] ?? '' }}<br>
                                {{ $settings['company_phone'] ?? '' }}<br>
                                {{ $settings['company_website'] ?? '' }}
                                @if(isset($settings['tax_id']))
                                    <br>Tax ID: {{ $settings['tax_id'] }}
                                @endif
                            </div>
                        </div>
                        <div class="w-1/2 pl-4">
                            <h3 class="text-xs font-bold text-gray-500 uppercase border-b border-gray-200 pb-1 mb-2">Bill To</h3>
                            <div class="text-sm leading-relaxed">
                                <strong class="text-gray-900 text-base">{{ $invoice->client->name }}</strong><br>
                                @if($invoice->client->company_name)
                                    {{ $invoice->client->company_name }}<br>
                                @endif
                                {!! nl2br(e($invoice->client->address)) !!}<br>
                                {{ $invoice->client->email }}<br>
                                {{ $invoice->client->website ?? '' }}
                                @if($invoice->client->tax_id)
                                    <br>Tax ID: {{ $invoice->client->tax_id }}
                                @endif
                            </div>
                            
                            <div class="mt-6 flex justify-between">
                                <div>
                                    <h3 class="text-xs font-bold text-gray-500 uppercase border-b border-gray-200 pb-1 mb-1">Issue Date</h3>
                                    <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-gray-500 uppercase border-b border-gray-200 pb-1 mb-1">Due Date</h3>
                                    <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-10">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Description</th>
                                    <th class="text-right py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Qty</th>
                                    <th class="text-right py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Price</th>
                                    <th class="text-right py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $index => $item)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b border-gray-100">
                                        <td class="py-3 px-4 text-sm text-gray-800">
                                            @if($item->title)
                                                <div class="font-bold">{{ $item->title }}</div>
                                            @endif
                                            <div class="text-gray-600">{{ $item->description }}</div>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-600 text-right">{{ $item->quantity }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-600 text-right">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="py-3 px-4 text-sm font-medium text-gray-900 text-right">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="flex justify-end mb-10">
                        <div class="w-full md:w-1/2 lg:w-1/3">
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 font-medium">Subtotal</span>
                                        <span class="text-gray-900 font-bold">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($invoice->subtotal, 2) }}</span>
                                    </div>
                                    @if($invoice->tax > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 font-medium">Tax</span>
                                            <span class="text-gray-900 font-bold">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($invoice->tax, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($invoice->discount > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 font-medium">
                                                Discount 
                                                @if($invoice->discount_type === 'percent')
                                                    ({{ number_format($invoice->discount, 0) }}%)
                                                @endif
                                            </span>
                                            <span class="text-red-600 font-bold">
                                                @if($invoice->discount_type === 'percent')
                                                    -{{ $settings['currency_symbol'] ?? '$' }}{{ number_format(($invoice->subtotal * $invoice->discount / 100), 2) }}
                                                @else
                                                    -{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($invoice->discount, 2) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    <div class="border-t border-gray-200 my-3 pt-3 flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900">Grand Total</span>
                                        <span class="text-xl font-bold text-indigo-600">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($invoice->grand_total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-1">
                                        <span class="text-sm font-medium text-gray-600">Balance Due</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($invoice->balance_due, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($invoice->notes)
                        <div class="bg-gray-50 rounded p-4 mb-8">
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Notes / Terms</h4>
                            <p class="text-sm text-gray-600 whitespace-pre-line">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment History & Actions (Outside Paper) -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-100 dark:border-gray-700 no-print">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Payment History</h3>
                
                @if($invoice->payments->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No payments recorded yet.</p>
                @else
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Method</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($invoice->payments as $payment)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ ucfirst($payment->payment_method) }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-white text-right">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if($invoice->balance_due > 0)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Record Payment</h4>
                        <form action="{{ route('invoices.payments.store', $invoice) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            @csrf
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount</label>
                                <input type="number" name="amount" step="0.01" max="{{ $invoice->balance_due }}" value="{{ $invoice->balance_due }}" class="w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Method</label>
                                <select name="payment_method" class="w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cash">Cash</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                            <div class="md:col-span-1">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-colors">
                                    Record
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
