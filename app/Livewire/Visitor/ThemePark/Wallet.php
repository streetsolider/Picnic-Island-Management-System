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
    public $creditPrice = 0;

    // Top-up form
    public $showTopUpForm = false;
    #[Validate('required|numeric|min:10|max:10000')]
    public $topUpAmount = '';

    // Purchase credits form
    public $showPurchaseForm = false;
    #[Validate('required|integer|min:1')]
    public $creditCount = 1;

    public $filter = 'all'; // all, top_up, ticket_purchase

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->loadStats();
        $this->creditPrice = ThemeParkSetting::getCreditPrice();
    }

    public function loadStats()
    {
        $service = app(ThemeParkWalletService::class);
        $this->stats = $service->getWalletStats(auth()->id());
        $this->creditPrice = $this->stats['credit_price_mvr'];
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
        $this->reset(['creditCount']);
        $this->resetValidation();
        $this->creditCount = 1;
        $this->showPurchaseForm = true;
    }

    public function purchaseCredits()
    {
        $this->validate([
            'creditCount' => 'required|integer|min:1',
        ]);

        $service = app(ThemeParkWalletService::class);
        $result = $service->purchaseCredits(auth()->id(), $this->creditCount);

        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->loadStats();
            $this->showPurchaseForm = false;
            $this->reset(['creditCount']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function updatedCreditCount()
    {
        // Recalculate cost when credit count changes
        $this->dispatch('credit-count-updated');
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
