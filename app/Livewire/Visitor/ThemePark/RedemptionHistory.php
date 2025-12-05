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
        // Get all tickets first
        $allTickets = ThemeParkActivityTicket::with(['activity.zone', 'showSchedule'])
            ->where('guest_id', auth()->id())
            ->orderBy('purchase_datetime', 'desc')
            ->get();

        // Compute actual status based on expiration check
        $allTickets->each(function ($ticket) {
            // Check if ticket should be expired based on valid_until time
            if ($ticket->status === 'valid' && $ticket->isExpired()) {
                $ticket->status = 'expired';
            }
        });

        // Apply filter AFTER computing status
        if ($this->filter !== 'all') {
            $allTickets = $allTickets->filter(function ($ticket) {
                return $ticket->status === $this->filter;
            });
        }

        // Manual pagination
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $allTickets->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $tickets = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allTickets->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('livewire.visitor.theme-park.redemption-history', [
            'tickets' => $tickets,
        ])->layout('layouts.visitor');
    }
}
