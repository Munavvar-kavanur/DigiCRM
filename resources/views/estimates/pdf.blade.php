<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Estimate #{{ $estimate->estimate_number }}</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #444;
            line-height: 1.5;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }
        .header-bar {
            background-color: #1a202c;
            color: #fff;
            padding: 40px;
            border-bottom: 4px solid #4a5568;
        }
        .container {
            padding: 40px;
        }
        .header-table {
            width: 100%;
        }
        .header-table td {
            vertical-align: top;
        }
        .logo-container img {
            max-height: 70px;
            object-fit: contain;
        }
        .invoice-title {
            text-align: right;
            font-size: 32px;
            font-weight: 300;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
            color: #fff;
        }
        .invoice-meta {
            text-align: right;
            margin-top: 10px;
        }
        .meta-item {
            margin-bottom: 2px;
            font-size: 13px;
        }
        .meta-label {
            color: #a0aec0;
            margin-right: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 40px;
            margin-top: 20px;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
            padding-right: 20px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #718096;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 10px;
            width: 90%;
        }
        .address-block {
            font-size: 13px;
            line-height: 1.6;
            color: #2d3748;
        }
        .address-block strong {
            font-size: 14px;
            color: #1a202c;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #cbd5e0;
        }
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #edf2f7;
            color: #2d3748;
        }
        .items-table tr:nth-child(even) {
            background-color: #fbfdff;
        }
        .items-table .text-right {
            text-align: right;
        }
        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .totals-table td {
            padding: 8px 0;
            border-bottom: 1px solid #edf2f7;
        }
        .totals-table .label {
            font-weight: 600;
            color: #718096;
            font-size: 12px;
        }
        .totals-table .amount {
            text-align: right;
            font-weight: 600;
            color: #2d3748;
        }
        .grand-total-row td {
            border-top: 2px solid #1a202c;
            border-bottom: none;
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .grand-total-label {
            font-size: 14px;
            font-weight: bold;
            color: #1a202c;
            text-transform: uppercase;
        }
        .grand-total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #1a202c;
            text-align: right;
        }
        .notes-section {
            margin-top: 40px;
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #cbd5e0;
            page-break-inside: avoid;
        }
        .notes-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #718096;
            margin-bottom: 8px;
        }
        .notes-content {
            font-size: 12px;
            color: #4a5568;
            line-height: 1.6;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 11px;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header-bar">
        <table class="header-table">
            <tr>
                <td class="logo-container">
                    @if(isset($settings['invoice_logo_dark']))
                        <img src="{{ public_path('storage/' . $settings['invoice_logo_dark']) }}" alt="Logo">
                    @elseif(isset($settings['invoice_logo_light']))
                        <img src="{{ public_path('storage/' . $settings['invoice_logo_light']) }}" alt="Logo">
                    @else
                        <h2 style="margin:0; font-size: 24px;">{{ $settings['company_name'] ?? 'COMPANY' }}</h2>
                    @endif
                </td>
                <td style="text-align: right;">
                    <h1 class="invoice-title">ESTIMATE</h1>
                    <div class="invoice-meta">
                        <div class="meta-item">
                            <span class="meta-label">Estimate #</span>
                            <span style="font-weight: bold;">{{ $estimate->estimate_number }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Date</span>
                            <span>{{ \Carbon\Carbon::parse($estimate->created_at)->format('M d, Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Valid Until</span>
                            <span>{{ \Carbon\Carbon::parse($estimate->valid_until)->format('M d, Y') }}</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="container">
        <table class="info-table">
            <tr>
                <td>
                    <div class="section-title">From</div>
                    <div class="address-block">
                        <strong>{{ $settings['company_name'] ?? 'My Company' }}</strong><br>
                        {!! nl2br(e($settings['company_address'] ?? '')) !!}<br>
                        {{ $settings['company_email'] ?? '' }}<br>
                        {{ $settings['company_phone'] ?? '' }}<br>
                        {{ $settings['company_website'] ?? '' }}
                        @if(isset($settings['tax_id']))
                            <br>Tax ID: {{ $settings['tax_id'] }}
                        @endif
                    </div>
                </td>
                <td>
                    <div class="section-title">Bill To</div>
                    <div class="address-block">
                        <strong>{{ $estimate->client->name }}</strong><br>
                        @if($estimate->client->company_name)
                            {{ $estimate->client->company_name }}<br>
                        @endif
                        {!! nl2br(e($estimate->client->address)) !!}<br>
                        {{ $estimate->client->email }}<br>
                        {{ $estimate->client->website ?? '' }}
                        @if($estimate->client->tax_id)
                            <br>Tax ID: {{ $estimate->client->tax_id }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estimate->items as $item)
                    <tr>
                        <td>
                            @if($item->title)
                                <div style="font-weight: bold; color: #1a202c; margin-bottom: 4px;">{{ $item->title }}</div>
                            @endif
                            <div style="color: #4a5568;">{{ $item->description }}</div>
                        </td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right" style="font-weight: bold;">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="amount">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($estimate->subtotal, 2) }}</td>
            </tr>
            @if($estimate->tax > 0)
            <tr>
                <td class="label">Tax ({{ $estimate->tax }}%)</td>
                <td class="amount">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format(($estimate->subtotal * $estimate->tax / 100), 2) }}</td>
            </tr>
            @endif
            @if($estimate->discount > 0)
            <tr>
                <td class="label">
                    Discount
                    @if($estimate->discount_type == 'percent')
                        ({{ $estimate->discount }}%)
                    @else
                        (Fixed)
                    @endif
                </td>
                <td class="amount" style="color: #e53e3e;">
                    -{{ $settings['currency_symbol'] ?? '$' }}
                    @if($estimate->discount_type == 'percent')
                        {{ number_format(($estimate->subtotal * $estimate->discount / 100), 2) }}
                    @else
                        {{ number_format($estimate->discount, 2) }}
                    @endif
                </td>
            </tr>
            @endif
            <tr class="grand-total-row">
                <td class="grand-total-label">Total Amount</td>
                <td class="grand-total-amount">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($estimate->total_amount, 2) }}</td>
            </tr>
        </table>

        @if($estimate->notes)
            <div class="notes-section">
                <div class="notes-title">Notes / Terms</div>
                <div class="notes-content">{!! nl2br(e($estimate->notes)) !!}</div>
            </div>
        @endif

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>{{ $settings['company_name'] ?? '' }} &bull; {{ $settings['company_email'] ?? '' }}</p>
        </div>
    </div>
</body>
</html>
