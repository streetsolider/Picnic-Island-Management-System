<div class="min-h-screen bg-gradient-to-br from-brand-light via-blue-50 to-brand-primary/10 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-display font-bold text-brand-dark mb-2">
                Beach <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary">Activities</span>
            </h1>
            <p class="text-gray-600">Explore exciting water sports and beach activities</p>
        </div>

        {{-- Category Tabs --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex flex-wrap gap-2 justify-center">
                <button
                    wire:click="$set('categoryFilter', '')"
                    class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $categoryFilter === '' ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                    All Activities
                </button>
                @foreach($categories as $category)
                    <button
                        wire:click="$set('categoryFilter', {{ $category->id }})"
                        class="px-6 py-2.5 rounded-xl font-semibold transition-all {{ $categoryFilter == $category->id ? 'bg-brand-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="mr-1">{{ $category->icon }}</span>
                        {{ $category->name }}
                        <span class="ml-1 text-xs opacity-75">({{ $category->services_count }})</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Services Grid --}}
        @if($services->isEmpty())
            <div class="bg-white rounded-3xl shadow-lg p-16 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-2xl font-display font-bold text-brand-dark mb-2">
                    No Activities Found
                </h3>
                <p class="text-gray-600 mb-6">No beach activities match your current filter. Try selecting a different category or clearing your search.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                    <a
                        href="{{ route('visitor.beach-activities.details', $service) }}"
                        wire:navigate
                        class="bg-white rounded-3xl shadow-lg overflow-hidden cursor-pointer transition-all duration-300 hover:shadow-2xl hover:scale-[1.02] flex flex-col">
                        <div class="p-6 flex flex-col flex-1">
                            {{-- Category Badge & Icon --}}
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-brand-primary/10 text-brand-primary">
                                    {{ $service->category->name }}
                                </span>
                                <span class="text-4xl">{{ $service->category->icon }}</span>
                            </div>

                            {{-- Service Name --}}
                            <h3 class="text-xl font-display font-bold text-brand-dark mb-3">
                                {{ $service->name }}
                            </h3>

                            {{-- Description (fixed height for consistency) --}}
                            <div class="mb-4 h-10">
                                @if($service->description)
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $service->description }}
                                    </p>
                                @endif
                            </div>

                            {{-- Operating Hours --}}
                            @if($service->opening_time && $service->closing_time)
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <svg class="w-4 h-4 mr-2 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($service->opening_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($service->closing_time)->format('g:i A') }}
                                </div>
                            @endif

                            {{-- Capacity --}}
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <svg class="w-4 h-4 mr-2 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Capacity: {{ $service->concurrent_capacity }}
                            </div>

                            {{-- Pricing (pushed to bottom) --}}
                            <div class="border-t border-gray-100 pt-4 mt-auto">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm text-gray-600">
                                        @if($service->booking_type === 'fixed_slot')
                                            {{ $service->slot_duration_minutes }} min slot
                                        @else
                                            Per hour
                                        @endif
                                    </span>
                                    <span class="text-2xl font-bold text-brand-primary">
                                        MVR {{ number_format($service->getPricePerUnit(), 2) }}
                                    </span>
                                </div>

                                {{-- Book Now Button --}}
                                <button class="w-full bg-brand-primary hover:bg-brand-primary/90 text-white py-3 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg shadow-brand-primary/30">
                                    View Details & Book
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
