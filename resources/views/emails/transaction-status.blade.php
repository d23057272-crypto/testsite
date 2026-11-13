<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction {{ ucfirst($status) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0f172a;
            color: #1e293b;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper { 
            max-width: 680px; 
            margin: 0 auto; 
            padding: 40px 20px;
            background: #0f172a;
        }
        .container {
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.05);
        }
        .banner {
            position: relative;
            height: 220px;
            background: url('https://covachain.org/uploads/logos/transaction.jpg') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.4) 50%, rgba(16,185,129,0.2) 100%);
        }
        .banner-badge {
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .banner-badge .dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: {{ $status === 'completed' ? '#10b981' : ($status === 'pending' ? '#f59e0b' : '#ef4444') }};
            box-shadow: 0 0 12px {{ $status === 'completed' ? 'rgba(16,185,129,0.6)' : ($status === 'pending' ? 'rgba(245,158,11,0.6)' : 'rgba(239,68,68,0.6)') }};
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.2); }
        }
        .banner-badge span {
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .content { padding: 40px; }
        .greeting {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .greeting span {
            color: #10b981;
        }
        .subtext {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 32px;
        }
        .amount-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 20px;
            padding: 32px;
            text-align: center;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }
        .amount-card::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 60%);
        }
        .amount-card .label {
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            position: relative;
        }
        .amount-card .value {
            color: #fff;
            font-size: 42px;
            font-weight: 800;
            position: relative;
        }
        .amount-card .crypto {
            color: #10b981;
            font-size: 20px;
            font-weight: 600;
            position: relative;
        }
        .details-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }
        .details-card h3 {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .label {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }
        .detail-row .value {
            color: #0f172a;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
            max-width: 60%;
            word-break: break-all;
        }
        .detail-row .value.code {
            font-family: 'SF Mono', Monaco, monospace;
            background: #0f172a;
            color: #10b981;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
        }
        .detail-row .value.badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-completed { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-failed { background: #fee2e2; color: #991b1b; }
        .status-message {
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.7;
        }
        .status-message.pending {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
            color: #92400e;
        }
        .status-message.failed {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .status-message.completed {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #86efac;
            color: #166534;
        }
        .security-box {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
            padding: 24px;
            margin-top: 24px;
            border-left: 4px solid #10b981;
        }
        .security-box h4 {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .security-box p {
            color: #475569;
            font-size: 14px;
            line-height: 1.7;
        }
        .security-box a {
            color: #10b981;
            font-weight: 600;
            text-decoration: none;
        }
        .footer {
            background: #0f172a;
            padding: 40px;
            text-align: center;
            color: #94a3b8;
        }
        .footer-brand {
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
        }
        .footer-tagline {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 24px;
        }
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-bottom: 24px;
        }
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: #10b981; }
        .footer-social {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        .footer-social a {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s;
        }
        .footer-social a:hover {
            background: #10b981;
            color: #fff;
            transform: translateY(-2px);
        }
        .footer-copy {
            font-size: 12px;
            color: #475569;
        }
        @media (max-width: 480px) {
            .wrapper { padding: 20px 12px; }
            .content { padding: 24px; }
            .banner { height: 160px; }
            .greeting { font-size: 22px; }
            .amount-card .value { font-size: 32px; }
            .detail-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .detail-row .value { max-width: 100%; text-align: left; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Banner -->
            <div class="banner">
                <div class="banner-badge">
                    <div class="dot"></div>
                    <span>Transaction {{ ucfirst($status) }}</span>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">Transaction <span>{{ ucfirst($status) }}</span></div>
                <p class="subtext">Dear {{ $transaction->user->name ?? 'Valued Customer' }}, your {{ $transaction->type }} transaction has been {{ $status }}.</p>

                <!-- Amount Card -->
                <div class="amount-card">
                    <div class="label">Transaction Amount</div>
                    <div class="value">{{ number_format($amount ?? 0, 8) }}</div>
                    <div class="crypto">{{ strtoupper($crypto ?? 'CRYPTO') }}</div>
                </div>

                <!-- Details -->
                <div class="details-card">
                    <h3>Transaction Details</h3>
                    <div class="detail-row">
                        <span class="label">Type</span>
                        <span class="value">{{ ucfirst($transaction->type ?? 'N/A') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Status</span>
                        <span class="value badge badge-{{ $status }}">{{ ucfirst($status) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Date</span>
                        <span class="value">{{ $transaction->created_at->format('M d, Y H:i:s') ?? now()->format('M d, Y H:i:s') }}</span>
                    </div>
                    @if($fee)
                    <div class="detail-row">
                        <span class="label">Network Fee</span>
                        <span class="value">{{ number_format($fee, 8) }} {{ strtoupper($crypto ?? 'CRYPTO') }}</span>
                    </div>
                    @endif
                    @if($hash)
                    <div class="detail-row">
                        <span class="label">Transaction Hash</span>
                        <span class="value code">{{ strlen($hash) > 20 ? substr($hash, 0, 50) . '...' . substr($hash, -6) : $hash }}</span>
                    </div>
                    @endif
                    @if($transaction->from_address ?? false)
                    <div class="detail-row">
                        <span class="label">From Address</span>
                        <span class="value code">{{ $transaction->from_address }}</span>
                    </div>
                    @endif
                    @if($transaction->to_address ?? false)
                    <div class="detail-row">
                        <span class="label">To Address</span>
                        <span class="value code">{{ $transaction->to_address }}</span>
                    </div>
                    @endif
                    @if($status === 'failed' && $reason)
                    <div class="detail-row">
                        <span class="label">Failure Reason</span>
                        <span class="value" style="color: #ef4444;">{{ $reason }}</span>
                    </div>
                    @endif
                </div>

                <!-- Status Messages -->
                @if($status === 'pending')
                <div class="status-message pending">
                    <strong>Processing in Progress</strong><br>
                    Your transaction is currently being processed on the blockchain. You will receive another notification once it is completed. Processing times may vary depending on network congestion.
                </div>
                @endif

                @if($status === 'failed')
                <div class="status-message failed">
                    <strong>Transaction Failed</strong><br>
                    @if(($transaction->type ?? '') === 'withdrawal' || ($transaction->type ?? '') === 'deposit')
                        Please verify the transaction details and try again. Double-check wallet addresses and network fees.
                    @else
                        Our team is reviewing this issue. Please contact support if you have any questions or need assistance.
                    @endif
                </div>
                @endif

                @if($status === 'completed')
                <div class="status-message completed">
                    <strong>Transaction Successful</strong><br>
                    Your transaction has been successfully processed and confirmed on the blockchain. The funds are now available in your account.
                </div>
                @endif

                <!-- Security Notice -->
                <div class="security-box">
                    <h4>Security Notice</h4>
                    <p>If you did not authorize this transaction or notice any unusual activity, please <strong>contact our support team immediately</strong> at <a href="mailto:{{ $supportEmail ?? 'support@covachain.org' }}">{{ $supportEmail ?? 'support@covachain.org' }}</a></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-brand">{{ $companyName ?? 'CovaChain' }}</div>
                <div class="footer-tagline">Secure. Fast. Trusted.</div>
                <div class="footer-links">
                    <a href="https://covachain.org">Website</a>
                    <a href="https://covachain.org/support">Support</a>
                    <a href="https://covachain.org/terms">Terms</a>
                    <a href="https://covachain.org/privacy">Privacy</a>
                </div>
                <div class="footer-social">
                    <a href="#">&#xf09a;</a>
                    <a href="#">&#xf099;</a>
                    <a href="#">&#xf16d;</a>
                    <a href="#">&#xf0e1;</a>
                </div>
                <div class="footer-copy">
                    &copy; {{ date('Y') }} {{ $companyName ?? 'CovaChain' }}. All rights reserved.<br>
                    This is an automated message. Please do not reply to this email.
                </div>
            </div>
        </div>
    </div>
</body>
</html>