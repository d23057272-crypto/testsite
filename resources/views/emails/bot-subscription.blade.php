<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Subscription Activated</title>
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
            background: url('https://covachain.org/uploads/logos/bot.jpg') center/cover no-repeat;
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
        .banner-badge .icon {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 12px rgba(16,185,129,0.6);
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
        .greeting span { color: #10b981; }
        .subtext {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 32px;
        }
        .bot-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }
        .bot-card::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 60%);
        }
        .bot-card h3 {
            font-size: 14px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            position: relative;
        }
        .bot-name {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 20px;
            position: relative;
        }
        .bot-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            position: relative;
        }
        .stat-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }
        .stat-item .label {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .stat-item .value {
            color: #fff;
            font-size: 20px;
            font-weight: 800;
        }
        .stat-item .value.green { color: #10b981; }
        .details-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 32px;
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
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .features-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }
        .features-table th {
            background: #0f172a;
            color: #fff;
            padding: 14px 20px;
            text-align: left;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .features-table th:first-child { border-radius: 12px 0 0 0; }
        .features-table th:last-child { border-radius: 0 12px 0 0; }
        .features-table td {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .features-table tr:nth-child(even) { background: #f8fafc; }
        .features-table tr:last-child td:first-child { border-radius: 0 0 0 12px; }
        .features-table tr:last-child td:last-child { border-radius: 0 0 12px 0; }
        .cta-button {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            text-align: center;
            padding: 18px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            margin-bottom: 32px;
            box-shadow: 0 8px 24px rgba(16,185,129,0.3);
            transition: all 0.3s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(16,185,129,0.4);
        }
        .notes-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .notes-box h4 {
            font-size: 15px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 12px;
        }
        .notes-box ul {
            list-style: none;
            padding: 0;
        }
        .notes-box li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 6px 0;
            color: #78350f;
            font-size: 14px;
        }
        .notes-box li .dot {
            width: 6px; height: 6px;
            background: #f59e0b;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 8px;
        }
        .help-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 16px;
            padding: 24px;
            border-left: 4px solid #10b981;
        }
        .help-box p {
            color: #475569;
            font-size: 14px;
            line-height: 1.7;
        }
        .help-box a {
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
            .bot-stats { grid-template-columns: 1fr; }
            .detail-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .detail-row .value { text-align: left; }
            .features-table th, .features-table td { padding: 10px 12px; font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Banner -->
            <div class="banner">
                <div class="banner-badge">
                    <div class="icon"></div>
                    <span>Bot Activated</span>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">Bot <span>Subscription</span></div>
                <p class="subtext">Dear {{ $subscription->user->name ?? 'Valued Customer' }}, your subscription to the trading bot has been successfully activated!</p>

                <!-- Bot Stats Card -->
                <div class="bot-card">
                    <h3>Bot Performance</h3>
                    <div class="bot-name">{{ $bot->name ?? 'Trading Bot' }}</div>
                    <div class="bot-stats">
                        <div class="stat-item">
                            <div class="label">Win Rate</div>
                            <div class="value green">{{ $bot->win_rate ?? '0' }}%</div>
                        </div>
                        <div class="stat-item">
                            <div class="label">Daily Profit</div>
                            <div class="value green">{{ $bot->daily_profit ?? '0' }}%</div>
                        </div>
                        <div class="stat-item">
                            <div class="label">Investment</div>
                            <div class="value">{{ number_format($amount ?? 0, 2) }} USDT</div>
                        </div>
                    </div>
                </div>

                <!-- Subscription Details -->
                <div class="details-card">
                    <h3>Subscription Details</h3>
                    <div class="detail-row">
                        <span class="label">Trading Pair</span>
                        <span class="value">{{ $trading_pair ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Subscription Date</span>
                        <span class="value">{{ $subscription->subscribed_at->format('M d, Y H:i:s') ?? now()->format('M d, Y H:i:s') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Expires</span>
                        <span class="value">{{ $expires_at->format('M d, Y H:i:s') ?? now()->addMonth()->format('M d, Y H:i:s') }}</span>
                    </div>
                </div>

                <!-- Features Table -->
                <table class="features-table">
                    <tr>
                        <th>Feature</th>
                        <th>Details</th>
                    </tr>
                    <tr>
                        <td>Trading Type</td>
                        <td><span class="badge badge-green">{{ ucfirst($bot->bot_type ?? 'Auto') }}</span></td>
                    </tr>
                    <tr>
                        <td>Strategy</td>
                        <td><span class="badge badge-blue">{{ ucfirst($bot->strategy ?? 'Standard') }}</span></td>
                    </tr>
                    <tr>
                        <td>Risk Level</td>
                        <td><span class="badge badge-amber">{{ ucfirst($bot->risk_level ?? 'Medium') }}</span></td>
                    </tr>
                </table>

                <!-- CTA -->
                <a href="{{ route('bots.show', $bot->id ?? 1) }}" class="cta-button">View Bot Performance</a>

                <!-- Important Notes -->
                <div class="notes-box">
                    <h4>Important Notes</h4>
                    <ul>
                        <li><span class="dot"></span>The bot will trade automatically 24/7 based on market conditions</li>
                        <li><span class="dot"></span>All profits are automatically added to your account balance</li>
                        <li><span class="dot"></span>You can view detailed trade history in your dashboard</li>
                        <li><span class="dot"></span>Trading involves risk and past performance does not guarantee future results</li>
                    </ul>
                </div>

                <!-- Help Box -->
                <div class="help-box">
                    <p>If you have any questions about your bot subscription or need assistance, please contact our support team at <a href="mailto:{{ $supportEmail ?? 'support@covachain.org' }}">{{ $supportEmail ?? 'support@covachain.org' }}</a></p>
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