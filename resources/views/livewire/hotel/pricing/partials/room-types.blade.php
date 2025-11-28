{{-- Room Type Pricing Tab --}}
<div>
    {{-- Header with Add Button --}}
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Room Type Base Pricing</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Set base prices for each room type (in MVR)</p>
        </div>
        <x-admin.button.primary
            wire:click="openRoomTypePricingForm"
            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
            Add Room Type Pricing
        </x-admin.button.primary>
    </div>

    {{-- Room Type Pricing Form Modal --}}
    @if ($showRoomTypePricingForm)
        <div x-data="{ isOpen: true }"
             x-init="
                $nextTick(() => { isOpen = true; });
                const handleEscape = (e) => { if (e.key === 'Escape') { $wire.closeRoomTypePricingForm(); } };
                window.addEventListener('keydown', handleEscape);
                $el._cleanup = () => window.removeEventListener('keydown', handleEscape);
             "
             x-on:click.self="$wire.closeRoomTypePricingForm()"
             class="fixed inset-0 z-50 overflow-y-auto">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 backdrop-blur-sm z-40"
                 @click="$wire.closeRoomTypePricingForm()">
            </div>

            {{-- Modal Content --}}
            <div class="relative z-50 flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                     @click.stop>
                    <form wire:submit.prevent="saveRoomTypePricing">
                        <div class="bg-white dark:bg-gray-800 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                                {{ $editingRoomTypePricingId ? 'Edit Room Type Pricing' : 'Add Room Type Pricing' }}
                            </h3>

                            {{-- Room Type Selection --}}
                            <div class="mb-4">
                                <label for="selectedRoomType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Room Type <span class="text-red-500">*</span>
                                </label>
                                <select id="selectedRoomType" wire:model="selectedRoomType"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    {{ $editingRoomTypePricingId ? 'disabled' : '' }}>
                                    <option value="">Select a room type</option>
                                    @foreach ($roomTypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('selectedRoomType')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Base Price --}}
                            <div class="mb-4">
                                <label for="basePrice" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Base Price (MVR) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="basePrice" wire:model="basePrice" step="0.01" min="0"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="e.g., 2500.00">
                                @error('basePrice')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Is Active Toggle --}}
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="roomTypeIsActive"
                                        class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                </label>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
                            <x-admin.button.secondary type="button" wire:click="closeRoomTypePricingForm">
                                Cancel
                            </x-admin.button.secondary>
                            <x-admin.button.primary type="submit" wire:loading.attr="disabled" wire:target="saveRoomTypePricing">
                                {{ $editingRoomTypePricingId ? 'Update' : 'Create' }}
                            </x-admin.button.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Pricing List --}}
    @php
        $pricings = $this->getRoomTypePricings();
    @endphp

    @if ($pricings->isEmpty())
        <x-admin.card.empty-state
            icon="💰"
            title="No room type pricing configured"
            description="Get started by setting base prices for your room types.">
            <x-slot name="action">
                <x-admin.button.primary
                    wire:click="openRoomTypePricingForm"
                    :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
                    Add First Pricing
                </x-admin.button.primary>
            </x-slot>
        </x-admin.card.empty-state>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <x-admin.table.wrapper hoverable>
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <x-admin.table.header>Room Type</x-admin.table.header>
                        <x-admin.table.header>Base Price</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pricings as $pricing)
                        <x-admin.table.row>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                    {{ ucfirst($pricing->room_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $pricing->formatted_price }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleRoomTypePricingStatus({{ $pricing->id }})" type="button"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $pricing->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                    {{ $pricing->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button wire:click="editRoomTypePricing({{ $pricing->id }})" type="button"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDeleteRoomTypePricing({{ $pricing->id }})"
                                        x-data
                                        x-on:click="$dispatch('open-modal', 'delete-room-type-pricing-modal')"
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
