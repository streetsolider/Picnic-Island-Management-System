<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Hotel Settings - Default Checkout Time') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Flash Messages -->
        @if(session()->has('message'))
            <x-admin.alert.success dismissible class="mb-6">
                {{ session('message') }}
            </x-admin.alert.success>
        @endif

        @if(session()->has('error'))
            <x-admin.alert.danger dismissible class="mb-6">
                {{ session('error') }}
            </x-admin.alert.danger>
        @endif

        <!-- Current Settings Card -->
        <x-admin.card.base>
            <x-slot name="title">
                <div class="flex items-center justify-between">
                    <span>Default Checkout Time</span>
                    <x-admin.button.primary wire:click="openEditModal" size="sm">
                        Edit
                    </x-admin.button.primary>
                </div>
            </x-slot>

            <div class="space-y-4">
                <!-- Hotel Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $hotel->name }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $hotel->star_rating }} Star Hotel
                    </p>
                </div>

                <!-- Current Checkout Time Display -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    Current Default Checkout Time
                                </p>
                                <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $hotel->formatted_checkout_time }}
                                </p>
                            </div>
                            <div class="text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <x-admin.alert.info>
                        <div class="space-y-2">
                            <p class="font-semibold">About Checkout Time:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                <li>This is the default time when guests must check out of their rooms</li>
                                <li>Allowed range: 10:00 AM to 2:00 PM</li>
                                <li>Guests can request late checkout (up to 6:00 PM) for manager approval</li>
                                <li>Late checkout requests are FREE of charge</li>
                            </ul>
                        </div>
                    </x-admin.alert.info>
                </div>
            </div>
        </x-admin.card.base>
    </div>

    <!-- Edit Modal -->
    <x-admin.modal.form
        name="edit-checkout-time"
        :show="$showEditModal"
        title="Edit Default Checkout Time"
        submitText="Save Changes"
        wire:submit="save"
        :loading="'save'">

        <div class="space-y-4">
            <!-- Time Input -->
            <div>
                <label for="checkoutTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Checkout Time <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    id="checkoutTime"
                    wire:model="checkoutTime"
                    min="10:00"
                    max="14:00"
                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
                @error('checkoutTime')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Alert -->
            <x-admin.alert.warning>
                <p class="text-sm">
                    <strong>Note:</strong> Checkout time must be between 10:00 AM and 2:00 PM. This change will apply to all future bookings.
                </p>
            </x-admin.alert.warning>
        </div>

        <x-slot name="footer">
            <x-admin.button.secondary wire:click="closeEditModal" type="button">
                Cancel
            </x-admin.button.secondary>
            <x-admin.button.primary type="submit" :loading="$showEditModal">
                Save Changes
            </x-admin.button.primary>
        </x-slot>
    </x-admin.modal.form>
</div>
