<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\HotelBooking;
use App\Models\Ferry\FerryTicket;
use App\Models\ThemeParkActivityTicket;
use App\Models\ThemeParkWallet;
use App\Models\ThemeParkWalletTransaction;
use App\Models\ThemeParkShowSchedule;

class ClearAllBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:clear
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all booking data (hotel bookings, ferry tickets, theme park tickets, and wallet transactions)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Confirmation prompt
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  This will DELETE ALL booking data. Are you sure you want to continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }

            if (!$this->confirm('This action CANNOT be undone. Continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting to clear all booking data...');
        $this->newLine();

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Clear Hotel Bookings
            $hotelBookingsCount = HotelBooking::count();
            DB::table('hotel_bookings')->truncate();
            $this->line("✓ Cleared {$hotelBookingsCount} hotel bookings");

            // 2. Clear Ferry Tickets
            $ferryTicketsCount = FerryTicket::count();
            DB::table('ferry_tickets')->truncate();
            $this->line("✓ Cleared {$ferryTicketsCount} ferry tickets");

            // 3. Clear Theme Park Activity Tickets
            $activityTicketsCount = ThemeParkActivityTicket::count();
            DB::table('theme_park_activity_tickets')->truncate();
            $this->line("✓ Cleared {$activityTicketsCount} theme park activity tickets");

            // 4. Clear Theme Park Wallet Transactions
            $walletTransactionsCount = ThemeParkWalletTransaction::count();
            DB::table('theme_park_wallet_transactions')->truncate();
            $this->line("✓ Cleared {$walletTransactionsCount} wallet transactions");

            // 5. Reset Theme Park Wallet Balances
            $walletsCount = ThemeParkWallet::count();
            DB::table('theme_park_wallets')->update([
                'balance_mvr' => 0.00,
                'credit_balance' => 0,
                'total_topped_up_mvr' => 0.00,
                'total_credits_purchased' => 0,
                'total_credits_redeemed' => 0,
            ]);
            $this->line("✓ Reset {$walletsCount} wallet balances to zero");

            // 6. Reset Theme Park Show Schedules (tickets_sold)
            $schedulesUpdated = DB::table('theme_park_show_schedules')
                ->where('tickets_sold', '>', 0)
                ->update(['tickets_sold' => 0]);
            $this->line("✓ Reset tickets_sold for {$schedulesUpdated} show schedules");

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->newLine();
            $this->info('✅ All booking data has been successfully cleared!');
            $this->newLine();

            // Summary
            $this->table(
                ['Data Type', 'Records Cleared/Reset'],
                [
                    ['Hotel Bookings', $hotelBookingsCount],
                    ['Ferry Tickets', $ferryTicketsCount],
                    ['Theme Park Activity Tickets', $activityTicketsCount],
                    ['Wallet Transactions', $walletTransactionsCount],
                    ['Wallet Balances Reset', $walletsCount],
                    ['Show Schedules Updated', $schedulesUpdated],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->error('Failed to clear booking data: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
