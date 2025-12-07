<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                Secure <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Payment</span>
            </h1>
            <p class="text-gray-600">Complete your booking with secure payment</p>
        </div>

        {{-- Error Message --}}
        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-red-600 font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Payment Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-lg p-8">
                    <form wire:submit.prevent="processPayment">
                        {{-- Bank Selection --}}
                        <div class="mb-6">
                            <h3 class="text-xl font-display font-bold text-brand-dark mb-4">Select Bank</h3>
                            @if($useSavedCard && $savedCardId)
                                <p class="text-sm text-gray-600 mb-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <strong>Note:</strong> Bank is automatically selected based on your saved card.
                                </p>
                            @endif
                            <div class="grid grid-cols-3 gap-4">
                                @foreach($banks as $code => $bank)
                                    <button
                                        type="button"
                                        wire:click="selectBank('{{ $code }}')"
                                        @if($useSavedCard && $savedCardId) disabled @endif
                                        class="p-4 border-2 rounded-xl transition-all
                                            {{ $useSavedCard && $savedCardId ? 'opacity-50 cursor-not-allowed' : '' }}
                                            {{ $selectedBank === $code
                                                ? 'border-brand-primary bg-brand-primary/5 shadow-md'
                                                : 'border-gray-200 hover:border-brand-primary/50 hover:shadow' }}">
                                        <div class="w-full h-16 flex items-center justify-center mb-2 bg-white rounded-lg">
                                            <img src="{{ asset('images/banks/' . $bank['logo']) }}"
                                                 alt="{{ $bank['name'] }}"
                                                 class="max-w-full max-h-12 object-contain"
                                                 onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-2xl font-bold text-brand-primary\'>{{ $code }}</span>';">
                                        </div>
                                        <p class="text-xs text-center font-semibold text-gray-700">{{ $code }}</p>
                                    </button>
                                @endforeach
                            </div>
                            @error('selectedBank') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Saved Cards Section --}}
                        @if($savedCards->isNotEmpty())
                            <div class="pb-6 border-t border-gray-100 pt-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model.live="useSavedCard" class="rounded text-brand-primary focus:ring-brand-primary">
                                    <span class="font-semibold text-brand-dark">Use saved card</span>
                                </label>

                                @if($useSavedCard)
                                    <div class="mt-4 space-y-3">
                                        @foreach($savedCards as $card)
                                            <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all
                                                {{ $savedCardId === $card->id
                                                    ? 'border-brand-primary bg-brand-primary/5 shadow-md'
                                                    : 'border-gray-200 hover:border-brand-primary/50' }}">
                                                <input type="radio"
                                                       wire:model.live="savedCardId"
                                                       value="{{ $card->id }}"
                                                       name="savedCard"
                                                       class="text-brand-primary focus:ring-brand-primary">
                                                <div class="flex-1">
                                                    <p class="font-semibold text-brand-dark">{{ $card->getDisplayName() }}</p>
                                                    <p class="text-sm text-gray-600">Expires: {{ $card->card_expiry }}</p>
                                                </div>
                                                @if($card->is_default)
                                                    <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">Default</span>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Card Input Form --}}
                        @if(!$useSavedCard)
                            <div class="space-y-4 pb-6 {{ $savedCards->isNotEmpty() ? 'border-t border-gray-100 pt-6' : '' }}">
                                {{-- Card Number --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Card Number</label>
                                    <input type="text"
                                           wire:model="cardNumber"
                                           placeholder="1234 **** **** ****"
                                           x-data
                                           x-on:input="$event.target.value = $event.target.value.replace(/\D/g, '').replace(/(\d{4})/g, '$1 ').trim().slice(0, 19)"
                                           inputmode="numeric"
                                           maxlength="19"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                    @error('cardNumber') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Card Holder --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Card Holder Name</label>
                                    <input type="text"
                                           wire:model="cardHolder"
                                           placeholder="Aishath Ibrahim"
                                           x-data
                                           x-on:input="$event.target.value = $event.target.value.replace(/[^a-zA-Z\s'-]/g, '')"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent uppercase">
                                    @error('cardHolder') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Expiry & CVV --}}
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                                        <input type="text"
                                               wire:model="expiryMonth"
                                               placeholder="MM"
                                               x-data
                                               x-on:input="$event.target.value = $event.target.value.replace(/\D/g, '').slice(0, 2)"
                                               inputmode="numeric"
                                               maxlength="2"
                                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent text-center">
                                        @error('expiryMonth') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                                        <input type="text"
                                               wire:model="expiryYear"
                                               placeholder="YYYY"
                                               x-data
                                               x-on:input="$event.target.value = $event.target.value.replace(/\D/g, '').slice(0, 4)"
                                               inputmode="numeric"
                                               maxlength="4"
                                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent text-center">
                                        @error('expiryYear') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">CVV</label>
                                        <input type="text"
                                               wire:model="cvv"
                                               placeholder="123"
                                               x-data
                                               x-on:input="$event.target.value = $event.target.value.replace(/\D/g, '').slice(0, 3)"
                                               inputmode="numeric"
                                               maxlength="3"
                                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent text-center">
                                        @error('cvv') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- Save Card Option --}}
                                <div class="pt-4 border-t border-gray-100">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model.live="saveCard" class="rounded text-brand-primary focus:ring-brand-primary">
                                        <span class="text-sm font-medium text-gray-700">Save card for future bookings</span>
                                    </label>

                                    @if($saveCard)
                                        <label class="flex items-center gap-2 cursor-pointer mt-2 ml-6">
                                            <input type="checkbox" wire:model="setAsDefault" class="rounded text-brand-primary focus:ring-brand-primary">
                                            <span class="text-sm text-gray-600">Set as default payment method</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        @else
                            {{-- CVV for saved card --}}
                            <div class="pb-6 border-t border-gray-100 pt-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">CVV Security Code</label>
                                <input type="text"
                                       wire:model="cvv"
                                       placeholder="123"
                                       maxlength="3"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent text-center">
                                @error('cvv') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Enter the 3-digit security code on the back of your card</p>
                            </div>
                        @endif

                        {{-- Pay Button --}}
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full mt-6 bg-gradient-to-r from-brand-primary to-brand-secondary text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="processPayment">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Pay MVR {{ number_format($totalAmount, 2) }}
                                </span>
                            </span>
                            <span wire:loading wire:target="processPayment">Processing...</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-2xl p-6 sticky top-8">
                    <h3 class="text-xl font-display font-bold text-brand-dark mb-4">Order Summary</h3>

                    @if($bookingType === 'hotel')
                        <div class="space-y-3 text-sm">
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-xs text-gray-600 mb-1">Hotel</p>
                                <p class="font-semibold text-brand-dark">{{ $bookingData['hotel_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-xs text-gray-600 mb-1">Room</p>
                                <p class="font-semibold text-brand-dark">{{ $bookingData['room_number'] ?? 'N/A' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <p class="text-xs text-gray-600 mb-1">Check-in</p>
                                    <p class="font-semibold text-brand-dark text-xs">{{ $bookingData['check_in_date'] ?? 'N/A' }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <p class="text-xs text-gray-600 mb-1">Check-out</p>
                                    <p class="font-semibold text-brand-dark text-xs">{{ $bookingData['check_out_date'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-xs text-gray-600 mb-1">Guests</p>
                                <p class="font-semibold text-brand-dark">{{ $bookingData['number_of_guests'] ?? 'N/A' }} {{ Str::plural('Guest', $bookingData['number_of_guests'] ?? 1) }}</p>
                            </div>
                        </div>
                    @elseif($bookingType === 'wallet_topup')
                        <div class="space-y-3 text-sm">
                            <div class="p-4 bg-gradient-to-br from-brand-primary/10 to-brand-secondary/10 rounded-xl border-2 border-brand-primary/20">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-12 h-12 bg-brand-primary/20 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 mb-1">Service</p>
                                        <p class="font-bold text-brand-dark text-lg">{{ $bookingData['description'] ?? 'Wallet Top-up' }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-3">Add funds to your Theme Park wallet to purchase credits and book activities.</p>
                            </div>
                        </div>
                    @else
                        <div class="space-y-3 text-sm">
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-xs text-gray-600 mb-1">Service</p>
                                <p class="font-semibold text-brand-dark">{{ $bookingData['service_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-xs text-gray-600 mb-1">Date</p>
                                <p class="font-semibold text-brand-dark">{{ $bookingData['booking_date'] ?? 'N/A' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-xs text-gray-600 mb-1">Time</p>
                                <p class="font-semibold text-brand-dark">{{ $bookingData['start_time'] ?? 'N/A' }} - {{ $bookingData['end_time'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-brand-dark">MVR {{ number_format($totalAmount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span class="text-brand-dark">Total</span>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">
                                MVR {{ number_format($totalAmount, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <div>
                                <p class="text-xs font-semibold text-brand-dark">Secure Payment</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    Powered by {{ $selectedBank ? $banks[$selectedBank]['name'] : 'Maldivian Banks' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Simulation Modal --}}
        @if($showSimulation)
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-brand-primary to-brand-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-display font-bold text-brand-dark mb-2">Payment Simulation</h3>
                        <p class="text-gray-600">This is a test environment. Simulate the payment outcome:</p>
                    </div>
                    <div class="space-y-3">
                        <button
                            wire:click="simulateSuccess"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simulate Success
                        </button>
                        <button
                            wire:click="simulateFailure"
                            class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Simulate Failure
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
