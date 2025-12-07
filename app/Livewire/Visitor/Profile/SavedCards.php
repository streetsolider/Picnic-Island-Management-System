<?php

namespace App\Livewire\Visitor\Profile;

use App\Models\SavedPaymentMethod;
use App\Services\PaymentService;
use Livewire\Component;

class SavedCards extends Component
{
    public $showDeleteConfirm = false;
    public $cardToDelete = null;

    protected $listeners = ['refreshCards' => '$refresh'];

    public function setAsDefault($cardId)
    {
        try {
            $card = SavedPaymentMethod::where('guest_id', auth()->id())
                ->findOrFail($cardId);

            $card->setAsDefault();

            session()->flash('success', 'Default payment method updated successfully.');
            $this->dispatch('refreshCards');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update default payment method.');
        }
    }

    public function confirmDelete($cardId)
    {
        $this->cardToDelete = $cardId;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->cardToDelete = null;
        $this->showDeleteConfirm = false;
    }

    public function deleteCard()
    {
        try {
            $card = SavedPaymentMethod::where('guest_id', auth()->id())
                ->findOrFail($this->cardToDelete);

            $paymentService = app(PaymentService::class);
            $paymentService->deleteSavedCard($card);

            session()->flash('success', 'Payment method deleted successfully.');
            $this->cancelDelete();
            $this->dispatch('refreshCards');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete payment method.');
            $this->cancelDelete();
        }
    }

    public function render()
    {
        $savedCards = SavedPaymentMethod::where('guest_id', auth()->id())
            ->orderBy('is_default', 'desc')
            ->orderBy('last_used_at', 'desc')
            ->get();

        return view('livewire.visitor.profile.saved-cards', [
            'savedCards' => $savedCards,
        ])->layout('layouts.visitor');
    }
}
