<?php

namespace App\Livewire\Visitor\FerryTickets;

use App\Models\Ferry\FerryTicket;
use Livewire\Component;

class MyTickets extends Component
{
    public $filter = 'upcoming'; // upcoming, past, cancelled, all

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        $query = FerryTicket::with(['schedule.route', 'schedule.vessel'])
            ->where('guest_id', auth()->id())
            ->orderBy('travel_date', 'desc');

        // Apply filters
        if ($this->filter === 'upcoming') {
            $query->where('travel_date', '>=', now()->toDateString())
                  ->where('status', 'confirmed');
        } elseif ($this->filter === 'past') {
            $query->where('status', '!=', 'cancelled')
                  ->where(function($q) {
                      $q->where('travel_date', '<', now()->toDateString())
                        ->orWhere('status', 'used');
                  });
        } elseif ($this->filter === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $tickets = $query->get();

        return view('livewire.visitor.ferry-tickets.my-tickets', [
            'tickets' => $tickets
        ])->layout('layouts.visitor');
    }
}
