<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payroll->user->name }} - {{ $payroll->salary_month->format('F Y') }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #4f46e5;
        }
        .meta {
            margin-bottom: 30px;
        }
        .meta table {
            width: 100%;
        }
        .meta td {
            vertical-align: top;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .salary-table th, .salary-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .salary-table th {
            text-align: left;
            background-color: #f9fafb;
        }
        .salary-table td.amount {
            text-align: right;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            color: #4f46e5;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.8em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payslip</h1>
            <p>{{ config('app.name') }}</p>
        </div>

        <div class="meta">
            <table>
                <tr>
                    <td width="50%">
                        <strong>Employee Details:</strong><br>
                        {{ $payroll->user->name }}<br>
                        {{ $payroll->user->job_title ?? 'No Job Title' }}<br>
                        {{ $payroll->user->email }}
                    </td>
                    <td width="50%" style="text-align: right;">
                        <strong>Payslip For:</strong> {{ $payroll->salary_month->format('F Y') }}<br>
                        <strong>Status:</strong> {{ ucfirst($payroll->status) }}<br>
                        @if($payroll->payment_date)
                            <strong>Paid On:</strong> {{ $payroll->payment_date->format('M d, Y') }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <table class="salary-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Base Salary</td>
                    <td class="amount">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($payroll->base_salary, 2) }}</td>
                </tr>
                <tr>
                    <td>Bonus</td>
                    <td class="amount text-green">+ {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($payroll->bonus, 2) }}</td>
                </tr>
                <tr>
                    <td>Deductions</td>
                    <td class="amount text-red">- {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($payroll->deductions, 2) }}</td>
                </tr>
                <tr style="background-color: #f3f4f6;">
                    <td><strong>Net Salary</strong></td>
                    <td class="amount total">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            </tbody>
        </table>

        @if($payroll->notes)
            <div style="margin-top: 20px; padding: 15px; background-color: #f9fafb; border-radius: 5px;">
                <strong>Notes:</strong><br>
                {{ $payroll->notes }}
            </div>
        @endif

        <div class="footer">
            <p>This is a computer-generated document and does not require a signature.</p>
        </div>
    </div>
</body>
</html>
