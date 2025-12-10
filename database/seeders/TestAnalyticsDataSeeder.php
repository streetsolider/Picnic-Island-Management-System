<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\BeachService;
use App\Models\BeachServiceBooking;
use App\Models\ThemeParkWallet;
use App\Models\ThemeParkWalletTransaction;
use App\Models\Ferry\FerryTicket;
use App\Models\Ferry\FerryVessel;
use App\Models\Ferry\FerryRoute;
use App\Models\Ferry\FerrySchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestAnalyticsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * All test data uses email prefix "test-analytics-" for easy identification and removal
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->command->info('🌱 Starting test analytics data seeding...');

            // Step 1: Create test guests
            $guests = $this->createTestGuests(20);
            $this->command->info('✅ Created ' . count($guests) . ' test guests');

            // Step 2: Get or create hotels and rooms
            $hotels = $this->ensureHotelsExist();
            $rooms = $this->ensureRoomsExist($hotels);
            $this->command->info('✅ Ensured hotels and rooms exist');

            // Step 3: Create hotel bookings (last 60 days)
            $bookings = $this->createHotelBookings($guests, $rooms, 100);
            $this->command->info('✅ Created ' . count($bookings) . ' hotel bookings');

            // Step 4: Create ferry tickets (linked to hotel bookings)
            $ferryTickets = $this->createFerryTickets($guests, $bookings, 80);
            $this->command->info('✅ Created ' . count($ferryTickets) . ' ferry tickets');

            // Step 5: Create beach service bookings
            $beachBookings = $this->createBeachServiceBookings($guests, $bookings, 50);
            $this->command->info('✅ Created ' . count($beachBookings) . ' beach service bookings');

            // Step 6: Create theme park wallet transactions
            $walletTransactions = $this->createThemeParkWalletTransactions($guests, 40);
            $this->command->info('✅ Created ' . count($walletTransactions) . ' wallet transactions');

            $this->command->info('🎉 Test analytics data seeded successfully!');
            $this->command->info('');
            $this->command->warn('⚠️  To remove all test data, run: php artisan db:seed --class=CleanTestAnalyticsDataSeeder');
        });
    }

    private function createTestGuests(int $count): array
    {
        $guests = [];

        for ($i = 1; $i <= $count; $i++) {
            $guest = Guest::create([
                'guest_id' => 'TEST-GST-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => 'Test Guest ' . $i,
                'email' => 'test-analytics-guest' . $i . '@example.com',
                'password' => Hash::make('password'),
                'phone' => '+960' . rand(7000000, 7999999),
                'nationality' => collect(['Maldivian', 'Indian', 'Chinese', 'British', 'American'])->random(),
                'date_of_birth' => now()->subYears(rand(25, 60))->format('Y-m-d'),
                'id_type' => 'passport',
                'id_number' => 'TEST' . Str::upper(Str::random(8)),
            ]);

            $guests[] = $guest;
        }

        return $guests;
    }

    private function ensureHotelsExist(): array
    {
        $hotels = Hotel::all();

        if ($hotels->isEmpty()) {
            $this->command->warn('No hotels found. Creating test hotels...');

            $hotelNames = ['Paradise Resort', 'Ocean View Hotel', 'Beach Paradise', 'Island Retreat'];
            foreach ($hotelNames as $name) {
                $hotels[] = Hotel::create([
                    'name' => $name,
                    'description' => 'A beautiful resort on Picnic Island',
                    'location' => 'Picnic Island',
                    'latitude' => 4.1755 + (rand(-100, 100) / 10000),
                    'longitude' => 73.5093 + (rand(-100, 100) / 10000),
                    'star_rating' => rand(3, 5),
                    'is_active' => true,
                ]);
            }
            $hotels = collect($hotels);
        }

        return $hotels->toArray();
    }

    private function ensureRoomsExist(array $hotels): array
    {
        $rooms = Room::all();

        if ($rooms->count() < 10) {
            $this->command->warn('Creating test rooms...');

            $roomTypes = ['standard', 'superior', 'deluxe', 'suite', 'family'];
            $bedSizes = ['king', 'queen', 'twin'];
            $bedCounts = ['single', 'double'];

            foreach ($hotels as $hotel) {
                for ($i = 1; $i <= 10; $i++) {
                    $rooms[] = Room::create([
                        'hotel_id' => $hotel->id,
                        'room_number' => $hotel->id . '0' . $i,
                        'room_type' => $roomTypes[array_rand($roomTypes)],
                        'bed_size' => $bedSizes[array_rand($bedSizes)],
                        'bed_count' => $bedCounts[array_rand($bedCounts)],
                        'floor_number' => (int)($i / 10) + 1,
                        'max_occupancy' => rand(2, 4),
                        'is_active' => true,
                    ]);
                }
            }
            $rooms = collect($rooms);
        }

        return Room::all()->toArray();
    }

    private function createHotelBookings(array $guests, array $rooms, int $count): array
    {
        $bookings = [];
        $statuses = ['confirmed', 'completed', 'cancelled', 'no-show', 'checked_in'];
        $statusWeights = [40, 30, 15, 5, 10]; // Weighted distribution

        for ($i = 0; $i < $count; $i++) {
            $guest = $guests[array_rand($guests)];
            $room = $rooms[array_rand($rooms)];

            // Random date in the last 60 days
            $createdAt = now()->subDays(rand(0, 60));
            $checkIn = $createdAt->copy()->addDays(rand(1, 30));
            $checkOut = $checkIn->copy()->addDays(rand(2, 7));
            $nights = $checkIn->diffInDays($checkOut);

            // Random status
            $status = $this->weightedRandom($statuses, $statusWeights);

            // Payment status based on booking status
            $paymentStatus = match ($status) {
                'completed', 'checked_in' => 'paid',
                'cancelled' => rand(0, 1) ? 'refunded' : 'paid',
                'no-show' => 'paid',
                default => rand(0, 2) ? 'paid' : 'pending',
            };

            $pricePerNight = rand(2000, 8000);
            $totalPrice = $pricePerNight * $nights;

            $booking = HotelBooking::create([
                'booking_reference' => 'TEST-BK-' . strtoupper(Str::random(8)),
                'guest_id' => $guest->id,
                'hotel_id' => $room['hotel_id'],
                'room_id' => $room['id'],
                'check_in_date' => $checkIn->format('Y-m-d'),
                'check_out_date' => $checkOut->format('Y-m-d'),
                'number_of_guests' => rand(1, 3),
                'number_of_rooms' => rand(1, 2),
                'total_price' => $totalPrice,
                'payment_status' => $paymentStatus,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create payment if paid or refunded
            if (in_array($paymentStatus, ['paid', 'refunded'])) {
                $this->createPayment($booking, 'App\Models\HotelBooking', $totalPrice, $paymentStatus, $createdAt);
            }

            $bookings[] = $booking;
        }

        return $bookings;
    }

    private function createFerryTickets(array $guests, array $bookings, int $count): array
    {
        $tickets = [];

        // Ensure ferry infrastructure exists
        $vessel = FerryVessel::first();
        if (!$vessel) {
            $vessel = FerryVessel::create([
                'registration_number' => 'TEST-FV-001',
                'vessel_type' => 'ferry',
                'capacity' => 50,
                'is_active' => true,
            ]);
        }

        $route = FerryRoute::first();
        if (!$route) {
            $route = FerryRoute::create([
                'origin' => 'Mainland Port',
                'destination' => 'Picnic Island',
                'is_active' => true,
            ]);
        }

        $schedule = FerrySchedule::first();
        if (!$schedule) {
            $schedule = FerrySchedule::create([
                'ferry_vessel_id' => $vessel->id,
                'ferry_route_id' => $route->id,
                'departure_time' => '09:00:00',
                'arrival_time' => '10:30:00',
                'days_of_week' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
            ]);
        }

        $statuses = ['confirmed', 'used', 'cancelled', 'expired'];
        $statusWeights = [20, 60, 10, 10];

        for ($i = 0; $i < $count; $i++) {
            $booking = $bookings[array_rand($bookings)];
            $guest = Guest::find($booking->guest_id);

            $createdAt = $booking->created_at->copy()->addHours(rand(1, 24));
            $travelDate = $booking->check_in_date;

            $status = $this->weightedRandom($statuses, $statusWeights);
            $paymentStatus = $status === 'cancelled' ? 'refunded' : 'paid';

            $ticket = FerryTicket::create([
                'ticket_reference' => 'TEST-FT-' . strtoupper(Str::random(8)),
                'guest_id' => $guest->id,
                'hotel_booking_id' => $booking->id,
                'ferry_schedule_id' => $schedule->id,
                'ferry_route_id' => $route->id,
                'ferry_vessel_id' => $vessel->id,
                'travel_date' => $travelDate,
                'direction' => 'to_island',
                'number_of_passengers' => $booking->number_of_guests,
                'price_per_passenger' => 0, // Ferry is FREE
                'total_price' => 0,
                'payment_status' => $paymentStatus,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $tickets[] = $ticket;
        }

        return $tickets;
    }

    private function createBeachServiceBookings(array $guests, array $bookings, int $count): array
    {
        $beachBookings = [];

        $services = BeachService::all();
        if ($services->isEmpty()) {
            $this->command->warn('No beach services found. Creating test services...');
            $serviceTypes = ['excursions', 'water_sports', 'beach_sports', 'beach_huts'];
            foreach ($serviceTypes as $type) {
                $services[] = BeachService::create([
                    'name' => ucfirst(str_replace('_', ' ', $type)),
                    'service_type' => $type,
                    'description' => 'Test ' . $type . ' service',
                    'capacity' => rand(10, 50),
                    'price_per_unit' => rand(500, 2000),
                    'unit_type' => 'hourly',
                    'is_active' => true,
                ]);
            }
            $services = collect($services);
        }

        $statuses = ['confirmed', 'redeemed', 'cancelled', 'expired'];
        $statusWeights = [20, 60, 10, 10];

        for ($i = 0; $i < $count; $i++) {
            $booking = $bookings[array_rand($bookings)];
            $service = $services->random();
            $guest = Guest::find($booking->guest_id);

            $createdAt = $booking->created_at->copy()->addDays(rand(1, 5));
            $bookingDate = $booking->check_in_date;

            $status = $this->weightedRandom($statuses, $statusWeights);
            $paymentStatus = match ($status) {
                'redeemed' => 'paid',
                'cancelled' => rand(0, 1) ? 'refunded' : 'paid',
                default => rand(0, 1) ? 'paid' : 'pending',
            };

            $duration = rand(1, 4);
            $pricePerUnit = $service->price_per_unit ?? rand(500, 2000); // Fallback to random price if not set
            $totalPrice = $pricePerUnit * $duration;

            $beachBooking = BeachServiceBooking::create([
                'booking_reference' => 'TEST-BS-' . strtoupper(Str::random(8)),
                'guest_id' => $guest->id,
                'beach_service_id' => $service->id,
                'hotel_booking_id' => $booking->id,
                'booking_date' => $bookingDate,
                'start_time' => '09:00:00',
                'end_time' => sprintf('%02d:00:00', 9 + $duration),
                'duration_hours' => $duration,
                'price_per_unit' => $pricePerUnit,
                'total_price' => $totalPrice,
                'payment_status' => $paymentStatus,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create payment if paid or refunded
            if (in_array($paymentStatus, ['paid', 'refunded'])) {
                $this->createPayment($beachBooking, 'App\Models\BeachServiceBooking', $totalPrice, $paymentStatus, $createdAt);
            }

            $beachBookings[] = $beachBooking;
        }

        return $beachBookings;
    }

    private function createThemeParkWalletTransactions(array $guests, int $count): array
    {
        $transactions = [];

        foreach (array_slice($guests, 0, $count) as $guest) {
            // Create wallet for guest
            $wallet = ThemeParkWallet::firstOrCreate(
                ['user_id' => $guest->id],
                [
                    'balance_mvr' => 0,
                    'credit_balance' => 0,
                    'total_topped_up_mvr' => 0,
                    'total_credits_purchased' => 0,
                    'total_credits_redeemed' => 0,
                ]
            );

            // Create 1-3 top-up transactions per guest
            $topUpCount = rand(1, 3);
            for ($i = 0; $i < $topUpCount; $i++) {
                $amount = [500, 1000, 1500, 2000][array_rand([500, 1000, 1500, 2000])];
                $createdAt = now()->subDays(rand(0, 60));

                $transaction = ThemeParkWalletTransaction::create([
                    'user_id' => $guest->id,
                    'transaction_type' => 'top_up',
                    'amount_mvr' => $amount,
                    'credits_amount' => 0,
                    'balance_before_mvr' => $wallet->balance_mvr,
                    'balance_after_mvr' => $wallet->balance_mvr + $amount,
                    'balance_before_credits' => $wallet->credit_balance,
                    'balance_after_credits' => $wallet->credit_balance,
                    'transaction_reference' => 'TEST-TPW-' . strtoupper(Str::random(8)),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // Update wallet totals
                $wallet->increment('balance_mvr', $amount);
                $wallet->increment('total_topped_up_mvr', $amount);

                $transactions[] = $transaction;
            }
        }

        return $transactions;
    }

    private function createPayment($model, string $modelType, float $amount, string $status, $createdAt): Payment
    {
        $transactionId = 'TEST-TXN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
        $paymentReference = 'TEST-PAY-' . strtoupper(Str::random(8));

        $payment = Payment::create([
            'payable_id' => $model->id,
            'payable_type' => $modelType,
            'guest_id' => $model->guest_id,
            'amount' => $amount,
            'currency' => 'MVR',
            'status' => $status === 'refunded' ? 'refunded' : ($status === 'paid' ? 'completed' : 'pending'),
            'transaction_id' => $transactionId,
            'payment_reference' => $paymentReference,
            'bank' => ['MIB', 'BML', 'CBM'][array_rand(['MIB', 'BML', 'CBM'])],
            'card_type' => ['Visa', 'Mastercard'][array_rand(['Visa', 'Mastercard'])],
            'card_last_four' => str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            'initiated_at' => $createdAt,
            'completed_at' => $status === 'completed' ? $createdAt->copy()->addMinutes(5) : null,
            'failed_at' => $status === 'failed' ? $createdAt->copy()->addMinutes(5) : null,
            'refunded_at' => $status === 'refunded' ? $createdAt->copy()->addDays(rand(1, 7)) : null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        // Add some failed payments for testing
        if (rand(1, 20) === 1) {
            $payment->update([
                'status' => 'failed',
                'failed_at' => $createdAt->copy()->addMinutes(5),
            ]);
        }

        return $payment;
    }

    private function weightedRandom(array $values, array $weights): mixed
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);

        $weightSum = 0;
        foreach ($values as $i => $value) {
            $weightSum += $weights[$i];
            if ($random <= $weightSum) {
                return $value;
            }
        }

        return $values[0];
    }
}
