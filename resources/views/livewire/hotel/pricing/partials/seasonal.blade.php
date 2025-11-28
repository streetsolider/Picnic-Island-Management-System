{{-- Seasonal Pricing Tab --}}
<div>
    {{-- Header with Add Button --}}
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Seasonal Pricing</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Configure date-range based pricing adjustments</p>
        </div>
        <x-admin.button.primary
            wire:click="openSeasonalPricingForm"
            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
            Add Seasonal Pricing
        </x-admin.button.primary>
    </div>

    {{-- Seasonal Pricing Form Modal --}}
    @if ($showSeasonalPricingForm)
        <div x-data="{ isOpen: true }"
             x-init="
                $nextTick(() => { isOpen = true; });
                const handleEscape = (e) => { if (e.key === 'Escape') { $wire.closeSeasonalPricingForm(); } };
                window.addEventListener('keydown', handleEscape);
                $el._cleanup = () => window.removeEventListener('keydown', handleEscape);
             "
             x-on:click.self="$wire.closeSeasonalPricingForm()"
             class="fixed inset-0 z-50 overflow-y-auto">

            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 backdrop-blur-sm z-40"
                 @click="$wire.closeSeasonalPricingForm()">
            </div>

            <div class="relative z-50 flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                     @click.stop>
                    <form wire:submit.prevent="saveSeasonalPricing">
                        <div class="bg-white dark:bg-gray-800 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                                {{ $editingSeasonalPricingId ? 'Edit Seasonal Pricing' : 'Add Seasonal Pricing' }}
                            </h3>

                            {{-- Season Name --}}
                            <div class="mb-4">
                                <label for="seasonName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Season Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="seasonName" wire:model="seasonName"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="e.g., Peak Season, Holiday Rates">
                                @error('seasonName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date Range --}}
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="seasonStartDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Start Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="seasonStartDate" wire:model="seasonStartDate"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('seasonStartDate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="seasonEndDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        End Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="seasonEndDate" wire:model="seasonEndDate"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('seasonEndDate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Modifier Type and Value --}}
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="seasonModifierType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Modifier Type <span class="text-red-500">*</span>
                                    </label>
                                    <select id="seasonModifierType" wire:model="seasonModifierType"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @foreach ($modifierTypes as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('seasonModifierType')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="seasonModifierValue" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Value <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="seasonModifierValue" wire:model="seasonModifierValue" step="0.01"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="e.g., 30">
                                    @error('seasonModifierValue')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Priority --}}
                            <div class="mb-4">
                                <label for="seasonPriority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Priority (1-10) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="seasonPriority" wire:model="seasonPriority" min="1" max="10"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Higher priority applies when seasons overlap (10 = highest)
                                </p>
                                @error('seasonPriority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Is Active Toggle --}}
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="seasonIsActive"
                                        class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
                            <x-admin.button.secondary type="button" wire:click="closeSeasonalPricingForm">
                                Cancel
                            </x-admin.button.secondary>
                            <x-admin.button.primary type="submit" wire:loading.attr="disabled" wire:target="saveSeasonalPricing">
                                {{ $editingSeasonalPricingId ? 'Update' : 'Create' }}
                            </x-admin.button.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Pricing List --}}
    @php
        $pricings = $this->getSeasonalPricings();
    @endphp

    @if ($pricings->isEmpty())
        <x-admin.card.empty-state
            icon="📅"
            title="No seasonal pricing configured"
            description="Create seasonal pricing rules for different times of the year.">
            <x-slot name="action">
                <x-admin.button.primary
                    wire:click="openSeasonalPricingForm"
                    :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
                    Add First Season
                </x-admin.button.primary>
            </x-slot>
        </x-admin.card.empty-state>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <x-admin.table.wrapper hoverable>
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <x-admin.table.header>Season</x-admin.table.header>
                        <x-admin.table.header>Date Range</x-admin.table.header>
                        <x-admin.table.header>Modifier</x-admin.table.header>
                        <x-admin.table.header>Priority</x-admin.table.header>
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
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $pricing->season_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $pricing->start_date->format('M d, Y') }} - {{ $pricing->end_date->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    @if($pricing->modifier_type === 'fixed')
                                        {{ $pricing->modifier_value >= 0 ? '+' : '' }}MVR {{ number_format($pricing->modifier_value, 2) }}
                                    @else
                                        {{ $pricing->modifier_value >= 0 ? '+' : '' }}{{ $pricing->modifier_value }}%
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ $pricing->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleSeasonalPricingStatus({{ $pricing->id }})" type="button"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $pricing->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                    {{ $pricing->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button wire:click="editSeasonalPricing({{ $pricing->id }})" type="button"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDeleteSeasonalPricing({{ $pricing->id }})"
                                        x-data
                                        x-on:click="$dispatch('open-modal', 'delete-seasonal-pricing-modal')"
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
