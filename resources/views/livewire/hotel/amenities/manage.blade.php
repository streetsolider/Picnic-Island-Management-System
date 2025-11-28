<div>
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Amenities Management</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Manage amenity categories and items for {{ $hotel->name }}
            </p>
        </div>
        <button wire:click="openCategoryForm" type="button"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Category
        </button>
    </div>

    {{-- Category Form Modal --}}
    @if ($showCategoryForm)
        <div x-data="{ isOpen: true }"
             x-init="
                $nextTick(() => { isOpen = true; });
                const handleEscape = (e) => { if (e.key === 'Escape') { $wire.closeCategoryForm(); } };
                window.addEventListener('keydown', handleEscape);
                $el._cleanup = () => window.removeEventListener('keydown', handleEscape);
             "
             x-on:click.self="$wire.closeCategoryForm()"
             class="fixed inset-0 z-50 overflow-y-auto">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 backdrop-blur-sm z-40"
                 @click="$wire.closeCategoryForm()">
            </div>

            {{-- Modal Content --}}
            <div class="relative z-50 flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                     @click.stop>
                    <form wire:submit.prevent="saveCategory">
                        <div class="bg-white dark:bg-gray-800 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                                {{ $editingCategoryId ? 'Edit Category' : 'Add New Category' }}
                            </h3>

                            {{-- Category Name --}}
                            <div class="mb-4">
                                <label for="categoryName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="categoryName" wire:model="categoryName"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="e.g., Bathroom, Electronics, Comfort">
                                @error('categoryName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label for="categoryDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Description (Optional)
                                </label>
                                <textarea id="categoryDescription" wire:model="categoryDescription" rows="3"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Brief description of this category"></textarea>
                                @error('categoryDescription')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
                        <x-admin.button.secondary type="button" wire:click="closeCategoryForm">
                            Cancel
                        </x-admin.button.secondary>
                        <x-admin.button.primary type="submit" wire:loading.attr="disabled" wire:target="saveCategory">
                            {{ $editingCategoryId ? 'Update' : 'Create' }}
                        </x-admin.button.primary>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Amenity Form Modal --}}
    @if ($showAmenityForm)
        <div x-data="{ isOpen: true }"
             x-init="
                $nextTick(() => { isOpen = true; });
                const handleEscape = (e) => { if (e.key === 'Escape') { $wire.closeAmenityForm(); } };
                window.addEventListener('keydown', handleEscape);
                $el._cleanup = () => window.removeEventListener('keydown', handleEscape);
             "
             x-on:click.self="$wire.closeAmenityForm()"
             class="fixed inset-0 z-50 overflow-y-auto">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 backdrop-blur-sm z-40"
                 @click="$wire.closeAmenityForm()">
            </div>

            {{-- Modal Content --}}
            <div class="relative z-50 flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                     @click.stop>
                    <form wire:submit.prevent="saveAmenity">
                        <div class="bg-white dark:bg-gray-800 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                                {{ $editingAmenityId ? 'Edit Amenity Item' : 'Add New Amenity Item' }}
                            </h3>

                            {{-- Category Selection --}}
                            <div class="mb-4">
                                <label for="selectedCategoryId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select id="selectedCategoryId" wire:model="selectedCategoryId"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedCategoryId')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Amenity Name --}}
                            <div class="mb-4">
                                <label for="amenityName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Amenity Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="amenityName" wire:model="amenityName"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="e.g., Shower, Bathtub, TV, Minibar">
                                @error('amenityName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label for="amenityDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Description (Optional)
                                </label>
                                <textarea id="amenityDescription" wire:model="amenityDescription" rows="3"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Brief description of this amenity"></textarea>
                                @error('amenityDescription')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end gap-3">
                        <x-admin.button.secondary type="button" wire:click="closeAmenityForm">
                            Cancel
                        </x-admin.button.secondary>
                        <x-admin.button.primary type="submit" wire:loading.attr="disabled" wire:target="saveAmenity">
                            {{ $editingAmenityId ? 'Update' : 'Create' }}
                        </x-admin.button.primary>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Categories and Items List --}}
    @if ($categories->isEmpty())
        <x-admin.card.empty-state
            icon="✨"
            title="No amenity categories yet"
            description="Get started by creating your first amenity category.">
            <x-slot name="action">
                <x-admin.button.primary wire:click="openCategoryForm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Category
                </x-admin.button.primary>
            </x-slot>
        </x-admin.card.empty-state>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <x-admin.table.wrapper hoverable>
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <x-admin.table.header>Category / Amenity</x-admin.table.header>
                        <x-admin.table.header>Description</x-admin.table.header>
                        <x-admin.table.header>Status</x-admin.table.header>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        {{-- Category Row --}}
                        <x-admin.table.row class="bg-gray-50 dark:bg-gray-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                        {{ $category->amenities->count() }} items
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($category->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $category->description }}</p>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500 italic">No description</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleCategoryStatus({{ $category->id }})" type="button"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button wire:click="openAmenityForm({{ $category->id }})" type="button"
                                        class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 text-sm font-medium">
                                        Add Item
                                    </button>
                                    <button wire:click="editCategory({{ $category->id }})" type="button"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDeleteCategory({{ $category->id }})"
                                        x-data
                                        x-on:click="$dispatch('open-modal', 'delete-category-modal')"
                                        type="button"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </x-admin.table.row>

                        {{-- Amenity Items Rows --}}
                        @if ($category->amenities->isEmpty())
                            <x-admin.table.row>
                                <td colspan="4" class="px-6 py-8 text-center">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">No amenity items in this category yet.</p>
                                    <x-admin.button.link wire:click="openAmenityForm({{ $category->id }})">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <span>Add first item</span>
                                        </span>
                                    </x-admin.button.link>
                                </td>
                            </x-admin.table.row>
                        @else
                            @foreach ($category->amenities as $amenity)
                                <x-admin.table.row>
                                    <td class="px-6 py-3 pl-12">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $amenity->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3" colspan="2">
                                        @if ($amenity->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $amenity->description }}</p>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">No description</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <button wire:click="editAmenity({{ $amenity->id }})" type="button"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                                Edit
                                            </button>
                                            <button wire:click="confirmDeleteAmenity({{ $amenity->id }})"
                                                x-data
                                                x-on:click="$dispatch('open-modal', 'delete-amenity-modal')"
                                                type="button"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </x-admin.table.row>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </x-admin.table.wrapper>
        </div>
    @endif

    {{-- Delete Category Confirmation Modal --}}
    <x-admin.modal.confirmation
        name="delete-category-modal"
        title="Delete Category?"
        description="Are you sure you want to delete this category? All amenity items in this category will also be permanently deleted. This action cannot be undone."
        method="deleteCategory"
        confirmText="Yes, Delete Category"
    />

    {{-- Delete Amenity Confirmation Modal --}}
    <x-admin.modal.confirmation
        name="delete-amenity-modal"
        title="Delete Amenity Item?"
        description="Are you sure you want to delete this amenity item? This action cannot be undone."
        method="deleteAmenity"
        confirmText="Yes, Delete Item"
    />
</div>
