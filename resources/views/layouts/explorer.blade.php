<x-user-layout :title="'Transaction'">
<style>
.explorer-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', monospace;
}
.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    padding: 24px;
    margin-bottom: 24px;
}
.header {
    text-align: center;
    margin-bottom: 24px;
}
.logo {
    font-size: 24px;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 8px;
}
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-completed { background: #dcfce7; color: #166534; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-failed { background: #fee2e2; color: #b91c1c; }
.demo-badge {
    background: #fee2e2;
    color: #b91c1c;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    margin-top: 8px;
    display: inline-block;
}
.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
}
.detail-row:last-child { border-bottom: none; }
.label { color: #6b7280; font-size: 14px; }
.value { font-weight: 600; text-align: right; word-break: break-all; }
.hash { 
    font-family: monospace; 
    background: #f9fafb; 
    padding: 12px; 
    border-radius: 6px; 
    margin: 16px 0;
    font-size: 14px;
}
.address { 
    font-family: monospace; 
    font-size: 14px; 
    color: #4f46e5;
}
</style>

<div class="explorer-container">
    <div class="header">
        <div class="logo">CovaChain Explorer</div>
        @if($isSimulated)
            <div class="demo-badge">SIMULATED TRANSACTION • DEMO ONLY</div>
        @endif
        <div class="status-badge status-{{ $transaction->status }}">
            {{ ucfirst($transaction->status) }}
        </div>
    </div>

    <!-- Transaction Hash -->
    <div class="card">
        <div class="hash">{{ $transaction->transaction_hash }}</div>
        <div class="text-center mt-2">
            <a href="https://fauxchain.io/{{ strtolower($transaction->which_crypto ?? 'btc') }}/tx/{{ $transaction->transaction_hash }}"
               target="_blank"
               class="text-blue-600 hover:underline text-sm">
                View on Fauxchain
            </a>
        </div>
    </div>

    <!-- Summary -->
    <div class="card">
        <div class="detail-row">
            <span class="label">Amount</span>
            <span class="value">
                {{ number_format($transaction->amount_in ?? 0, 8) }} {{ strtoupper($transaction->which_crypto ?? 'CRYPTO') }}
            </span>
        </div>
        <div class="detail-row">
            <span class="label">Type</span>
            <span class="value">{{ ucfirst($transaction->type) }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Network</span>
            <span class="value">{{ strtoupper($transaction->which_crypto ?? 'N/A') }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Confirmations</span>
            <span class="value">{{ $confirmations }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Block Height</span>
            <span class="value">{{ $blockHeight }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Timestamp</span>
            <span class="value">{{ date('Y-m-d H:i:s', $timestamp) }}</span>
        </div>
        @if($transaction->network_fee)
        <div class="detail-row">
            <span class="label">Network Fee</span>
            <span class="value">{{ number_format($transaction->network_fee, 8) }} {{ strtoupper($transaction->which_crypto ?? 'CRYPTO') }}</span>
        </div>
        @endif
    </div>

    <!-- Addresses -->
    @if($transaction->from_address || $transaction->to_address)
    <div class="card">
        @if($transaction->from_address)
        <div class="detail-row">
            <span class="label">From</span>
            <span class="value address">{{ $transaction->from_address }}</span>
        </div>
        @endif
        @if($transaction->to_address)
        <div class="detail-row">
            <span class="label">To</span>
            <span class="value address">{{ $transaction->to_address }}</span>
        </div>
        @endif
    </div>
    @endif

    <div class="text-center text-gray-500 text-sm mt-6">
        Data provided by CovaChain Wallet • Not a real blockchain explorer
    </div>
</div>
</x-user-layout>