<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        @page {
            /* A3 Portrait width is 297mm, same as A4 Landscape width. 
               Height is 420mm, much taller than A4 Landscape (210mm).
               This satisfies "increase page height" while keeping "web view width". */
            size: A3 portrait;
            margin: 0;
        }

        body {
            /* Dejavu Sans is required for UTF-8 characters like Rupee symbol in DomPDF */
            font-family: 'DejaVu Sans', sans-serif;
            color: #1f2937;
            /* gray-800 */
            line-height: 1.5;
            font-size: 14px;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* Utility Colors matching Tailwind */
        .text-gray-500 {
            color: #6b7280;
        }

        .text-gray-600 {
            color: #4b5563;
        }

        .text-gray-700 {
            color: #374151;
        }

        .text-gray-800 {
            color: #1f2937;
        }

        .text-gray-900 {
            color: #111827;
        }

        .text-red-600 {
            color: #dc2626;
        }

        .text-indigo-600 {
            color: #4f46e5;
        }

        .bg-gray-50 {
            background-color: #f9fafb;
        }

        .bg-gray-800 {
            background-color: #1f2937;
        }

        .header-bar {
            background-color: #1f2937;
            /* bg-gray-800 */
            color: #ffffff;
            padding: 30px 50px;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-img {
            max-height: 60px;
            object-fit: contain;
        }

        .invoice-label {
            font-size: 30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            text-align: right;
            margin: 0;
            color: #ffffff;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .invoice-number {
            color: #9ca3af;
            /* gray-400 */
            font-size: 14px;
            text-align: right;
            margin-top: 4px;
            font-family: 'DejaVu Sans', sans-serif;
        }

        /* Stamp Style */
        .paid-stamp {
            position: absolute;
            right: 50px;
            top: 15px;
            max-width: 120px;
            max-height: 120px;
            opacity: 0.85;
            transform: rotate(-15deg);
            z-index: 10;
        }

        .container {
            padding: 40px 50px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 40px;
        }

        .info-table td {
            vertical-align: top;
            width: 50%;
        }

        .section-label {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            /* gray-500 */
            border-bottom: 1px solid #e5e7eb;
            /* gray-200 */
            padding-bottom: 4px;
            margin-bottom: 8px;
            width: 90%;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .address-box {
            font-size: 14px;
            line-height: 1.6;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .company-name {
            font-weight: bold;
            font-size: 16px;
            color: #111827;
            /* gray-900 */
            font-family: 'DejaVu Sans', sans-serif;
        }

        .dates-table {
            margin-top: 20px;
            width: 90%;
        }

        .dates-table td {
            vertical-align: top;
            padding-bottom: 10px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4b5563;
            /* gray-600 */
            background-color: #f9fafb;
            /* gray-50 */
            border-bottom: 2px solid #e5e7eb;
            /* gray-200 */
            letter-spacing: 0.05em;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .items-table td {
            padding: 12px 16px;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
            /* gray-100 */
            color: #1f2937;
            /* gray-800 */
            font-family: 'DejaVu Sans', sans-serif;
        }

        .items-table tr:nth-child(even) {
            background-color: #ffffff;
            /* bg-white */
        }

        .items-table tr:nth-child(odd) {
            background-color: #f9fafb;
            /* bg-gray-50 from web view logic */
        }

        .items-table .text-right {
            text-align: right;
        }

        .items-table .font-bold {
            font-weight: bold;
        }

        .items-table .font-medium {
            font-weight: 500;
        }

        .items-table .text-sm {
            font-size: 13px;
        }

        /* Totals */
        .totals-container {
            width: 100%;
        }

        .totals-table {
            width: 350px;
            margin-left: auto;
            background-color: #f9fafb;
            /* gray-50 */
            border: 1px solid #f3f4f6;
            /* gray-100 */
            border-radius: 8px;
            /* Not supported well in DomPDF table border, but ok */
            padding: 20px;
            page-break-inside: avoid;
        }

        .totals-row td {
            padding: 5px 0;
            font-size: 13px;
        }

        .totals-label {
            color: #4b5563;
            /* gray-600 */
            font-weight: 500;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .totals-val {
            text-align: right;
            font-weight: bold;
            color: #111827;
            /* gray-900 */
            font-family: 'DejaVu Sans', sans-serif;
        }

        .grand-total-row td {
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            /* gray-200 */
            margin-top: 12px;
        }

        .grand-total-label {
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .grand-total-val {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
            /* indigo-600 */
            text-align: right;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .balance-row td {
            padding-top: 5px;
        }

        .balance-label {
            font-size: 13px;
            font-weight: 500;
            color: #4b5563;
        }

        .balance-val {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            text-align: right;
            font-family: 'DejaVu Sans', sans-serif;
        }

        /* Notes */
        .notes-box {
            background-color: #f9fafb;
            /* gray-50 */
            padding: 16px;
            border-radius: 4px;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .notes-header {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            /* gray-500 */
            margin-bottom: 8px;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .notes-text {
            font-size: 13px;
            color: #4b5563;
            /* gray-600 */
            white-space: pre-line;
            font-family: 'DejaVu Sans', sans-serif;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header-bar">
        @if($invoice->status === 'paid')
            <img src="{{ public_path('images/paid.webp') }}" class="paid-stamp" alt="PAID">
        @endif

        <table class="header-table">
            <tr>
                <!-- Logo / Company Name -->
                <td style="vertical-align: middle;">
                    @if(isset($settings['invoice_logo_dark']))
                        <img src="{{ public_path('storage/' . $settings['invoice_logo_dark']) }}" class="logo-img"
                            alt="Logo">
                    @elseif(isset($settings['invoice_logo_light']))
                        <img src="{{ public_path('storage/' . $settings['invoice_logo_light']) }}" class="logo-img"
                            alt="Logo">
                    @else
                        <h2 style="font-size: 24px; font-weight: bold; margin: 0;">
                            {{ $settings['company_name'] ?? 'COMPANY' }}
                        </h2>
                    @endif
                </td>

                <!-- Invoice Info -->
                <td style="vertical-align: middle; text-align: right;">
                    <div class="invoice-label">INVOICE</div>
                    <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Main Content -->
    <div class="container">

        <!-- From / To Section -->
        <table class="info-table">
            <tr>
                <td style="padding-right: 30px;">
                    <div class="section-label">From</div>
                    <div class="address-box">
                        <div class="company-name">{{ $settings['company_name'] ?? 'My Company' }}</div>
                        <div class="text-gray-600">
                            {!! nl2br(e($settings['company_address'] ?? '')) !!}<br>
                            {{ $settings['company_email'] ?? '' }}<br>
                            {{ $settings['company_phone'] ?? '' }}<br>
                            {{ $settings['company_website'] ?? '' }}
                            @if(isset($settings['tax_id']))
                                <br>Tax ID: {{ $settings['tax_id'] }}
                            @endif
                        </div>
                    </div>
                </td>
                <td style="padding-left: 10px;">
                    <div class="section-label">Bill To</div>
                    <div class="address-box">
                        <div class="company-name">{{ $invoice->client->name }}</div>
                        <div class="text-gray-600">
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

                        <table class="dates-table">
                            <tr>
                                <td>
                                    <div class="section-label" style="border: none; margin: 0; padding: 0;">Issue Date
                                    </div>
                                    <div style="font-weight: 500; font-size: 13px;">
                                        {{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="section-label" style="border: none; margin: 0; padding: 0;">Due Date
                                    </div>
                                    <div style="font-weight: 500; font-size: 13px;">
                                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                    <tr class="{{ $index % 2 === 0 ? '' : 'bg-gray-50' }}"
                        style="background-color: {{ $index % 2 !== 0 ? '#f9fafb' : '#ffffff' }}">
                        <td>
                            @if($item->title)
                                <div class="font-bold">{{ $item->title }}</div>
                            @endif
                            <div class="text-gray-600">{{ $item->description }}</div>
                        </td>
                        <td class="text-right text-gray-600">{{ $item->quantity }}</td>
                        <td class="text-right text-gray-600">
                            {{ \App\Models\Setting::formatCurrency($item->unit_price, $settings) }}
                        </td>
                        <td class="text-right font-medium text-gray-900">
                            {{ \App\Models\Setting::formatCurrency($item->total, $settings) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-container">
            <table class="totals-table">
                <tr class="totals-row">
                    <td class="totals-label">Subtotal</td>
                    <td class="totals-val">{{ \App\Models\Setting::formatCurrency($invoice->subtotal, $settings) }}</td>
                </tr>

                @if($invoice->tax > 0)
                    <tr class="totals-row">
                        <td class="totals-label">Tax</td>
                        <td class="totals-val">{{ \App\Models\Setting::formatCurrency($invoice->tax, $settings) }}</td>
                    </tr>
                @endif

                @if($invoice->discount > 0)
                    <tr class="totals-row">
                        <td class="totals-label">
                            Discount
                            @if($invoice->discount_type === 'percent')
                                ({{ number_format($invoice->discount, 0) }}%)
                            @endif
                        </td>
                        <td class="totals-val text-red-600">
                            @if($invoice->discount_type === 'percent')
                                -{{ \App\Models\Setting::formatCurrency(($invoice->subtotal * $invoice->discount / 100), $settings) }}
                            @else
                                -{{ \App\Models\Setting::formatCurrency($invoice->discount, $settings) }}
                            @endif
                        </td>
                    </tr>
                @endif

                <tr class="grand-total-row">
                    <td class="grand-total-label">Grand Total</td>
                    <td class="grand-total-val">
                        {{ \App\Models\Setting::formatCurrency($invoice->grand_total, $settings) }}
                    </td>
                </tr>

                <tr class="balance-row">
                    <td class="balance-label">Balance Due</td>
                    <td class="balance-val">{{ \App\Models\Setting::formatCurrency($invoice->balance_due, $settings) }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
            <div class="notes-box">
                <div class="notes-header">Notes / Terms</div>
                <div class="notes-text">{!! nl2br(e($invoice->notes)) !!}</div>
            </div>
        @endif

    </div>

</body>

</html>