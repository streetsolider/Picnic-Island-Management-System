<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Beach Activity Categories
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex-1">
                        <input wire:model.live="search" type="text" placeholder="Search categories..." class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>
                    <button wire:click="openCreateModal" class="ml-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                        Add Category
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Services</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-6 py-4 text-2xl">{{ $category->icon }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</div>
                                <div class="text-sm text-gray-500">{{ $category->description }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $category->services_count }}</td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleStatus({{ $category->id }})" class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <button wire:click="openEditModal({{ $category->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button wire:click="openDeleteModal({{ $category->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $categories->links() }}</div>
        </div>

        <!-- Create/Edit Modal -->
        @if($showCreateModal || $showEditModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 z-50" wire:click="closeModals"></div>
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 min-w-[500px] z-50" @click.stop>
                <h3 class="text-lg font-semibold mb-4">{{ $showCreateModal ? 'Create' : 'Edit' }} Category</h3>
                <form wire:submit="{{ $showCreateModal ? 'createCategory' : 'updateCategory' }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Name</label>
                        <input wire:model="name" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Icon (emoji)</label>
                        <input wire:model="icon" type="text" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input wire:model="is_active" type="checkbox" class="rounded">
                            <span class="ml-2 text-sm">Active</span>
                        </label>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeModals" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Save</button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Delete Modal -->
        @if($showDeleteModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 z-50" wire:click="closeModals"></div>
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 min-w-[400px] z-50">
                <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
                <p class="mb-4">Are you sure you want to delete this category?</p>
                <div class="flex justify-end gap-2">
                    <button wire:click="closeModals" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                    <button wire:click="deleteCategory" class="px-4 py-2 bg-red-600 text-white rounded-md">Delete</button>
                </div>
            </div>
        @endif
    </div>
</div>
