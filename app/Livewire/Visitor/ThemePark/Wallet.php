<?php

namespace App\Livewire\Visitor\ThemePark;

use App\Services\ThemeParkWalletService;
use App\Models\ThemeParkSetting;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class Wallet extends Component
{
    use WithPagination;

    public $stats = [];
    public $ticketPrice = 0;

    // Top-up form
    public $showTopUpForm = false;
    #[Validate('required|numeric|min:10|max:10000')]
    public $topUpAmount = '';

    // Purchase tickets form
    public $showPurchaseForm = false;
    #[Validate('required|integer|min:1')]
    public $ticketCount = 1;

    public $filter = 'all'; // all, top_up, ticket_purchase

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->loadStats();
        $this->ticketPrice = ThemeParkSetting::getTicketPrice();
    }

    public function loadStats()
    {
        $service = app(ThemeParkWalletService::class);
        $this->stats = $service->getWalletStats(auth()->id());
        $this->ticketPrice = $this->stats['ticket_price_mvr'];
    }

    public function openTopUpForm()
    {
        $this->reset(['topUpAmount']);
        $this->resetValidation();
        $this->showTopUpForm = true;
    }

    public function topUp()
    {
        $this->validate([
            'topUpAmount' => 'required|numeric|min:10|max:10000',
        ]);

        $service = app(ThemeParkWalletService::class);
        $result = $service->topUpWallet(auth()->id(), $this->topUpAmount);

        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->loadStats();
            $this->showTopUpForm = false;
            $this->reset(['topUpAmount']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function openPurchaseForm()
    {
        $this->reset(['ticketCount']);
        $this->resetValidation();
        $this->ticketCount = 1;
        $this->showPurchaseForm = true;
    }

    public function purchaseTickets()
    {
        $this->validate([
            'ticketCount' => 'required|integer|min:1',
        ]);

        $service = app(ThemeParkWalletService::class);
        $result = $service->purchaseTickets(auth()->id(), $this->ticketCount);

        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->loadStats();
            $this->showPurchaseForm = false;
            $this->reset(['ticketCount']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function updatedTicketCount()
    {
        // Recalculate cost when ticket count changes
        $this->dispatch('ticket-count-updated');
    }

    public function render()
    {
        $service = app(ThemeParkWalletService::class);

        $transactions = $service->getTransactionHistory(
            auth()->id(),
            $this->filter === 'all' ? null : $this->filter,
            10
        );

        return view('livewire.visitor.theme-park.wallet', [
            'transactions' => $transactions,
        ])->layout('layouts.visitor');
    }
}
