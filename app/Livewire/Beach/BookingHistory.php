<?php

namespace App\Livewire\Beach;

use App\Models\BeachService;
use App\Models\BeachServiceBooking;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.staff')]
#[Title('Booking History')]
class BookingHistory extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $searchGuest = '';

    protected $queryString = ['statusFilter', 'dateFrom', 'dateTo', 'searchGuest'];

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function updatingSearchGuest()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['statusFilter', 'dateFrom', 'dateTo', 'searchGuest']);
        $this->resetPage();
    }

    public function render()
    {
        $staffId = auth('staff')->id();

        // Get the selected service from session
        $selectedServiceId = session('beach_selected_service_id');

        // Get the beach service
        $assignedService = $selectedServiceId
            ? BeachService::where('id', $selectedServiceId)
                ->where('assigned_staff_id', $staffId)
                ->first()
            : BeachService::where('assigned_staff_id', $staffId)->first();

        if (!$assignedService) {
            return view('livewire.beach.booking-history', [
                'assignedService' => null,
                'bookings' => collect(),
                'totalRevenue' => 0,
                'totalBookings' => 0,
            ]);
        }

        // Query bookings for assigned service
        $bookings = BeachServiceBooking::where('beach_service_id', $assignedService->id)
            ->with(['guest', 'hotelBooking', 'redeemedByStaff'])
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFrom, function ($query) {
                $query->where('booking_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->where('booking_date', '<=', $this->dateTo);
            })
            ->when($this->searchGuest, function ($query) {
                $query->whereHas('guest', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchGuest . '%')
                      ->orWhere('email', 'like', '%' . $this->searchGuest . '%');
                });
            })
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        // Calculate stats
        $totalRevenue = BeachServiceBooking::where('beach_service_id', $assignedService->id)
            ->where('status', 'redeemed')
            ->sum('total_price');

        $totalBookings = BeachServiceBooking::where('beach_service_id', $assignedService->id)->count();

        return view('livewire.beach.booking-history', [
            'assignedService' => $assignedService,
            'bookings' => $bookings,
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
        ]);
    }
}
