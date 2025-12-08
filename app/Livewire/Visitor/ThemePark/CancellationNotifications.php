<?php

namespace App\Livewire\Visitor\ThemePark;

use App\Models\ThemeParkActivityTicket;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CancellationNotifications extends Component
{
    public $showNotifications = true;

    public function dismiss()
    {
        $this->showNotifications = false;

        // Store dismissal in session so it persists during the session
        session(['theme_park_cancellations_dismissed' => true]);
    }

    public function mount()
    {
        // Check if notifications were already dismissed this session
        if (session('theme_park_cancellations_dismissed')) {
            $this->showNotifications = false;
        }
    }

    public function render()
    {
        // Get recently cancelled tickets (last 30 days)
        $recentCancellations = ThemeParkActivityTicket::where('guest_id', auth()->id())
            ->where('status', 'cancelled')
            ->where('updated_at', '>=', now()->subDays(30))
            ->with(['activity', 'showSchedule'])
            ->latest('updated_at')
            ->get();

        // Only show if there are cancellations and not dismissed
        $shouldShow = $this->showNotifications && $recentCancellations->isNotEmpty();

        return view('livewire.visitor.theme-park.cancellation-notifications', [
            'recentCancellations' => $recentCancellations,
            'shouldShow' => $shouldShow,
        ]);
    }
}
