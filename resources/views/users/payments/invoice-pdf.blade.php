<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice — {{ $payment->reference ?? 'N/A' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 18px;
            color: #0d1117;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 90%;
            padding: 12px 16px 80px 16px;
            background: #ffffff;
            display: block;
            position: relative;
        }

        /* Every table fills full available width */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        td, th {
            overflow: hidden;
            word-wrap: break-word;
        }

        /* ── ACCENT BARS ── */
        .bar {
            width: 100%;
            height: 3px;
            background-color: #1ABB9C;
            display: block;
        }
        .bar-top  { margin-bottom: 6px; }
        .bar-bot  { margin-top: 6px; }

        /* ── HEADER ── */
        .hdr-logo-cell {
            width: 48%;
            vertical-align: middle;
            padding-bottom: 5px;
        }
        .hdr-title-cell {
            width: 52%;
            text-align: left;
            vertical-align: middle;
            padding-bottom: 5px;
        }
        .logo-img { max-height: 32px; max-width: 100px; display: block; }
        .co-name  { font-size: 16px; font-weight: bold; color: #1ABB9C; margin-bottom: 0px; }
        .co-sub   { font-size: 8px; color: #8a939f; line-height: 1.2; }
        .inv-word {
            font-size: 32px;
            font-weight: bold;
            color: #0d1117;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .inv-word span { color: #1ABB9C; }
        .inv-sub  { font-size: 9px; color: #8a939f; margin-top: 0px; }
        .hdr-rule { border: none; border-bottom: 1px solid #e2e6eb; margin-bottom: 4px; }

        /* ── META STRIP ── */
        .meta-box {
            background-color: #f7f8fa;
            border: 1px solid #e2e6eb;
            padding: 3px 4px;
            vertical-align: top;
        }
        .meta-box + .meta-box { border-left: none; }
        .meta-lbl {
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8a939f;
            margin-bottom: 0px;
        }
        .meta-val  { font-size: 10px; font-weight: bold; color: #0d1117; }
        .meta-teal { font-size: 10px; font-weight: bold; color: #1ABB9C; }
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 2px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-paid, .badge-success { background:#dcfce7; color:#15803d; }
        .badge-pending              { background:#fef9c3; color:#854d0e; }
        .badge-failed               { background:#fee2e2; color:#b91c1c; }

        /* ── BILL TO / PAYMENT ── */
        .info-rule {
            border: none;
            border-top: 1px solid #e2e6eb;
            margin: 6px 0 5px;
        }
        .sec-lbl {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #1ABB9C;
            padding-bottom: 3px;
            border-bottom: 1px solid #e2e6eb;
            margin-bottom: 4px;
        }
        .info-name   { font-size: 11px; font-weight: bold; color: #0d1117; margin: 3px 0 2px; }
        .info-detail { font-size: 9px; color: #555e6e; line-height: 1.5; }

        /* ── ITEMS TABLE ── */
        .items-rule { border: none; border-top: 1px solid #e2e6eb; margin: 8px 0 6px; }
        .tbl-items thead tr { background-color: #0d1117; }
        .tbl-items th {
            padding: 8px 6px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #aaaaaa;
            text-align: left;
        }
        .tbl-items th.r { text-align: right; }
        .tbl-items th.c { text-align: center; }
        .tbl-items td {
            padding: 6px 6px;
            border-bottom: 1px solid #e8eaed;
            vertical-align: top;
            line-height: 1.5;
        }
        .tbl-items tbody { min-height: 350px; display: table-row-group; }
        .tbl-items tbody tr:last-child td { border-bottom: none; }
        .td-name { font-size: 10px; font-weight: bold; color: #0d1117; }
        .td-sub  { font-size: 8px; color: #8a939f; margin-top: 0px; }
        .td-c    { text-align: center; font-size: 10px; color: #555; }
        .td-r    { text-align: right; font-size: 10px; font-weight: bold; color: #0d1117; }

        /* ── TOTALS ── */
        .totals-rule { border: none; border-top: 1px solid #e2e6eb; margin: 6px 0 4px; }
        .tbl-totals {
            /* override width:100% for this one table — nest it in a right-aligned cell */
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .tot-spacer { width: 60%; }
        .tot-label  {
            width: 22%;
            text-align: left;
            padding: 8px 6px;
            font-size: 10px;
            color: #555e6e;
            border-bottom: 1px solid #e8eaed;
        }
        .tot-value  {
            width: 18%;
            text-align: right;
            padding: 8px 6px;
            font-size: 10px;
            color: #555e6e;
            border-bottom: 1px solid #e8eaed;
        }
        .tot-final-label {
            background-color: #1ABB9C;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            border-bottom: none;
        }
        .tot-final-value {
            background-color: #1ABB9C;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 10px 8px;
            text-align: right;
            border-bottom: none;
        }

        /* ── FOOTER ── */
        .footer-wrapper {
            position: absolute;
            bottom: 10px;
            left: 12px;
            right: 12px;
            width: calc(100% - 24px);
        }
        .footer-rule { border: none; border-top: 1px solid #e2e6eb; margin-top: 6px; margin-bottom: 6px; }
        .tbl-footer td { padding-top: 8px; vertical-align: bottom; }
        .sig-lbl {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #8a939f;
        }
        .sig-line {
            border: none;
            border-top: 1px solid #d1d5db;
            width: 50px;
            margin: 4px 0 1px;
        }
        .sig-id   { font-size: 9px; color: #aaaaaa; }
        .footer-r {
            text-align: left;
            font-size: 9px;
            color: #8a939f;
            line-height: 1.2;
            vertical-align: bottom;
        }
    </style>
</head>
<body>

@if(!$payment)
    <div style="text-align:center;padding:80px 40px;">
        <p style="font-weight:bold;font-size:13px;color:#555;margin-bottom:6px;">Invoice Not Found</p>
        <p style="font-size:10px;color:#999;">The requested invoice could not be located.</p>
    </div>
@else
<div class="page">

    {{-- TOP ACCENT --}}
    <div class="bar bar-top"></div>

    {{-- HEADER --}}
    <table style="margin-bottom:0;" >
        <tr>
            <td class="hdr-logo-cell">
                @php $logoPath = public_path('logo.png'); @endphp
                @if(file_exists($logoPath))
                    <img class="logo-img"
                         src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}"
                         alt="Logo">
                         {{-- <div class="co-name" style="font_size:14px">{{ $company['name'] ?? env('APP_NAME','Viomia') }}</div> --}}
                @else
                    <div class="co-name">{{ $company['name'] ?? env('APP_NAME','Viomia') }}</div>
                @endif
                <div class="co-sub" style="margin-top:3px;font-size:12px">
                    {{ $company['street'] ?? 'KG 12 St' }}, {{ $company['address'] ?? 'Kigali, Rwanda' }}<br>
                    {{ $company['email'] ?? 'support@viomia.com' }} &nbsp;&middot;&nbsp; {{ $company['phone'] ?? '+250 788 123 456' }}
                </div>
            </td>
            <td class="hdr-title-cell">
                <div class="inv-word">Invoice<span>.</span></div>
                <div class="inv-sub">{{ $company['name'] ?? env('APP_NAME','Viomia') }} &nbsp;&middot;&nbsp; Digital Services</div>
            </td>
        </tr>
    </table>
    <br><br>
    <hr class="hdr-rule">

    {{-- META STRIP — 4 equal columns --}}
    <table style="margin-bottom:3px;">
        <tr>
            <td class="meta-box" style="width:35%;">
                <div class="meta-lbl">Invoice No.</div>
                <div class="meta-teal">{{ $payment->reference ?? '—' }}</div>
            </td>
            <td class="meta-box" style="width:20%;">
                <div class="meta-lbl">Issue Date</div>
                <div class="meta-val">{{ optional($payment->created_at)->format('d M Y') ?? '—' }}</div>
            </td>
            <td class="meta-box" style="width:20%;">
                <div class="meta-lbl">Type</div>
                <div class="meta-val">
                    {{ ($payment->payment_type ?? '') === 'subscription' ? 'Subscription' : 'Weekly' }}
                </div>
            </td>
            <td class="meta-box" style="width:25%;">
                <div class="meta-lbl">Status</div>
                @php $status = strtolower($payment->status ?? 'pending'); @endphp
                <span class="badge badge-{{ $status }}" style="margin-top:2px;display:inline-block;">{{ ucfirst($status) }}</span>
            </td>
        </tr>
    </table>

    {{-- BILL TO / PAYMENT METHOD --}}
    <table style="margin-bottom:0;">
        <tr>
            <td style="width:50%;vertical-align:top;padding-right:10px;">
                <div class="sec-lbl">Billed To</div>
                <div class="info-name">{{ $user->name ?? 'Customer' }}</div>
                <div class="info-detail">
                    {{ $user->email ?? '—' }}
                    @if($user->phone ?? null)<br>{{ $user->phone }}@endif
                    @if($user->country ?? null)<br>{{ $user->country }}@endif
                </div>
            </td>
            <td style="width:50%;vertical-align:top;">
                <div class="sec-lbl">Payment Method</div>
                <div class="info-name">
                    {{ ucfirst(str_replace('_', ' ', $payment->provider ?? $payment->payment_method ?? '—')) }}
                </div>
                <div class="info-detail">
                    @if(($payment->payment_type ?? '') === 'weekly' && ($payment->momo_phone ?? null))
                        MOMO: {{ $payment->momo_phone }}<br>
                    @endif
                    Ref: {{ $payment->reference ?? '—' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- ITEMS --}}
    <hr class="items-rule">
    <table class="tbl-items" style="margin-bottom:0;">
        <thead>
            <tr>
                <th style="width:54%;">Description</th>
                <th class="c" style="width:10%;">Qty</th>
                <th class="r" style="width:18%;">Unit Price</th>
                <th class="r" style="width:18%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="td-name">
                        @if(($payment->payment_type ?? '') === 'subscription')
                            Premium Subscription Plan
                        @else
                            Weekly Performance Payment
                        @endif
                    </div>
                    <div class="td-sub">
                        @if(($payment->payment_type ?? '') === 'subscription')
                            Full access to all platform features and priority support
                        @else
                            Performance-based weekly settlement
                        @endif
                    </div>
                </td>
                <td class="td-c">1</td>
                <td class="td-r">${{ number_format($payment->amount ?? 0, 2) }}</td>
                <td class="td-r">${{ number_format($payment->amount ?? 0, 2) }}</td>
            </tr><br> {{-- empty row for spacing --}}
            <br> {{-- empty row for spacing --}}
            <br> {{-- empty row for spacing --}}
            <br> {{-- empty row for spacing --}}
            <br> {{-- empty row for spacing --}}
        </tbody>
    </table>

    {{-- TOTALS --}}
    <hr class="totals-rule">
    <table class="tbl-totals" style="margin-bottom:2px;">
        <tr>
            <td class="tot-spacer" rowspan="3"></td>
            <td class="tot-label">Subtotal</td>
            <td class="tot-value">${{ number_format($payment->amount ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="tot-label">Tax (0%)</td>
            <td class="tot-value">$0.00</td>
        </tr>
        <tr>
            <td class="tot-final-label">Total Due</td>
            <td class="tot-final-value">${{ number_format($payment->amount ?? 0, 2) }}</td>
        </tr>
    </table>

    {{-- FOOTER WRAPPER --}}
    <div class="footer-wrapper">
        {{-- FOOTER --}}
        <hr class="footer-rule">
        <table style="margin-bottom:0;">
            <tr>
                <td style="width:50%;vertical-align:bottom;">
                    <div class="sig-lbl">Authorised Signature</div>
                    <hr class="sig-line">
                    <div class="sig-id">{{ $company['signature'] ?? auth()->id() . '-' . date('YmdHis') }}</div>
                </td>
                <td class="footer-r">
                    Generated: {{ now()->format('d M Y · H:i') }}<br>
                    {{ $company['email'] ?? 'support@viomia.com' }} &nbsp;&middot;&nbsp; {{ $company['phone'] ?? '+250 788 123 456' }}
                </td>
            </tr>
        </table>

        {{-- BOTTOM ACCENT --}}
        <div class="bar bar-bot"></div>
    </div>

</div>
@endif

<script>
    @if(request()->get('print') === 'true')
    window.onload = function () { window.print(); };
    @endif
</script>
</body>
</html>