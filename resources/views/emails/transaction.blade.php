<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject ?? 'Transaction Notification' }}</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #1e293b;
            background-color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        td {
            padding: 0;
            vertical-align: top;
        }

        img {
            border: 0;
            display: block;
            max-width: 100%;
            height: auto;
        }

        .wrapper {
            width: 100%;
            background-color: #0f172a;
            padding: 24px 10px;
        }

        .container {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px -20px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255,255,255,0.05);
        }

        /* Banner */
        .banner-wrap {
            position: relative;
            width: 100%;
        }

        .banner {
            width: 100%;
            height: auto;
            display: block;
        }

        .banner-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 70%;
            background: linear-gradient(180deg, transparent 0%, rgba(15,23,42,0.85) 100%);
        }

        .banner-badge-wrap {
            position: absolute;
            bottom: 28px;
            left: 36px;
        }

        .banner-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(16,185,129,0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(16,185,129,0.35);
            color: #6ee7b7;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 8px 18px;
            border-radius: 30px;
            font-family: 'Inter', sans-serif;
        }

        .banner-badge .dot {
            width: 7px;
            height: 7px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(16,185,129,0.6);
        }

        /* Content */
        .content {
            padding: 44px 40px;
        }

        .greeting {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #0f172a;
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.5px;
        }

        .greeting span {
            color: #10b981;
        }

        .subtitle {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 32px;
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        /* Transaction Type Badge */
        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 24px;
            font-family: 'Inter', sans-serif;
        }

        .badge-deposit {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-withdrawal {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .badge-swap {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        /* Amount Display */
        .amount-box {
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 50%, #1e293b 100%);
            border-radius: 20px;
            padding: 36px;
            text-align: center;
            margin-bottom: 32px;
            border: 1px solid rgba(51,65,85,0.5);
            position: relative;
            overflow: hidden;
        }

        .amount-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(16,185,129,0.06) 0%, transparent 60%);
        }

        .amount-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #64748b;
            margin-bottom: 10px;
            position: relative;
            font-family: 'Inter', sans-serif;
        }

        .amount-value {
            font-size: 42px;
            font-weight: 800;
            color: #10b981;
            margin-bottom: 4px;
            position: relative;
            font-family: 'Inter', sans-serif;
            letter-spacing: -1px;
        }

        .amount-crypto {
            font-size: 18px;
            font-weight: 600;
            color: #e2e8f0;
            position: relative;
            font-family: 'Inter', sans-serif;
        }

        .amount-usd {
            font-size: 14px;
            color: #475569;
            margin-top: 8px;
            position: relative;
            font-family: 'Inter', sans-serif;
        }

        /* Details Box */
        .details-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 28px;
            border: 1px solid #f1f5f9;
        }

        .details-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #475569;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid #e2e8f0;
            font-family: 'Inter', sans-serif;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
        }

        .detail-value {
            font-size: 13px;
            color: #0f172a;
            font-weight: 600;
            text-align: right;
            word-break: break-all;
            max-width: 55%;
            font-family: 'Inter', sans-serif;
        }

        .detail-value.crypto {
            font-family: 'SF Mono', 'Courier New', monospace;
            font-size: 12px;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 6px;
            color: #334155;
        }

        .detail-value.status-completed {
            color: #059669;
            background: #ecfdf5;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .detail-value.status-pending {
            color: #b45309;
            background: #fef3c7;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .detail-value.status-failed {
            color: #dc2626;
            background: #fef2f2;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        /* Swap Arrow */
        .swap-arrow {
            text-align: center;
            padding: 16px 0;
        }

        .swap-arrow-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 16px rgba(16,185,129,0.35);
        }

        /* Security Box */
        .security-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef9c3 100%);
            border-left: 4px solid #f59e0b;
            border-radius: 0 16px 16px 0;
            padding: 24px 28px;
            margin: 28px 0;
        }

        .security-title {
            font-weight: 700;
            color: #92400e;
            margin-bottom: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Inter', sans-serif;
        }

        .security-text {
            color: #78350f;
            font-size: 14px;
            line-height: 1.7;
            font-family: 'Inter', sans-serif;
        }

        .security-text a {
            color: #d97706;
            text-decoration: none;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            padding: 40px 40px;
            text-align: center;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            border-top: 1px solid #e2e8f0;
        }

        .footer-brand {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.3px;
        }

        .footer-brand span {
            color: #10b981;
        }

        .footer-text {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 20px;
            font-family: 'Inter', sans-serif;
        }

        .footer-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 20px 0;
        }

        .footer-links {
            margin-bottom: 20px;
        }

        .footer-links a {
            color: #64748b;
            text-decoration: none;
            font-size: 12px;
            margin: 0 14px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #10b981;
        }

        .footer-copyright {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.8;
            font-family: 'Inter', sans-serif;
        }

        @media (max-width: 480px) {
            .wrapper { padding: 12px 6px; }
            .content { padding: 28px 20px; }
            .footer { padding: 28px 20px; }
            .amount-value { font-size: 32px; }
            .detail-row { flex-direction: column; align-items: flex-start; gap: 6px; }
            .detail-value { max-width: 100%; text-align: left; }
            .greeting { font-size: 20px; }
            .banner-badge-wrap { left: 20px; bottom: 20px; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #0f172a; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#0f172a">
        <tr>
            <td align="center" valign="top" style="padding: 24px 10px;">
                <table class="container" width="640" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-radius: 24px; overflow: hidden; box-shadow: 0 25px 80px -20px rgba(0, 0, 0, 0.6); border: 1px solid rgba(255,255,255,0.05);">

                    <!-- Banner Image -->
                    <tr>
                        <td class="banner-wrap" style="position: relative;">
                            <img src="https://covachain.org/uploads/logos/transaction.jpg" alt="Transaction Banner" class="banner" style="width: 100%; height: auto; display: block;">
                            <div class="banner-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; height: 70%; background: linear-gradient(180deg, transparent 0%, rgba(15,23,42,0.85) 100%);"></div>
                            <div class="banner-badge-wrap" style="position: absolute; bottom: 28px; left: 36px;">
                                <div class="banner-badge" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(16,185,129,0.15); backdrop-filter: blur(16px); border: 1px solid rgba(16,185,129,0.35); color: #6ee7b7; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; padding: 8px 18px; border-radius: 30px; font-family: 'Inter', sans-serif;">
                                    <span class="dot" style="width: 7px; height: 7px; background: #10b981; border-radius: 50%; box-shadow: 0 0 8px rgba(16,185,129,0.6);"></span>
                                    Transaction
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content" style="padding: 44px 40px;">

                            @php
                                $badgeClass = match($type ?? 'deposit') {
                                    'deposit' => 'badge-deposit',
                                    'withdrawal' => 'badge-withdrawal',
                                    'swap' => 'badge-swap',
                                    default => 'badge-deposit'
                                };
                                $typeLabel = match($type ?? 'deposit') {
                                    'deposit' => 'Deposit',
                                    'withdrawal' => 'Withdrawal',
                                    'swap' => 'Crypto Swap',
                                    default => 'Transaction'
                                };
                            @endphp

                            <div class="type-badge {{ $badgeClass }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; border-radius: 30px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 24px; font-family: 'Inter', sans-serif;">
                                {{ $typeLabel }}
                            </div>

                            <div class="greeting" style="font-size: 24px; font-weight: 700; margin-bottom: 8px; color: #0f172a; font-family: 'Inter', sans-serif; letter-spacing: -0.5px;">
                                Hello <span style="color: #10b981;">{{ $transaction->user->name ?? 'Valued Member' }}</span>,
                            </div>

                            <div class="subtitle" style="color: #64748b; font-size: 15px; margin-bottom: 32px; font-family: 'Inter', sans-serif; line-height: 1.6;">
                                @if(($type ?? 'deposit') === 'swap')
                                    Your crypto swap has been completed successfully.
                                @elseif(($type ?? 'deposit') === 'deposit')
                                    Crypto has been received and credited to your account.
                                @elseif(($type ?? 'deposit') === 'withdrawal')
                                    Your withdrawal has been processed and sent.
                                @else
                                    Your transaction has been processed.
                                @endif
                            </div>

                            <!-- Amount Display -->
                            <div class="amount-box" style="background: linear-gradient(145deg, #1e293b 0%, #0f172a 50%, #1e293b 100%); border-radius: 20px; padding: 36px; text-align: center; margin-bottom: 32px; border: 1px solid rgba(51,65,85,0.5); position: relative; overflow: hidden;">
                                <div class="amount-box" style="position: relative; z-index: 1;">
                                    <div class="amount-label" style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #64748b; margin-bottom: 10px; font-family: 'Inter', sans-serif;">
                                        @if(($type ?? 'deposit') === 'swap')
                                            You Received
                                        @elseif(($type ?? 'deposit') === 'deposit')
                                            Amount Received
                                        @else
                                            Amount Sent
                                        @endif
                                    </div>
                                    <div class="amount-value" style="font-size: 42px; font-weight: 800; color: #10b981; margin-bottom: 4px; font-family: 'Inter', sans-serif; letter-spacing: -1px;">
                                        @if(($type ?? 'deposit') === 'swap')
                                            {{ number_format($amount_out ?? 0, 8) }}
                                        @else
                                            {{ number_format($amount ?? 0, 8) }}
                                        @endif
                                    </div>
                                    <div class="amount-crypto" style="font-size: 18px; font-weight: 600; color: #e2e8f0; font-family: 'Inter', sans-serif;">
                                        @if(($type ?? 'deposit') === 'swap')
                                            {{ $to_crypto ?? 'BTC' }}
                                        @else
                                            {{ $crypto ?? 'BTC' }}
                                        @endif
                                    </div>
                                    @if(isset($usd_value) && $usd_value > 0)
                                    <div class="amount-usd" style="font-size: 14px; color: #475569; margin-top: 8px; font-family: 'Inter', sans-serif;">
                                        ≈ ${{ number_format($usd_value, 2) }} USD
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Transaction Details -->
                            <div class="details-box" style="background: #f8fafc; border-radius: 16px; padding: 28px; margin-bottom: 28px; border: 1px solid #f1f5f9;">
                                <div class="details-title" style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #475569; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 2px solid #e2e8f0; font-family: 'Inter', sans-serif;">
                                    Transaction Details
                                </div>

                                @if(($type ?? 'deposit') === 'swap')
                                    <div class="detail-row" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                                        <span class="detail-label" style="font-size: 13px; color: #64748b; font-weight: 500; font-family: 'Inter', sans-serif;">From</span>
                                        <span class="detail-value crypto" style="font-size: 13px; color: #0f172a; font-weight: 600; text-align: right; word-break: break-all; max-width: 55%; font-family: 'SF Mono', 'Courier New', monospace; font-size: 12px; background: #f1f5f9; padding: 4px 10px; border-radius: 6px; color: #334155;">{{ number_format($amount_in ?? 0, 8) }} {{ $from_crypto ?? 'BTC' }}</span>
                                    </div>

                                    <div class="swap-arrow" style="text-align: center; padding: 16px 0;">
                                        <div class="swap-arrow-icon" style="width: 44px; height: 44px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 20px; box-shadow: 0 4px 16px rgba(16,185,129,0.35);">&#8595;</div>
                                    </div>

                                    <div class="detail-row" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                                        <span class="detail-label" style="font-size: 13px; color: #64748b; font-weight: 500; font-family: 'Inter', sans-serif;">To</span>
                                        <span class="detail-value crypto" style="font-size: 13px; color: #0f172a; font-weight: 600; text-align: right; word-break: break-all; max-width: 55%; font-family: 'SF Mono', 'Courier New', monospace; font-size: 12px; background: #f1f5f9; padding: 4px 10px; border-radius: 6px; color: #334155;">{{ number_format($amount_out ?? 0, 8) }} {{ $to_crypto ?? 'ETH' }}</span>
                                    </div>

                                    <div class="detail-row" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                                        <span class="detail-label" style="font-size: 13px; color: #64748b; font-weight: 500; font-family: 'Inter', sans-serif;">Exchange Rate</span>
                                        <span class="detail-value" style="font-size: 13px; color: #0f172a; font-weight: 600; text-align: right; word-break: break-all; max-width: 55%; font-family: 'Inter', sans-serif;">1 {{ $from_crypto ?? 'BTC' }} = {{ number_format($rate ?? 0, 8) }} {{ $to_crypto ?? 'ETH' }}</span>
                                    </div>
                                @else
                                    <div class="detail-row" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                                        <span class="detail-label" style="font-size: 13px; color: #64748b; font-weight: 500; font-family: 'Inter', sans-serif;">Amount</span>
                                        <span class="detail-value crypto" style="font-size: 13px; color: #0f172a; font-weight: 600; text-align: right; word-break: break-all; max-width: 55%; font-family: 'SF Mono', 'Courier New', monospace; font-size: 12px; background: #f1f5f9; padding: 4px 10px; border-radius: 6px; color: #334155;">{{ number_format($amount ?? 0, 8) }} {{ $crypto ?? 'BTC' }}</span>
                                    </div>

                                    <div class="detail-row" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                                        <span class="detail-label" style="font-size: 13px; color: #64748b; font-weight: 500; font-family: 'Inter', sans-serif;">Status</span>
                                        <span class="detail-value status-{{ $status ?? 'completed' }}" style="font-size: 13px; color: #0f172a; font-weight: 600; text-align: right; word-break: break-all; max-width: 55%; font-family: 'Inter', sans-serif;">{{ ucfirst($status ?? 'completed') }}</span>
                                    </div>
                                @endif

                                <div class="detail-row" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0;">
                                    <span class="detail-label" style="font-size: 13px; color: #64748b; font-weight: 500; font-family: 'Inter', sans-serif;">Date & Time</span>
                                    <span class="detail-value" style="font-size: 13px; color: #0f172a; font-weight: 600; text-align: right; word-break: break-all; max-width: 55%; font-family: 'Inter', sans-serif;">{{ isset($created_at) ? $created_at->format('M d, Y H:i:s') : now()->format('M d, Y H:i:s') }}</span>
                                </div>
                            </div>

                            <!-- Security Notice -->
                            <div class="security-box" style="background: linear-gradient(135deg, #fffbeb 0%, #fef9c3 100%); border-left: 4px solid #f59e0b; border-radius: 0 16px 16px 0; padding: 24px 28px; margin: 28px 0;">
                                <div class="security-title" style="font-weight: 700; color: #92400e; margin-bottom: 10px; font-size: 14px; display: flex; align-items: center; gap: 10px; font-family: 'Inter', sans-serif;">
                                    <span style="font-size: 18px;">&#128274;</span>
                                    Security Notice
                                </div>
                                <div class="security-text" style="color: #78350f; font-size: 14px; line-height: 1.7; font-family: 'Inter', sans-serif;">
                                    If you did not authorize this transaction or notice unusual activity, <strong>contact our support team immediately</strong> at <a href="mailto:{{ $supportEmail ?? 'support@covachain.org' }}" style="color: #d97706; text-decoration: none; font-weight: 600;">{{ $supportEmail ?? 'support@covachain.org' }}</a>
                                </div>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer" style="padding: 40px 40px; text-align: center; background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%); border-top: 1px solid #e2e8f0;">
                            <div class="footer-brand" style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 6px; font-family: 'Inter', sans-serif; letter-spacing: -0.3px;">
                                {{ $companyName ?? 'CovaChain' }}
                            </div>
                            <div class="footer-text" style="font-size: 13px; color: #94a3b8; margin-bottom: 20px; font-family: 'Inter', sans-serif;">Your trusted partner in crypto</div>

                            <div class="footer-divider" style="height: 1px; background: #e2e8f0; margin: 20px 0;"></div>

                            <div class="footer-links" style="margin-bottom: 20px;">
                                <a href="https://covachain.org" style="color: #64748b; text-decoration: none; font-size: 12px; margin: 0 14px; font-weight: 500; font-family: 'Inter', sans-serif;">Website</a>
                                <a href="https://covachain.org/privacy" style="color: #64748b; text-decoration: none; font-size: 12px; margin: 0 14px; font-weight: 500; font-family: 'Inter', sans-serif;">Privacy</a>
                                <a href="https://covachain.org/terms" style="color: #64748b; text-decoration: none; font-size: 12px; margin: 0 14px; font-weight: 500; font-family: 'Inter', sans-serif;">Terms</a>
                                <a href="https://covachain.org/contact" style="color: #64748b; text-decoration: none; font-size: 12px; margin: 0 14px; font-weight: 500; font-family: 'Inter', sans-serif;">Contact</a>
                            </div>

                            <div class="footer-copyright" style="font-size: 11px; color: #94a3b8; line-height: 1.8; font-family: 'Inter', sans-serif;">
                                &copy; {{ date('Y') }} {{ $companyName ?? 'CovaChain' }}. All rights reserved.<br>
                                This is an automated transaction notification.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>