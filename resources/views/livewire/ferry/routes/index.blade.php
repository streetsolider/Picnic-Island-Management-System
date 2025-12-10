<div>
    {{-- Vessel Selection --}}
    @if($vessels->count() > 1)
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Vessel
            </label>
            <select
                wire:change="selectVessel($event.target.value)"
                class="w-full md:w-96 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @foreach($vessels as $vessel)
                    <option value="{{ $vessel->id }}" {{ $selectedVesselId == $vessel->id ? 'selected' : '' }}>
                        {{ $vessel->name }} - {{ $vessel->registration_number }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <x-admin.alert.success dismissible class="mb-6">
            {{ session('success') }}
        </x-admin.alert.success>
    @endif

    @if (session()->has('error'))
        <x-admin.alert.danger dismissible class="mb-6">
            {{ session('error') }}
        </x-admin.alert.danger>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Ferry Routes</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Manage routes for {{ $selectedVessel->name }} ({{ $selectedVessel->registration_number }})
            </p>
        </div>
        <x-admin.button.primary wire:click="openForm">
            Add Route
        </x-admin.button.primary>
    </div>

    {{-- Search --}}
    <div class="mb-6">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search routes..."
            class="w-full md:w-96 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
    </div>

    {{-- Routes Table --}}
    @if($routes->isEmpty())
        <x-admin.card.empty-state
            icon="🚢"
            title="No routes yet"
            description="Create your first ferry route to get started.">
            <x-slot name="action">
                <x-admin.button.primary wire:click="openForm">
                    Add Route
                </x-admin.button.primary>
            </x-slot>
        </x-admin.card.empty-state>
    @else
        <x-admin.card.base>
            <x-admin.table.wrapper hoverable>
                <thead>
                    <tr>
                        <x-admin.table.header>Route</x-admin.table.header>
                        <x-admin.table.header>Origin</x-admin.table.header>
                        <x-admin.table.header>Destination</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                        <x-admin.table.header>Actions</x-admin.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($routes as $route)
                        <x-admin.table.row>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $route->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $route->origin }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $route->destination }}
                            </td>
                            <td class="px-6 py-4">
                                <x-admin.badge.status :status="$route->is_active ? 'active' : 'inactive'">
                                    {{ $route->is_active ? 'Active' : 'Inactive' }}
                                </x-admin.badge.status>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <x-admin.button.secondary size="sm" wire:click="edit({{ $route->id }})">
                                        Edit
                                    </x-admin.button.secondary>
                                    <x-admin.button.danger size="sm" wire:click="confirmDelete({{ $route->id }})">
                                        Delete
                                    </x-admin.button.danger>
                                </div>
                            </td>
                        </x-admin.table.row>
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        </x-admin.card.base>
    @endif

    {{-- Create/Edit Modal --}}
    <x-overlays.modal name="route-form" maxWidth="2xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                {{ $editingRouteId ? 'Edit Route' : 'Create Route' }}
            </h2>

            <form wire:submit="save" class="space-y-4">
            {{-- Important Notice --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Note:</strong> Either Origin or Destination must be "Picnic Island". The route name will be auto-generated based on your selections.
                </p>
            </div>

            {{-- Origin and Destination --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Origin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Origin <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="origin"
                        placeholder="e.g., Mainland Port or Picnic Island"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('origin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Destination --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Destination <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="destination"
                        placeholder="e.g., Picnic Island or Mainland Port"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('destination') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Active Status --}}
            <div class="flex items-center">
                <input
                    type="checkbox"
                    wire:model="is_active"
                    id="is_active"
                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Route is active
                </label>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-admin.button.secondary
                    type="button"
                    x-on:click="$dispatch('close-modal', 'route-form')"
                    size="md">
                    Cancel
                </x-admin.button.secondary>

                <x-admin.button.primary
                    type="submit"
                    size="md"
                    wire:loading.attr="disabled"
                    wire:target="save">
                    Save
                </x-admin.button.primary>
            </div>
        </form>
        </div>
    </x-overlays.modal>

    {{-- Delete Confirmation Modal --}}
    <x-overlays.modal name="delete-route-modal" maxWidth="md" focusable>
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/20 rounded-full mb-4">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-center text-gray-900 dark:text-white mb-2">
                Delete Route
            </h3>
            <p class="text-sm text-center text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to delete this route? This action cannot be undone.
            </p>

            <div class="flex items-center justify-center gap-3">
                <x-admin.button.secondary
                    type="button"
                    wire:click="cancelDelete"
                    size="md">
                    Cancel
                </x-admin.button.secondary>

                <x-admin.button.danger
                    type="button"
                    wire:click="deleteRoute"
                    size="md"
                    wire:loading.attr="disabled"
                    wire:target="deleteRoute">
                    <span wire:loading.remove wire:target="deleteRoute">Delete Route</span>
                    <span wire:loading wire:target="deleteRoute">Deleting...</span>
                </x-admin.button.danger>
            </div>
        </div>
    </x-overlays.modal>
</div>
