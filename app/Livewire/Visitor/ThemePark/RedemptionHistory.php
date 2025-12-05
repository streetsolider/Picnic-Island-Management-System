<?php

namespace App\Livewire\Visitor\ThemePark;

use App\Models\ThemeParkTicketRedemption;
use Livewire\Component;
use Livewire\WithPagination;

class RedemptionHistory extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, pending, validated, cancelled

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function cancelRedemption($redemptionId)
    {
        $redemption = ThemeParkTicketRedemption::where('id', $redemptionId)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if (!$redemption) {
            session()->flash('error', 'Redemption not found or cannot be cancelled.');
            return;
        }

        $redemption->cancel('Cancelled by visitor');

        // Return credits to wallet
        $wallet = $redemption->user->themeParkWallet;
        $wallet->credit_balance += $redemption->tickets_redeemed;
        $wallet->total_credits_redeemed -= $redemption->tickets_redeemed;
        $wallet->save();

        session()->flash('success', 'Redemption cancelled successfully. Credits have been returned to your wallet.');
    }

    public function render()
    {
        $query = ThemeParkTicketRedemption::with(['activity.zone', 'validatedBy'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $redemptions = $query->paginate(10);

        return view('livewire.visitor.theme-park.redemption-history', [
            'redemptions' => $redemptions,
        ])->layout('layouts.visitor');
    }
}
