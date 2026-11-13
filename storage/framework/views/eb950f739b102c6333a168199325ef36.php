<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to <?php echo e($companyName ?? 'CovaChain'); ?></title>
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
            inset: 0;
            background: linear-gradient(135deg, rgba(16,185,129,0.3) 0%, rgba(15,23,42,0.8) 60%, rgba(15,23,42,0.95) 100%);
        }

        .banner-content {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            color: #ffffff;
            z-index: 2;
        }

        .banner-icon {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }

        .banner-icon svg {
            width: 36px;
            height: 36px;
            fill: #ffffff;
        }

        .banner-title {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 2px 16px rgba(0,0,0,0.3);
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.5px;
        }

        .banner-subtitle {
            font-size: 15px;
            font-weight: 400;
            opacity: 0.85;
            font-family: 'Inter', sans-serif;
        }

        /* Body */
        .body {
            padding: 44px 40px;
        }

        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 16px;
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.3px;
        }

        .greeting span {
            color: #1e293b;
        }

        .intro-text {
            font-size: 15px;
            color: #64748b;
            margin-bottom: 32px;
            line-height: 1.7;
            font-family: 'Inter', sans-serif;
        }

        /* Account Details Card */
        .details-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 28px;
        }

        .details-title {
            font-size: 12px;
            font-weight: 700;
            color: #10b981;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Inter', sans-serif;
        }

        .details-title svg {
            width: 18px;
            height: 18px;
            fill: #10b981;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            font-family: 'Inter', sans-serif;
        }

        .detail-value {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            font-family: 'SF Mono', 'Courier New', monospace;
            background: #ffffff;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        /* Security Panel */
        .security-panel {
            background: linear-gradient(135deg, #fffbeb 0%, #fef9c3 100%);
            border: 1px solid #fde68a;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 28px;
        }

        .security-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .security-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(245,158,11,0.25);
        }

        .security-icon svg {
            width: 18px;
            height: 18px;
            fill: #ffffff;
        }

        .security-title {
            font-size: 15px;
            font-weight: 700;
            color: #92400e;
            font-family: 'Inter', sans-serif;
        }

        .security-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .security-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 8px 0;
            font-size: 14px;
            color: #78350f;
            font-family: 'Inter', sans-serif;
        }

        .security-list li::before {
            content: "✓";
            width: 20px;
            height: 20px;
            min-width: 20px;
            background: #facc15;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1px;
            color: #713f12;
            font-size: 11px;
            font-weight: 800;
        }

        /* CTA Button */
        .cta-section {
            text-align: center;
            margin-bottom: 28px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 18px 44px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 6px 24px rgba(16,185,129,0.35);
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
        }

        .cta-note {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 14px;
            font-family: 'Inter', sans-serif;
        }

        /* Support Section */
        .support-section {
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
            border: 1px solid #a7f3d0;
            border-radius: 16px;
            padding: 24px 28px;
            text-align: center;
            margin-bottom: 28px;
        }

        .support-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            box-shadow: 0 4px 16px rgba(16,185,129,0.3);
        }

        .support-icon svg {
            width: 22px;
            height: 22px;
            fill: #ffffff;
        }

        .support-title {
            font-size: 15px;
            font-weight: 700;
            color: #065f46;
            margin-bottom: 8px;
            font-family: 'Inter', sans-serif;
        }

        .support-text {
            font-size: 14px;
            color: #047857;
            font-family: 'Inter', sans-serif;
        }

        .support-text a {
            color: #10b981;
            font-weight: 600;
            text-decoration: none;
        }

        /* Closing */
        .closing {
            font-size: 15px;
            color: #475569;
            margin-bottom: 6px;
            font-family: 'Inter', sans-serif;
        }

        .signature {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            font-family: 'Inter', sans-serif;
        }

        /* Footer */
        .footer {
            background: #0f172a;
            padding: 40px 40px;
            text-align: center;
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
            color: #64748b;
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

        .footer-social {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            background: #1e293b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .social-icon:hover {
            background: #10b981;
        }

        .social-icon svg {
            width: 18px;
            height: 18px;
            fill: #94a3b8;
        }

        .footer-copyright {
            font-size: 11px;
            color: #475569;
            line-height: 1.8;
            font-family: 'Inter', sans-serif;
        }

        /* Mobile */
        @media (max-width: 480px) {
            .wrapper { padding: 12px 6px; }
            .body { padding: 28px 20px; }
            .banner-title { font-size: 28px; }
            .banner-icon { width: 56px; height: 56px; }
            .banner-icon svg { width: 28px; height: 28px; }
            .detail-row { flex-direction: column; align-items: flex-start; gap: 6px; }
            .footer { padding: 28px 20px; }
            .greeting { font-size: 20px; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #0f172a; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#0f172a">
        <tr>
            <td align="center" valign="top" style="padding: 24px 10px;">
                <table class="container" width="640" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-radius: 24px; overflow: hidden; box-shadow: 0 25px 80px -20px rgba(0, 0, 0, 0.6); border: 1px solid rgba(255,255,255,0.05);">
                    <!-- Banner -->
                    <tr>
                        <td class="banner-wrap" style="position: relative;">
                            <img src="https://covachain.org/uploads/logos/welcome.jpg" alt="Welcome Banner" class="banner" style="width: 100%; height: auto; display: block;">
                            <div class="banner-overlay" style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(16,185,129,0.3) 0%, rgba(15,23,42,0.8) 60%, rgba(15,23,42,0.95) 100%);"></div>
                            <div class="banner-content" style="position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 40px; color: #ffffff; z-index: 2;">
                                <div class="banner-icon" style="width: 72px; height: 72px; background: rgba(255,255,255,0.1); backdrop-filter: blur(16px); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
                                    <svg viewBox="0 0 24 24" width="36" height="36" fill="#ffffff"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                </div>
                                <div class="banner-title" style="font-size: 36px; font-weight: 800; margin-bottom: 8px; text-shadow: 0 2px 16px rgba(0,0,0,0.3); font-family: 'Inter', sans-serif; letter-spacing: -0.5px;">Welcome Aboard!</div>
                                <div class="banner-subtitle" style="font-size: 15px; font-weight: 400; opacity: 0.85; font-family: 'Inter', sans-serif;">Your journey with <?php echo e($companyName ?? 'CovaChain'); ?> starts now</div>
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="body" style="padding: 44px 40px;">
                            <div class="greeting" style="font-size: 24px; font-weight: 700; color: #10b981; margin-bottom: 16px; font-family: 'Inter', sans-serif; letter-spacing: -0.3px;">Hello <span style="color: #1e293b;"><?php echo e($name ?? 'Valued Member'); ?></span>,</div>

                            <p class="intro-text" style="font-size: 15px; color: #64748b; margin-bottom: 32px; line-height: 1.7; font-family: 'Inter', sans-serif;">
                                Thank you for joining <strong><?php echo e($companyName ?? 'CovaChain'); ?></strong>! We're thrilled to have you as part of our community. Your account has been successfully created and is ready to use.
                            </p>

                            <!-- Account Details -->
                            <div class="details-card" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px; margin-bottom: 28px;">
                                <div class="details-title" style="font-size: 12px; font-weight: 700; color: #10b981; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; font-family: 'Inter', sans-serif;">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#10b981"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                    Your Account Details
                                </div>
                                <div class="detail-row" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                    <span class="detail-label" style="font-size: 13px; font-weight: 500; color: #64748b; font-family: 'Inter', sans-serif;">UUID</span>
                                    <span class="detail-value" style="font-size: 13px; font-weight: 600; color: #0f172a; font-family: 'SF Mono', 'Courier New', monospace; background: #ffffff; padding: 6px 14px; border-radius: 8px; border: 1px solid #e2e8f0;"><?php echo e($uuid ?? 'N/A'); ?></span>
                                </div>
                                <div class="detail-row" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0;">
                                    <span class="detail-label" style="font-size: 13px; font-weight: 500; color: #64748b; font-family: 'Inter', sans-serif;">Email Address</span>
                                    <span class="detail-value" style="font-size: 13px; font-weight: 600; color: #0f172a; font-family: 'SF Mono', 'Courier New', monospace; background: #ffffff; padding: 6px 14px; border-radius: 8px; border: 1px solid #e2e8f0;"><?php echo e($email ?? 'N/A'); ?></span>
                                </div>
                            </div>

                            <!-- Security Panel -->
                            <div class="security-panel" style="background: linear-gradient(135deg, #fffbeb 0%, #fef9c3 100%); border: 1px solid #fde68a; border-radius: 16px; padding: 24px 28px; margin-bottom: 28px;">
                                <div class="security-header" style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                                    <div class="security-icon" style="width: 32px; height: 32px; background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(245,158,11,0.25);">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="#ffffff"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                                    </div>
                                    <div class="security-title" style="font-size: 15px; font-weight: 700; color: #92400e; font-family: 'Inter', sans-serif;">Secure Your Account</div>
                                </div>
                                <ul class="security-list" style="list-style: none; padding: 0; margin: 0;">
                                    <li style="display: flex; align-items: flex-start; gap: 12px; padding: 8px 0; font-size: 14px; color: #78350f; font-family: 'Inter', sans-serif;">Create a strong passcode for all transactions and withdrawals</li>
                                    <li style="display: flex; align-items: flex-start; gap: 12px; padding: 8px 0; font-size: 14px; color: #78350f; font-family: 'Inter', sans-serif;">Enable two-factor authentication for extra protection</li>
                                </ul>
                            </div>

                            <!-- CTA -->
                            <div class="cta-section" style="text-align: center; margin-bottom: 28px;">
                                <a href="<?php echo e($dashboardUrl ?? 'https://covachain.org/dashboard'); ?>" class="cta-button" style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff !important; text-decoration: none; padding: 18px 44px; border-radius: 14px; font-size: 15px; font-weight: 700; letter-spacing: 0.5px; box-shadow: 0 6px 24px rgba(16,185,129,0.35); font-family: 'Inter', sans-serif;">Access Your Dashboard</a>
                                <p class="cta-note" style="font-size: 12px; color: #94a3b8; margin-top: 14px; font-family: 'Inter', sans-serif;">Click the button above or copy this link: <?php echo e($dashboardUrl ?? 'https://covachain.org/dashboard'); ?></p>
                            </div>

                            <!-- Support -->
                            <div class="support-section" style="background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%); border: 1px solid #a7f3d0; border-radius: 16px; padding: 24px 28px; text-align: center; margin-bottom: 28px;">
                                <div class="support-icon" style="width: 44px; height: 44px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px; box-shadow: 0 4px 16px rgba(16,185,129,0.3);">
                                    <svg viewBox="0 0 24 24" width="22" height="22" fill="#ffffff"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/><path d="M7 9h10v2H7zm0-3h10v2H7z"/></svg>
                                </div>
                                <div class="support-title" style="font-size: 15px; font-weight: 700; color: #065f46; margin-bottom: 8px; font-family: 'Inter', sans-serif;">Need Help Getting Started?</div>
                                <p class="support-text" style="font-size: 14px; color: #047857; font-family: 'Inter', sans-serif;">
                                    Our support team is available 24/7. Reach us at <a href="mailto:<?php echo e($supportEmail ?? 'support@covachain.org'); ?>" style="color: #10b981; font-weight: 600; text-decoration: none;"><?php echo e($supportEmail ?? 'support@covachain.org'); ?></a>
                                </p>
                            </div>

                            <!-- Closing -->
                            <p class="closing" style="font-size: 15px; color: #475569; margin-bottom: 6px; font-family: 'Inter', sans-serif;">Best regards,</p>
                            <p class="signature" style="font-size: 15px; font-weight: 700; color: #0f172a; font-family: 'Inter', sans-serif;">The <?php echo e($companyName ?? 'CovaChain'); ?> Team</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer" style="background: #0f172a; padding: 40px 40px; text-align: center;">
                            <div class="footer-brand" style="font-size: 20px; font-weight: 800; color: #ffffff; margin-bottom: 6px; font-family: 'Inter', sans-serif; letter-spacing: -0.3px;"><?php echo e($companyName ?? 'CovaChain'); ?></div>
                            <div class="footer-tagline" style="font-size: 13px; color: #64748b; margin-bottom: 20px; font-family: 'Inter', sans-serif;">Your trusted partner in digital finance</div>

                            <div class="footer-divider" style="height: 1px; background: #334155; margin: 20px 0;"></div>

                            <div class="footer-links" style="margin-bottom: 20px;">
                                <a href="https://covachain.org" style="color: #94a3b8; text-decoration: none; font-size: 13px; margin: 0 14px; font-weight: 500; font-family: 'Inter', sans-serif;">Home</a>
                                <a href="https://covachain.org/about" style="color: #94a3b8; text-decoration: none; font-size: 13px; margin: 0 14px; font-weight: 500; font-family: 'Inter', sans-serif;">About</a>
                                <a href="https://covachain.org/contact" style="color: #94a3b8; text-decoration: none; font-size: 13px; margin: 0 14px; font-weight: 500; font-family: 'Inter', sans-serif;">Contact</a>
                                <a href="https://covachain.org/privacy" style="color: #94a3b8; text-decoration: none; font-size: 13px; margin: 0 14px; font-weight: 500; font-family: 'Inter', sans-serif;">Privacy</a>
                            </div>

                            <div class="footer-social" style="display: flex; justify-content: center; gap: 14px; margin-bottom: 20px;">
                                <a href="#" class="social-icon" style="width: 40px; height: 40px; background: #1e293b; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#94a3b8"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                </a>
                                <a href="#" class="social-icon" style="width: 40px; height: 40px; background: #1e293b; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#94a3b8"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </a>
                                <a href="#" class="social-icon" style="width: 40px; height: 40px; background: #1e293b; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#94a3b8"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                            </div>

                            <div class="footer-copyright" style="font-size: 11px; color: #475569; line-height: 1.8; font-family: 'Inter', sans-serif;">
                                &copy; <?php echo e(date('Y')); ?> <?php echo e($companyName ?? 'CovaChain'); ?>. All rights reserved.<br>
                                You received this email because you created an account on our platform.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html><?php /**PATH C:\Tools\covachain.org\resources\views/emails/welcome.blade.php ENDPATH**/ ?>