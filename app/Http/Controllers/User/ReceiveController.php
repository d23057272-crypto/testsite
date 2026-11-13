<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Services\CryptoService;
use Illuminate\Support\Facades\Auth;

class ReceiveController extends Controller
{
    private CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /**
     * Normalize network names used by routes and the database.
     */
    private function normalizeNetwork(?string $network): string
    {
        $network = strtoupper(trim($network ?? 'NATIVE'));

        if ($network === '' || $network === 'NATIVE') {
            return 'NATIVE';
        }

        if ($network === 'BNB') {
            return 'BEP20';
        }

        return $network;
    }

    /**
     * Retrieve the administrator-configured system deposit address.
     */
    private function getSystemWalletAddress(Cryptocurrency $crypto): string
    {
        return trim($crypto->wallet_address);
    }

    /**
     * Determine the display symbol.
     *
     * Network-specific assets use their catalog name.
     */
    private function getDisplaySymbol(Cryptocurrency $crypto): string
    {
        if (!empty($crypto->network)) {
            return strtoupper($crypto->name ?: $crypto->symbol);
        }

        return strtoupper($crypto->symbol);
    }

    /**
     * Build the asset information used by the Receive views.
     */
    private function formatAsset(
        Cryptocurrency $crypto,
        $balance,
        array $prices,
        ?string $address = null
    ): array {
        $price = (float) $crypto->getEffectivePrice($prices);

        $coinPriceData = $prices[$crypto->coin_id] ?? [];

        $change = (float) ($coinPriceData['usd_24h_change'] ?? 0);

        $balance = (float) ($balance ?? 0);

        $iconUrl = $crypto->icon_url
            ?: $this->cryptoService->getIconUrl($crypto->coin_id);

        $network = $this->normalizeNetwork($crypto->network);

        $displaySymbol = $this->getDisplaySymbol($crypto);

        $displayName = $crypto->name ?: $displaySymbol;

        if ($network !== 'NATIVE') {
            $displayName .= ' (' . $network . ')';
        }

        return [
            'id'       => $crypto->id,
            'symbol'   => $displaySymbol,
            'name'     => $displayName,
            'balance'  => $balance,
            'value'    => $balance * $price,
            'price'    => $price,
            'change'   => $change < 0
                ? number_format($change, 2)
                : '+' . number_format($change, 2),
            'icon_url' => $iconUrl,
            'network'  => $network === 'NATIVE' ? null : $network,

            // Always provide the address key to the Blade views.
            'address'  => trim((string) ($address ?? '')),
        ];
    }

    /**
     * Get all deposit-enabled cryptocurrencies for the authenticated user.
     */
    private function getUserReceiveAssets(bool $includeAddresses = false): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        $prices = $this->cryptoService->getPrices();

        $cryptocurrencies = Cryptocurrency::query()
            ->where('is_active', true)
            ->where('is_deposit_enabled', true)
            ->orderBy('name')
            ->orderBy('network')
            ->get();

        $assets = [];

        foreach ($cryptocurrencies as $crypto) {
            /*
             * Create the user's balance record if this currency
             * was recently added by an administrator.
             */
            $userBalance = $crypto->getOrCreateUserBalance($user);

            if (!$userBalance) {
                continue;
            }

            if (!(bool) $userBalance->is_enabled) {
                continue;
            }

            /*
             * System deposit addresses are stored in settings.
             * User balances remain user-specific.
             */
            $address = $includeAddresses
                ? $this->getSystemWalletAddress($crypto)
                : null;

            $assets[] = $this->formatAsset(
                $crypto,
                $userBalance->balance,
                $prices,
                $address
            );
        }

        return $assets;
    }

    /**
     * Find a deposit-enabled cryptocurrency by symbol and network.
     */
    private function findReceiveCryptocurrency(
        string $symbol,
        ?string $network = 'native'
    ): ?Cryptocurrency {
        $symbol = strtoupper(trim($symbol));
        $network = $this->normalizeNetwork($network);

        $cryptocurrencies = Cryptocurrency::query()
            ->where('is_active', true)
            ->where('is_deposit_enabled', true)
            ->where(function ($query) use ($symbol) {
                $query->whereRaw('UPPER(symbol) = ?', [$symbol])
                    ->orWhereRaw('UPPER(name) = ?', [$symbol]);
            })
            ->get();

        foreach ($cryptocurrencies as $crypto) {
            $catalogNetwork = $this->normalizeNetwork($crypto->network);

            if ($catalogNetwork === $network) {
                return $crypto;
            }
        }

        return null;
    }

    /**
     * Get a user's balance record for a particular cryptocurrency.
     */
    private function getUserBalance(
        Cryptocurrency $crypto,
        $user
    ) {
        if (!$user) {
            return null;
        }

        $balance = $crypto->getOrCreateUserBalance($user);

        if (!$balance || !(bool) $balance->is_enabled) {
            return null;
        }

        return $balance;
    }

    /**
     * RECEIVE THROUGH PAYID - ASSET LIST
     */
    public function payidAssets()
    {
        $assets = $this->getUserReceiveAssets(false);

        if (empty($assets)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'No deposit-enabled cryptocurrencies are currently available.');
        }

        return view('user.receive.assets-payid', [
            'assets' => $assets,
        ]);
    }

    /**
     * RECEIVE THROUGH EXTERNAL WALLET - ASSET LIST
     */
    public function externalAssets()
    {
        $assets = $this->getUserReceiveAssets(true);

        if (empty($assets)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'No deposit-enabled cryptocurrencies are currently available.');
        }

        return view('user.receive.assets-external', [
            'assets' => $assets,
        ]);
    }

    /**
     * PAYID RECEIVE DETAILS
     */
    public function payidDetails($symbol, $network = 'native')
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Please log in to continue.');
        }

        $crypto = $this->findReceiveCryptocurrency($symbol, $network);

        if (!$crypto) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'This cryptocurrency or network is not available for receiving.');
        }

        $userBalance = $this->getUserBalance($crypto, $user);

        if (!$userBalance) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'This cryptocurrency is not enabled for your account.');
        }

        $prices = $this->cryptoService->getPrices();

        $asset = $this->formatAsset(
            $crypto,
            $userBalance->balance,
            $prices
        );

        /*
         * PayID QR identifies the recipient and requested asset.
         */
        $qrData = [
            'payId'   => $user->uuid,
            'network' => $asset['network'] ?? 'native',
            'symbol'  => $asset['symbol'],
            'type'    => 'DB_PAYMENT',
        ];

        return view('user.receive.payid-details', [
            'asset'  => $asset,
            'user'   => $user,
            'qrData' => json_encode($qrData),
        ]);
    }

    /**
     * EXTERNAL WALLET RECEIVE DETAILS
     */
    public function externalDetails($symbol, $network = 'native')
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Please log in to continue.');
        }

        $crypto = $this->findReceiveCryptocurrency($symbol, $network);

        if (!$crypto) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'This cryptocurrency or network is not available for receiving.');
        }

        $userBalance = $this->getUserBalance($crypto, $user);

        if (!$userBalance) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'This cryptocurrency is not enabled for your account.');
        }

        $prices = $this->cryptoService->getPrices();

        /*
         * IMPORTANT:
         * Retrieve the administrator-configured system deposit address
         * from the settings table instead of the user's balance record.
         */
        $address = $this->getSystemWalletAddress($crypto);

        $asset = $this->formatAsset(
            $crypto,
            $userBalance->balance,
            $prices,
            $address
        );

        return view('user.receive.external-details', [
            'asset' => $asset,
        ]);
    }
}