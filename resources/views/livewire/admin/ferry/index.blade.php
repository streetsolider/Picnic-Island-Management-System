<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ferry Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs -->
            <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button wire:click="setTab('vessels')" class="{{ $activeTab === 'vessels'
    ? 'border-blue-500 text-blue-600 dark:text-blue-400'
    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}
                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Vessels
                    </button>

                    <button wire:click="setTab('routes')" class="{{ $activeTab === 'routes'
    ? 'border-blue-500 text-blue-600 dark:text-blue-400'
    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}
                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Routes
                    </button>

                    <button wire:click="setTab('schedules')" class="{{ $activeTab === 'schedules'
    ? 'border-blue-500 text-blue-600 dark:text-blue-400'
    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}
                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Schedules
                    </button>
                </nav>
            </div>

            <!-- Content -->
            <div>
                @if($activeTab === 'vessels')
                    <livewire:admin.ferry.vessels.index />
                @elseif($activeTab === 'routes')
                    <livewire:admin.ferry.routes.index />
                @elseif($activeTab === 'schedules')
                    <livewire:admin.ferry.schedules.index />
                @endif
            </div>
        </div>
    </div>
</div>