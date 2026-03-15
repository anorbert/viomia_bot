<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1ABB9C 0%, #159d84 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .details-box {
            background-color: #f9f9f9;
            border-left: 4px solid #1ABB9C;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
        }
        .detail-value {
            color: #333;
            font-weight: 500;
        }
        .amount {
            font-size: 24px;
            color: #1ABB9C;
            font-weight: 700;
        }
        .status-badge {
            display: inline-block;
            background-color: #22C55E;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
        }
        .transaction-id-box {
            background-color: #f0f9f7;
            border: 1px solid #1ABB9C;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .transaction-id-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }
        .transaction-id-value {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            color: #1ABB9C;
            font-weight: bold;
            word-break: break-all;
        }
        .cta-section {
            background-color: #f9f9f9;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .button {
            display: inline-block;
            background-color: #1ABB9C;
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            margin: 10px 5px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #159d84;
            text-decoration: none;
        }
        .contact-section {
            background-color: #f5f5f5;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
        }
        .contact-title {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .contact-link {
            display: block;
            margin: 8px 0;
            color: #1ABB9C;
            text-decoration: none;
            font-size: 13px;
        }
        .contact-link:hover {
            text-decoration: underline;
        }
        .footer {
            background-color: #f5f5f5;
            border-top: 1px solid #eee;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .divider {
            height: 1px;
            background-color: #eee;
            margin: 20px 0;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>💳 Payment Confirmed!</h1>
            <p>Your transaction has been successfully processed</p>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                Hi <strong>{{ $user->name }}</strong>,
            </div>

            <p>Thank you for your payment! Your transaction has been successfully confirmed and processed.</p>

            <!-- Transaction Details -->
            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Reference ID</span>
                    <span class="detail-value">{{ $payment->reference }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount</span>
                    <span class="detail-value amount">{{ $payment->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ $payment->created_at->format('l, F j, Y \a\t g:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value"><span class="status-badge">✓ COMPLETED</span></span>
                </div>
                @if($payment->provider)
                <div class="detail-row">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">{{ ucfirst($payment->provider) }}</span>
                </div>
                @endif
                @if($plan)
                <div class="detail-row">
                    <span class="detail-label">Plan</span>
                    <span class="detail-value">{{ $plan->name }}</span>
                </div>
                @endif
            </div>

            <!-- Transaction ID -->
            <div class="transaction-id-box">
                <div class="transaction-id-label">Transaction ID</div>
                <div class="transaction-id-value">{{ $payment->provider_txn_id ?? $payment->reference }}</div>
            </div>

            <!-- What's Next -->
            <div class="divider"></div>
            <h3 style="color: #333;">What's Next?</h3>
            @if($plan)
            <p>Your subscription to <strong>{{ $plan->name }}</strong> is now <strong>active</strong> and you can start using all available features immediately.</p>
            @else
            <p>Your payment has been processed and recorded in your account. You can view your transaction details anytime by logging into your dashboard.</p>
            @endif

            <div class="cta-section">
                <a href="{{ url('/dashboard') }}" class="button">View Dashboard</a>
            </div>

            <!-- Support Section -->
            <div class="divider"></div>
            <h3 style="color: #333;">Need Help?</h3>
            <p>If you have any questions about your payment or need further assistance, we're here to help!</p>
            
            <div class="contact-section">
                <div class="contact-title">📞 Contact Us</div>
                <a href="mailto:support@viomia.com" class="contact-link">📧 Email: support@viomia.com</a>
                <a href="https://viomia.com/support" class="contact-link">💬 Live Chat Support</a>
                <a href="https://wa.me/+250787373722?text=Reference:%20{{ $payment->reference }}" class="contact-link">📱 WhatsApp Support</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0;">
                © {{ date('Y') }} {{ config('app.name', 'Viomia') }}. All rights reserved.<br>
                This is an automated email. Please do not reply directly to this message.
            </p>
        </div>
    </div>
</body>
</html>

