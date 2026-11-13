<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Models\UserCryptoBalance;
use App\Models\Transaction;
use App\Services\CryptoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CryptoController extends Controller
{
    protected CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /**
     * Resolve a cryptocurrency using its catalog symbol and network.
     *
     * Supports:
     * BTC
     * ETH
     * USDT + TRC20
     * USDT + ERC20
     * USDT + BEP20
     * USDT_TRC20
     */
    private function resolveCryptocurrency($symbol, $network = 'native')
    {
        $symbol = strtolower(trim((string) $symbol));
        $network = strtolower(trim((string) $network));

        // First try the exact internal catalog symbol.
        $crypto = Cryptocurrency::where('symbol', $symbol)->first();

        if ($crypto) {
            return $crypto;
        }

        // Support URLs using USDT as the symbol and the network separately.
        if ($symbol === 'usdt' && $network !== 'native' && $network !== '') {
            return Cryptocurrency::whereRaw('LOWER(name) = ?', ['usdt'])
                ->whereRaw('LOWER(network) = ?', [$network])
                ->first();
        }

        // Support network-specific symbols such as USDT_TRC20.
        if (str_starts_with($symbol, 'usdt_')) {
            $networkName = substr($symbol, 5);

            return Cryptocurrency::where('symbol', $symbol)->first()
                ?? Cryptocurrency::whereRaw('LOWER(name) = ?', ['usdt'])
                    ->whereRaw('LOWER(network) = ?', [$networkName])
                    ->first();
        }

        return null;
    }

    /**
     * Return the transaction identifier used by the transaction history.
     */
    private function getTransactionCryptoIdentifier(Cryptocurrency $crypto): string
    {
        return strtolower($crypto->symbol);
    }

    /**
     * Get or create the authenticated user's balance record.
     */
    private function getUserBalance($user, Cryptocurrency $crypto): UserCryptoBalance
    {
        return $crypto->getOrCreateUserBalance($user);
    }

    /**
     * Get live and manual prices for the cryptocurrency catalog.
     */
    private function getCryptoPrice(Cryptocurrency $crypto, array $prices): float
    {
        try {
            $effectivePrice = $crypto->getEffectivePrice($prices);

            if ($effectivePrice !== null && is_numeric($effectivePrice)) {
                return (float) $effectivePrice;
            }
        } catch (\Throwable $e) {
            Log::warning('Unable to calculate cryptocurrency price', [
                'symbol' => $crypto->symbol,
                'error' => $e->getMessage(),
            ]);
        }

        // Fallback to manual price if available.
        return (float) ($crypto->manual_price ?? 0);
    }

    /**
     * Cryptocurrency details page.
     */
    public function details($symbol, $network = 'native')
    {
        $user = Auth::user();

        $crypto = $this->resolveCryptocurrency($symbol, $network);

        if (!$crypto || !$crypto->is_active) {
            return redirect()
                ->route('user.crypto.manage')
                ->with('error', 'This cryptocurrency is currently unavailable.');
        }

        $balanceRecord = $this->getUserBalance($user, $crypto);

        $currentCrypto = $this->getTransactionCryptoIdentifier($crypto);

        $transactions = Transaction::where('user_id', $user->id)
            ->where(function ($query) use ($currentCrypto) {
                $query->where('which_crypto', $currentCrypto)
                    ->orWhere('from_crypto', $currentCrypto)
                    ->orWhere('to_crypto', $currentCrypto);
            })
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $prices = [];

        try {
            $prices = $this->cryptoService->getPrices() ?? [];
        } catch (\Throwable $e) {
            Log::error('Crypto details price retrieval failed', [
                'symbol' => $crypto->symbol,
                'error' => $e->getMessage(),
            ]);
        }

        $price = $this->getCryptoPrice($crypto, $prices);
        $balance = (float) $balanceRecord->balance;

        $displaySymbol = strtolower($crypto->name) === 'usdt'
            ? 'USDT'
            : strtoupper($crypto->symbol);

        $networkName = $crypto->network
            ? strtoupper($crypto->network)
            : null;

        $priceIdentifier = $crypto->coin_id;

        $assetData = [
            'id' => $crypto->id,
            'symbol' => $displaySymbol,
            'name' => $crypto->name,
            'balance' => $balance,
            'price' => $price,
            'value' => $balance * $price,
            'price_change_24h' => $prices[$priceIdentifier]['usd_24h_change'] ?? 0,
            'icon_url' => $crypto->icon_url,
            'network' => $networkName,
            'network_url' => $networkName
                ? $this->cryptoService->getNetworkIcon(strtolower($networkName))
                : null,
            'address' => $balanceRecord->address,
            'is_enabled' => $balanceRecord->is_enabled,
        ];

        return view('user.crypto.details', [
            'asset' => $assetData,
            'coinId' => $crypto->coin_id,
            'transactions' => $transactions,
            'currentCrypto' => $currentCrypto,
        ]);
    }

    /**
     * Wallet management page.
     *
     * Automatically displays every active cryptocurrency added by the admin.
     */
    public function manage()
    {
        $user = Auth::user();

        $cryptocurrencies = Cryptocurrency::where('is_active', true)
            ->orderBy('name')
            ->orderBy('network')
            ->get();

        $prices = [];

        try {
            $prices = $this->cryptoService->getPrices() ?? [];
        } catch (\Throwable $e) {
            Log::error('Crypto management price retrieval failed', [
                'error' => $e->getMessage(),
            ]);
        }

        $cryptos = $cryptocurrencies->map(function ($crypto) use ($user, $prices) {

            $balanceRecord = $this->getUserBalance($user, $crypto);

            $balance = (float) $balanceRecord->balance;
            $price = $this->getCryptoPrice($crypto, $prices);

            return [
                'id' => $crypto->id,
                'symbol' => strtoupper($crypto->symbol),
                'name' => $crypto->name,
                'network' => $crypto->network,
                'balance' => $balance,
                'status' => (bool) $balanceRecord->is_enabled,
                'address' => $balanceRecord->address,
                'price' => $price,
                'value' => $balance * $price,
                'icon' => $crypto->icon_url,
                'can_deposit' => (bool) $crypto->is_deposit_enabled,
                'can_withdraw' => (bool) $crypto->is_withdrawal_enabled,
            ];
        });

        return view('user.crypto.manage', [
            'cryptos' => $cryptos,
        ]);
    }

    /**
     * Enable or disable a cryptocurrency for the authenticated user.
     *
     * The crypto field accepts the catalog symbol, for example:
     * btc, eth, usdt_trc20, xrp, hbar.
     */
    public function updateManage(Request $request)
    {
        $validated = $request->validate([
            'crypto' => ['required', 'string', 'max:50'],
            'status' => ['required', 'boolean'],
        ]);

        try {
            $user = Auth::user();

            $crypto = $this->resolveCryptocurrency(
                $validated['crypto'],
                $request->input('network', 'native')
            );

            if (!$crypto || !$crypto->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cryptocurrency not found or unavailable.',
                ], 404);
            }

            $balanceRecord = $this->getUserBalance($user, $crypto);

            $balanceRecord->is_enabled = (bool) $validated['status'];
            $balanceRecord->save();

            return response()->json([
                'success' => true,
                'message' => 'Cryptocurrency status updated successfully.',
                'crypto' => $crypto->symbol,
                'status' => (bool) $balanceRecord->is_enabled,
            ]);

        } catch (\Throwable $e) {
            Log::error('Crypto Manage Update Error', [
                'user_id' => Auth::id(),
                'crypto' => $request->input('crypto'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update cryptocurrency status. Please try again.',
            ], 500);
        }
    }

    /**
     * Display wallet addresses for enabled cryptocurrencies.
     *
     * The receiving addresses are configured by administrators.
     */
    public function address()
    {
        $user = Auth::user();

        $cryptocurrencies = Cryptocurrency::where('is_active', true)
            ->where('is_deposit_enabled', true)
            ->orderBy('name')
            ->orderBy('network')
            ->get();

        $activeCryptos = $cryptocurrencies->map(function ($crypto) use ($user) {

            $balanceRecord = $this->getUserBalance($user, $crypto);

            if (!$balanceRecord->is_enabled) {
                return null;
            }

            return [
                'id' => $crypto->id,
                'symbol' => strtoupper($crypto->symbol),
                'name' => $crypto->name,
                'network' => $crypto->network,
                'balance' => $balanceRecord->balance,
                'address' => $crypto->wallet_address,
                'icon_url' => $crypto->icon_url,
                'can_deposit' => (bool) $crypto->is_deposit_enabled,
            ];
        })->filter()->values();

        return view('user.crypto.address', [
            'activeCryptos' => $activeCryptos,
        ]);
    }
}
