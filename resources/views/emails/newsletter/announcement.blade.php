<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject }}</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f1f5f9;
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
            background-color: #f1f5f9;
            padding: 20px 10px;
        }

        .container {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
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
            height: 60%;
            background: linear-gradient(180deg, transparent 0%, rgba(15,23,42,0.7) 100%);
        }

        .banner-badge {
            position: absolute;
            bottom: 24px;
            left: 32px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16,185,129,0.2);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(16,185,129,0.4);
            color: #6ee7b7;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 6px 14px;
            border-radius: 20px;
            font-family: 'Inter', sans-serif;
        }

        /* Content */
        .content {
            padding: 40px 36px;
        }

        .greeting {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #f59e0b;
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.3px;
        }

        .greeting span {
            color: #1e293b;
        }

        .intro-text {
            font-size: 15px;
            color: #64748b;
            margin-bottom: 28px;
            line-height: 1.7;
            font-family: 'Inter', sans-serif;
        }

        .main-content {
            color: #475569;
            font-size: 15px;
            line-height: 1.8;
            font-family: 'Inter', sans-serif;
        }

        .main-content p {
            margin-bottom: 16px;
        }

        .main-content h2 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 28px 0 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.3px;
        }

        .main-content h3 {
            font-size: 17px;
            font-weight: 600;
            color: #1e293b;
            margin: 24px 0 12px;
            font-family: 'Inter', sans-serif;
        }

        .main-content ul, .main-content ol {
            margin: 16px 0;
            padding-left: 24px;
        }

        .main-content li {
            margin-bottom: 10px;
            position: relative;
        }

        .main-content li::marker {
            color: #f59e0b;
        }

        .main-content a {
            color: #f59e0b;
            text-decoration: none;
            font-weight: 600;
        }

        .main-content a:hover {
            text-decoration: underline;
        }

        .main-content img {
            max-width: 100%;
            border-radius: 12px;
            margin: 16px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .main-content blockquote {
            border-left: 4px solid #10b981;
            padding: 16px 20px;
            margin: 20px 0;
            color: #475569;
            font-style: italic;
            background: #f8fafc;
            border-radius: 0 10px 10px 0;
        }

        /* Referral Box */
        .referral-box {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 2px dashed #10b981;
            border-radius: 16px;
            padding: 28px;
            text-align: center;
            margin: 28px 0;
        }

        .referral-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #b45309;
            margin-bottom: 12px;
            font-family: 'Inter', sans-serif;
        }

        .referral-code {
            font-family: 'SF Mono', Monaco, 'Courier New', monospace;
            font-size: 24px;
            font-weight: 800;
            color: #92400e;
            letter-spacing: 3px;
            background: #ffffff;
            padding: 14px 28px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 16px;
            border: 1px solid #fde68a;
            box-shadow: 0 2px 8px rgba(16,185,129,0.1);
        }

        .referral-btn {
            display: inline-block;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 16px rgba(245,158,11,0.35);
            font-family: 'Inter', sans-serif;
        }

        .referral-note {
            font-size: 13px;
            color: #b45309;
            margin-top: 12px;
            font-family: 'Inter', sans-serif;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
            border-left: 4px solid #10b981;
            border-radius: 0 12px 12px 0;
            padding: 20px 24px;
            margin: 24px 0;
        }

        .info-box-title {
            font-weight: 700;
            color: #92400e;
            margin-bottom: 8px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Inter', sans-serif;
        }

        .info-box-text {
            color: #78350f;
            font-size: 14px;
            line-height: 1.6;
            font-family: 'Inter', sans-serif;
        }

        .info-box-text a {
            color: #10b981;
            font-weight: 600;
            text-decoration: none;
        }

        /* Dark Footer */
        .footer {
            padding: 40px 36px;
            text-align: center;
            background: #0f172a;
        }

        .footer-brand {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 6px;
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.3px;
        }

        .footer-tagline {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 20px;
            font-family: 'Inter', sans-serif;
        }

        .footer-divider {
            height: 1px;
            background: #334155;
            margin: 20px 0;
        }

        .footer-links {
            margin-bottom: 20px;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 13px;
            margin: 0 14px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #10b981;
        }

        .footer-copyright {
            font-size: 12px;
            color: #475569;
            line-height: 1.8;
            font-family: 'Inter', sans-serif;
        }

        /* Mobile */
        @media (max-width: 480px) {
            .wrapper { padding: 10px 6px; }
            .content { padding: 28px 20px; }
            .greeting { font-size: 20px; }
            .referral-code { font-size: 20px; letter-spacing: 2px; padding: 12px 20px; }
            .footer { padding: 28px 20px; }
            .footer-links a { margin: 0 8px; font-size: 12px; }
            .banner-badge { left: 20px; bottom: 16px; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f1f5f9">
        <tr>
            <td align="center" valign="top" style="padding: 20px 10px;">
                <table class="container" width="680" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-radius: 20px; overflow: hidden; box-shadow: 0 8px 40px rgba(0, 0, 0, 0.06); border: 1px solid #f1f5f9;">
                    <!-- Banner Image -->
                    <tr>
                        <td class="banner-wrap" style="position: relative;">
                            <img src="https://covachain.org/uploads/logos/announcement.jpg" alt="Important Announcement Banner" class="banner" style="width: 100%; height: auto; display: block;">
                            <div class="banner-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; height: 60%; background: linear-gradient(180deg, transparent 0%, rgba(15,23,42,0.7) 100%);"></div>
                            <div class="banner-badge" style="position: absolute; bottom: 24px; left: 32px; display: inline-flex; align-items: center; gap: 6px; background: rgba(245,158,11,0.2); backdrop-filter: blur(12px); border: 1px solid rgba(245,158,11,0.4); color: #fcd34d; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 6px 14px; border-radius: 20px; font-family: 'Inter', sans-serif;">Important Announcement</div>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content" style="padding: 40px 36px;">
                            <div class="greeting" style="font-size: 22px; font-weight: 700; margin-bottom: 16px; color: #10b981; font-family: 'Inter', sans-serif; letter-spacing: -0.3px;">
                                Hello <span style="color: #1e293b;">{{ $user->name ?? 'Valued Member' }}</span>,
                            </div>

                            <p class="intro-text" style="font-size: 15px; color: #64748b; margin-bottom: 28px; line-height: 1.7; font-family: 'Inter', sans-serif;">
                                Thank you for being part of our community. Here is your latest update from {{ $companyName }}.
                            </p>

                            <div class="main-content" style="color: #475569; font-size: 15px; line-height: 1.8; font-family: 'Inter', sans-serif;">
                                {!! $content !!}
                            </div>

                            <!-- Referral Box -->
                            @if(isset($user->referral_code) && $user->referral_code)
                            <div class="referral-box" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 2px dashed #f59e0b; border-radius: 16px; padding: 28px; text-align: center; margin: 28px 0;">
                                <div class="referral-label" style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #059669; margin-bottom: 12px; font-family: 'Inter', sans-serif;">Your Referral Code</div>
                                <div class="referral-code" style="font-family: 'SF Mono', Monaco, 'Courier New', monospace; font-size: 24px; font-weight: 800; color: #065f46; letter-spacing: 3px; background: #ffffff; padding: 14px 28px; border-radius: 12px; display: inline-block; margin-bottom: 16px; border: 1px solid #fde68a; box-shadow: 0 2px 8px rgba(16,185,129,0.1);">
                                    {{ strtoupper($user->referral_code) }}
                                </div>
                                <br>
                                <a href="https://covachain.org/referral" class="referral-btn" style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-size: 14px; font-weight: 700; letter-spacing: 0.5px; box-shadow: 0 4px 16px rgba(245,158,11,0.35); font-family: 'Inter', sans-serif;">REFER A FRIEND</a>
                                <div class="referral-note" style="font-size: 13px; color: #059669; margin-top: 12px; font-family: 'Inter', sans-serif;">Earn up to 50.00% when friends sign up</div>
                            </div>
                            @endif

                            <!-- Help Box -->
                            <div class="info-box" style="background: linear-gradient(135deg, #fffbeb 0%, #fef9c3 100%); border-left: 4px solid #f59e0b; border-radius: 0 12px 12px 0; padding: 20px 24px; margin: 24px 0;">
                                <div class="info-box-title" style="font-weight: 700; color: #065f46; margin-bottom: 8px; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-family: 'Inter', sans-serif;">Need Help?</div>
                                <div class="info-box-text" style="color: #047857; font-size: 14px; line-height: 1.6; font-family: 'Inter', sans-serif;">
                                    Our support team is available 24/7. Reply to this email or contact us at 
                                    <a href="mailto:{{ $companyEmail }}" style="color: #10b981; font-weight: 600; text-decoration: none;">{{ $companyEmail }}</a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Dark Footer -->
                    <tr>
                        <td class="footer" style="padding: 40px 36px; text-align: center; background: #0f172a;">
                            <div class="footer-brand" style="font-size: 20px; font-weight: 800; color: #ffffff; margin-bottom: 6px; font-family: 'Inter', sans-serif; letter-spacing: -0.3px;">
                                {{ $companyName }}
                            </div>
                            <div class="footer-tagline" style="font-size: 13px; color: #94a3b8; margin-bottom: 20px; font-family: 'Inter', sans-serif;">Your trusted partner in financial growth</div>

                            <div class="footer-divider" style="height: 1px; background: #334155; margin: 20px 0;"></div>

                           
                            <div class="footer-copyright" style="font-size: 12px; color: #475569; line-height: 1.8; font-family: 'Inter', sans-serif;">
                                &copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.<br>
                               
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>