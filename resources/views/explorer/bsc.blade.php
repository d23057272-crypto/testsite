<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BNB Smart Chain Transaction Hash (Txhash) Details | BscScan</title>
  <meta name="description" content="BNB Smart Chain (BSC) detailed transaction info for txhash {{ $tx->hash ?? 'N/A' }}. The transaction status, block confirmation, gas fee, {{ $tx->symbol ?? 'BNB' }}, and token transfer are shown.">
  <link rel="icon" href="https://bscscan.com/assets/generic/html/favicon.ico">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: #f8f9fa;
      color: #212529;
      line-height: 1.5;
    }

    .header {
      background: white;
      border-bottom: 1px solid #dee2e6;
      padding: 0.5rem 0;
    }

    .header-top {
      background: #f8f9fa;
      padding: 0.25rem 0;
      font-size: 0.875rem;
      border-bottom: 1px solid #dee2e6;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1rem;
    }

    .header-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .price-info {
      color: #6c757d;
    }

    .price-negative {
      color: #dc3545;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 0;
    }

    .logo {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #212529;
      font-weight: bold;
      font-size: 1.5rem;
    }

    .logo img {
      height: 32px;
      margin-right: 0.5rem;
    }

    .nav-menu {
      display: flex;
      list-style: none;
      gap: 2rem;
      align-items: center;
    }

    .nav-menu a {
      text-decoration: none;
      color: #495057;
      font-weight: 500;
      transition: color 0.2s;
    }

    .nav-menu a:hover {
      color: #007bff;
    }

    .sign-in-btn {
      background: #007bff;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      text-decoration: none;
      font-weight: 500;
      transition: background-color 0.2s;
    }

    .sign-in-btn:hover {
      background: #0056b3;
    }

    .main-content {
      padding: 2rem 0;
    }

    .page-title {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      color: #212529;
    }

    .sponsored-ads {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .ad-card {
      background: white;
      border: 1px solid #dee2e6;
      border-radius: 0.5rem;
      padding: 1rem;
      text-decoration: none;
      color: inherit;
      transition: box-shadow 0.2s;
    }

    .ad-card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .ad-tag {
      background: #007bff;
      color: white;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.75rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
      display: inline-block;
    }

    .transaction-tabs {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
      border-bottom: 1px solid #dee2e6;
    }

    .tab {
      padding: 0.75rem 1rem;
      background: none;
      border: none;
      color: #6c757d;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.2s;
    }

    .tab.active {
      color: #007bff;
      border-bottom-color: #007bff;
    }

    .transaction-details {
      background: white;
      border: 1px solid #dee2e6;
      border-radius: 0.5rem;
      overflow: hidden;
    }

    .detail-row {
      display: flex;
      padding: 1rem;
      border-bottom: 1px solid #f8f9fa;
    }

    .detail-row:last-child {
      border-bottom: none;
    }

    .detail-label {
      width: 200px;
      font-weight: 500;
      color: #6c757d;
      flex-shrink: 0;
    }

    .detail-value {
      flex: 1;
      word-break: break-all;
    }

    .hash-value {
      font-family: 'Monaco', 'Menlo', monospace;
      font-size: 0.875rem;
      color: #007bff;
    }

    .status-success {
      background: #d4edda;
      color: #155724;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .status-failed {
      background: #f8d7da;
      color: #721c24;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .block-link {
      color: #007bff;
      text-decoration: none;
    }

    .block-link:hover {
      text-decoration: underline;
    }

    .address-link {
      color: #007bff;
      text-decoration: none;
      font-family: 'Monaco', 'Menlo', monospace;
      font-size: 0.875rem;
    }

    .address-link:hover {
      text-decoration: underline;
    }

    .bnb-value {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .bnb-icon {
      width: 16px;
      height: 16px;
      background: #f0b90b;
      border-radius: 50%;
    }

    .gas-usage {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .progress-bar {
      width: 100px;
      height: 8px;
      background: #e9ecef;
      border-radius: 4px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: #28a745;
      width: 100%;
    }

    .input-data {
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 0.375rem;
      font-family: 'Monaco', 'Menlo', monospace;
      font-size: 0.875rem;
      margin-top: 0.5rem;
    }

    .footer {
      background: #343a40;
      color: white;
      padding: 3rem 0 2rem;
      margin-top: 4rem;
    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-bottom: 2rem;
    }

    .footer-section h4 {
      margin-bottom: 1rem;
      color: white;
    }

    .footer-section ul {
      list-style: none;
    }

    .footer-section ul li {
      margin-bottom: 0.5rem;
    }

    .footer-section ul li a {
      color: #adb5bd;
      text-decoration: none;
      transition: color 0.2s;
    }

    .footer-section ul li a:hover {
      color: white;
    }

    .footer-bottom {
      border-top: 1px solid #495057;
      padding-top: 1rem;
      text-align: center;
      color: #adb5bd;
      font-size: 0.875rem;
    }

    .more-details {
      cursor: pointer;
      color: #007bff;
      text-decoration: none;
    }

    .more-details:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .navbar {
        flex-direction: column;
        gap: 1rem;
      }

      .nav-menu {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
      }

      .detail-row {
        flex-direction: column;
        gap: 0.5rem;
      }

      .detail-label {
        width: auto;
        font-weight: 600;
      }

      .sponsored-ads {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <header class="header">
    <div class="header-top">
      <div class="container">
        <div class="header-info">
          <div class="price-info">
            {{ $tx->symbol ?? 'BNB' }} Price: <strong>${{ number_format($tx->rate ?? 1118.51, 2) }}</strong> <span class="price-negative">(-1.11%)</span>
          </div>
          <div class="price-info">
            Gas: <strong>{{ $tx->confirmations ? rand(5, 50) : 0 }} Gwei</strong>
          </div>
        </div>
      </div>
    </div>
    
    <div class="container">
      <nav class="navbar">
        <a href="#" class="logo">
          <img src="https://bscscan.com/assets/bsc/images/svg/logos/logo-light.svg" alt="BscScan Logo">
          BscScan
        </a>
        
        <ul class="nav-menu">
          <li><a href="#">Home</a></li>
          <li><a href="#">Blockchain</a></li>
          <li><a href="#">Validators</a></li>
          <li><a href="#">Tokens</a></li>
          <li><a href="#">NFTs</a></li>
          <li><a href="#">Resources</a></li>
          <li><a href="#">Developers</a></li>
          <li><a href="#">More</a></li>
          <li><a href="#" class="sign-in-btn">Sign In</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="main-content">
    <div class="container">
      <h1 class="page-title">Transaction Details</h1>
      
      <div class="sponsored-ads">
        <a href="#" class="ad-card">
          <div class="ad-tag">Buy</div>
          <strong>eToro</strong><br>
          A global broker built for crypto trading. Buy Now!<br>
          <small>User-friendly interface. Trusted worldwide.</small>
        </a>
        
        <a href="#" class="ad-card">
          <div class="ad-tag">Presale</div>
          <strong>Remittix</strong><br>
          XRP 2.0? Discover Remittix. Explorer Now<br>
          <small>Remittix is revolutionizing global payments. $18.5M+ raised.</small>
        </a>
        
        <a href="#" class="ad-card">
          <div class="ad-tag">Play</div>
          <strong>Coins.game</strong><br>
          - 100 free spins for registration. Spin Now!<br>
          <small>Everyday giveaways up to 8.88BTC, Lucky Spins.</small>
        </a>
        
        <a href="#" class="ad-card">
          <div class="ad-tag">Gaming</div>
          <strong>BC.GAME</strong><br>
          The Best BNB Casino with 1,000,000 BNB Daily Bonus. Claim Now<br>
          <small>5000+ Slots, Live casino games, 50+ cryptos</small>
        </a>
      </div>

      <div class="transaction-tabs">
        <button class="tab active">Overview</button>
        <button class="tab">State</button>
      </div>

      <div class="transaction-details">
        <div class="detail-row">
          <div class="detail-label">Transaction Hash:</div>
          <div class="detail-value">
            <span class="hash-value">{{ $tx->hash ?? 'N/A' }}</span>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Status:</div>
          <div class="detail-value">
            @if($tx->status === 'success')
              <span class="status-success">Success</span>
            @else
              <span class="status-failed">Failed</span>
            @endif
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Block:</div>
          <div class="detail-value">
            <a href="#" class="block-link">{{ $tx->block_number ?? 'N/A' }}</a>
            <span style="color: #6c757d; margin-left: 0.5rem;">{{ $tx->confirmations ?? 0 }} Block Confirmations</span>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Timestamp:</div>
          <div class="detail-value">
            {{ $tx->age ?? 'N/A' }} ({{ $tx->timestamp ?? 'N/A' }})
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">From:</div>
          <div class="detail-value">
            <a href="#" class="address-link">{{ $tx->from ?? 'N/A' }}</a>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">To:</div>
          <div class="detail-value">
            <a href="#" class="address-link">{{ $tx->to ?? 'N/A' }}</a>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Value:</div>
          <div class="detail-value">
            <div class="bnb-value">
              <div class="bnb-icon"></div>
              <span>
                @if(($tx->symbol ?? 'BNB') === 'BNB')
                  {{ number_format($tx->amount ?? 0, 8) }} BNB (${{ number_format($tx->usd_value ?? 0, 2) }})
                @else
                  {{ number_format($tx->amount ?? 0, 6) }} {{ $tx->symbol }} (${{ number_format($tx->usd_value ?? 0, 2) }})
                @endif
              </span>
            </div>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Transaction Fee:</div>
          <div class="detail-value">
            {{ number_format($tx->fee_bnb ?? 0, 8) }} BNB (${{ number_format(($tx->fee_bnb ?? 0) * ($tx->rate ?? 1118.51), 2) }})
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Gas Price:</div>
          <div class="detail-value">
            {{ number_format($tx->network_fee ?? 0, 8) }} BNB ({{ number_format(($tx->network_fee ?? 0) * ($tx->rate ?? 1118.51), 2) }} USD)
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Gas Limit & Usage by Txn:</div>
          <div class="detail-value">
            <div class="gas-usage">
              <span>21,000 | 21,000 (100%)</span>
              <div class="progress-bar">
                <div class="progress-fill"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Burnt Fees:</div>
          <div class="detail-value">
            🔥 {{ number_format($tx->fee_bnb ?? 0, 8) }} BNB (${{ number_format(($tx->fee_bnb ?? 0) * ($tx->rate ?? 1118.51), 2) }})
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Other Attributes:</div>
          <div class="detail-value">
            Nonce: {{ rand(0, 1000) }}<br>
            Position In Block: {{ rand(1, 500) }}
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Input Data:</div>
          <div class="detail-value">
            <div class="input-data">0x{{ str_pad(dechex((int)(($tx->amount ?? 0) * 1e18)), 64, '0', STR_PAD_LEFT) }}</div>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">More Details:</div>
          <div class="detail-value">
            <a href="#" class="more-details">Click to show more</a>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-label">Private Note:</div>
          <div class="detail-value">
            To access the <strong>Private Note</strong> feature, you must be <a href="#" class="block-link">Logged In</a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h4>Company</h4>
          <ul>
            <li><a href="#">Delegate to BscScan Staking</a></li>
            <li><a href="#">Brand Assets</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Terms & Privacy</a></li>
            <li><a href="#">Bug Bounty</a></li>
          </ul>
        </div>
        
        <div class="footer-section">
          <h4>Community</h4>
          <ul>
            <li><a href="#">API Documentation</a></li>
            <li><a href="#">Knowledge Base</a></li>
            <li><a href="#">Network Status</a></li>
            <li><a href="#">Learn BSC</a></li>
          </ul>
        </div>
        
        <div class="footer-section">
          <h4>Products & Services</h4>
          <ul>
            <li><a href="#">Advertise</a></li>
            <li><a href="#">Explorer as a Service (EaaS)</a></li>
            <li><a href="#">API Plans</a></li>
            <li><a href="#">Priority Support</a></li>
            <li><a href="#">Blockscan</a></li>
          </ul>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>BscScan © 2025 (BSC-C) | ⛏ Built by Team <strong>Etherscan</strong></p>
        <p>Donations: 0x71c765...d8976f</p>
      </div>
    </div>
  </footer>

  <script>
    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', function() {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
      });
    });

    document.querySelector('.more-details').addEventListener('click', function(e) {
      e.preventDefault();
      this.textContent = this.textContent === 'Click to show more' ? 'Click to show less' : 'Click to show more';
    });
  </script>
</body>
</html>