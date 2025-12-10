<div>
    @if($shouldShow)
        <div class="mb-6 bg-gradient-to-r from-orange-400 via-red-400 to-pink-500 border-l-4 border-orange-600 rounded-2xl shadow-xl overflow-hidden" role="alert">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-white/30 backdrop-blur-sm rounded-xl flex items-center justify-center text-2xl">
                                ⚠️
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-white mb-2">
                                Show Cancellation Notice
                            </h3>
                            <p class="text-sm text-white/90 mb-4">
                                @if($recentCancellations->count() === 1)
                                    A show you booked has been cancelled. Your credits have been refunded to your wallet.
                                @else
                                    {{ $recentCancellations->count() }} shows you booked have been cancelled. Your credits have been refunded to your wallet.
                                @endif
                            </p>

                            {{-- Cancelled Shows List --}}
                            <div class="space-y-3">
                                @foreach($recentCancellations as $cancellation)
                                    <div class="bg-white/95 backdrop-blur-sm rounded-xl border border-white/50 p-4">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 text-base">
                                                    {{ $cancellation->activity->name }}
                                                </div>
                                                @if($cancellation->showSchedule)
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        📅 {{ $cancellation->showSchedule->show_date->format('M d, Y') }}
                                                        at {{ \Carbon\Carbon::parse($cancellation->showSchedule->show_time)->format('g:i A') }}
                                                    </div>
                                                @endif
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Cancelled {{ $cancellation->updated_at->diffForHumans() }}
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div class="text-right">
                                                    <div class="text-xs text-gray-600">Credits Refunded</div>
                                                    <div class="text-lg font-bold text-green-600">
                                                        +{{ $cancellation->credits_spent }}
                                                    </div>
                                                </div>
                                                <div class="text-3xl">💰</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Total Credits Refunded --}}
                            @php
                                $totalRefunded = $recentCancellations->sum('credits_spent');
                            @endphp
                            <div class="mt-4 p-3 bg-white/95 backdrop-blur-sm border border-white/50 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-800">
                                        Total Credits Refunded
                                    </span>
                                    <span class="text-xl font-bold text-green-600">
                                        {{ $totalRefunded }} credits
                                    </span>
                                </div>
                            </div>

                            {{-- Info Message --}}
                            <div class="mt-4 flex items-start space-x-2">
                                <svg class="w-5 h-5 text-white mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm text-white/90">
                                    You can use your refunded credits to book other activities.
                                    <a href="{{ route('visitor.theme-park.activities') }}" class="text-white font-bold hover:underline">
                                        Browse available shows →
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Dismiss Button --}}
                    <button wire:click="dismiss" class="flex-shrink-0 ml-4 text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
