<div class="p-6">
    {{-- Upload Map Section --}}
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Upload Map Image</h2>

        @if (session()->has('success'))
            <x-admin.alert.success dismissible class="mb-4">
                {{ session('success') }}
            </x-admin.alert.success>
        @endif

        <form wire:submit.prevent="uploadMapImage" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Map Image (JPG, PNG - Max 10MB)
                </label>
                <input type="file" wire:model.live="mapImage" accept="image/jpeg,image/jpg,image/png"
                    class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                @error('mapImage')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="uploadMapImage,mapImage"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                        <span wire:loading.remove wire:target="uploadMapImage,mapImage">Upload Map</span>
                        <span wire:loading wire:target="uploadMapImage,mapImage">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>

                    @if ($mapImage)
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Selected: {{ $mapImage->getClientOriginalName() }}
                        </span>
                    @endif
                </div>

                {{-- Reset All Markers Button --}}
                <button type="button"
                    wire:click="confirmReset"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset All Markers
                </button>
            </div>

            <div wire:loading wire:target="mapImage" class="text-sm text-blue-600 dark:text-blue-400">
                <svg class="inline animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Uploading file...
            </div>
        </form>
    </div>

    {{-- Hidden Drag Preview Icons --}}
    <div class="hidden">
        <img x-ref="hotelDragImage" src="{{ asset('images/map/hotel_pin.png') }}" class="w-8 h-8 object-cover rounded-full border-2 border-white shadow-md">
        <img x-ref="themeparkDragImage" src="{{ asset('images/map/themepark_pin.png') }}" class="w-8 h-8 object-cover rounded-full border-2 border-white shadow-md">
        <img x-ref="beachDragImage" src="{{ asset('images/map/beach_pin.png') }}" class="w-8 h-8 object-cover rounded-full border-2 border-white shadow-md">
    </div>

    {{-- Map Manager --}}
    <div x-data="{
    dragging: false,
    dragType: null,
    dragId: null,
    mapWidth: 0,
    mapHeight: 0,

    init() {
        this.updateMapDimensions();
        window.addEventListener('resize', () => this.updateMapDimensions());
    },

    updateMapDimensions() {
        const map = this.$refs.mapContainer;
        if(map) {
            this.mapWidth = map.offsetWidth;
            this.mapHeight = map.offsetHeight;
        }
    },

    startDrag(event, type, id) {
        this.dragging = true;
        this.dragType = type;
        this.dragId = id;

        // Set custom drag image based on type
        let dragImage;
        if (type === 'hotel') {
            dragImage = this.$refs.hotelDragImage;
        } else if (type === 'themepark') {
            dragImage = this.$refs.themeparkDragImage;
        } else if (type === 'beach') {
            dragImage = this.$refs.beachDragImage;
        } else if (type === 'existing') {
            // For existing markers, use the marker's own icon image
            const markerImg = event.target.querySelector('img') || event.target.closest('.group').querySelector('img');
            if (markerImg) {
                event.dataTransfer.setDragImage(markerImg, 16, 16);
                return;
            }
        }

        if (dragImage) {
            // Offset to center of the icon (16px = half of 32px icon size)
            event.dataTransfer.setDragImage(dragImage, 16, 16);
        }
    },

    drop(event) {
        if (!this.dragging) return;

        const rect = this.$refs.mapContainer.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        // Calculate percentage
        const xPercent = (x / this.mapWidth) * 100;
        const yPercent = (y / this.mapHeight) * 100;

        // Clamp values 0-100
        const finalX = Math.max(0, Math.min(100, xPercent));
        const finalY = Math.max(0, Math.min(100, yPercent));

        if (this.dragType === 'existing') {
            @this.saveMarkerPosition(this.dragId, finalX, finalY);
        } else {
            @this.addMarker(this.dragType, this.dragId, finalX, finalY);
        }

        this.dragging = false;
        this.dragType = null;
        this.dragId = null;
    }
}">
    <div class="flex gap-6">
        {{-- Sidebar for Unplaced Items --}}
        <div class="w-1/4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow space-y-6 overflow-y-auto max-h-[80vh]">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Available Items</h2>

            {{-- Hotels --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Hotels</h3>
                <div class="space-y-2">
                    @forelse($hotels as $hotel)
                        <div draggable="true" @dragstart="startDrag($event, 'hotel', {{ $hotel->id }})"
                            class="flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700 rounded cursor-move hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <img src="{{ asset('images/map/hotel_pin.png') }}" class="w-6 h-6 object-contain">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $hotel->name }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">No unplaced hotels.</p>
                    @endforelse
                </div>
            </div>

            {{-- Theme Park --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Theme Park</h3>
                <div class="space-y-2">
                    @forelse($themeParkActivities as $activity)
                        <div draggable="true" @dragstart="startDrag($event, 'themepark', {{ $activity->id }})"
                            class="flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700 rounded cursor-move hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <img src="{{ asset('images/map/themepark_pin.png') }}" class="w-6 h-6 object-contain">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $activity->name }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">No unplaced activities.</p>
                    @endforelse
                </div>
            </div>

            {{-- Beach --}}
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Beach Activities</h3>
                <div class="space-y-2">
                    @forelse($beachActivities as $activity)
                        <div draggable="true" @dragstart="startDrag($event, 'beach', {{ $activity->id }})"
                            class="flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700 rounded cursor-move hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <img src="{{ asset('images/map/beach_pin.png') }}"
                                class="w-6 h-6 object-cover rounded-full border border-gray-300">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $activity->name }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">No unplaced activities.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Map Container --}}
        <div class="flex-1 relative bg-blue-100 rounded-lg overflow-hidden shadow-lg" x-ref="mapContainer"
            @dragover.prevent @drop.prevent="drop($event)">

            <img src="{{ str_starts_with($currentMapPath, 'images/') ? asset($currentMapPath) : asset('storage/' . $currentMapPath) }}"
                class="w-full h-auto object-cover select-none pointer-events-none" alt="Island Map"
                @load="updateMapDimensions()">

            {{-- Placed Markers --}}
            @foreach($markers as $marker)
                <div class="absolute transform -translate-x-1/2 -translate-y-1/2 group cursor-move z-10 hover:z-50 transition-all"
                    style="left: {{ $marker->x_position }}%; top: {{ $marker->y_position }}%;" draggable="true"
                    @dragstart="startDrag($event, 'existing', {{ $marker->id }})">

                    {{-- Icon Selection based on type --}}
                    @if($marker->mappable_type === 'App\Models\Hotel')
                        <img src="{{ asset('images/map/hotel_pin.png') }}"
                            class="w-8 h-8 object-cover rounded-full border-2 border-white shadow-md">
                    @elseif($marker->mappable_type === 'App\Models\ThemeParkActivity')
                        <img src="{{ asset('images/map/themepark_pin.png') }}"
                            class="w-8 h-8 object-cover rounded-full border-2 border-white shadow-md">
                    @elseif($marker->mappable_type === 'App\Models\BeachActivity')
                        <img src="{{ asset('images/map/beach_pin.png') }}"
                            class="w-8 h-8 object-cover rounded-full border-2 border-white shadow-md">
                    @endif

                    {{-- Tooltip / Controls --}}
                    <div
                        class="absolute bottom-full left-1/2 -translate-x-1/2 pb-2 hidden group-hover:flex flex-col items-center z-10 w-max">
                        <div class="bg-black text-white text-xs rounded px-2 py-1 mb-1">
                            {{ $marker->mappable ? $marker->mappable->name : 'Unknown' }}
                        </div>
                        <button wire:click="deleteMarker({{ $marker->id }})"
                            class="text-red-500 hover:text-red-700 bg-white rounded-full p-1 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach

            {{-- Drop visual cue --}}
            <div x-show="dragging"
                class="absolute inset-0 bg-transparent border-4 border-blue-500 border-dashed pointer-events-none z-10 transition-colors">
            </div>
        </div>
    </div>

    {{-- Reset Confirmation Modal --}}
    @if($showResetConfirmation)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 transition-opacity" style="z-index: 99999; background: rgba(0, 0, 0, 0.5);"
            wire:click="cancelReset"></div>

        <!-- Modal Content -->
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 min-w-[400px] max-w-[500px]"
            style="z-index: 100000;" @click.stop>

            {{-- Warning Icon --}}
            <div class="flex items-start gap-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Reset All Markers?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Are you sure you want to reset all markers? All hotels, theme parks, and beach activities will be removed from the map and returned to the Available Items list. This action cannot be undone.
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <x-admin.button.secondary wire:click="cancelReset">
                    Cancel
                </x-admin.button.secondary>
                <x-admin.button.danger wire:click="resetAllMarkers">
                    Yes, Reset All
                </x-admin.button.danger>
            </div>
        </div>
    @endif
</div>