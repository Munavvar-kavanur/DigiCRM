<x-app-layout>
    <div class="py-12">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header & Actions -->
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Estimate Details') }}
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('estimates.edit', $estimate) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors">
                        Edit
                    </a>
                    <a href="{{ route('reminders.create', ['type' => 'estimate', 'related_id' => $estimate->id, 'related_type' => 'App\Models\Estimate', 'branch_id' => $estimate->branch_id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors">
                        Create Reminder
                    </a>
                    <a href="{{ route('estimates.pdf', $estimate) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>

            <!-- Digital Paper Container -->
            <div class="bg-white shadow-2xl rounded-sm overflow-hidden relative print:shadow-none print:w-full">
                <!-- Header Bar -->
                <div class="bg-gray-800 text-white px-8 py-6 flex justify-between items-center">
                    <div class="flex items-center">
                        @if(isset($settings['invoice_logo_dark']))
                            <img src="{{ asset('storage/' . $settings['invoice_logo_dark']) }}" alt="Logo" class="h-12 mr-4">
                        @elseif(isset($settings['invoice_logo_light']))
                            <img src="{{ asset('storage/' . $settings['invoice_logo_light']) }}" alt="Logo" class="h-12 mr-4">
                        @else
                            <h2 class="text-2xl font-bold tracking-wider">{{ $settings['company_name'] ?? 'COMPANY' }}</h2>
                        @endif
                    </div>
                    <div class="text-right">
                        <h1 class="text-3xl font-bold tracking-widest uppercase">ESTIMATE</h1>
                        <p class="text-gray-400 text-sm mt-1">#{{ $estimate->id }}</p>
                        <div class="mt-2">
                            <span class="px-2 py-1 text-xs font-bold uppercase rounded bg-white text-gray-800">
                                {{ $estimate->status }}
                            </span>
                            @if(auth()->user()->isSuperAdmin() && $estimate->branch)
                                <span class="px-2 py-1 text-xs font-bold uppercase rounded bg-purple-100 text-purple-800 ml-2">
                                    {{ $estimate->branch->name }}
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
                            </div>
                        </div>
                        <div class="w-1/2 pl-4">
                            <h3 class="text-xs font-bold text-gray-500 uppercase border-b border-gray-200 pb-1 mb-2">Estimate For</h3>
                            <div class="text-sm leading-relaxed">
                                <strong class="text-gray-900 text-base">{{ $estimate->client->name }}</strong><br>
                                @if($estimate->client->company_name)
                                    {{ $estimate->client->company_name }}<br>
                                @endif
                                {!! nl2br(e($estimate->client->address)) !!}<br>
                                {{ $estimate->client->email }}<br>
                                {{ $estimate->client->website ?? '' }}
                            </div>
                            
                            <div class="mt-6 flex justify-between">
                                <div>
                                    <h3 class="text-xs font-bold text-gray-500 uppercase border-b border-gray-200 pb-1 mb-1">Valid Until</h3>
                                    <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($estimate->valid_until)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-10">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Item</th>
                                    <th class="text-center py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Qty</th>
                                    <th class="text-right py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Price</th>
                                    <th class="text-right py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estimate->items as $item)
                                    <tr class="bg-white border-b border-gray-100">
                                        <td class="py-3 px-4 text-sm text-gray-800">
                                            @if($item->title)
                                                <div class="font-bold text-gray-900">{{ $item->title }}</div>
                                            @endif
                                            <div class="text-gray-600">{{ $item->description }}</div>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-800 text-center">{{ $item->quantity }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-800 text-right">{{ \App\Models\Setting::formatCurrency($item->unit_price, $settings) }}</td>
                                        <td class="py-3 px-4 text-sm font-medium text-gray-900 text-right">{{ \App\Models\Setting::formatCurrency($item->total, $settings) }}</td>
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
                                        <span class="text-gray-900 font-bold">{{ \App\Models\Setting::formatCurrency($estimate->subtotal, $settings) }}</span>
                                    </div>
                                    @if($estimate->tax > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 font-medium">Tax ({{ $estimate->tax }}%)</span>
                                            <span class="text-gray-900 font-bold">{{ \App\Models\Setting::formatCurrency(($estimate->subtotal * $estimate->tax / 100), $settings) }}</span>
                                        </div>
                                    @endif
                                    @if($estimate->discount > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 font-medium">
                                                Discount 
                                                @if($estimate->discount_type == 'percent')
                                                    ({{ $estimate->discount }}%)
                                                @else
                                                    (Fixed)
                                                @endif
                                            </span>
                                            <span class="text-red-600 font-bold">
                                                @if($estimate->discount_type == 'percent')
                                                    -{{ \App\Models\Setting::formatCurrency(($estimate->subtotal * $estimate->discount / 100), $settings) }}
                                                @else
                                                    -{{ \App\Models\Setting::formatCurrency($estimate->discount, $settings) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    <div class="border-t border-gray-200 my-3 pt-3 flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900">Total Amount</span>
                                        <span class="text-xl font-bold text-indigo-600">{{ \App\Models\Setting::formatCurrency($estimate->total_amount, $settings) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes & Terms -->
                    @if($estimate->notes || $estimate->terms)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-200 pt-8">
                            @if($estimate->notes)
                                <div>
                                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Notes</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($estimate->notes)) !!}</p>
                                </div>
                            @endif
                            @if($estimate->terms)
                                <div>
                                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Terms & Conditions</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($estimate->terms)) !!}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
