<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\BeachServiceBooking;
use App\Models\ThemeParkWallet;
use App\Models\ThemeParkWalletTransaction;
use App\Models\Ferry\FerryTicket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanTestAnalyticsDataSeeder extends Seeder
{
    /**
     * Remove all test analytics data
     *
     * Removes all data with "TEST-" prefixes and "test-analytics-" email addresses
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->command->warn('⚠️  Starting cleanup of test analytics data...');
            $this->command->warn('This will delete all test data from the database.');
            $this->command->newLine();

            // Step 1: Delete payments for test bookings
            $deletedPayments = $this->cleanPayments();
            $this->command->info("✅ Deleted {$deletedPayments} test payments");

            // Step 2: Delete ferry tickets
            $deletedFerryTickets = FerryTicket::where('ticket_reference', 'LIKE', 'TEST-FT-%')->delete();
            $this->command->info("✅ Deleted {$deletedFerryTickets} test ferry tickets");

            // Step 3: Delete beach service bookings
            $deletedBeachBookings = BeachServiceBooking::where('booking_reference', 'LIKE', 'TEST-BS-%')->delete();
            $this->command->info("✅ Deleted {$deletedBeachBookings} test beach service bookings");

            // Step 4: Delete theme park wallet transactions and wallets
            $deletedWalletTransactions = $this->cleanThemeParkWallets();
            $this->command->info("✅ Deleted {$deletedWalletTransactions} test wallet transactions");

            // Step 5: Delete hotel bookings
            $deletedBookings = HotelBooking::where('booking_reference', 'LIKE', 'TEST-BK-%')->delete();
            $this->command->info("✅ Deleted {$deletedBookings} test hotel bookings");

            // Step 6: Delete test guests (this will cascade delete related records if configured)
            $deletedGuests = Guest::where('email', 'LIKE', 'test-analytics-%')->delete();
            $this->command->info("✅ Deleted {$deletedGuests} test guests");

            $this->command->newLine();
            $this->command->info('🎉 Test analytics data cleaned successfully!');
            $this->command->info('Your database is now clean and ready for production data.');
        });
    }

    private function cleanPayments(): int
    {
        // Delete payments with TEST transaction IDs
        return Payment::where('transaction_id', 'LIKE', 'TEST-TXN-%')->delete();
    }

    private function cleanThemeParkWallets(): int
    {
        // Get test guest IDs
        $testGuestIds = Guest::where('email', 'LIKE', 'test-analytics-%')->pluck('id');

        if ($testGuestIds->isEmpty()) {
            return 0;
        }

        // Delete wallet transactions for test guests
        $deleted = ThemeParkWalletTransaction::where('transaction_reference', 'LIKE', 'TEST-TPW-%')->delete();

        // Delete wallets for test guests
        ThemeParkWallet::whereIn('user_id', $testGuestIds)->delete();

        return $deleted;
    }
}
