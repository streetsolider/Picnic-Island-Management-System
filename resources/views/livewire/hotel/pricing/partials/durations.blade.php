{{-- Duration Discounts Tab --}}
<div>
    {{-- Header with Add Button --}}
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Duration Discounts</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Configure discounts based on length of stay</p>
        </div>
        <x-admin.button.primary
            wire:click="openDurationDiscountForm"
            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
            Add Duration Discount
        </x-admin.button.primary>
    </div>

    {{-- Duration Discount Form Modal --}}
    @if ($showDurationDiscountForm)
        <div x-data="{ isOpen: true }"
             x-init="
                $nextTick(() => { isOpen = true; });
                const handleEscape = (e) => { if (e.key === 'Escape') { $wire.closeDurationDiscountForm(); } };
                window.addEventListener('keydown', handleEscape);
                $el._cleanup = () => window.removeEventListener('keydown', handleEscape);
             "
             x-on:click.self="$wire.closeDurationDiscountForm()"
             class="fixed inset-0 z-50 overflow-y-auto">

            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 backdrop-blur-sm z-40"
                 @click="$wire.closeDurationDiscountForm()">
            </div>

            <div class="relative z-50 flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                     @click.stop>
                    <form wire:submit.prevent="saveDurationDiscount">
                        <div class="bg-white dark:bg-gray-800 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                                {{ $editingDurationDiscountId ? 'Edit Duration Discount' : 'Add Duration Discount' }}
                            </h3>

                            {{-- Discount Name --}}
                            <div class="mb-4">
                                <label for="durationDiscountName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Discount Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="durationDiscountName" wire:model="durationDiscountName"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="e.g., 3+ Nights Discount, Weekly Stay Discount">
                                @error('durationDiscountName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Night Range --}}
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="durationMinimumNights" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Minimum Nights <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="durationMinimumNights" wire:model="durationMinimumNights" min="1"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="e.g., 3">
                                    @error('durationMinimumNights')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="durationMaximumNights" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Maximum Nights (Optional)
                                    </label>
                                    <input type="number" id="durationMaximumNights" wire:model="durationMaximumNights" min="1"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Leave empty for no limit">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Leave empty for open-ended
                                    </p>
                                    @error('durationMaximumNights')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Discount Type and Value --}}
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="durationDiscountType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Discount Type <span class="text-red-500">*</span>
                                    </label>
                                    <select id="durationDiscountType" wire:model="durationDiscountType"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @foreach ($modifierTypes as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('durationDiscountType')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="durationDiscountValue" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Discount Value <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="durationDiscountValue" wire:model="durationDiscountValue" step="0.01" min="0"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="e.g., 10">
                                    @error('durationDiscountValue')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Is Active Toggle --}}
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="durationIsActive"
                                        class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
                            <x-admin.button.secondary type="button" wire:click="closeDurationDiscountForm">
                                Cancel
                            </x-admin.button.secondary>
                            <x-admin.button.primary type="submit" wire:loading.attr="disabled" wire:target="saveDurationDiscount">
                                {{ $editingDurationDiscountId ? 'Update' : 'Create' }}
                            </x-admin.button.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Discounts List --}}
    @php
        $discounts = $this->getDurationDiscounts();
    @endphp

    @if ($discounts->isEmpty())
        <x-admin.card.empty-state
            icon="🎯"
            title="No duration discounts configured"
            description="Create discounts to encourage longer stays (e.g., 7+ nights, 30+ nights).">
            <x-slot name="action">
                <x-admin.button.primary
                    wire:click="openDurationDiscountForm"
                    :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
                    Add First Discount
                </x-admin.button.primary>
            </x-slot>
        </x-admin.card.empty-state>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <x-admin.table.wrapper hoverable>
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <x-admin.table.header>Discount Name</x-admin.table.header>
                        <x-admin.table.header>Night Range</x-admin.table.header>
                        <x-admin.table.header>Discount</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($discounts as $discount)
                        <x-admin.table.row>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $discount->discount_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $discount->minimum_nights }}{{ $discount->maximum_nights ? '-' . $discount->maximum_nights : '+' }} nights
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                    @if($discount->discount_type === 'fixed')
                                        -MVR {{ number_format($discount->discount_value, 2) }}
                                    @else
                                        -{{ $discount->discount_value }}%
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleDurationDiscountStatus({{ $discount->id }})" type="button"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $discount->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                    {{ $discount->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button wire:click="editDurationDiscount({{ $discount->id }})" type="button"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDeleteDurationDiscount({{ $discount->id }})"
                                        x-data
                                        x-on:click="$dispatch('open-modal', 'delete-duration-discount-modal')"
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
</div>
