<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                Saved <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Payment Methods</span>
            </h1>
            <p class="text-gray-600">Manage your saved cards for faster checkout</p>
        </div>

        {{-- Success/Error Messages --}}
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <p class="text-green-600 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-red-600 font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Saved Cards List --}}
        @if($savedCards->isEmpty())
            {{-- Empty State --}}
            <div class="bg-white rounded-3xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-brand-light to-brand-primary/20 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-display font-bold text-brand-dark mb-2">No saved payment methods</h3>
                <p class="text-gray-600 mb-6">You haven't saved any payment methods yet. Save a card during checkout for faster future bookings.</p>
                <a href="{{ route('booking.search') }}"
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-brand-primary to-brand-secondary text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                    Make a Booking
                </a>
            </div>
        @else
            {{-- Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @foreach($savedCards as $card)
                    <div class="bg-white rounded-3xl shadow-lg p-6 border-2 transition-all hover:shadow-xl
                        {{ $card->is_default ? 'border-brand-primary' : 'border-gray-100' }}">
                        {{-- Card Header --}}
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                {{-- Bank Logo --}}
                                <div class="w-20 h-10 mb-3 flex items-center justify-center bg-gray-50 rounded-lg">
                                    <img src="{{ asset('images/banks/' . strtolower($card->bank) . '-logo.svg') }}"
                                         alt="{{ $card->bank }}"
                                         class="max-w-full max-h-8 object-contain"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-sm font-bold text-brand-primary\'>{{ $card->bank }}</span>';">
                                </div>

                                {{-- Card Display Name --}}
                                <h3 class="text-lg font-bold text-brand-dark">
                                    {{ $card->getDisplayName() }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ $card->card_holder_name }}</p>
                            </div>

                            {{-- Default Badge --}}
                            @if($card->is_default)
                                <span class="px-3 py-1 bg-gradient-to-r from-brand-primary to-brand-secondary text-white text-xs font-bold rounded-full">
                                    Default
                                </span>
                            @endif
                        </div>

                        {{-- Card Details --}}
                        <div class="mb-4 pb-4 border-b border-gray-100 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Expires:</span>
                                <span class="font-semibold {{ $card->isExpired() ? 'text-red-600' : 'text-brand-dark' }}">
                                    {{ $card->card_expiry }}
                                    @if($card->isExpired())
                                        <span class="text-xs">(Expired)</span>
                                    @endif
                                </span>
                            </div>
                            @if($card->last_used_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Last used:</span>
                                    <span class="text-brand-dark">{{ $card->last_used_at->diffForHumans() }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Times used:</span>
                                <span class="text-brand-dark font-semibold">{{ $card->usage_count }}</span>
                            </div>
                        </div>

                        {{-- Card Actions --}}
                        <div class="flex gap-3">
                            @if(!$card->is_default)
                                <button
                                    wire:click="setAsDefault({{ $card->id }})"
                                    class="flex-1 px-4 py-3 bg-gradient-to-r from-brand-primary to-brand-secondary text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                                    Set as Default
                                </button>
                            @endif

                            <button
                                wire:click="confirmDelete({{ $card->id }})"
                                class="px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all
                                    {{ $card->is_default ? 'flex-1' : '' }}">
                                Delete
                            </button>
                        </div>

                        {{-- Expired Warning --}}
                        @if($card->isExpired())
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                <p class="text-sm text-red-600 font-semibold">
                                    This card has expired and cannot be used for payments.
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Info Card --}}
            <div class="bg-blue-50 border border-blue-200 rounded-3xl shadow-lg p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-brand-dark mb-2">About Saved Payment Methods</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Only the last 4 digits of your card are stored for security
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Your full card number and CVV are never saved
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                You'll need to enter your CVV for each payment
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Set a default card for faster checkout
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-brand-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                You can delete saved cards at any time
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Delete Confirmation Modal --}}
        @if($showDeleteConfirm)
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-display font-bold text-brand-dark text-center mb-2">Delete Payment Method?</h3>
                        <p class="text-gray-600 text-center">
                            Are you sure you want to delete this payment method? This action cannot be undone.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button
                            wire:click="cancelDelete"
                            class="flex-1 px-6 py-4 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-all">
                            Cancel
                        </button>
                        <button
                            wire:click="deleteCard"
                            class="flex-1 px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
