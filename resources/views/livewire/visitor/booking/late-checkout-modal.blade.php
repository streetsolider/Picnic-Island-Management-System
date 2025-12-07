{{-- Late Checkout Request Modal --}}
@if($showLateCheckoutModal && $selectedBookingForLateCheckout)
<div x-data="{ show: @entangle('showLateCheckoutModal') }"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-0"
             @click="show = false"
             aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal panel --}}
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 relative z-10">

            <div class="mb-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-display font-bold text-brand-dark" id="modal-title">
                        Request Late Checkout
                    </h3>
                    <button wire:click="closeLateCheckoutModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <form wire:submit.prevent="submitLateCheckoutRequest">
                <div class="space-y-4">
                    {{-- Info Alert --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Late Checkout Information</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    <li>Late checkout is <strong>FREE</strong></li>
                                    <li>Maximum checkout time: 6:00 PM</li>
                                    <li>Subject to hotel manager approval</li>
                                    <li>Current checkout: {{ $selectedBookingForLateCheckout->hotel->formatted_checkout_time }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Booking Details --}}
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Booking Details</h4>
                        <dl class="grid grid-cols-2 gap-2 text-sm">
                            <dt class="text-gray-600">Hotel:</dt>
                            <dd class="text-gray-900 font-medium">{{ $selectedBookingForLateCheckout->hotel->name }}</dd>

                            <dt class="text-gray-600">Room:</dt>
                            <dd class="text-gray-900 font-medium">{{ $selectedBookingForLateCheckout->room->room_number }}</dd>

                            <dt class="text-gray-600">Checkout Date:</dt>
                            <dd class="text-gray-900 font-medium">{{ $selectedBookingForLateCheckout->check_out_date->format('M d, Y') }}</dd>
                        </dl>
                    </div>

                    {{-- Requested Time --}}
                    <div>
                        <label for="requestedCheckoutTime" class="block text-sm font-medium text-gray-700 mb-2">
                            Requested Checkout Time <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="time"
                            id="requestedCheckoutTime"
                            wire:model="requestedCheckoutTime"
                            min="{{ \Carbon\Carbon::parse($selectedBookingForLateCheckout->hotel->default_checkout_time)->format('H:i') }}"
                            max="18:00"
                            required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm"
                        />
                        @error('requestedCheckoutTime')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Must be after {{ $selectedBookingForLateCheckout->hotel->formatted_checkout_time }} and before 6:00 PM
                        </p>
                    </div>

                    {{-- Guest Notes --}}
                    <div>
                        <label for="guestNotes" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Request (Optional)
                        </label>
                        <textarea
                            id="guestNotes"
                            wire:model="guestNotes"
                            rows="3"
                            maxlength="500"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm"
                            placeholder="Let the hotel know why you need late checkout..."></textarea>
                        @error('guestNotes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            {{ strlen($guestNotes) }}/500 characters
                        </p>
                    </div>
                </div>

                {{-- Modal Actions --}}
                <div class="mt-6 flex gap-3">
                    <button type="button"
                            wire:click="closeLateCheckoutModal"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-brand-primary hover:bg-brand-primary/90 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
