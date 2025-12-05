<?php

namespace App\Livewire\Visitor\ThemePark;

use App\Models\ThemeParkActivityTicket;
use App\Services\ThemeParkTicketService;
use Livewire\Component;
use Livewire\WithPagination;

class RedemptionHistory extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, valid, redeemed, cancelled, expired

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function cancelTicket($ticketId)
    {
        $service = app(ThemeParkTicketService::class);
        $result = $service->cancelTicket($ticketId, auth()->id(), 'Cancelled by visitor');

        if ($result['success']) {
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function render()
    {
        $query = ThemeParkActivityTicket::with(['activity.zone', 'showSchedule'])
            ->where('guest_id', auth()->id())
            ->orderBy('purchase_datetime', 'desc');

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $tickets = $query->paginate(10);

        return view('livewire.visitor.theme-park.redemption-history', [
            'tickets' => $tickets,
        ])->layout('layouts.visitor');
    }
}
