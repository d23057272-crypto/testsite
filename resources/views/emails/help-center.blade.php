<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Help Center Message</title>
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
            background: url('https://covachain.org/uploads/logos/help.jpg') center/cover no-repeat;
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
        .sender-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 32px;
        }
        .sender-card h3 {
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
        .message-box {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 32px;
        }
        .message-box h3 {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .message-box .message {
            color: #334155;
            font-size: 15px;
            line-height: 1.8;
            white-space: pre-wrap;
        }
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
        .note-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 16px;
            padding: 24px;
            border-left: 4px solid #10b981;
        }
        .note-box p {
            color: #475569;
            font-size: 14px;
            line-height: 1.7;
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
            .detail-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .detail-row .value { text-align: left; }
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
                    <span>Help Center</span>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">New <span>Message</span></div>
                <p class="subtext">A new message has been submitted through the Help Center.</p>

                <!-- Sender Info -->
                <div class="sender-card">
                    <h3>Sender Information</h3>
                    <div class="detail-row">
                        <span class="label">Name</span>
                        <span class="value">{{ $name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Email</span>
                        <span class="value">{{ $email ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Date</span>
                        <span class="value">{{ now()->format('M d, Y H:i:s') }}</span>
                    </div>
                </div>

                <!-- Message -->
                <div class="message-box">
                    <h3>Message Content</h3>
                    <div class="message">{{ $user_message ?? 'No message content provided.' }}</div>
                </div>

                <!-- CTA -->
                <a href="https://covachain.org/admin/help-center" class="cta-button">Reply to Message</a>

                <!-- Note -->
                <div class="note-box">
                    <p><strong>Note:</strong> This message was sent via the Help Center. Please respond promptly to ensure customer satisfaction.</p>
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