<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Report - {{ now()->format('d M Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            color: #333;
        }

        .container {
            background-color: white;
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 3px solid #1ABB9C;
            padding-bottom: 20px;
        }

        .company-info h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1ABB9C;
            margin-bottom: 5px;
        }

        .company-info p {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }

        .report-meta {
            text-align: right;
        }

        .report-meta-item {
            margin-bottom: 10px;
        }

        .report-meta-label {
            font-size: 11px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-meta-value {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        /* User Info */
        .user-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .user-info h4 {
            font-size: 12px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .user-info p {
            font-size: 13px;
            color: #333;
            margin: 3px 0;
        }

        /* Stats Section */
        .stats-grid {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-box {
            flex: 1;
            padding: 12px;
            background: #f8f9fa;
            border-left: 4px solid #1ABB9C;
            border-radius: 4px;
        }

        .stat-label {
            font-size: 10px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 800;
            color: #1ABB9C;
            margin-top: 5px;
        }

        /* Table */
        .payments-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        .payments-table thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e3e6ed;
        }

        .payments-table th {
            padding: 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #5e6e82;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .payments-table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 12px;
        }

        .payments-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .badge-failed {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #999;
            font-size: 11px;
        }

        /* Print Styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
        }

        .amount {
            font-weight: 700;
            color: #1ABB9C;
            text-align: right;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>{{ $company['name'] ?? env('APP_NAME', 'VIOMIA') }}</h1>
                <p><strong>Payment Report</strong></p>
                <p>{{ $company['street'] ?? 'KG 12 St' }}, {{ $company['address'] ?? 'Kigali, Rwanda' }}</p>
                <p>Phone: {{ $company['phone'] ?? '+250 788 123 456' }}</p>
            </div>
            <div class="report-meta">
                <div class="report-meta-item">
                    <div class="report-meta-label">Report Date</div>
                    <div class="report-meta-value">{{ now()->format('d M Y H:i A') }}</div>
                </div>
                <div class="report-meta-item">
                    <div class="report-meta-label">Total Payments</div>
                    <div class="report-meta-value">{{ count($payments) }}</div>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="user-info">
            <h4>Account Holder</h4>
            <p><strong>{{ $user->name ?? 'N/A' }}</strong></p>
            <p>Email: {{ $user->email ?? '-' }}</p>
            @if($user->phone ?? null)
                <p>Phone: {{ $user->phone }}</p>
            @endif
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">✓ Successful</div>
                <div class="stat-value">{{ $stats['successful'] ?? 0 }}</div>
            </div>
            <div class="stat-box" style="border-left-color: #f39c12;">
                <div class="stat-label" style="color: #f39c12;">⏳ Pending</div>
                <div class="stat-value" style="color: #f39c12;">{{ $stats['pending'] ?? 0 }}</div>
            </div>
            <div class="stat-box" style="border-left-color: #e74c3c;">
                <div class="stat-label" style="color: #e74c3c;">✗ Failed</div>
                <div class="stat-value" style="color: #e74c3c;">{{ $stats['failed'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Payments Table -->
        <table class="payments-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 15%;">Reference</th>
                    <th style="width: 20%;">Description</th>
                    <th style="width: 15%;">Method</th>
                    <th style="width: 12%; text-align: right;">Amount</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ optional($payment->created_at)->format('d M Y') ?? '-' }}</td>
                        <td><strong>#{{ $payment->reference ?? '-' }}</strong></td>
                        <td>
                            @if($payment->payment_type === 'subscription')
                                Premium Subscription Plan
                            @else
                                Weekly Performance Payment
                            @endif
                        </td>
                        <td>
                            {{ ucfirst(str_replace('_', ' ', $payment->provider ?? '-')) }}
                        </td>
                        <td class="amount">${{ number_format($payment->amount ?? 0, 2) }}</td>
                        <td>
                            @php
                                $status = strtolower($payment->status ?? 'pending');
                            @endphp
                            <span class="badge badge-{{ $status }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #999;">
                            No payments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated report. For support, contact {{ $company['email'] ?? 'support@viomia.com' }}</p>
            <p style="margin-top: 10px;">Generated on {{ now()->format('d M Y \a\t H:i A') }}</p>
        </div>
    </div>
</body>
</html>
