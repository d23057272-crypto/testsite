<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Security Alert</title>
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
            background: linear-gradient(180deg, rgba(15,23,42,0.3) 0%, rgba(15,23,42,0.85) 100%);
        }

        .banner-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 36px 40px;
            color: #ffffff;
            z-index: 2;
        }

        .banner-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(239,68,68,0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(239,68,68,0.35);
            color: #fca5a5;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 8px 18px;
            border-radius: 30px;
            font-family: 'Inter', sans-serif;
            margin-bottom: 16px;
        }

        .banner-badge .pulse {
            width: 7px;
            height: 7px;
            background: #ef4444;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(239,68,68,0.6);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }

        .banner-title {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
            text-shadow: 0 2px 12px rgba(0,0,0,0.3);
            font-family: 'Inter', sans-serif;
        }

        .banner-subtitle {
            font-size: 15px;
            font-weight: 400;
            opacity: 0.8;
            margin-top: 6px;
            font-family: 'Inter', sans-serif;
        }

        /* Body */
        .body {
            padding: 44px 40px;
        }

        /* Alert Summary */
        .alert-summary {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fecaca;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 32px;
        }

        .alert-summary-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }

        .alert-summary-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(239,68,68,0.3);
        }

        .alert-summary-icon svg {
            width: 20px;
            height: 20px;
            fill: #ffffff;
        }

        .alert-summary-title {
            font-size: 16px;
            font-weight: 700;
            color: #991b1b;
            font-family: 'Inter', sans-serif;
        }

        .alert-summary-desc {
            font-size: 14px;
            color: #7f1d1d;
            line-height: 1.7;
            font-family: 'Inter', sans-serif;
        }

        /* Section */
        .section {
            margin-bottom: 32px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .section-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(124,58,237,0.25);
        }

        .section-icon svg {
            width: 18px;
            height: 18px;
            fill: #ffffff;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Inter', sans-serif;
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .data-table th {
            background: #f8fafc;
            padding: 14px 18px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            width: 32%;
            font-family: 'Inter', sans-serif;
        }

        .data-table td {
            padding: 14px 18px;
            font-size: 13px;
            color: #334155;
            font-weight: 500;
            border-bottom: 1px solid #f1f5f9;
            word-break: break-word;
            font-family: 'Inter', sans-serif;
        }

        .data-table tr:last-child td,
        .data-table tr:last-child th {
            border-bottom: none;
        }

        .data-table tr:nth-child(even) td {
            background: #fafbfc;
        }

        /* User ID */
        .user-id {
            font-family: 'SF Mono', 'Courier New', monospace;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            color: #475569;
            font-weight: 600;
        }

        /* IP badge */
        .ip-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #dbeafe;
            color: #1e40af;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
        }

        /* User Agent */
        .user-agent {
            font-size: 11px;
            color: #94a3b8;
            font-family: 'SF Mono', 'Courier New', monospace;
            line-height: 1.5;
        }

        /* Details Card */
        .details-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
        }

        .details-card-body .data-table {
            border: none;
            border-radius: 0;
        }

        .details-card-body .data-table th {
            background: transparent;
        }

        /* JSON Block */
        .json-block {
            background: #0f172a;
            color: #e2e8f0;
            padding: 16px 18px;
            border-radius: 10px;
            font-size: 11px;
            font-family: 'SF Mono', 'Courier New', monospace;
            line-height: 1.6;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-all;
            margin: 0;
        }

        /* Footer */
        .footer {
            background: #0f172a;
            padding: 36px 40px;
            text-align: center;
        }

        .footer-brand {
            font-size: 18px;
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

        .footer-notice {
            font-size: 12px;
            color: #475569;
            margin-bottom: 8px;
            font-family: 'Inter', sans-serif;
        }

        .footer-copyright {
            font-size: 11px;
            color: #334155;
            font-family: 'Inter', sans-serif;
        }

        /* Mobile */
        @media (max-width: 480px) {
            .wrapper { padding: 12px 6px; }
            .body { padding: 28px 20px; }
            .banner-title { font-size: 24px; }
            .data-table th, .data-table td { padding: 10px 14px; font-size: 12px; }
            .footer { padding: 28px 20px; }
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
                            <img src="https://covachain.org/uploads/logos/activities.jpg" alt="Security Alert" class="banner" style="width: 100%; height: auto; display: block;">
                            <div class="banner-overlay" style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(15,23,42,0.3) 0%, rgba(15,23,42,0.85) 100%);"></div>
                            <div class="banner-content" style="position: absolute; bottom: 0; left: 0; right: 0; padding: 36px 40px; color: #ffffff; z-index: 2;">
                                <div class="banner-badge" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(239,68,68,0.15); backdrop-filter: blur(16px); border: 1px solid rgba(239,68,68,0.35); color: #fca5a5; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; padding: 8px 18px; border-radius: 30px; font-family: 'Inter', sans-serif; margin-bottom: 16px;">
                                    <span class="pulse" style="width: 7px; height: 7px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 8px rgba(239,68,68,0.6); animation: pulse 2s infinite;"></span>
                                    Security Alert
                                </div>
                                <div class="banner-title" style="font-size: 32px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; text-shadow: 0 2px 12px rgba(0,0,0,0.3); font-family: 'Inter', sans-serif;">Activity Detected</div>
                                <div class="banner-subtitle" style="font-size: 15px; font-weight: 400; opacity: 0.8; margin-top: 6px; font-family: 'Inter', sans-serif;">{{ $activity['activity'] ?? 'User Activity' }}</div>
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="body" style="padding: 44px 40px;">
                            <!-- Alert Summary -->
                            <div class="alert-summary" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 1px solid #fecaca; border-radius: 16px; padding: 24px 28px; margin-bottom: 32px;">
                                <div class="alert-summary-header" style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;">
                                    <div class="alert-summary-icon" style="width: 40px; height: 40px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(239,68,68,0.3);">
                                        <svg viewBox="0 0 24 24" width="20" height="20" fill="#ffffff"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                    </div>
                                    <div class="alert-summary-title" style="font-size: 16px; font-weight: 700; color: #991b1b; font-family: 'Inter', sans-serif;">Alert Summary</div>
                                </div>
                                <p class="alert-summary-desc" style="font-size: 14px; color: #7f1d1d; line-height: 1.7; font-family: 'Inter', sans-serif;">{{ $activity['description'] ?? 'Activity detected on your account.' }}</p>
                            </div>

                            <!-- User Information -->
                            <div class="section" style="margin-bottom: 32px;">
                                <div class="section-header" style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                                    <div class="section-icon" style="width: 36px; height: 36px; background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(124,58,237,0.25);">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="#ffffff"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                    </div>
                                    <div class="section-title" style="font-size: 14px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 1px; font-family: 'Inter', sans-serif;">User Information</div>
                                </div>
                                <table class="data-table" style="width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0;">
                                    <tr>
                                        <th style="background: #f8fafc; padding: 14px 18px; text-align: left; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; width: 32%; font-family: 'Inter', sans-serif;">User ID</th>
                                        <td style="padding: 14px 18px; font-size: 13px; color: #334155; font-weight: 500; border-bottom: 1px solid #f1f5f9; word-break: break-word; font-family: 'Inter', sans-serif;"><span class="user-id" style="font-family: 'SF Mono', 'Courier New', monospace; background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 12px; color: #475569; font-weight: 600;">{{ $activity['user_id'] ?? 'N/A' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8fafc; padding: 14px 18px; text-align: left; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; width: 32%; font-family: 'Inter', sans-serif;">Name</th>
                                        <td style="padding: 14px 18px; font-size: 13px; color: #334155; font-weight: 500; border-bottom: 1px solid #f1f5f9; word-break: break-word; font-family: 'Inter', sans-serif;">{{ $activity['user_name'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8fafc; padding: 14px 18px; text-align: left; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; width: 32%; font-family: 'Inter', sans-serif;">Email</th>
                                        <td style="padding: 14px 18px; font-size: 13px; color: #334155; font-weight: 500; border-bottom: 1px solid #f1f5f9; word-break: break-word; font-family: 'Inter', sans-serif;">{{ $activity['user_email'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8fafc; padding: 14px 18px; text-align: left; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; width: 32%; font-family: 'Inter', sans-serif;">Timestamp</th>
                                        <td style="padding: 14px 18px; font-size: 13px; color: #334155; font-weight: 500; border-bottom: 1px solid #f1f5f9; word-break: break-word; font-family: 'Inter', sans-serif;">{{ $activity['timestamp'] ?? now()->format('M d, Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8fafc; padding: 14px 18px; text-align: left; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; width: 32%; font-family: 'Inter', sans-serif;">IP Address</th>
                                        <td style="padding: 14px 18px; font-size: 13px; color: #334155; font-weight: 500; border-bottom: 1px solid #f1f5f9; word-break: break-word; font-family: 'Inter', sans-serif;">
                                            <span class="ip-badge" style="display: inline-flex; align-items: center; gap: 6px; background: #dbeafe; color: #1e40af; padding: 5px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; font-family: 'Inter', sans-serif;">
                                                <svg viewBox="0 0 24 24" width="12" height="12" fill="#3b82f6"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                                {{ $activity['ip_address'] ?? 'N/A' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8fafc; padding: 14px 18px; text-align: left; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; width: 32%; font-family: 'Inter', sans-serif;">User Agent</th>
                                        <td style="padding: 14px 18px; font-size: 13px; color: #334155; font-weight: 500; word-break: break-word; font-family: 'Inter', sans-serif;"><div class="user-agent" style="font-size: 11px; color: #94a3b8; font-family: 'SF Mono', 'Courier New', monospace; line-height: 1.5;">{{ $activity['user_agent'] ?? 'N/A' }}</div></td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Additional Details -->
                            @if(!empty($activity['data']))
                            <div class="section" style="margin-bottom: 32px;">
                                <div class="section-header" style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                                    <div class="section-icon" style="width: 36px; height: 36px; background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(124,58,237,0.25);">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="#ffffff"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                                    </div>
                                    <div class="section-title" style="font-size: 14px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 1px; font-family: 'Inter', sans-serif;">Additional Details</div>
                                </div>
                                <div class="details-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden;">
                                    <div class="details-card-body">
                                        <table class="data-table" style="width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 14px; overflow: hidden; border: none;">
                                            @foreach($activity['data'] as $key => $value)
                                            <tr>
                                                <th style="background: transparent; padding: 14px 18px; text-align: left; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; width: 32%; font-family: 'Inter', sans-serif;">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                <td style="padding: 14px 18px; font-size: 13px; color: #334155; font-weight: 500; border-bottom: 1px solid #f1f5f9; word-break: break-word; font-family: 'Inter', sans-serif;">
                                                    @if(is_array($value))
                                                        <pre class="json-block" style="background: #0f172a; color: #e2e8f0; padding: 16px 18px; border-radius: 10px; font-size: 11px; font-family: 'SF Mono', 'Courier New', monospace; line-height: 1.6; overflow-x: auto; white-space: pre-wrap; word-break: break-all; margin: 0;">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer" style="background: #0f172a; padding: 36px 40px; text-align: center;">
                            <div class="footer-brand" style="font-size: 18px; font-weight: 800; color: #ffffff; margin-bottom: 6px; font-family: 'Inter', sans-serif; letter-spacing: -0.3px;">{{ config('app.name') }}</div>
                            <div class="footer-tagline" style="font-size: 13px; color: #64748b; margin-bottom: 20px; font-family: 'Inter', sans-serif;">Security & Activity Monitoring</div>
                            <div class="footer-divider" style="height: 1px; background: #334155; margin: 20px 0;"></div>
                            <div class="footer-notice" style="font-size: 12px; color: #475569; margin-bottom: 8px; font-family: 'Inter', sans-serif;">This is an automated security alert. Please do not reply to this email.</div>
                            <div class="footer-copyright" style="font-size: 11px; color: #334155; font-family: 'Inter', sans-serif;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>