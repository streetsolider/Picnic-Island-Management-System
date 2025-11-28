<div>
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Hotel Policies</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Manage policies and terms for {{ $hotel->name }}
            </p>
        </div>
        <x-admin.button.primary
            wire:click="openPolicyForm"
            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
            Add Policy
        </x-admin.button.primary>
    </div>

    {{-- Success Message --}}
    @if (session()->has('success'))
        <x-admin.alert.success class="mb-6">
            {{ session('success') }}
        </x-admin.alert.success>
    @endif

    {{-- Policy Form Modal --}}
    @if ($showPolicyForm)
        <div x-data="{ isOpen: true }"
             x-init="
                $nextTick(() => { isOpen = true; });
                const handleEscape = (e) => { if (e.key === 'Escape') { $wire.closePolicyForm(); } };
                window.addEventListener('keydown', handleEscape);
                $el._cleanup = () => window.removeEventListener('keydown', handleEscape);
             "
             x-on:click.self="$wire.closePolicyForm()"
             class="fixed inset-0 z-50 overflow-y-auto">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 backdrop-blur-sm z-40"
                 @click="$wire.closePolicyForm()">
            </div>

            {{-- Modal Content --}}
            <div class="relative z-50 flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                     @click.stop>
                    <form wire:submit.prevent="savePolicy">
                        <div class="bg-white dark:bg-gray-800 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                                {{ $editingPolicyId ? 'Edit Policy' : 'Add New Policy' }}
                            </h3>

                            {{-- Policy Type Selection --}}
                            <div class="mb-4">
                                <label for="selectedPolicyType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Policy Type <span class="text-red-500">*</span>
                                </label>
                                <select id="selectedPolicyType" wire:model="selectedPolicyType"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    {{ $editingPolicyId ? 'disabled' : '' }}>
                                    <option value="">Select a policy type</option>
                                    @foreach ($policyTypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('selectedPolicyType')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Policy Title --}}
                            <div class="mb-4">
                                <label for="policyTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Policy Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="policyTitle" wire:model="policyTitle"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="e.g., Free Cancellation up to 24 Hours">
                                @error('policyTitle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label for="policyDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Policy Description <span class="text-red-500">*</span>
                                </label>
                                <textarea id="policyDescription" wire:model="policyDescription" rows="6"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Detailed policy terms and conditions..."></textarea>
                                @error('policyDescription')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Is Active Toggle --}}
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="policyIsActive"
                                        class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active (Visible to customers)</span>
                                </label>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
                            <x-admin.button.secondary type="button" wire:click="closePolicyForm">
                                Cancel
                            </x-admin.button.secondary>
                            <x-admin.button.primary type="submit" wire:loading.attr="disabled" wire:target="savePolicy">
                                {{ $editingPolicyId ? 'Update Policy' : 'Create Policy' }}
                            </x-admin.button.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Override Form Modal --}}
    @if ($showOverrideForm)
        <div x-data="{ isOpen: true }"
             x-init="
                $nextTick(() => { isOpen = true; });
                const handleEscape = (e) => { if (e.key === 'Escape') { $wire.closeOverrideForm(); } };
                window.addEventListener('keydown', handleEscape);
                $el._cleanup = () => window.removeEventListener('keydown', handleEscape);
             "
             x-on:click.self="$wire.closeOverrideForm()"
             class="fixed inset-0 z-50 overflow-y-auto">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 backdrop-blur-sm z-40"
                 @click="$wire.closeOverrideForm()">
            </div>

            {{-- Modal Content --}}
            <div class="relative z-50 flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                     @click.stop>
                    <form wire:submit.prevent="saveOverride">
                        <div class="bg-white dark:bg-gray-800 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                                {{ $editingOverrideId ? 'Edit Room Type Override' : 'Add Room Type Override' }}
                            </h3>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Create a different policy for a specific room type. This will override the default hotel-wide policy.
                            </p>

                            {{-- Room Type Selection --}}
                            <div class="mb-4">
                                <label for="selectedOverrideRoomType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Room Type <span class="text-red-500">*</span>
                                </label>
                                <select id="selectedOverrideRoomType" wire:model="selectedOverrideRoomType"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    {{ $editingOverrideId ? 'disabled' : '' }}>
                                    <option value="">Select a room type</option>
                                    @foreach ($roomTypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('selectedOverrideRoomType')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Policy Type Selection --}}
                            <div class="mb-4">
                                <label for="selectedOverridePolicyType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Policy Type <span class="text-red-500">*</span>
                                </label>
                                <select id="selectedOverridePolicyType" wire:model="selectedOverridePolicyType"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    {{ $editingOverrideId ? 'disabled' : '' }}>
                                    <option value="">Select a policy type</option>
                                    @foreach ($policyTypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('selectedOverridePolicyType')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Override Title --}}
                            <div class="mb-4">
                                <label for="overrideTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Policy Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="overrideTitle" wire:model="overrideTitle"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="e.g., Flexible Cancellation for Suites">
                                @error('overrideTitle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Override Description --}}
                            <div class="mb-4">
                                <label for="overrideDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Policy Description <span class="text-red-500">*</span>
                                </label>
                                <textarea id="overrideDescription" wire:model="overrideDescription" rows="6"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Detailed policy terms for this room type..."></textarea>
                                @error('overrideDescription')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
                            <x-admin.button.secondary type="button" wire:click="closeOverrideForm">
                                Cancel
                            </x-admin.button.secondary>
                            <x-admin.button.primary type="submit" wire:loading.attr="disabled" wire:target="saveOverride">
                                {{ $editingOverrideId ? 'Update Override' : 'Create Override' }}
                            </x-admin.button.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Policies List --}}
    @php
        $policies = $this->getPolicies();
    @endphp
    @if ($policies->isEmpty())
        <x-admin.card.empty-state
            icon="📋"
            title="No policies configured yet"
            description="Get started by creating your first hotel policy.">
            <x-slot name="action">
                <x-admin.button.primary
                    wire:click="openPolicyForm"
                    :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
                    Add Policy
                </x-admin.button.primary>
            </x-slot>
        </x-admin.card.empty-state>
    @else
        <div class="space-y-6">
            @foreach ($policyTypes as $typeKey => $typeName)
                @php
                    $policy = $policies->get($typeKey)?->first();
                    $overrides = $this->getOverridesForPolicyType($typeKey);
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    {{-- Policy Type Header --}}
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $typeName }}
                                </h3>
                                @if ($policy)
                                    <x-admin.badge.status
                                        :active="$policy->is_active"
                                        activeText="Active"
                                        inactiveText="Inactive"
                                    />
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                                        Not Configured
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($overrides->count() > 0)
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $overrides->count() }} override{{ $overrides->count() !== 1 ? 's' : '' }}
                                    </span>
                                @endif
                                @if ($policy)
                                    <x-admin.button.link
                                        wire:click="editPolicy({{ $policy->id }})"
                                        size="sm">
                                        Edit
                                    </x-admin.button.link>
                                    <x-admin.button.link
                                        wire:click="togglePolicyStatus({{ $policy->id }})"
                                        size="sm">
                                        {{ $policy->is_active ? 'Deactivate' : 'Activate' }}
                                    </x-admin.button.link>
                                    <x-admin.button.danger
                                        wire:click="confirmDeletePolicy({{ $policy->id }})"
                                        x-data
                                        x-on:click="$dispatch('open-modal', 'delete-policy-modal')"
                                        size="sm">
                                        Delete
                                    </x-admin.button.danger>
                                @else
                                    <x-admin.button.success
                                        wire:click="openPolicyForm('{{ $typeKey }}')"
                                        size="sm"
                                        :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
                                        Create Policy
                                    </x-admin.button.success>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Policy Content --}}
                    @if ($policy)
                        <div class="px-6 py-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $policy->title }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">
                                {{ $policy->description }}
                            </p>

                            {{-- Room Type Overrides --}}
                            @if ($overrides->count() > 0)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-sm font-semibold text-gray-900 dark:text-white">
                                            Room Type Overrides
                                        </h5>
                                        <x-admin.button.link
                                            wire:click="openOverrideForm('{{ $typeKey }}')"
                                            size="sm"
                                            :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
                                            Add Override
                                        </x-admin.button.link>
                                    </div>
                                    <div class="space-y-3">
                                        @foreach ($overrides as $override)
                                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                                <div class="flex items-start justify-between mb-2">
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                                            {{ $override->room_type }}
                                                        </span>
                                                        <h6 class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            {{ $override->title }}
                                                        </h6>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <x-admin.button.link
                                                            wire:click="editOverride({{ $override->id }})"
                                                            size="sm">
                                                            Edit
                                                        </x-admin.button.link>
                                                        <x-admin.button.danger
                                                            wire:click="confirmDeleteOverride({{ $override->id }})"
                                                            x-data
                                                            x-on:click="$dispatch('open-modal', 'delete-override-modal')"
                                                            size="sm">
                                                            Delete
                                                        </x-admin.button.danger>
                                                    </div>
                                                </div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-line">
                                                    {{ $override->description }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-center py-3">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                            No room type specific overrides
                                        </p>
                                        <x-admin.button.link
                                            wire:click="openOverrideForm('{{ $typeKey }}')"
                                            size="sm"
                                            :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\'></path></svg>'">
                                            Add Room Type Override
                                        </x-admin.button.link>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Delete Policy Confirmation Modal --}}
    <x-admin.modal.confirmation
        name="delete-policy-modal"
        title="Delete Policy?"
        description="Are you sure you want to delete this policy? This action cannot be undone."
        method="deletePolicy"
        confirmText="Yes, Delete Policy"
    />

    {{-- Delete Override Confirmation Modal --}}
    <x-admin.modal.confirmation
        name="delete-override-modal"
        title="Delete Room Type Override?"
        description="Are you sure you want to delete this override? The default hotel policy will apply to this room type. This action cannot be undone."
        method="deleteOverride"
        confirmText="Yes, Delete Override"
    />
</div>
