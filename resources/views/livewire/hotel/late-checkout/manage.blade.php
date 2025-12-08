<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Late Checkout Requests') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Flash Messages -->
        @if(session()->has('message'))
            <x-admin.alert.success dismissible>
                {{ session('message') }}
            </x-admin.alert.success>
        @endif

        @if(session()->has('error'))
            <x-admin.alert.danger dismissible>
                {{ session('error') }}
            </x-admin.alert.danger>
        @endif

        <!-- Filter Tabs -->
        <x-admin.card.base padding="p-0">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px">
                    <button
                        wire:click="$set('statusFilter', 'pending')"
                        class="py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors
                            @if($statusFilter === 'pending')
                                border-indigo-500 text-indigo-600 dark:text-indigo-400
                            @else
                                border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300
                            @endif">
                        Pending
                        @if($pendingCount > 0)
                            <span class="ml-2 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 py-0.5 px-2.5 rounded-full text-xs">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </button>

                    <button
                        wire:click="$set('statusFilter', 'approved')"
                        class="py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors
                            @if($statusFilter === 'approved')
                                border-indigo-500 text-indigo-600 dark:text-indigo-400
                            @else
                                border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300
                            @endif">
                        Approved
                    </button>

                    <button
                        wire:click="$set('statusFilter', 'rejected')"
                        class="py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors
                            @if($statusFilter === 'rejected')
                                border-indigo-500 text-indigo-600 dark:text-indigo-400
                            @else
                                border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300
                            @endif">
                        Rejected
                    </button>

                    <button
                        wire:click="$set('statusFilter', 'all')"
                        class="py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors
                            @if($statusFilter === 'all')
                                border-indigo-500 text-indigo-600 dark:text-indigo-400
                            @else
                                border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300
                            @endif">
                        All
                    </button>
                </nav>
            </div>
        </x-admin.card.base>

        <!-- Requests Table -->
        @if($requests->isEmpty())
            <x-admin.card.empty-state
                icon="🏖️"
                title="No late checkout requests"
                description="There are no late checkout requests{{ $statusFilter !== 'all' ? ' with status: ' . $statusFilter : '' }}.">
            </x-admin.card.empty-state>
        @else
            <x-admin.card.base padding="p-0">
                <div class="overflow-x-auto">
                    <x-admin.table.wrapper hoverable>
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <x-admin.table.header>Guest</x-admin.table.header>
                                <x-admin.table.header>Room</x-admin.table.header>
                                <x-admin.table.header>Checkout Date</x-admin.table.header>
                                <x-admin.table.header>Requested Time</x-admin.table.header>
                                <x-admin.table.header>Next Booking</x-admin.table.header>
                                <x-admin.table.header>Status</x-admin.table.header>
                                <x-admin.table.header>Requested On</x-admin.table.header>
                                <x-admin.table.header>Actions</x-admin.table.header>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($requests as $request)
                                <x-admin.table.row>
                                    <!-- Guest -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $request->booking->guest->display_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->booking->booking_reference }}
                                        </div>
                                    </td>

                                    <!-- Room -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $request->booking->room->room_number }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ ucfirst($request->booking->room->room_type) }}
                                        </div>
                                    </td>

                                    <!-- Checkout Date -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $request->booking->check_out_date->format('M d, Y') }}
                                    </td>

                                    <!-- Requested Time -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                            {{ $request->formatted_requested_time }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Default: {{ $request->booking->hotel->formatted_checkout_time }}
                                        </div>
                                    </td>

                                    <!-- Next Booking -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($request->has_next_booking)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                ⚠️ Has next booking
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                ✓ No conflict
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_badge_class }}">
                                            {{ $request->status_display }}
                                        </span>
                                    </td>

                                    <!-- Requested On -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->created_at->format('M d, g:i A') }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($request->isPending())
                                            <div class="flex items-center space-x-2">
                                                <button
                                                    wire:click="openApproveModal({{ $request->id }})"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    Approve
                                                </button>
                                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                                <button
                                                    wire:click="openRejectModal({{ $request->id }})"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    Reject
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-600">No actions</span>
                                        @endif
                                    </td>
                                </x-admin.table.row>
                            @endforeach
                        </tbody>
                    </x-admin.table.wrapper>
                </div>

                <!-- Pagination -->
                @if($requests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $requests->links() }}
                    </div>
                @endif
            </x-admin.card.base>
        @endif
    </div>

    <!-- Approve Modal -->
    @if($showApproveModal && $selectedRequest)
        <x-admin.modal.form
            name="approve-request"
            :show="$showApproveModal"
            title="Approve Late Checkout Request"
            submitText="Approve Request"
            submitColor="success"
            wire:submit="approve"
            :loading="'approve'">

            <div class="space-y-4">
                <!-- Guest Info -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Guest Information</h4>
                    <dl class="grid grid-cols-2 gap-2 text-sm">
                        <dt class="text-gray-600 dark:text-gray-400">Name:</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $selectedRequest->booking->guest->display_name }}</dd>

                        <dt class="text-gray-600 dark:text-gray-400">Room:</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $selectedRequest->booking->room->room_number }} ({{ ucfirst($selectedRequest->booking->room->room_type) }})</dd>

                        <dt class="text-gray-600 dark:text-gray-400">Checkout Date:</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $selectedRequest->booking->check_out_date->format('M d, Y') }}</dd>

                        <dt class="text-gray-600 dark:text-gray-400">Requested Time:</dt>
                        <dd class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ $selectedRequest->formatted_requested_time }}</dd>
                    </dl>
                </div>

                <!-- Guest Notes -->
                @if($selectedRequest->guest_notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Guest's Reason:
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            {{ $selectedRequest->guest_notes }}
                        </p>
                    </div>
                @endif

                <!-- Next Booking Info -->
                @if($selectedRequest->has_next_booking && $selectedRequest->next_booking_info)
                    <x-admin.alert.warning>
                        <h5 class="font-semibold mb-2">⚠️ Next Booking Exists</h5>
                        <dl class="grid grid-cols-2 gap-1 text-sm">
                            <dt>Guest:</dt>
                            <dd class="font-medium">{{ $selectedRequest->next_booking_info['guest_name'] }}</dd>

                            <dt>Booking Ref:</dt>
                            <dd class="font-medium">{{ $selectedRequest->next_booking_info['booking_reference'] }}</dd>

                            <dt>Check-in:</dt>
                            <dd class="font-medium">{{ \Carbon\Carbon::parse($selectedRequest->next_booking_info['check_in_date'])->format('M d, Y') }} at {{ $selectedRequest->next_booking_info['check_in_time'] }}</dd>
                        </dl>
                        <p class="text-xs mt-2">Please ensure late checkout won't conflict with the next guest's arrival.</p>
                    </x-admin.alert.warning>
                @endif

                <!-- Manager Notes (Optional) -->
                <div>
                    <label for="approveNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Manager Notes (Optional)
                    </label>
                    <textarea
                        id="approveNotes"
                        wire:model="managerNotes"
                        rows="3"
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Add any notes for this approval..."></textarea>
                </div>
            </div>

            <x-slot name="footer">
                <x-admin.button.secondary wire:click="closeModals" type="button">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.success type="submit">
                    Approve Request
                </x-admin.button.success>
            </x-slot>
        </x-admin.modal.form>
    @endif

    <!-- Reject Modal -->
    @if($showRejectModal && $selectedRequest)
        <x-admin.modal.form
            name="reject-request"
            :show="$showRejectModal"
            title="Reject Late Checkout Request"
            submitText="Reject Request"
            submitColor="danger"
            wire:submit="reject"
            :loading="'reject'">

            <div class="space-y-4">
                <!-- Guest Info -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Guest Information</h4>
                    <dl class="grid grid-cols-2 gap-2 text-sm">
                        <dt class="text-gray-600 dark:text-gray-400">Name:</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $selectedRequest->booking->guest->display_name }}</dd>

                        <dt class="text-gray-600 dark:text-gray-400">Room:</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $selectedRequest->booking->room->room_number }}</dd>

                        <dt class="text-gray-600 dark:text-gray-400">Requested Time:</dt>
                        <dd class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ $selectedRequest->formatted_requested_time }}</dd>
                    </dl>
                </div>

                <!-- Guest Notes -->
                @if($selectedRequest->guest_notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Guest's Reason:
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            {{ $selectedRequest->guest_notes }}
                        </p>
                    </div>
                @endif

                <!-- Manager Notes (Required) -->
                <div>
                    <label for="rejectNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="rejectNotes"
                        wire:model="managerNotes"
                        rows="4"
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                        placeholder="Please explain why this request is being rejected..." required></textarea>
                    @error('managerNotes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <x-admin.alert.danger>
                    <p class="text-sm">
                        <strong>Important:</strong> Please provide a clear reason for rejecting this request. The guest will see your notes.
                    </p>
                </x-admin.alert.danger>
            </div>

            <x-slot name="footer">
                <x-admin.button.secondary wire:click="closeModals" type="button">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.danger type="submit">
                    Reject Request
                </x-admin.button.danger>
            </x-slot>
        </x-admin.modal.form>
    @endif
</div>
