<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('User Management') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <input wire:model.live="search" type="text" placeholder="Search by name or email..."
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                    </div>

                    <!-- Role Filter -->
                    <div class="w-full md:w-48">
                        <select wire:model.live="roleFilter"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                            <option value="">All Roles</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="w-full md:w-48">
                        <select wire:model.live="statusFilter"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Create Button -->
                    <div>
                        <button wire:click="openCreateModal"
                            class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-sm">
                            Create User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Staff ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 font-mono">
                                        {{ $user->staff_id ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($user->role === \App\Enums\UserRole::ADMINISTRATOR) bg-red-100 text-red-800
                                        @elseif($user->role === \App\Enums\UserRole::HOTEL_MANAGER) bg-purple-100 text-purple-800
                                        @elseif($user->role === \App\Enums\UserRole::FERRY_OPERATOR) bg-blue-100 text-blue-800
                                        @elseif($user->role === \App\Enums\UserRole::THEME_PARK_STAFF) bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $user->role->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="toggleStatus({{ $user->id }})"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="openEditModal({{ $user->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                        Edit
                                    </button>
                                    <button wire:click="resetPassword({{ $user->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-3">
                                        Reset Password
                                    </button>
                                    <button wire:click="openDeleteModal({{ $user->id }})"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <x-admin.pagination :paginator="$users" />
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
@if($showCreateModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" wire:click="closeModals"></div>
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Create New User</h3>

                <form wire:submit="createUser">
                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input wire:model="name" type="text"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input wire:model="email" type="email"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                        <input wire:model="password" type="password"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                        <input wire:model="password_confirmation" type="password"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                        <select wire:model="role"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">Select Role</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input wire:model="is_active" type="checkbox"
                                class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="closeModals"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- Edit User Modal -->
@if($showEditModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" wire:click="closeModals"></div>
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit User</h3>

                <form wire:submit="updateUser">
                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input wire:model="name" type="text"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input wire:model="email" type="email"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password (Optional) -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password (leave blank to keep current)</label>
                        <input wire:model="password" type="password"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                        <input wire:model="password_confirmation" type="password"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                        <select wire:model="role"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">Select Role</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input wire:model="is_active" type="checkbox"
                                class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="closeModals"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- Delete Confirmation Modal -->
@if($showDeleteModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" wire:click="closeModals"></div>
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Delete User</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Are you sure you want to delete this user? This action cannot be undone.
                </p>

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeModals"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteUser"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
