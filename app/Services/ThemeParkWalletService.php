<?php

namespace App\Services;

use App\Models\ThemeParkSetting;
use App\Models\ThemeParkWallet;
use App\Models\ThemeParkWalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ThemeParkWalletService
{
    /**
     * Top up a user's wallet with MVR.
     */
    public function topUpWallet(int $userId, float $amount): array
    {
        // Validate amount
        if ($amount < 10 || $amount > 10000) {
            return [
                'success' => false,
                'message' => 'Top-up amount must be between MVR 10 and MVR 10,000.',
            ];
        }

        try {
            DB::beginTransaction();

            $wallet = ThemeParkWallet::getOrCreateForUser($userId);

            // Create transaction record
            $transaction = ThemeParkWalletTransaction::create([
                'user_id' => $userId,
                'transaction_type' => 'top_up',
                'amount_mvr' => $amount,
                'balance_before_mvr' => $wallet->balance_mvr,
                'balance_after_mvr' => $wallet->balance_mvr + $amount,
                'balance_before_credits' => $wallet->credit_balance,
                'balance_after_credits' => $wallet->credit_balance,
            ]);

            // Update wallet
            $wallet->balance_mvr += $amount;
            $wallet->total_topped_up_mvr += $amount;
            $wallet->save();

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully topped up MVR {$amount}.",
                'transaction' => $transaction,
                'wallet' => $wallet->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to process top-up: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Purchase credits using MVR from wallet.
     */
    public function purchaseCredits(int $userId, int $creditCount): array
    {
        // Validate credit count
        if ($creditCount < 1) {
            return [
                'success' => false,
                'message' => 'Credit count must be at least 1.',
            ];
        }

        try {
            DB::beginTransaction();

            $wallet = ThemeParkWallet::getOrCreateForUser($userId);
            $creditPrice = ThemeParkSetting::getCreditPrice();
            $totalCost = $creditPrice * $creditCount;

            // Check if wallet has sufficient balance
            if (!$wallet->hasSufficientBalance($totalCost)) {
                return [
                    'success' => false,
                    'message' => "Insufficient balance. You need MVR {$totalCost} but have MVR {$wallet->balance_mvr}.",
                ];
            }

            // Create transaction record
            $transaction = ThemeParkWalletTransaction::create([
                'user_id' => $userId,
                'transaction_type' => 'ticket_purchase', // Keep for backward compatibility
                'amount_mvr' => $totalCost,
                'credits_amount' => $creditCount,
                'balance_before_mvr' => $wallet->balance_mvr,
                'balance_after_mvr' => $wallet->balance_mvr - $totalCost,
                'balance_before_credits' => $wallet->credit_balance,
                'balance_after_credits' => $wallet->credit_balance + $creditCount,
            ]);

            // Update wallet
            $wallet->balance_mvr -= $totalCost;
            $wallet->credit_balance += $creditCount;
            $wallet->total_credits_purchased += $creditCount;
            $wallet->save();

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully purchased {$creditCount} credit(s) for MVR {$totalCost}.",
                'transaction' => $transaction,
                'wallet' => $wallet->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to purchase credits: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get transaction history for a user.
     */
    public function getTransactionHistory(
        int $userId,
        ?string $type = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = ThemeParkWalletTransaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('transaction_type', $type);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get wallet statistics for a user.
     */
    public function getWalletStats(int $userId): array
    {
        $wallet = ThemeParkWallet::getOrCreateForUser($userId);

        return [
            'current_balance_mvr' => $wallet->balance_mvr,
            'current_credit_balance' => $wallet->credit_balance,
            'total_topped_up_mvr' => $wallet->total_topped_up_mvr,
            'total_credits_purchased' => $wallet->total_credits_purchased,
            'total_credits_redeemed' => $wallet->total_credits_redeemed,
            'credit_price_mvr' => ThemeParkSetting::getCreditPrice(),
        ];
    }
}
