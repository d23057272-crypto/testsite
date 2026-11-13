<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\TransactionEmail;
use App\Models\CryptoAsset;
use App\Models\Cryptocurrency;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserCryptoBalance;
use App\Services\CryptoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendController extends Controller
{
    protected CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /*
    |--------------------------------------------------------------------------
    | Cryptocurrency lookup
    |--------------------------------------------------------------------------
    */

    private function resolveCrypto(
        $symbol,
        $network = 'native'
    ): ?Cryptocurrency {
        $symbol = strtolower(trim((string) $symbol));
        $network = strtolower(trim((string) $network));

        // Preserve compatibility with older BNB network URLs.
        if ($network === 'bnb') {
            $network = 'bep20';
        }

        // First try the exact catalog symbol.
        $crypto = Cryptocurrency::whereRaw(
            'LOWER(symbol) = ?',
            [$symbol]
        )->first();

        if ($crypto) {
            return $crypto;
        }

        // Handle network-specific assets.
        if ($network !== 'native' && $network !== '') {
            $networkAliases = [
                'bnb' => 'bep20',
                'bsc' => 'bep20',
                'ethereum' => 'erc20',
                'tron' => 'trc20',
            ];

            $network = $networkAliases[$network] ?? $network;

            $crypto = Cryptocurrency::whereRaw(
                'LOWER(name) = ?',
                [$symbol]
            )->whereRaw(
                'LOWER(network) = ?',
                [$network]
            )->first();

            if ($crypto) {
                return $crypto;
            }
        }

        // Handle symbols such as usdt_trc20.
        if (str_starts_with($symbol, 'usdt_')) {
            $networkName = substr($symbol, 5);

            return Cryptocurrency::whereRaw(
                'LOWER(symbol) = ?',
                [$symbol]
            )->first()
            ?? Cryptocurrency::whereRaw(
                'LOWER(name) = ?',
                ['usdt']
            )->whereRaw(
                'LOWER(network) = ?',
                [$networkName]
            )->first();
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | User cryptocurrency balances
    |--------------------------------------------------------------------------
    */

    private function getUserBalance(
        User $user,
        Cryptocurrency $crypto
    ): UserCryptoBalance {
        return $crypto->getOrCreateUserBalance($user);
    }

    private function getCryptoIdentifier(
        Cryptocurrency $crypto
    ): string {
        return strtolower($crypto->symbol);
    }

    private function getDisplaySymbol(
        Cryptocurrency $crypto
    ): string {
        if (strtolower($crypto->name) === 'usdt') {
            return 'USDT';
        }

        return strtoupper($crypto->symbol);
    }

    /*
    |--------------------------------------------------------------------------
    | Cryptocurrency prices and icons
    |--------------------------------------------------------------------------
    */

    private function getPricesSafely(): array
    {
        try {
            return $this->cryptoService->getPrices() ?? [];
        } catch (\Throwable $e) {
            Log::warning('Cryptocurrency price retrieval failed', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    private function getAssetPrice(
        Cryptocurrency $crypto,
        array $prices
    ): float {
        try {
            $price = $crypto->getEffectivePrice($prices);

            if ($price !== null && is_numeric($price)) {
                return (float) $price;
            }
        } catch (\Throwable $e) {
            Log::warning('Cryptocurrency price lookup failed', [
                'symbol' => $crypto->symbol,
                'error' => $e->getMessage(),
            ]);
        }

        return (float) ($crypto->manual_price ?? 0);
    }

    private function getAssetIcon(
        Cryptocurrency $crypto
    ): ?string {
        if (!empty($crypto->icon_url)) {
            return $crypto->icon_url;
        }

        if (!empty($crypto->coin_id)) {
            try {
                return $this->cryptoService->getIconUrl(
                    $crypto->coin_id
                );
            } catch (\Throwable $e) {
                Log::warning('Cryptocurrency icon lookup failed', [
                    'symbol' => $crypto->symbol,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Format assets for existing Blade views
    |--------------------------------------------------------------------------
    */

    private function formatAsset(
        Cryptocurrency $crypto,
        UserCryptoBalance $balanceRecord,
        array $prices
    ): array {
        $price = $this->getAssetPrice($crypto, $prices);
        $balance = (float) $balanceRecord->balance;

        $coinId = $crypto->coin_id;

        $change = $coinId
            ? ($prices[$coinId]['usd_24h_change'] ?? 0)
            : 0;

        $network = $crypto->network
            ? strtoupper($crypto->network)
            : null;

        $asset = [
            'id' => $crypto->id,
            'symbol' => $this->getDisplaySymbol($crypto),
            'name' => $crypto->name . (
                $network ? " ({$network})" : ''
            ),
            'balance' => $balance,
            'value' => $balance * $price,
            'price' => $price,
            'change' => ((float) $change < 0 ? '' : '+')
                . number_format((float) $change, 2),
            'icon_url' => $this->getAssetIcon($crypto),
            'network' => $network,
        ];

        if ($network) {
            $networkCoinId = match (strtolower($network)) {
                'trc20' => 'tron',
                'erc20' => 'ethereum',
                'bep20' => 'binancecoin',
                default => null,
            };

            $asset['network_url'] = $networkCoinId
                ? $this->cryptoService->getIconUrl($networkCoinId)
                : null;
        }

        return $asset;
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamically retrieve sendable cryptocurrencies
    |--------------------------------------------------------------------------
    */

    private function getSendableAssets(User $user): array
    {
        $cryptocurrencies = Cryptocurrency::where(
            'is_active',
            true
        )->where(
            'is_withdrawal_enabled',
            true
        )->orderBy(
            'name'
        )->orderBy(
            'network'
        )->get();

        $prices = $this->getPricesSafely();

        $assets = [];

        foreach ($cryptocurrencies as $crypto) {
            $balanceRecord = $this->getUserBalance(
                $user,
                $crypto
            );

            if (!$balanceRecord->is_enabled) {
                continue;
            }

            $assets[] = $this->formatAsset(
                $crypto,
                $balanceRecord,
                $prices
            );
        }

        return $assets;
    }

    /*
    |--------------------------------------------------------------------------
    | Selected asset details
    |--------------------------------------------------------------------------
    */

    private function getSelectedAsset(
        User $user,
        $symbol,
        $network = 'native'
    ): array {
        $crypto = $this->resolveCrypto($symbol, $network);

        if (
            !$crypto ||
            !$crypto->is_active ||
            !$crypto->is_withdrawal_enabled
        ) {
            throw new \RuntimeException(
                'This cryptocurrency is unavailable for sending.'
            );
        }

        $balanceRecord = $this->getUserBalance(
            $user,
            $crypto
        );

        if (!$balanceRecord->is_enabled) {
            throw new \RuntimeException(
                'This cryptocurrency is disabled in your wallet.'
            );
        }

        $prices = $this->getPricesSafely();

        $asset = $this->formatAsset(
            $crypto,
            $balanceRecord,
            $prices
        );

        $asset['symbol'] = $this->getDisplaySymbol($crypto);
        $asset['name'] = $crypto->name;

        $asset['network'] = $crypto->network
            ? strtoupper($crypto->network)
            : (
                strtoupper($network) !== 'NATIVE'
                    ? strtoupper($network)
                    : null
            );

        $asset['fee'] = (float) $crypto->withdrawal_fee;
        $asset['min_withdrawal'] = (float) $crypto->min_withdrawal;
        $asset['address'] = $balanceRecord->address;
        $asset['system_wallet_address'] = trim($crypto->wallet_address);

        return [
            'crypto' => $crypto,
            'balanceRecord' => $balanceRecord,
            'asset' => $asset,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy transaction compatibility
    |--------------------------------------------------------------------------
    |
    | The existing transactions table uses cryptoasset_id.
    | The actual wallet balance is stored in user_crypto_balances.
    |
    */

    private function getLegacyTransactionAsset(
        User $user
    ): CryptoAsset {
        return CryptoAsset::firstOrCreate([
            'user_id' => $user->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Sending permissions
    |--------------------------------------------------------------------------
    */

    private function checkSendingPermission(
        User $user
    ): ?string {
        if (
            $user->require_kyc &&
            $user->kyc_status !== 'approved'
        ) {
            return 'Please complete KYC verification before sending cryptocurrency.';
        }

        if (!$user->enable_crypto_sending) {
            return 'Crypto sending is currently disabled. Please contact support.';
        }

        return null;
    }

    private function permissionRedirect(
        User $user,
        string $message
    ) {
        $route = (
            $user->require_kyc &&
            $user->kyc_status !== 'approved'
        ) ? 'kyc' : 'dashboard';

        return redirect()->route($route)->with(
            'error',
            $message
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Asset lists
    |--------------------------------------------------------------------------
    */

    public function payidAssets()
    {
        $user = Auth::user();

        return view('user.send.assets-payid', [
            'assets' => $this->getSendableAssets($user),
        ]);
    }

    public function externalAssets()
    {
        $user = Auth::user();

        return view('user.send.assets-external', [
            'assets' => $this->getSendableAssets($user),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Asset details
    |--------------------------------------------------------------------------
    */

    public function payidDetails(
        $symbol,
        $network = 'native'
    ) {
        $user = Auth::user();

        $permissionError = $this->checkSendingPermission($user);

        if ($permissionError) {
            return $this->permissionRedirect(
                $user,
                $permissionError
            );
        }

        try {
            $selected = $this->getSelectedAsset(
                $user,
                $symbol,
                $network
            );

            $asset = $selected['asset'];

            // Internal transfers have no blockchain network fee.
            $asset['fee'] = 0;

            return view('user.send.payid-details', [
                'asset' => $asset,
            ]);

        } catch (\Throwable $e) {
            Log::warning('PayID asset details failed', [
                'symbol' => $symbol,
                'network' => $network,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('dashboard')
                ->with('error', $e->getMessage());
        }
    }

    public function externalDetails(
        $symbol,
        $network = 'native'
    ) {
        $user = Auth::user();

        $permissionError = $this->checkSendingPermission($user);

        if ($permissionError) {
            return $this->permissionRedirect(
                $user,
                $permissionError
            );
        }

        try {
            $selected = $this->getSelectedAsset(
                $user,
                $symbol,
                $network
            );

            return view('user.send.external-details', [
                'asset' => $selected['asset'],
            ]);

        } catch (\Throwable $e) {
            Log::warning('External asset details failed', [
                'symbol' => $symbol,
                'network' => $network,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('dashboard')
                ->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Success and failure pages
    |--------------------------------------------------------------------------
    */

    public function success(
        Request $request,
        $symbol,
        $network = 'native',
        $transactionId = null
    ) {
        $transaction = Transaction::where(
            'id',
            $transactionId
        )->where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        return view('user.send.payid-success', [
            'transaction' => $transaction,
            'symbol' => $symbol,
            'network' => $network,
        ]);
    }

    public function failed(
        Request $request,
        $symbol,
        $network = 'native'
    ) {
        return view('user.send.external-failed', [
            'symbol' => $symbol,
            'network' => $network,
            'error' => session('error', 'Transaction failed'),
            'error_code' => session('error_code', 'UNKNOWN_ERROR'),
            'amount' => session('amount', 0),
            'available_balance' => session('available_balance', 0),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PayID verification
    |--------------------------------------------------------------------------
    */

    public function verifyPayId(Request $request)
    {
        $validated = $request->validate([
            'payid' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $recipient = User::where(
            'uuid',
            trim($validated['payid'])
        )->first();

        if (!$recipient) {
            return response()->json([
                'success' => false,
                'message' => 'PayID not found.',
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $recipient->name,
                'uuid' => $recipient->uuid,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PayID processing
    |--------------------------------------------------------------------------
    */

    public function processPayId(
        Request $request,
        $symbol,
        $network = 'native'
    ) {
        $validated = $request->validate([
            'payid' => [
                'required',
                'string',
                'max:255',
            ],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'regex:/^\d+(\.\d{1,8})?$/',
            ],
        ]);

        $sender = Auth::user();

        $recipient = User::where(
            'uuid',
            trim($validated['payid'])
        )->first();

        $redirect = route('send.payid.details', [
            'symbol' => $symbol,
            'network' => $network,
        ]);

        $permissionError = $this->checkSendingPermission(
            $sender
        );

        if ($permissionError) {
            return redirect($redirect)->with([
                'error' => $permissionError,
                'error_code' => 'SENDING_DISABLED',
                'amount' => $validated['amount'],
            ]);
        }

        if (!$recipient) {
            return redirect($redirect)->with([
                'error' => 'PayID not found.',
                'error_code' => 'RECIPIENT_NOT_FOUND',
                'amount' => $validated['amount'],
            ]);
        }

        if ((int) $sender->id === (int) $recipient->id) {
            return redirect($redirect)->with([
                'error' => 'You cannot send cryptocurrency to yourself.',
                'error_code' => 'SELF_TRANSFER',
                'amount' => $validated['amount'],
            ]);
        }

        try {
            $crypto = $this->resolveCrypto(
                $symbol,
                $network
            );

            if (
                !$crypto ||
                !$crypto->is_active ||
                !$crypto->is_withdrawal_enabled
            ) {
                throw new \RuntimeException(
                    'This cryptocurrency is unavailable for sending.'
                );
            }

            $amount = (float) $validated['amount'];

            $minimum = (float) $crypto->min_withdrawal;

            if ($minimum > 0 && $amount < $minimum) {
                throw new \RuntimeException(
                    'The minimum transfer amount is ' . $minimum . '.'
                );
            }

            $whichCrypto = $this->getCryptoIdentifier(
                $crypto
            );

            $senderLegacyAsset = $this->getLegacyTransactionAsset(
                $sender
            );

            $recipientLegacyAsset = $this->getLegacyTransactionAsset(
                $recipient
            );

            $result = DB::transaction(function () use (
                $sender,
                $recipient,
                $crypto,
                $amount,
                $whichCrypto,
                $senderLegacyAsset,
                $recipientLegacyAsset,
                $validated,
                $network
            ) {
                $senderBalance = UserCryptoBalance::where(
                    'user_id',
                    $sender->id
                )->where(
                    'cryptocurrency_id',
                    $crypto->id
                )->lockForUpdate()->first();

                if (
                    !$senderBalance ||
                    !$senderBalance->is_enabled
                ) {
                    throw new \RuntimeException(
                        'This cryptocurrency is not enabled in your wallet.'
                    );
                }

                // Create a recipient balance if one does not exist.
                $recipientBalance = $crypto->getOrCreateUserBalance($recipient);

                // Lock the recipient balance before updating it.
                $recipientBalance = UserCryptoBalance::where(
                    'id',
                    $recipientBalance->id
                )->lockForUpdate()->firstOrFail();

                if ((float) $senderBalance->balance < $amount) {
                    throw new \RuntimeException(
                        'Insufficient balance.'
                    );
                }

                $senderBalance->balance =
                    (float) $senderBalance->balance - $amount;

                $recipientBalance->balance =
                    (float) $recipientBalance->balance + $amount;

                $senderBalance->save();
                $recipientBalance->save();

                $senderTransaction = Transaction::create([
                    'user_id' => $sender->id,
                    'cryptoasset_id' => $senderLegacyAsset->id,
                    'type' => 'withdrawal',
                    'which_crypto' => $whichCrypto,
                    'amount_out' => $amount,
                    'amount_in' => $amount,
                    'status' => Transaction::STATUS_COMPLETED,
                    'network_fee' => 0,
                    'metadata' => [
                        'transfer_type' => 'internal',
                        'recipient_id' => $recipient->id,
                        'recipient_name' => $recipient->name,
                        'recipient_payid' => $validated['payid'],
                        'cryptocurrency_id' => $crypto->id,
                        'network' => $network,
                    ],
                ]);

                $recipientTransaction = Transaction::create([
                    'user_id' => $recipient->id,
                    'cryptoasset_id' => $recipientLegacyAsset->id,
                    'type' => 'deposit',
                    'which_crypto' => $whichCrypto,
                    'amount_in' => $amount,
                    'amount_out' => 0,
                    'status' => Transaction::STATUS_COMPLETED,
                    'network_fee' => 0,
                    'metadata' => [
                        'transfer_type' => 'internal',
                        'sender_id' => $sender->id,
                        'sender_name' => $sender->name,
                        'sender_payid' => $sender->uuid,
                        'cryptocurrency_id' => $crypto->id,
                        'network' => $network,
                    ],
                ]);

                Notification::create([
                    'user_id' => $sender->id,
                    'type' => 'send_crypto',
                    'title' => 'Crypto Sent',
                    'message' => sprintf(
                        'You sent %s %s to %s',
                        number_format($amount, 8),
                        strtoupper($whichCrypto),
                        $recipient->name
                    ),
                    'is_read' => false,
                    'extra_data' => json_encode([
                        'transaction_id' => $senderTransaction->id,
                        'amount' => $amount,
                        'crypto' => $whichCrypto,
                        'recipient_id' => $recipient->id,
                    ]),
                ]);

                Notification::create([
                    'user_id' => $recipient->id,
                    'type' => 'receive_crypto',
                    'title' => 'Crypto Received',
                    'message' => sprintf(
                        'You received %s %s from %s',
                        number_format($amount, 8),
                        strtoupper($whichCrypto),
                        $sender->name
                    ),
                    'is_read' => false,
                    'extra_data' => json_encode([
                        'transaction_id' => $recipientTransaction->id,
                        'amount' => $amount,
                        'crypto' => $whichCrypto,
                        'sender_id' => $sender->id,
                    ]),
                ]);

                return [
                    'sender_transaction' => $senderTransaction,
                    'recipient_transaction' => $recipientTransaction,
                ];
            });

            // Send emails after the database transaction commits.
            Mail::to($sender->email)->queue(
                new TransactionEmail(
                    $result['sender_transaction']
                )
            );

            Mail::to($recipient->email)->queue(
                new TransactionEmail(
                    $result['recipient_transaction']
                )
            );

            return redirect()->route('send.payid.success', [
                'symbol' => $symbol,
                'network' => $network,
                'transaction' => $result['sender_transaction']->id,
            ])->with(
                'success',
                'Transaction completed successfully.'
            );

        } catch (\Throwable $e) {
            Log::error('PayID transfer failed', [
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'symbol' => $symbol,
                'network' => $network,
                'error' => $e->getMessage(),
            ]);

            return redirect($redirect)->with([
                'error' => $e->getMessage(),
                'error_code' => 'TRANSACTION_FAILED',
                'amount' => $validated['amount'],
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | External wallet address validation
    |--------------------------------------------------------------------------
    */

    public function verifyExternal(Request $request)
    {
        $validated = $request->validate([
            'address' => [
                'required',
                'string',
                'max:255',
            ],
            'symbol' => [
                'required',
                'string',
                'max:50',
            ],
            'network' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        $crypto = $this->resolveCrypto(
            $validated['symbol'],
            $validated['network'] ?? 'native'
        );

        if (
            !$crypto ||
            !$crypto->is_active ||
            !$crypto->is_withdrawal_enabled
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Cryptocurrency or network is unavailable.',
            ]);
        }

        $valid = $this->validateCryptoAddress(
            trim($validated['address']),
            $crypto
        );

        return response()->json([
            'success' => $valid,
            'message' => $valid
                ? 'Address format accepted.'
                : 'Address format is unsupported or invalid for this network.',
        ]);
    }

    /**
     * Address format validation.
     *
     * This does not verify that an address exists on the blockchain.
     */
    private function validateCryptoAddress(
        string $address,
        Cryptocurrency $crypto
    ): bool {
        $symbol = strtolower($crypto->symbol);
        $name = strtolower($crypto->name);
        $network = strtolower((string) $crypto->network);

        if (
            $name === 'usdt' ||
            str_starts_with($symbol, 'usdt_')
        ) {
            if (!$network && str_starts_with($symbol, 'usdt_')) {
                $network = substr($symbol, 5);
            }
        }

        return match ($network) {
            'erc20',
            'ethereum',
            'eth',
            'bep20',
            'bsc',
            'bnb',
            'polygon',
            'matic',
            'arbitrum',
            'optimism',
            'base' =>
                (bool) preg_match(
                    '/^0x[a-fA-F0-9]{40}$/',
                    $address
                ),

            'trc20',
            'tron',
            'trx' =>
                (bool) preg_match(
                    '/^T[1-9A-HJ-NP-Za-km-z]{33}$/',
                    $address
                ),

            'bitcoin',
            'btc' =>
                (bool) preg_match(
                    '/^(1[1-9A-HJ-NP-Za-km-z]{25,34}|3[1-9A-HJ-NP-Za-km-z]{25,34}|bc1[ac-hj-np-z02-9]{11,71})$/i',
                    $address
                ),

            'solana',
            'sol' =>
                (bool) preg_match(
                    '/^[1-9A-HJ-NP-Za-km-z]{32,44}$/',
                    $address
                ),

            'stellar',
            'xlm' =>
                (bool) preg_match(
                    '/^G[A-Z2-7]{55}$/',
                    $address
                ),

            'xrp',
            'ripple' =>
                (bool) preg_match(
                    '/^r[1-9A-HJ-NP-Za-km-z]{24,34}$/',
                    $address
                ),

            'xdc',
            'xdc network' =>
                (bool) preg_match(
                    '/^(xdc)?[a-fA-F0-9]{40}$/i',
                    $address
                ),

            'litecoin',
            'ltc' =>
                (bool) preg_match(
                    '/^(L|M|ltc1)[a-zA-HJ-NP-Z0-9]{20,60}$/',
                    $address
                ),

            'dogecoin',
            'doge' =>
                (bool) preg_match(
                    '/^D[1-9A-HJ-NP-Za-km-z]{25,34}$/',
                    $address
                ),

            'bitcoin cash',
            'bitcoin-cash',
            'bch' =>
                (bool) preg_match(
                    '/^(bitcoincash:)?(q|p)[a-z0-9]{41}$/i',
                    $address
                ),

            'dash' =>
                (bool) preg_match(
                    '/^[X7][1-9A-HJ-NP-Za-km-z]{24,34}$/',
                    $address
                ),

            'polkadot',
            'dot' =>
                (bool) preg_match(
                    '/^[1-9A-HJ-NP-Za-km-z]{47,48}$/',
                    $address
                ),

            default => $this->validateBySymbol(
                $address,
                $symbol
            ),
        };
    }

    private function validateBySymbol(
        string $address,
        string $symbol
    ): bool {
        return match ($symbol) {
            'btc' => (bool) preg_match(
                '/^(1|3|bc1)[a-zA-Z0-9]{26,45}$/',
                $address
            ),

            'eth',
            'bnb' => (bool) preg_match(
                '/^0x[a-fA-F0-9]{40}$/',
                $address
            ),

            'trx' => (bool) preg_match(
                '/^T[1-9A-HJ-NP-Za-km-z]{33}$/',
                $address
            ),

            'sol' => (bool) preg_match(
                '/^[1-9A-HJ-NP-Za-km-z]{32,44}$/',
                $address
            ),

            'xmr' => (bool) preg_match(
                '/^[4-9A-B][1-9A-HJ-NP-Za-km-z]{94}$/',
                $address
            ),

            'zec' => (bool) preg_match(
                '/^(t1|t3)[a-zA-Z0-9]{33}$/',
                $address
            ),

            default => false,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | External withdrawal processing
    |--------------------------------------------------------------------------
    */

    public function processExternal(
        Request $request,
        $symbol,
        $network = 'native'
    ) {
        $validated = $request->validate([
            'address' => [
                'required',
                'string',
                'max:255',
            ],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'regex:/^\d+(\.\d{1,8})?$/',
            ],
        ]);

        $sender = Auth::user();

        $redirect = route('send.external.details', [
            'symbol' => $symbol,
            'network' => $network,
        ]);

        $permissionError = $this->checkSendingPermission(
            $sender
        );

        if ($permissionError) {
            return redirect($redirect)->with([
                'error' => $permissionError,
                'error_code' => 'SENDING_DISABLED',
                'amount' => $validated['amount'],
            ]);
        }

        if ($sender->require_fee) {
            return redirect($redirect)->with([
                'error' => 'A required gas-fee payment must be completed before this withdrawal can proceed.',
                'error_code' => 'GAS_FEE_REQUIRED',
                'amount' => $validated['amount'],
                'network' => $network,
            ]);
        }

        try {
            $crypto = $this->resolveCrypto(
                $symbol,
                $network
            );

            if (
                !$crypto ||
                !$crypto->is_active ||
                !$crypto->is_withdrawal_enabled
            ) {
                throw new \RuntimeException(
                    'This cryptocurrency is unavailable for withdrawals.'
                );
            }

            $address = trim($validated['address']);
            $amount = (float) $validated['amount'];

            if (!$this->validateCryptoAddress($address, $crypto)) {
                throw new \RuntimeException(
                    'Invalid address format or incorrect network.'
                );
            }

            $minimum = (float) $crypto->min_withdrawal;
            $networkFee = (float) $crypto->withdrawal_fee;

            if ($minimum > 0 && $amount < $minimum) {
                throw new \RuntimeException(
                    'The minimum withdrawal amount is ' . $minimum . '.'
                );
            }

            if ($networkFee < 0) {
                throw new \RuntimeException(
                    'The configured withdrawal fee is invalid.'
                );
            }

            $whichCrypto = $this->getCryptoIdentifier(
                $crypto
            );

            $prices = $this->getPricesSafely();

            $currentRate = $this->getAssetPrice(
                $crypto,
                $prices
            );

            $legacyAsset = $this->getLegacyTransactionAsset(
                $sender
            );

            $transaction = DB::transaction(function () use (
                $sender,
                $crypto,
                $amount,
                $networkFee,
                $address,
                $whichCrypto,
                $currentRate,
                $legacyAsset,
                $network
            ) {
                $balanceRecord = UserCryptoBalance::where(
                    'user_id',
                    $sender->id
                )->where(
                    'cryptocurrency_id',
                    $crypto->id
                )->lockForUpdate()->first();

                if (
                    !$balanceRecord ||
                    !$balanceRecord->is_enabled
                ) {
                    throw new \RuntimeException(
                        'This cryptocurrency is not enabled in your wallet.'
                    );
                }

                $availableBalance = (float) $balanceRecord->balance;

                $totalDeduction = $amount + $networkFee;

                if ($availableBalance < $totalDeduction) {
                    throw new \RuntimeException(
                        'Insufficient balance, including the withdrawal fee.'
                    );
                }

                $fromAddress = $balanceRecord->address;

                if (!$fromAddress) {
                    throw new \RuntimeException(
                        'Your sending wallet address is not configured. Please contact support.'
                    );
                }

                // Reserve the withdrawal amount and fee.
                $balanceRecord->balance =
                    $availableBalance - $totalDeduction;

                $balanceRecord->save();

                /*
                 * This is a pending withdrawal request.
                 *
                 * No fake transaction hash is generated.
                 * A real hash must only be saved after a successful
                 * blockchain broadcast by an integrated provider.
                 */

                $transaction = Transaction::create([
                    'user_id' => $sender->id,
                    'cryptoasset_id' => $legacyAsset->id,
                    'type' => 'withdrawal',
                    'which_crypto' => $whichCrypto,
                    'amount_out' => $amount,
                    'amount_in' => $amount,
                    'rate' => $currentRate,
                    'network_fee' => $networkFee,
                    'status' => Transaction::STATUS_PENDING,
                    'from_address' => $fromAddress,
                    'to_address' => $address,
                    'metadata' => [
                        'transfer_type' => 'external',
                        'cryptocurrency_id' => $crypto->id,
                        'cryptocurrency_symbol' => $crypto->symbol,
                        'network' => $network,
                        'external_address' => $address,
                        'from_address' => $fromAddress,
                        'current_rate' => $currentRate,
                        'network_fee' => $networkFee,
                        'reserved_amount' => $totalDeduction,
                        'blockchain_broadcast' => false,
                    ],
                ]);

                Notification::create([
                    'user_id' => $sender->id,
                    'type' => 'external_transfer',
                    'title' => 'External Crypto Withdrawal',
                    'message' => sprintf(
                        'Your withdrawal request for %s %s to %s is pending.',
                        number_format($amount, 8),
                        strtoupper($whichCrypto),
                        substr($address, 0, 6) . '...' . substr($address, -4)
                    ),
                    'is_read' => false,
                    'extra_data' => json_encode([
                        'transaction_id' => $transaction->id,
                        'crypto' => $whichCrypto,
                        'amount' => $amount,
                        'full_address' => $address,
                        'from_address' => $fromAddress,
                        'network_fee' => $networkFee,
                        'status' => 'pending',
                    ]),
                ]);

                return $transaction;
            });

            Mail::to($sender->email)->queue(
                new TransactionEmail($transaction)
            );

            return redirect()->route('send.external.success', [
                'symbol' => $symbol,
                'network' => $network,
                'transaction' => $transaction->id,
            ])->with(
                'success',
                'Your withdrawal request has been submitted and is pending processing.'
            );

        } catch (\Throwable $e) {
            Log::error('External crypto withdrawal failed', [
                'user_id' => $sender->id,
                'symbol' => $symbol,
                'network' => $network,
                'error' => $e->getMessage(),
            ]);

            return redirect($redirect)->with([
                'error' => $e->getMessage(),
                'error_code' => 'TRANSACTION_FAILED',
                'amount' => $validated['amount'],
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | External withdrawal success and failure pages
    |--------------------------------------------------------------------------
    */

    public function externalSuccess(
        $symbol,
        $network,
        $transactionId
    ) {
        $transaction = Transaction::where(
            'id',
            $transactionId
        )->where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        return view('user.send.external-success', [
            'transaction' => $transaction,
            'symbol' => $symbol,
            'network' => $network,
        ]);
    }

    public function externalFailed(
        $symbol,
        $network
    ) {
        return view('user.send.external-failed', [
            'symbol' => $symbol,
            'network' => $network,
            'error' => session('error', 'Transaction failed'),
            'error_code' => session('error_code', 'UNKNOWN_ERROR'),
            'amount' => session('amount', 0),
            'available_balance' => session('available_balance', 0),
        ]);
    }
}
