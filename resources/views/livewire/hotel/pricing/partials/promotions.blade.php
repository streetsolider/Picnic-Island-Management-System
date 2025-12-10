{{-- Promotional Discounts Tab --}}
<div>
    {{-- Header with Add Button --}}
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Promotional Discounts</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Create flexible promotional campaigns with custom conditions</p>
        </div>
        <div class="flex items-center gap-3">
            <x-admin.help-tooltip
                title="How Promotional Discounts Work"
                buttonText="Promotional Discounts Explained">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Purpose</h4>
                        <p>Create flexible promotional campaigns to attract more bookings.</p>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">How it works</h4>
                        <ul class="list-disc list-inside space-y-2 ml-2">
                            <li>Applied to the <strong>TOTAL</strong> after all other pricing (view, seasonal, day-type)</li>
                            <li>Can be based on multiple conditions combined</li>
                            <li>Auto-apply or require promo code</li>
                            <li>Best matching promotion automatically selected</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Condition Examples</h4>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li><strong>Multi-room:</strong> "Book 2+ rooms, get 15% off"</li>
                            <li><strong>Early Bird:</strong> "Book 30 days ahead, save MVR 1,000"</li>
                            <li><strong>Duration:</strong> "2-3 night stays get MVR 500 off"</li>
                            <li><strong>Room Type:</strong> "Deluxe & Suite rooms 12% off"</li>
                            <li><strong>Promo Code:</strong> "LOCAL2025 for 20% resident discount"</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Full Example</h4>
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="font-semibold mb-2">Family Fun Package:</p>
                            <p class="font-mono text-sm">
                                3-night booking, 2 rooms<br>
                                Subtotal = MVR 13,260<br>
                                "Family Fun Package" (2+ rooms, -15%)<br>
                                <strong>Final: MVR 11,271</strong><br>
                                (Saved MVR 1,989)
                            </p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Priority</h4>
                        <p>If multiple promotions match, the one with highest priority (or biggest discount) wins.</p>
                    </div>
                </div>
            </x-admin.help-tooltip>

            <x-admin.button.primary
                wire:click="openPromotionForm"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
                Add Promotion
            </x-admin.button.primary>
        </div>
    </div>

    {{-- Promotion Form Modal --}}
    @if ($showPromotionForm)
        <div x-data="{ isOpen: true }"
             x-init="
                $nextTick(() => { isOpen = true; });
                const handleEscape = (e) => { if (e.key === 'Escape') { $wire.closePromotionForm(); } };
                window.addEventListener('keydown', handleEscape);
                $el._cleanup = () => window.removeEventListener('keydown', handleEscape);
             "
             x-on:click.self="$wire.closePromotionForm()"
             class="fixed inset-0 z-50 overflow-y-auto">

            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 backdrop-blur-sm z-40"
                 @click="$wire.closePromotionForm()">
            </div>

            <div class="relative z-50 flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                     @click.stop>
                    <form wire:submit.prevent="savePromotion">
                        <div class="bg-white dark:bg-gray-800 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                                {{ $editingPromotionId ? 'Edit Promotional Discount' : 'Add Promotional Discount' }}
                            </h3>

                            {{-- Basic Information --}}
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Basic Information</h4>

                                {{-- Promotion Name --}}
                                <div class="mb-4">
                                    <label for="promotionName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Promotion Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="promotionName" wire:model="promotionName"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="e.g., Family Fun Package, Summer Special">
                                    @error('promotionName')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="mb-4">
                                    <label for="promotionDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Description
                                    </label>
                                    <textarea id="promotionDescription" wire:model="promotionDescription" rows="2"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Description shown to customers"></textarea>
                                    @error('promotionDescription')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Discount Configuration --}}
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Discount Configuration</h4>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="promotionDiscountType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Discount Type <span class="text-red-500">*</span>
                                        </label>
                                        <select id="promotionDiscountType" wire:model="promotionDiscountType"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            @foreach ($modifierTypes as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('promotionDiscountType')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="promotionDiscountValue" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Discount Value <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" id="promotionDiscountValue" wire:model="promotionDiscountValue" step="0.01" min="0"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="e.g., 15">
                                        @error('promotionDiscountValue')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Validity Period --}}
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Validity Period</h4>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="promotionStartDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Start Date
                                        </label>
                                        <input type="date" id="promotionStartDate" wire:model="promotionStartDate"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for always active</p>
                                        @error('promotionStartDate')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="promotionEndDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            End Date
                                        </label>
                                        <input type="date" id="promotionEndDate" wire:model="promotionEndDate"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for no end date</p>
                                        @error('promotionEndDate')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Booking Conditions --}}
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Booking Conditions (Optional)</h4>

                                {{-- Rooms --}}
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="promotionMinimumRooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Minimum Rooms
                                        </label>
                                        <input type="number" id="promotionMinimumRooms" wire:model="promotionMinimumRooms" min="1"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="e.g., 2 for family packages">
                                        @error('promotionMinimumRooms')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="promotionMaximumRooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Maximum Rooms
                                        </label>
                                        <input type="number" id="promotionMaximumRooms" wire:model="promotionMaximumRooms" min="1"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Leave empty for no limit">
                                        @error('promotionMaximumRooms')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Nights --}}
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="promotionMinimumNights" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Minimum Nights
                                        </label>
                                        <input type="number" id="promotionMinimumNights" wire:model="promotionMinimumNights" min="1"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="e.g., 2">
                                        @error('promotionMinimumNights')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="promotionMaximumNights" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Maximum Nights
                                        </label>
                                        <input type="number" id="promotionMaximumNights" wire:model="promotionMaximumNights" min="1"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="e.g., 3 for weekend getaway">
                                        @error('promotionMaximumNights')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Booking Advance Days --}}
                                <div class="mb-4">
                                    <label for="promotionBookingAdvanceDays" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Booking Advance Days (Early Bird)
                                    </label>
                                    <input type="number" id="promotionBookingAdvanceDays" wire:model="promotionBookingAdvanceDays" min="1"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="e.g., 30 for early bird discount">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum days before check-in to qualify</p>
                                    @error('promotionBookingAdvanceDays')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Room Types --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Applicable Room Types
                                    </label>
                                    <div class="space-y-2">
                                        @foreach ($roomTypes as $key => $label)
                                            <label class="flex items-center">
                                                <input type="checkbox" wire:model="promotionApplicableRoomTypes" value="{{ ucfirst($key) }}"
                                                    class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave unchecked for all room types</p>
                                    @error('promotionApplicableRoomTypes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Promo Code --}}
                                <div class="mb-4">
                                    <label for="promotionPromoCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Promo Code
                                    </label>
                                    <input type="text" id="promotionPromoCode" wire:model="promotionPromoCode"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm uppercase"
                                        placeholder="e.g., LOCAL2025, SUMMER15">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for auto-apply, or enter code for targeted marketing</p>
                                    @error('promotionPromoCode')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Priority & Status --}}
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Priority & Status</h4>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="promotionPriority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Priority <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" id="promotionPriority" wire:model="promotionPriority" min="0" max="100"
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="0-100">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Higher priority wins if multiple match (0-100)</p>
                                        @error('promotionPriority')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex items-center">
                                        <label class="flex items-center mt-6">
                                            <input type="checkbox" wire:model="promotionIsActive"
                                                class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
                            <x-admin.button.secondary type="button" wire:click="closePromotionForm">
                                Cancel
                            </x-admin.button.secondary>
                            <x-admin.button.primary type="submit" wire:loading.attr="disabled" wire:target="savePromotion">
                                {{ $editingPromotionId ? 'Update' : 'Create' }}
                            </x-admin.button.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Promotions List --}}
    @php
        $promotions = $this->getPromotions();
    @endphp

    @if ($promotions->isEmpty())
        <x-admin.card.empty-state
            icon="🎯"
            title="No promotional discounts configured"
            description="Create flexible promotions to attract more bookings (e.g., Family packages, Early bird discounts, Local resident specials).">
            <x-slot name="action">
                <x-admin.button.primary
                    wire:click="openPromotionForm"
                    :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
                    Add First Promotion
                </x-admin.button.primary>
            </x-slot>
        </x-admin.card.empty-state>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <x-admin.table.wrapper hoverable>
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <x-admin.table.header>Promotion Name</x-admin.table.header>
                        <x-admin.table.header>Discount</x-admin.table.header>
                        <x-admin.table.header>Conditions</x-admin.table.header>
                        <x-admin.table.header>Validity</x-admin.table.header>
                        <x-admin.table.header>Priority</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promotions as $promotion)
                        <x-admin.table.row>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $promotion->promotion_name }}
                                </div>
                                @if($promotion->promotion_description)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ Str::limit($promotion->promotion_description, 50) }}
                                    </div>
                                @endif
                                @if($promotion->promo_code)
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                            CODE: {{ $promotion->promo_code }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                    @if($promotion->discount_type === 'fixed')
                                        -MVR {{ number_format($promotion->discount_value, 2) }}
                                    @else
                                        -{{ $promotion->discount_value }}%
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-700 dark:text-gray-300 space-y-1">
                                    @if($promotion->minimum_rooms || $promotion->maximum_rooms)
                                        <div>🏨 {{ $promotion->minimum_rooms ?? '1' }}{{ $promotion->maximum_rooms ? '-' . $promotion->maximum_rooms : '+' }} rooms</div>
                                    @endif
                                    @if($promotion->minimum_nights || $promotion->maximum_nights)
                                        <div>🌙 {{ $promotion->minimum_nights ?? '1' }}{{ $promotion->maximum_nights ? '-' . $promotion->maximum_nights : '+' }} nights</div>
                                    @endif
                                    @if($promotion->booking_advance_days)
                                        <div>⏰ {{ $promotion->booking_advance_days }}+ days advance</div>
                                    @endif
                                    @if($promotion->applicable_room_types)
                                        <div>🛏️ {{ implode(', ', $promotion->applicable_room_types) }}</div>
                                    @endif
                                    @if(!$promotion->minimum_rooms && !$promotion->maximum_rooms && !$promotion->minimum_nights && !$promotion->maximum_nights && !$promotion->booking_advance_days && !$promotion->applicable_room_types)
                                        <div class="text-gray-400">No restrictions</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-700 dark:text-gray-300">
                                    @if($promotion->start_date || $promotion->end_date)
                                        <div>{{ $promotion->start_date?->format('M d, Y') ?? 'Anytime' }}</div>
                                        <div class="text-gray-400">to</div>
                                        <div>{{ $promotion->end_date?->format('M d, Y') ?? 'No end' }}</div>
                                    @else
                                        <div class="text-gray-400">Always active</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ $promotion->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="togglePromotionStatus({{ $promotion->id }})" type="button"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $promotion->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                    {{ $promotion->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button wire:click="editPromotion({{ $promotion->id }})" type="button"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDeletePromotion({{ $promotion->id }})"
                                        x-data
                                        x-on:click="$dispatch('open-modal', 'delete-promotion-modal')"
                                        type="button"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-admin.modal.confirmation
        name="delete-promotion-modal"
        title="Delete Promotional Discount"
        :show="!!$deletingPromotionId"
        wire:click="deletePromotion"
        x-on:close="$wire.deletingPromotionId = null">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Are you sure you want to delete this promotional discount? This action cannot be undone.
        </p>
    </x-admin.modal.confirmation>
</div>
