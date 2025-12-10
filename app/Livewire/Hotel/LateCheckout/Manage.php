<?php

namespace App\Livewire\Hotel\LateCheckout;

use App\Models\Hotel;
use App\Models\LateCheckoutRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;

    public Hotel $hotel;
    public $statusFilter = 'pending';

    // Modals
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $selectedRequest = null;
    public $managerNotes = '';

    public function mount()
    {
        // Get the hotel managed by the current user
        $this->hotel = Hotel::where('manager_id', auth('staff')->user()->id)
            ->firstOrFail();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openApproveModal($requestId)
    {
        $this->selectedRequest = LateCheckoutRequest::with([
            'booking.guest',
            'booking.room',
        ])->findOrFail($requestId);

        $this->managerNotes = '';
        $this->showApproveModal = true;
    }

    public function openRejectModal($requestId)
    {
        $this->selectedRequest = LateCheckoutRequest::with([
            'booking.guest',
            'booking.room',
        ])->findOrFail($requestId);

        $this->managerNotes = '';
        $this->showRejectModal = true;
    }

    public function closeModals()
    {
        $this->showApproveModal = false;
        $this->showRejectModal = false;
        $this->selectedRequest = null;
        $this->reset('managerNotes');
    }

    public function approve()
    {
        if (!$this->selectedRequest) {
            return;
        }

        try {
            $this->selectedRequest->approve(
                auth('staff')->user()->id,
                $this->managerNotes ?: null
            );

            session()->flash('message', 'Late checkout request approved successfully.');
            $this->closeModals();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    public function reject()
    {
        if (!$this->selectedRequest) {
            return;
        }

        // Validate manager notes are required for rejection
        $this->validate([
            'managerNotes' => 'required|min:10',
        ], [
            'managerNotes.required' => 'Please provide a reason for rejection.',
            'managerNotes.min' => 'Rejection reason must be at least 10 characters.',
        ]);

        try {
            $this->selectedRequest->reject(
                auth('staff')->user()->id,
                $this->managerNotes
            );

            session()->flash('message', 'Late checkout request rejected.');
            $this->closeModals();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }

    public function getRequestsProperty()
    {
        $query = LateCheckoutRequest::whereHas('booking', function ($q) {
                $q->where('hotel_id', $this->hotel->id);
            })
            ->with([
                'booking.guest',
                'booking.room',
                'booking.hotel',
            ])
            ->orderBy('created_at', 'desc');

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->paginate(15);
    }

    public function getPendingCountProperty()
    {
        return LateCheckoutRequest::whereHas('booking', function ($q) {
            $q->where('hotel_id', $this->hotel->id);
        })->where('status', 'pending')->count();
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.late-checkout.manage', [
            'requests' => $this->requests,
            'pendingCount' => $this->pendingCount,
        ]);
    }
}
