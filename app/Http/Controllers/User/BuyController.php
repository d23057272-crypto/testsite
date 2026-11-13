<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use App\Services\CryptoService;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    private CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /**
     * Normalize network names.
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
     * Get the display symbol for a catalog cryptocurrency.
     */
    private function getDisplaySymbol(Cryptocurrency $crypto): string
    {
        if (!empty($crypto->network)) {
            return strtoupper($crypto->name ?: $crypto->symbol);
        }

        return strtoupper($crypto->symbol);
    }

    /**
     * Get the network icon identifier where applicable.
     */
    private function getNetworkCoinId(?string $network): ?string
    {
        return match ($this->normalizeNetwork($network)) {
            'TRC20' => 'tron',
            'BEP20' => 'binancecoin',
            'ERC20' => 'ethereum',
            default => null,
        };
    }

    /**
     * Format cryptocurrency information for the Buy views.
     */
    private function formatAsset(
        Cryptocurrency $crypto,
        $balance,
        array $prices
    ): array {
        $price = (float) $crypto->getEffectivePrice($prices);

        $priceData = $prices[$crypto->coin_id] ?? [];

        $change = (float) ($priceData['usd_24h_change'] ?? 0);

        $balance = (float) $balance;

        $network = $this->normalizeNetwork($crypto->network);

        $displaySymbol = $this->getDisplaySymbol($crypto);

        $displayName = $crypto->name ?: $displaySymbol;

        if ($network !== 'NATIVE') {
            $displayName .= ' (' . $network . ')';
        }

        $iconUrl = $crypto->icon_url
            ?: $this->cryptoService->getIconUrl($crypto->coin_id);

        $networkCoinId = $this->getNetworkCoinId($crypto->network);

        $networkUrl = $networkCoinId
            ? $this->cryptoService->getIconUrl($networkCoinId)
            : null;

        return [
            'id' => $crypto->id,
            'symbol' => $displaySymbol,
            'name' => $displayName,
            'balance' => $balance,
            'value' => $balance * $price,
            'price' => $price,
            'change' => $change < 0
                ? number_format($change, 2)
                : '+' . number_format($change, 2),
            'icon_url' => $iconUrl,
            'network_url' => $networkUrl,
            'network' => $network === 'NATIVE' ? null : $network,
        ];
    }

    /**
     * Retrieve all active catalog cryptocurrencies for the user.
     *
     * Missing balance records are automatically created.
     */
    private function getUserBuyAssets(): array
    {
        $user = Auth::user();

        $prices = $this->cryptoService->getPrices();

        $cryptocurrencies = Cryptocurrency::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->orderBy('network')
            ->get();

        $assets = [];

        foreach ($cryptocurrencies as $crypto) {
            $userBalance = $crypto->getOrCreateUserBalance($user);

            if (!$userBalance) {
                continue;
            }

            if (!(bool) $userBalance->is_enabled) {
                continue;
            }

            $assets[] = $this->formatAsset(
                $crypto,
                $userBalance->balance,
                $prices
            );
        }

        return $assets;
    }

    /**
     * Find a cryptocurrency using its catalog symbol/name and network.
     */
    private function findCryptocurrency(
        string $symbol,
        ?string $network = 'native'
    ): ?Cryptocurrency {
        $symbol = strtoupper(trim($symbol));

        $network = $this->normalizeNetwork($network);

        $cryptocurrencies = Cryptocurrency::query()
            ->where('is_active', true)
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
     * BUY ASSET LIST
     */
    public function index()
    {
        $assets = $this->getUserBuyAssets();

        if (empty($assets)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'No active cryptocurrencies are currently available.');
        }

        return view('user.buy.index', [
            'assets' => $assets,
        ]);
    }

    /**
     * BUY ASSET DETAILS
     */
    public function details($symbol, $network = 'native')
    {
        $user = Auth::user();

        $crypto = $this->findCryptocurrency($symbol, $network);

        if (!$crypto) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Cryptocurrency not found or currently unavailable.');
        }

        $userBalance = $crypto->getOrCreateUserBalance($user);

        if (!$userBalance || !(bool) $userBalance->is_enabled) {
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

        return view('user.buy.details', [
            'asset' => $asset,
        ]);
    }
}
