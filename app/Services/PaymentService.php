<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\SavedPaymentMethod;
use App\Models\Guest;
use App\Services\BookingService;
use App\Services\BeachBookingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Initiate a new payment
     *
     * @param array $data
     * @return Payment
     */
    public function initiatePayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $payment = Payment::create([
                'payable_type' => $data['payable_type'] ?? null,
                'payable_id' => $data['payable_id'] ?? null,
                'guest_id' => $data['guest_id'],
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'MVR',
                'bank' => $data['bank'],
                'card_type' => $data['card_type'],
                'card_last_four' => $data['card_last_four'],
                'card_token' => $data['card_token'] ?? null,
                'status' => 'pending',
                'metadata' => [
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'initiated_from' => $data['source'] ?? 'web',
                ],
            ]);

            Log::info('Payment initiated', [
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'amount' => $payment->amount,
                'guest_id' => $payment->guest_id,
            ]);

            return $payment;
        });
    }

    /**
     * Simulate bank processing (fake gateway simulation)
     * This method would simulate the delay and processing
     *
     * @param Payment $payment
     * @return void
     */
    public function processPayment(Payment $payment): void
    {
        $payment->markAsProcessing();

        Log::info('Payment processing', [
            'payment_id' => $payment->id,
            'transaction_id' => $payment->transaction_id,
        ]);

        // In a real system, this would communicate with the bank's API
        // For our fake system, we'll just mark it as processing
        // The actual success/failure will be determined by user action in the UI
    }

    /**
     * Complete a successful payment
     *
     * @param Payment $payment
     * @param array $bookingData Booking details to create after successful payment
     * @return mixed The created booking
     */
    public function completePayment(Payment $payment, array $bookingData)
    {
        return DB::transaction(function () use ($payment, $bookingData) {
            // Create the booking based on type
            $booking = $this->createBookingAfterPayment($bookingData);

            // Mark payment as completed
            $payment->markAsCompleted();

            // Link payment to booking
            $payment->update([
                'payable_id' => $booking->id,
                'payable_type' => get_class($booking),
            ]);

            // Update booking with payment details
            $booking->update([
                'payment_id' => $payment->id,
                'payment_status' => 'paid',
                'payment_method' => $payment->bank,
            ]);

            // If card was saved during payment, mark it as used
            if ($payment->card_token) {
                $savedCard = SavedPaymentMethod::where('card_token', $payment->card_token)->first();
                if ($savedCard) {
                    $savedCard->markAsUsed();
                }
            }

            Log::info('Payment completed successfully', [
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'booking_type' => get_class($booking),
                'booking_id' => $booking->id,
            ]);

            return $booking;
        });
    }

    /**
     * Handle failed payment
     *
     * @param Payment $payment
     * @param string $reason
     * @return void
     */
    public function failPayment(Payment $payment, string $reason): void
    {
        $payment->markAsFailed($reason);

        Log::warning('Payment failed', [
            'payment_id' => $payment->id,
            'transaction_id' => $payment->transaction_id,
            'reason' => $reason,
        ]);
    }

    /**
     * Save payment method for future use
     *
     * @param Guest $guest
     * @param array $cardData
     * @param bool $setAsDefault
     * @return SavedPaymentMethod
     */
    public function savePaymentMethod(Guest $guest, array $cardData, bool $setAsDefault = false): SavedPaymentMethod
    {
        return DB::transaction(function () use ($guest, $cardData, $setAsDefault) {
            $savedCard = SavedPaymentMethod::create([
                'guest_id' => $guest->id,
                'bank' => $cardData['bank'],
                'card_type' => $cardData['card_type'],
                'card_last_four' => $cardData['card_last_four'],
                'card_expiry' => $cardData['card_expiry'],
                'card_holder_name' => $cardData['card_holder_name'],
                'is_default' => $setAsDefault,
            ]);

            if ($setAsDefault) {
                $savedCard->setAsDefault();
            }

            Log::info('Payment method saved', [
                'guest_id' => $guest->id,
                'card_token' => $savedCard->card_token,
                'bank' => $savedCard->bank,
            ]);

            return $savedCard;
        });
    }

    /**
     * Delete saved payment method
     *
     * @param SavedPaymentMethod $card
     * @return bool
     */
    public function deleteSavedCard(SavedPaymentMethod $card): bool
    {
        $wasDefault = $card->is_default;
        $guestId = $card->guest_id;

        $deleted = $card->delete();

        // If deleted card was default, set another as default
        if ($deleted && $wasDefault) {
            $nextCard = SavedPaymentMethod::where('guest_id', $guestId)->first();
            if ($nextCard) {
                $nextCard->setAsDefault();
            }
        }

        return $deleted;
    }

    /**
     * Create booking after successful payment
     *
     * @param array $data
     * @return mixed
     */
    protected function createBookingAfterPayment(array $data)
    {
        $type = $data['booking_type'];

        if ($type === 'hotel') {
            $bookingService = app(BookingService::class);
            return $bookingService->createBooking($data);
        } elseif ($type === 'beach') {
            $beachService = app(BeachBookingService::class);
            return $beachService->createBooking($data);
        }

        throw new \Exception('Invalid booking type');
    }

    /**
     * Extract card details from card number (for fake validation)
     *
     * @param string $cardNumber
     * @return array
     */
    public function extractCardDetails(string $cardNumber): array
    {
        // Remove spaces
        $cardNumber = str_replace(' ', '', $cardNumber);

        // Determine card type based on first digit (simplified)
        $cardType = $this->determineCardType($cardNumber);

        // Get last 4 digits
        $lastFour = substr($cardNumber, -4);

        return [
            'card_type' => $cardType,
            'card_last_four' => $lastFour,
        ];
    }

    /**
     * Determine card type from card number
     *
     * @param string $cardNumber
     * @return string
     */
    protected function determineCardType(string $cardNumber): string
    {
        $firstDigit = substr($cardNumber, 0, 1);

        // Visa starts with 4
        if ($firstDigit === '4') {
            return 'Visa';
        }

        // Mastercard starts with 5
        if ($firstDigit === '5') {
            return 'Mastercard';
        }

        // Default to Visa
        return 'Visa';
    }

    /**
     * Validate card expiry date
     *
     * @param string $expiry Format: MM/YY or MM/YYYY
     * @return bool
     */
    public function validateCardExpiry(string $expiry): bool
    {
        try {
            // Parse MM/YY or MM/YYYY
            if (strlen($expiry) === 5) {
                // MM/YY format
                $date = \Carbon\Carbon::createFromFormat('m/y', $expiry)->endOfMonth();
            } else {
                // MM/YYYY format
                $date = \Carbon\Carbon::createFromFormat('m/Y', $expiry)->endOfMonth();
            }

            return $date->isFuture();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Format expiry to MM/YYYY for storage
     *
     * @param string $expiry
     * @return string
     */
    public function formatExpiry(string $expiry): string
    {
        if (strlen($expiry) === 5) {
            // MM/YY to MM/YYYY
            $parts = explode('/', $expiry);
            $year = '20' . $parts[1];
            return $parts[0] . '/' . $year;
        }

        return $expiry;
    }
}
