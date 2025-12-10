<?php

namespace App\Livewire\Visitor\Payment;

use App\Models\Payment;
use App\Models\SavedPaymentMethod;
use App\Services\PaymentService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.visitor')]
#[Title('Payment Gateway')]
class Gateway extends Component
{
    // Session data from booking form
    public $bookingData;
    public $bookingType;
    public $totalAmount;

    // Payment form fields
    public $selectedBank = '';
    public $useSavedCard = false;
    public $savedCardId = null;
    public $cardNumber = '';
    public $cardHolder = '';
    public $expiryMonth = '';
    public $expiryYear = '';
    public $cvv = '';
    public $saveCard = false;
    public $setAsDefault = false;

    // UI state
    public $processing = false;
    public $showSimulation = false;
    public $paymentId = null;

    // Available banks
    public $banks = [
        'MIB' => [
            'name' => 'Maldives Islamic Bank',
            'logo' => 'mib-logo.png',
        ],
        'BML' => [
            'name' => 'Bank of Maldives',
            'logo' => 'bml-logo.png',
        ],
        'CBM' => [
            'name' => 'Commercial Bank Maldives',
            'logo' => 'cbm-logo.png',
        ],
    ];

    protected function rules()
    {
        return [
            'selectedBank' => 'required|in:MIB,BML,CBM',
            'cardNumber' => $this->useSavedCard ? '' : 'required|digits:16',
            'cardHolder' => $this->useSavedCard ? '' : 'required|string|max:100',
            'expiryMonth' => $this->useSavedCard ? '' : 'required|digits:2|min:1|max:12',
            'expiryYear' => $this->useSavedCard ? '' : 'required|digits:4',
            'cvv' => 'required|digits:3',
            'savedCardId' => $this->useSavedCard ? 'required|exists:saved_payment_methods,id' : '',
        ];
    }

    protected function messages()
    {
        return [
            'expiryMonth.required' => 'Expiry month is required',
            'expiryMonth.digits' => 'Expiry month must be 2 digits',
            'expiryMonth.min' => 'Invalid month',
            'expiryMonth.max' => 'Invalid month',
            'expiryYear.required' => 'Expiry year is required',
            'expiryYear.digits' => 'Expiry year must be 4 digits',
            'expiryYear.min' => 'Card has expired',
        ];
    }

    protected function validateExpiry()
    {
        if ($this->useSavedCard) {
            return true;
        }

        if (!$this->expiryMonth || !$this->expiryYear) {
            return false;
        }

        try {
            $expiry = \Carbon\Carbon::createFromFormat('m/Y', sprintf('%02d/%s', $this->expiryMonth, $this->expiryYear))->endOfMonth();

            if ($expiry->isPast()) {
                $this->addError('expiryMonth', 'This card has expired');
                $this->addError('expiryYear', 'This card has expired');
                return false;
            }

            return true;
        } catch (\Exception $e) {
            $this->addError('expiryMonth', 'Invalid expiry date');
            return false;
        }
    }

    public function mount()
    {
        // Retrieve booking data from session
        $this->bookingData = session('pending_booking');

        if (!$this->bookingData) {
            session()->flash('error', 'No pending booking found. Please start a new booking.');
            return redirect()->route('booking.search');
        }

        $this->bookingType = $this->bookingData['booking_type'];
        $this->totalAmount = $this->bookingData['total_price'];

        // Auto-select default card if exists
        if (auth()->check()) {
            $defaultCard = auth()->user()->defaultPaymentMethod;
            if ($defaultCard) {
                $this->useSavedCard = true;
                $this->savedCardId = $defaultCard->id;
                $this->selectedBank = $defaultCard->bank;
            }
        }
    }

    public function selectBank($bank)
    {
        $this->selectedBank = $bank;
    }

    public function toggleSavedCard()
    {
        $this->useSavedCard = !$this->useSavedCard;

        if (!$this->useSavedCard) {
            $this->savedCardId = null;
        }
    }

    public function selectSavedCard($cardId)
    {
        $this->savedCardId = $cardId;
        $card = SavedPaymentMethod::find($cardId);
        if ($card) {
            $this->selectedBank = $card->bank;
        }
    }

