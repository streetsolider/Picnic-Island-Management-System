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
                'balance_before_tickets' => $wallet->ticket_balance,
                'balance_after_tickets' => $wallet->ticket_balance,
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
     * Purchase tickets using MVR from wallet.
     */
    public function purchaseTickets(int $userId, int $ticketCount): array
    {
        // Validate ticket count
        if ($ticketCount < 1) {
            return [
                'success' => false,
                'message' => 'Ticket count must be at least 1.',
            ];
        }

        try {
            DB::beginTransaction();

            $wallet = ThemeParkWallet::getOrCreateForUser($userId);
            $ticketPrice = ThemeParkSetting::getTicketPrice();
            $totalCost = $ticketPrice * $ticketCount;

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
                'transaction_type' => 'ticket_purchase',
                'amount_mvr' => $totalCost,
                'tickets_amount' => $ticketCount,
                'balance_before_mvr' => $wallet->balance_mvr,
                'balance_after_mvr' => $wallet->balance_mvr - $totalCost,
                'balance_before_tickets' => $wallet->ticket_balance,
                'balance_after_tickets' => $wallet->ticket_balance + $ticketCount,
            ]);

            // Update wallet
            $wallet->balance_mvr -= $totalCost;
            $wallet->ticket_balance += $ticketCount;
            $wallet->total_tickets_purchased += $ticketCount;
            $wallet->save();

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully purchased {$ticketCount} ticket(s) for MVR {$totalCost}.",
                'transaction' => $transaction,
                'wallet' => $wallet->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to purchase tickets: ' . $e->getMessage(),
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
            'current_ticket_balance' => $wallet->ticket_balance,
            'total_topped_up_mvr' => $wallet->total_topped_up_mvr,
            'total_tickets_purchased' => $wallet->total_tickets_purchased,
            'total_tickets_redeemed' => $wallet->total_tickets_redeemed,
            'ticket_price_mvr' => ThemeParkSetting::getTicketPrice(),
        ];
    }
}
