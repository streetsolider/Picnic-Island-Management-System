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
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                </path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No amenity categories yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first amenity
                category.</p>
            <div class="mt-6">
                <button wire:click="openCategoryForm" type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Category
                </button>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($categories as $category)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    {{-- Category Header --}}
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $category->name }}
                            </h3>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                {{ $category->amenities_count }} items
                            </span>
                            <button wire:click="toggleCategoryStatus({{ $category->id }})" type="button"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="openAmenityForm({{ $category->id }})" type="button"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20 rounded-md">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Item
                            </button>
                            <button wire:click="editCategory({{ $category->id }})" type="button"
                                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>
                            <button wire:click="deleteCategory({{ $category->id }})"
                                wire:confirm="Are you sure? All amenity items in this category will also be deleted."
                                type="button"
                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Amenity Items --}}
                    @if ($category->amenities->isEmpty())
                        <div class="px-6 py-8 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">No amenity items in this category yet.
                            </p>
                            <button wire:click="openAmenityForm({{ $category->id }})" type="button"
                                class="mt-3 inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add first item
                            </button>
                        </div>
                    @else
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($category->amenities as $amenity)
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $amenity->name }}
                                            </p>
                                            @if ($amenity->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                    {{ $amenity->description }}
                                                </p>
                                            @endif
                                        </div>
                                        <button wire:click="toggleAmenityStatus({{ $amenity->id }})" type="button"
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $amenity->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                            {{ $amenity->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button wire:click="editAmenity({{ $amenity->id }})" type="button"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm">
                                            Edit
                                        </button>
                                        <button wire:click="deleteAmenity({{ $amenity->id }})"
                                            wire:confirm="Are you sure you want to delete this amenity item?"
                                            type="button"
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