    public function processPayment()
    {
        // Strip spaces from card number
        $this->cardNumber = str_replace(' ', '', $this->cardNumber);

        $this->validate();

        // Validate that selected bank matches saved card's bank
        if ($this->useSavedCard && $this->savedCardId) {
            $savedCard = SavedPaymentMethod::find($this->savedCardId);
            if ($savedCard && $savedCard->bank !== $this->selectedBank) {
                $this->addError('selectedBank', 'Selected bank must match the saved card\'s bank (' . $savedCard->bank . ')');
                $this->processing = false;
                return;
            }
        }

        // Validate card expiry
        if (!$this->validateExpiry()) {
            $this->processing = false;
            return;
        }

        $this->processing = true;

        try {
            $paymentService = app(PaymentService::class);

            // Prepare card data
            if ($this->useSavedCard) {
                $savedCard = SavedPaymentMethod::findOrFail($this->savedCardId);
                $cardData = [
                    'bank' => $savedCard->bank,
                    'card_type' => $savedCard->card_type,
                    'card_last_four' => $savedCard->card_last_four,
                    'card_token' => $savedCard->card_token,
                ];
            } else {
                $cardDetails = $paymentService->extractCardDetails($this->cardNumber);
                $cardData = [
                    'bank' => $this->selectedBank,
                    'card_type' => $cardDetails['card_type'],
                    'card_last_four' => $cardDetails['card_last_four'],
                    'card_token' => null,
                ];

                // Validate expiry
                $expiry = "{$this->expiryMonth}/{$this->expiryYear}";
                if (!$paymentService->validateCardExpiry($expiry)) {
                    $this->addError('expiryMonth', 'Card has expired or invalid expiry date');
                    $this->processing = false;
                    return;
                }
            }

            // Create payment record
            $payment = $paymentService->initiatePayment([
                'payable_type' => null,
                'payable_id' => null,
                'guest_id' => auth()->id(),
                'amount' => $this->totalAmount,
                'currency' => 'MVR',
                'bank' => $cardData['bank'],
                'card_type' => $cardData['card_type'],
                'card_last_four' => $cardData['card_last_four'],
                'card_token' => $cardData['card_token'],
                'source' => 'booking_flow',
            ]);

            // Start processing
            $paymentService->processPayment($payment);

            $this->paymentId = $payment->id;

            // Show simulation modal
            $this->showSimulation = true;

        } catch (\Exception $e) {
            $this->processing = false;
            session()->flash('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

    public function simulateSuccess()
    {
        try {
            $payment = Payment::findOrFail($this->paymentId);
            $paymentService = app(PaymentService::class);

            // Handle wallet top-up
            if ($this->bookingType === 'wallet_topup') {
                // Mark payment as completed
                $payment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                // Add funds to wallet
                $walletService = app(\App\Services\ThemeParkWalletService::class);
                $result = $walletService->topUpWallet(auth()->id(), $this->bookingData['amount']);

                if (!$result['success']) {
                    throw new \Exception($result['message']);
                }

                // Save card if requested
                if ($this->saveCard && !$this->useSavedCard) {
                    $expiry = $paymentService->formatExpiry("{$this->expiryMonth}/{$this->expiryYear}");

                    $paymentService->savePaymentMethod(auth()->user(), [
                        'bank' => $this->selectedBank,
                        'card_type' => $payment->card_type,
                        'card_last_four' => $payment->card_last_four,
                        'card_expiry' => $expiry,
                        'card_holder_name' => $this->cardHolder,
                    ], $this->setAsDefault);
                }

                // Clear session
                session()->forget('pending_booking');

                // Redirect to wallet with success message
                session()->flash('success', 'Wallet topped up successfully! MVR ' . number_format($this->bookingData['amount'], 2) . ' has been added to your account.');
                return redirect()->route('visitor.theme-park.wallet');
            }

            // Complete payment and create booking
            $booking = $paymentService->completePayment($payment, $this->bookingData);

            // Save card if requested
            if ($this->saveCard && !$this->useSavedCard) {
                $expiry = $paymentService->formatExpiry("{$this->expiryMonth}/{$this->expiryYear}");

                $paymentService->savePaymentMethod(auth()->user(), [
                    'bank' => $this->selectedBank,
                    'card_type' => $payment->card_type,
                    'card_last_four' => $payment->card_last_four,
                    'card_expiry' => $expiry,
                    'card_holder_name' => $this->cardHolder,
                ], $this->setAsDefault);
            }

            // Clear session
            session()->forget('pending_booking');

            // Redirect to confirmation
            if ($this->bookingType === 'hotel') {
                return redirect()->route('booking.confirmation', $booking->id);
            } else {
                return redirect()->route('visitor.beach-activities.confirmation', $booking->id);
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Transaction failed: ' . $e->getMessage());
            $this->processing = false;
            $this->showSimulation = false;
        }
    }

    public function simulateFailure()
    {
        $payment = Payment::findOrFail($this->paymentId);
        $paymentService = app(PaymentService::class);

        $paymentService->failPayment($payment, 'Simulated payment failure - User declined');

        session()->flash('error', 'Payment failed. Please try again with different payment details.');

        // Reset form
        $this->processing = false;
        $this->showSimulation = false;
        $this->paymentId = null;
    }

    public function render()
    {
        $savedCards = auth()->check() ? auth()->user()->savedPaymentMethods : collect();

        return view('livewire.visitor.payment.gateway', [
            'savedCards' => $savedCards,
        ]);
    }
}
