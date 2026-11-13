<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\CryptoAsset;
use App\Models\Notification;
use App\Mail\TransactionStatusEmail;
use App\Services\CryptoBalanceService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'cryptoAsset'])
            ->latest();

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('status')) {
            $query->ofStatus($request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_hash', 'like', "%{$search}%")
                  ->orWhere('from_address', 'like', "%{$search}%")
                  ->orWhere('to_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->paginate(10)->withQueryString();

        // ✅ Load current crypto prices for use in modals (e.g., rate auto-fill)
        $prices = app(\App\Services\CryptoService::class)->getPrices();

        return view('admin.transactions.index', compact('transactions', 'prices'));
    }

    public function store(Request $request)
    {
        $balanceService = app(CryptoBalanceService::class);
        $supportedSymbols = $balanceService->supportedSymbols();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:' . implode(',', Transaction::getValidTypes()),
            'which_crypto' => ['required', 'string', Rule::in($supportedSymbols)],
            'from_crypto' => ['required_if:type,swap', 'nullable', Rule::in($supportedSymbols)],
            'to_crypto' => ['required_if:type,swap', 'nullable', Rule::in($supportedSymbols)],
            'transaction_hash' => 'nullable|string',
            'from_address' => 'nullable|string',
            'to_address' => 'nullable|string',
            'amount_in' => 'required|numeric|min:0',
            'amount_out' => 'required_if:type,swap|nullable|numeric|min:0',
            'network_fee' => 'nullable|numeric|min:0',
            'rate' => 'nullable|numeric',
            'status' => 'required|in:' . implode(',', Transaction::getValidStatuses()),
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);

            if (
                $validated['type'] === Transaction::TYPE_WITHDRAWAL &&
                $balanceService->balance($user, $validated['which_crypto'])
                    < (float) $validated['amount_in']
            ) {
                throw new \DomainException('Insufficient balance for withdrawal.');
            }

            $cryptoAsset = CryptoAsset::firstOrCreate(
                ['user_id' => $user->id],
                CryptoAsset::getDefaultBalances()
            );
            $validated['cryptoasset_id'] = $cryptoAsset->id;

            $transaction = Transaction::create($validated);

            // Handle balance update & notifications if completed
            if ($transaction->isCompleted()) {
                $this->updateCryptoBalance($transaction);
                $this->createTransactionNotification($transaction, 'completed');
            } elseif ($transaction->isPending()) {
                $this->createTransactionNotification($transaction, 'pending');
            }

            DB::commit();

            return back()->with('status', 'Transaction created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }

    // ✅ FIXED: Return transaction WITH user data as JSON
    public function edit(Transaction $transaction)
    {
        // Ensure user is loaded
        $transaction->loadMissing('user');

        return response()->json([
            'id' => $transaction->id,
            'user_id' => $transaction->user_id,
            'user' => $transaction->user ? [
                'id' => $transaction->user->id,
                'name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ] : null,
            'type' => $transaction->type,
            'which_crypto' => $transaction->which_crypto,
            'from_crypto' => $transaction->from_crypto,
            'to_crypto' => $transaction->to_crypto,
            'amount_in' => $transaction->amount_in,
            'amount_out' => $transaction->amount_out,
            'network_fee' => $transaction->network_fee,
            'rate' => $transaction->rate,
            'transaction_hash' => $transaction->transaction_hash,
            'status' => $transaction->status,
            'from_address' => $transaction->from_address,
            'to_address' => $transaction->to_address,
            'created_at' => $transaction->created_at,
            'updated_at' => $transaction->updated_at,
        ]);
    }

    // ✅ UPDATE: Allow partial updates (only validate fields that are present)
    public function update(Request $request, Transaction $transaction)
    {
        $supportedSymbols = app(CryptoBalanceService::class)->supportedSymbols();

        $validated = $request->validate([
            'type' => 'sometimes|in:' . implode(',', Transaction::getValidTypes()),
            'which_crypto' => ['sometimes', 'string', Rule::in($supportedSymbols)],
            'from_crypto' => ['sometimes', 'required_if:type,swap', 'nullable', Rule::in($supportedSymbols)],
            'to_crypto' => ['sometimes', 'required_if:type,swap', 'nullable', Rule::in($supportedSymbols)],
            'transaction_hash' => 'sometimes|nullable|string',
            'from_address' => 'sometimes|nullable|string',
            'to_address' => 'sometimes|nullable|string',
            'amount_in' => 'sometimes|numeric|min:0',
            'amount_out' => 'sometimes|numeric|min:0|required_if:type,swap',
            'network_fee' => 'sometimes|nullable|numeric|min:0',
            'rate' => 'sometimes|nullable|numeric',
            'status' => 'sometimes|in:' . implode(',', Transaction::getValidStatuses()),
        ]);

        try {
            DB::transaction(function () use ($transaction, $validated) {
                $transaction = Transaction::whereKey($transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();
                $previous = clone $transaction;
                $wasCompleted = $previous->isCompleted();

                if ($wasCompleted) {
                    $this->reverseCryptoBalance($previous);
                }

                $transaction->fill($validated);
                $transaction->save();

                if ($transaction->isCompleted()) {
                    $this->updateCryptoBalance($transaction);

                    if (!$wasCompleted) {
                        $this->createTransactionNotification($transaction, 'completed');
                    }
                }
            });

            return back()->with('status', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }

    public function approve(Transaction $transaction)
    {
        try {
            DB::transaction(function () use ($transaction) {
                $transaction = Transaction::whereKey($transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$transaction->isPending()) {
                    throw new \DomainException('Only pending transactions can be approved.');
                }

                $transaction->status = Transaction::STATUS_COMPLETED;
                $transaction->save();

                $this->updateCryptoBalance($transaction);
                $this->createTransactionNotification($transaction, 'completed');
            });

            return back()->with('status', 'Transaction approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve transaction: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            DB::transaction(function () use ($transaction, $request) {
                $transaction = Transaction::whereKey($transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$transaction->isPending()) {
                    throw new \DomainException('Only pending transactions can be rejected.');
                }

                $transaction->status = Transaction::STATUS_FAILED;
                $transaction->metadata = array_merge($transaction->metadata ?? [], [
                    'rejection_reason' => $request->reason,
                    'rejected_at' => now()
                ]);
                $transaction->save();
            });

            $transaction->refresh();

            Mail::to($transaction->user->email)->queue(new TransactionStatusEmail(
                $transaction,
                'failed',
                $request->reason
            ));

            return back()->with('status', 'Transaction rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject transaction: ' . $e->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        try {
            DB::transaction(function () use ($transaction) {
                $transaction = Transaction::whereKey($transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($transaction->isCompleted()) {
                    $this->reverseCryptoBalance($transaction);
                }

                $transaction->delete();
            });

            return back()->with('status', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }

    protected function createTransactionNotification(Transaction $transaction, $status)
    {
        $user = $transaction->user;

        $notificationData = [
            'user_id' => $user->id,
            'type' => 'send_crypto',
            'title' => ucfirst($status) . ' Transaction',
            'message' => $this->generateTransactionMessage($transaction, $status),
            'extra_data' => [
                'transaction_hash' => $transaction->transaction_hash,
                'status' => $status
            ]
        ];

        Notification::createNotification($notificationData);

        Mail::to($user->email)->queue(new TransactionStatusEmail(
            $transaction,
            $status
        ));
    }

    protected function generateTransactionMessage(Transaction $transaction, $status)
    {
        $crypto = $transaction->which_crypto;
        $amount = $transaction->amount_in;

        switch ($transaction->type) {
            case Transaction::TYPE_DEPOSIT:
                return $status === 'pending'
                    ? "Incoming deposit of {$amount} {$crypto} is pending."
                    : "Deposit of {$amount} {$crypto} has been completed.";

            case Transaction::TYPE_WITHDRAWAL:
                $to = $transaction->to_address ? 'external wallet' : 'your wallet';
                return $status === 'pending'
                    ? "Withdrawal of {$amount} {$crypto} to {$to} is pending."
                    : "Withdrawal of {$amount} {$crypto} to {$to} has been completed.";

            case Transaction::TYPE_SWAP:
                $out = $transaction->amount_out;
                $toCrypto = $transaction->to_crypto;
                return $status === 'pending'
                    ? "Swap of {$amount} {$crypto} to {$out} {$toCrypto} is pending."
                    : "Swap of {$amount} {$crypto} to {$out} {$toCrypto} has been completed.";

            case Transaction::TYPE_FUNDING:
                return $status === 'pending'
                    ? "Your funding of {$amount} {$crypto} is pending."
                    : "Your funding of {$amount} {$crypto} has been completed.";

            case Transaction::TYPE_REFUND:
                return $status === 'pending'
                    ? "Refund of {$amount} {$crypto} is pending."
                    : "Refund of {$amount} {$crypto} has been completed.";

            default:
                return 'Your transaction status has been updated.';
        }
    }

    protected function updateCryptoBalance(Transaction $transaction)
    {
        $balanceService = app(CryptoBalanceService::class);
        $user = User::findOrFail($transaction->user_id);

        switch ($transaction->type) {
            case Transaction::TYPE_DEPOSIT:
            case Transaction::TYPE_FUNDING:
            case Transaction::TYPE_REFUND:
                $balanceService->adjust(
                    $user,
                    $transaction->which_crypto,
                    (float) $transaction->amount_in
                );
                break;

            case Transaction::TYPE_WITHDRAWAL:
                $balanceService->adjust(
                    $user,
                    $transaction->which_crypto,
                    -(float) $transaction->amount_in
                );
                break;

            case Transaction::TYPE_SWAP:
                $balanceService->adjust(
                    $user,
                    $transaction->from_crypto,
                    -(float) $transaction->amount_in
                );
                $balanceService->adjust(
                    $user,
                    $transaction->to_crypto,
                    (float) $transaction->amount_out
                );
                break;
        }
    }

    public function receipt($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json([
            'id' => $transaction->id,
            'type' => $transaction->type,
            'status' => $transaction->status,
            'which_crypto' => $transaction->which_crypto,
            'amount_in' => $transaction->amount_in,
            'created_at' => $transaction->created_at,
            'transaction_hash' => $transaction->transaction_hash,
        ]);
    }

    protected function reverseCryptoBalance(Transaction $transaction)
    {
        $balanceService = app(CryptoBalanceService::class);
        $user = User::findOrFail($transaction->user_id);

        switch ($transaction->type) {
            case Transaction::TYPE_DEPOSIT:
            case Transaction::TYPE_FUNDING:
            case Transaction::TYPE_REFUND:
                $balanceService->adjust(
                    $user,
                    $transaction->which_crypto,
                    -(float) $transaction->amount_in
                );
                break;

            case Transaction::TYPE_WITHDRAWAL:
                $balanceService->adjust(
                    $user,
                    $transaction->which_crypto,
                    (float) $transaction->amount_in
                );
                break;

            case Transaction::TYPE_SWAP:
                $balanceService->adjust(
                    $user,
                    $transaction->from_crypto,
                    (float) $transaction->amount_in
                );
                $balanceService->adjust(
                    $user,
                    $transaction->to_crypto,
                    -(float) $transaction->amount_out
                );
                break;
        }
    }
}