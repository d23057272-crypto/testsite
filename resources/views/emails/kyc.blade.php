<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Verification Update</title>
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
            background: url('https://covachain.org/uploads/logos/kyc.jpg') center/cover no-repeat;
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
        .status-card {
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 32px;
            text-align: center;
        }
        .status-card.approved {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #86efac;
        }
        .status-card.rejected {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fecaca;
        }
        .status-card.pending {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
        }
        .status-card .status-icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 28px;
        }
        .status-card.approved .status-icon { background: #dcfce7; color: #166534; }
        .status-card.rejected .status-icon { background: #fee2e2; color: #991b1b; }
        .status-card.pending .status-icon { background: #fef3c7; color: #92400e; }
        .status-card h3 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .status-card.approved h3 { color: #166534; }
        .status-card.rejected h3 { color: #991b1b; }
        .status-card.pending h3 { color: #92400e; }
        .status-card p {
            font-size: 14px;
            line-height: 1.7;
        }
        .status-card.approved p { color: #166534; }
        .status-card.rejected p { color: #991b1b; }
        .status-card.pending p { color: #92400e; }
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
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .documents-list {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 32px;
        }
        .documents-list h3 {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .documents-list ul {
            list-style: none;
            padding: 0;
        }
        .documents-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            color: #334155;
            font-size: 14px;
            font-weight: 500;
        }
        .documents-list li .check {
            width: 22px; height: 22px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 12px;
            flex-shrink: 0;
        }
        .requirements-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .requirements-box h4 {
            font-size: 15px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 12px;
        }
        .requirements-box ul {
            list-style: none;
            padding: 0;
        }
        .requirements-box li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 6px 0;
            color: #78350f;
            font-size: 14px;
        }
        .requirements-box li .dot {
            width: 6px; height: 6px;
            background: #f59e0b;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 8px;
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
                    <span>KYC Update</span>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                @if($type === 'submission')
                <!-- Documents Received -->
                <div class="greeting">Documents <span>Received</span></div>
                <p class="subtext">Dear {{ $user->name ?? 'Valued Customer' }}, we have received your KYC documents for verification. Thank you for submitting the required documentation.</p>

                <div class="status-card pending">
                    <div class="status-icon">&#9203;</div>
                    <h3>Under Review</h3>
                    <p>Our compliance team will review your documents within 24-48 hours. You will receive another email once the verification process is complete.</p>
                </div>

                @if(!empty($documents))
                <div class="documents-list">
                    <h3>Documents Received</h3>
                    <ul>
                        @foreach($documents as $doc => $path)
                        <li><span class="check">&#10003;</span> {{ ucwords(str_replace('_', ' ', $doc)) }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="requirements-box">
                    <h4>Important Notes</h4>
                    <ul>
                        <li><span class="dot"></span>Please ensure all documents are clear and legible</li>
                        <li><span class="dot"></span>Documents must be valid and not expired</li>
                        <li><span class="dot"></span>All information should match your account details</li>
                    </ul>
                </div>

                @else
                <!-- Status Update -->
                <div class="greeting">KYC <span>{{ ucfirst($status ?? 'Update') }}</span></div>
                <p class="subtext">Dear {{ $user->name ?? 'Valued Customer' }}, your KYC verification status has been updated.</p>

                @if($status === 'approved')
                <div class="status-card approved">
                    <div class="status-icon">&#10003;</div>
                    <h3>Verification Approved</h3>
                    <p>Your KYC verification has been successfully completed and approved. Your account now has full access to all features.</p>
                </div>

                <div class="details-card">
                    <h3>Account Benefits</h3>
                    <div class="detail-row">
                        <span class="label">Account Status</span>
                        <span class="value badge badge-approved">Fully Verified</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Verification Date</span>
                        <span class="value">{{ now()->format('M d, Y H:i:s') }}</span>
                    </div>
                </div>

                <div class="documents-list">
                    <h3>Unlocked Features</h3>
                    <ul>
                        <li><span class="check">&#10003;</span> Unlimited crypto sending and receiving</li>
                        <li><span class="check">&#10003;</span> Full trading features and limits</li>
                        <li><span class="check">&#10003;</span> Complete platform functionality</li>
                        <li><span class="check">&#10003;</span> Referral bonus eligibility</li>
                    </ul>
                </div>

                @elseif($status === 'rejected')
                <div class="status-card rejected">
                    <div class="status-icon">&#9888;</div>
                    <h3>Update Required</h3>
                    <p>Your KYC verification requires updates. Our compliance team has reviewed your submission and needs additional information.</p>
                </div>

                <div class="details-card">
                    <h3>Review Details</h3>
                    <div class="detail-row">
                        <span class="label">Status</span>
                        <span class="value badge badge-rejected">Update Required</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Review Date</span>
                        <span class="value">{{ now()->format('M d, Y H:i:s') }}</span>
                    </div>
                    @if($rejectionReason ?? false)
                    <div class="detail-row">
                        <span class="label">Reason</span>
                        <span class="value" style="color: #ef4444;">{{ $rejectionReason }}</span>
                    </div>
                    @endif
                </div>

                <div class="requirements-box">
                    <h4>Common Requirements</h4>
                    <ul>
                        <li><span class="dot"></span>Documents must be clear and fully visible</li>
                        <li><span class="dot"></span>All text should be legible</li>
                        <li><span class="dot"></span>Documents should be current and valid</li>
                        <li><span class="dot"></span>Information must match your account details</li>
                    </ul>
                </div>

                <a href="{{ route('kyc') }}" class="cta-button">Resubmit Documents</a>

                @else
                <div class="status-card pending">
                    <div class="status-icon">&#9203;</div>
                    <h3>Status Updated</h3>
                    <p>Your KYC verification status has been updated to: {{ ucfirst($status) }}</p>
                </div>
                @endif
                @endif

                <a href="{{ route('kyc') }}" class="cta-button">View KYC Status</a>

                <!-- Help Box -->
                <div class="help-box">
                    <p>If you need assistance or have questions about the verification process, our support team is here to help at <a href="mailto:{{ $supportEmail ?? 'support@covachain.org' }}">{{ $supportEmail ?? 'support@covachain.org' }}</a></p>
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