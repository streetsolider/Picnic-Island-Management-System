<?php

namespace App\Livewire\Hotel\Operations;

use App\Livewire\Hotel\Traits\HasHotelSelection;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\LateCheckoutRequest;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    use HasHotelSelection;

    public $selectedBooking = null;

    // Check-in form
    public $checkInNotes = '';
    public $actualGuestsCount = null;
    public $guestPhone = '';

    // ID Verification form (if guest doesn't have ID on file)
    public $idType = null;
    public $idNumber = '';
    public $officialFullName = '';
    public $nationality = 'Maldivian';
    public $dateOfBirth = '';
    public $address = '';

    // Room reassignment
    public $availableRooms = [];
    public $selectedNewRoomId = null;
    public $roomChangeReason = '';

    // Check-out form
    public $checkOutNotes = '';

    // Late checkout approval
    public $selectedLateCheckoutRequest = null;
    public $lateCheckoutManagerNotes = '';

    public function mount()
    {
        $this->initializeHotelSelection();
    }

    public function getTodayArrivalsProperty()
    {
        return $this->hotel->bookings()
            ->with(['guest', 'room'])
            ->where('status', 'confirmed')
            ->whereDate('check_in_date', Carbon::today())
            ->orderBy('check_in_date')
            ->get();
    }

    public function getTodayDeparturesProperty()
    {
        return $this->hotel->bookings()
            ->with(['guest', 'room'])
            ->where('status', 'checked_in')
            ->whereDate('check_out_date', Carbon::today())
            ->orderBy('check_out_date')
            ->get();
    }

    public function getInHouseGuestsProperty()
    {
        return $this->hotel->bookings()
            ->with(['guest', 'room', 'hotel', 'lateCheckoutRequest'])
            ->where('status', 'checked_in')
            ->get();
    }

    public function getUpcomingBookingsProperty()
    {
        return $this->hotel->bookings()
            ->with(['guest', 'room'])
            ->where('status', 'confirmed')
            ->whereDate('check_in_date', '>', Carbon::today())
            ->whereDate('check_in_date', '<=', Carbon::today()->addDays(7))
            ->orderBy('check_in_date')
            ->get();
    }

    public function getOccupancyStatsProperty()
    {
        $totalRooms = $this->hotel->rooms()->where('is_active', true)->count();
        $occupiedRooms = $this->hotel->bookings()
            ->where('status', 'checked_in')
            ->count();

        return [
            'total' => $totalRooms,
            'occupied' => $occupiedRooms,
            'available' => $totalRooms - $occupiedRooms,
            'rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0,
        ];
    }

    public function getTodayRevenueProperty()
    {
        return $this->hotel->bookings()
            ->whereDate('check_in_date', Carbon::today())
            ->where('payment_status', 'paid')
            ->sum('total_price');
    }

    public function getPendingLateCheckoutRequestsProperty()
    {
        return LateCheckoutRequest::whereHas('booking', function($query) {
                $query->where('hotel_id', $this->hotel->id);
            })
            ->with(['booking.guest', 'booking.room'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    // Check-in operations
    public function openCheckInModal($bookingId)
    {
        $this->selectedBooking = HotelBooking::with(['guest', 'room'])->findOrFail($bookingId);
        $this->checkInNotes = '';
        $this->actualGuestsCount = $this->selectedBooking->number_of_guests; // Default to booked amount

        // Initialize ID verification fields
        $guest = $this->selectedBooking->guest;

        // Initialize phone number
        $this->guestPhone = $guest->phone ?? '';

        if ($guest->id_type) {
            // Guest has ID on file - pre-fill for display
            $this->idType = $guest->id_type;
            $this->idNumber = $guest->id_number;
            $this->officialFullName = $guest->name; // Using their registered name
            $this->nationality = $guest->nationality;
            $this->dateOfBirth = $guest->date_of_birth ? $guest->date_of_birth->format('Y-m-d') : '';
            $this->address = $guest->address ?? '';
        } else {
            // No ID on file - prepare empty form with defaults
            $this->idType = 'passport'; // Default to passport for international guests
            $this->idNumber = '';
            $this->officialFullName = $guest->name; // Pre-fill with registered name
            $this->nationality = 'Maldivian';
            $this->dateOfBirth = '';
            $this->address = '';
        }

        // Load available rooms of the same type
        $this->loadAvailableRooms();
        $this->selectedNewRoomId = $this->selectedBooking->room_id; // Default to current room
        $this->roomChangeReason = '';

        $this->dispatch('open-modal', 'check-in');
    }

    public function loadAvailableRooms()
    {
        $currentRoom = $this->selectedBooking->room;
        $checkIn = $this->selectedBooking->check_in_date;
        $checkOut = $this->selectedBooking->check_out_date;

        // Find rooms with same attributes
        $this->availableRooms = \App\Models\Room::where('hotel_id', $this->hotel->id)
            ->where('is_active', true)
            ->where('room_type', $currentRoom->room_type)
            ->where('bed_size', $currentRoom->bed_size)
            ->where('bed_count', $currentRoom->bed_count)
            ->where('view', $currentRoom->view)
            ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
                $query->whereIn('status', ['confirmed', 'checked_in'])
                    ->where(function ($q) use ($checkIn, $checkOut) {
                        $q->whereBetween('check_in_date', [$checkIn, $checkOut])
                          ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                          ->orWhere(function ($q2) use ($checkIn, $checkOut) {
                              $q2->where('check_in_date', '<=', $checkIn)
                                 ->where('check_out_date', '>=', $checkOut);
                          });
                    });
            })
            ->orderBy('room_number')
            ->get();
    }

    public function confirmCheckIn()
    {
        $validationRules = [
            'checkInNotes' => 'nullable|string|max:1000',
            'actualGuestsCount' => 'required|integer|min:1|max:' . $this->selectedBooking->number_of_guests,
            'guestPhone' => 'required|string|max:20',
        ];

        $validationMessages = [
            'actualGuestsCount.required' => 'Please enter the number of guests checking in.',
            'actualGuestsCount.max' => 'Cannot exceed the booked number of guests (' . $this->selectedBooking->number_of_guests . ').',
            'guestPhone.required' => 'Please enter the guest\'s phone number.',
            'guestPhone.max' => 'Phone number is too long.',
        ];

        // If guest doesn't have ID on file, validate ID verification fields
        if (!$this->selectedBooking->guest->id_type) {
            $validationRules['idType'] = 'required|in:national_id,passport';
            $validationRules['idNumber'] = 'required|string|max:50';
            $validationRules['officialFullName'] = 'required|string|max:255';
            $validationRules['nationality'] = 'required|string|max:100';
            $validationRules['dateOfBirth'] = 'required|date|before:today';
            $validationRules['address'] = 'required|string|max:500';

            $validationMessages['idType.required'] = 'Please select an ID type.';
            $validationMessages['idNumber.required'] = 'Please enter the ID number.';
            $validationMessages['officialFullName.required'] = 'Please enter the guest\'s official full name.';
            $validationMessages['nationality.required'] = 'Please enter the nationality.';
            $validationMessages['dateOfBirth.required'] = 'Please enter the date of birth.';
            $validationMessages['dateOfBirth.before'] = 'Date of birth must be in the past.';
            $validationMessages['address.required'] = 'Please enter the address.';
        }

        // If room is being changed, validate reason
        if ($this->selectedNewRoomId != $this->selectedBooking->room_id) {
            $validationRules['roomChangeReason'] = 'required|string|max:500';
            $validationMessages['roomChangeReason.required'] = 'Please provide a reason for the room change.';
        }

        $this->validate($validationRules, $validationMessages);

        // Save ID verification details if guest doesn't have ID on file
        if (!$this->selectedBooking->guest->id_type) {
            $this->selectedBooking->guest->update([
                'official_name' => $this->officialFullName,
                'id_type' => $this->idType,
                'id_number' => $this->idNumber,
                'nationality' => $this->nationality,
                'date_of_birth' => $this->dateOfBirth,
                'address' => $this->address,
                'phone' => $this->guestPhone,
            ]);
        } else {
            // Update phone number if it was changed
            if ($this->selectedBooking->guest->phone !== $this->guestPhone) {
                $this->selectedBooking->guest->update([
                    'phone' => $this->guestPhone,
                ]);
            }
        }

        // Handle room reassignment if room was changed
        if ($this->selectedNewRoomId != $this->selectedBooking->room_id) {
            $this->selectedBooking->reassignRoom(
                $this->selectedNewRoomId,
                auth('staff')->id(),
                $this->roomChangeReason
            );
        }

        // Update actual guests count
        $this->selectedBooking->actual_guests_checked_in = $this->actualGuestsCount;
        $this->selectedBooking->save();

        // Perform check-in
        $this->selectedBooking->checkIn(
            auth('staff')->id(),
            $this->checkInNotes
        );

        $successMessage = 'Guest checked in successfully! ' . $this->actualGuestsCount . ' guest(s) checked in.';
        if ($this->selectedNewRoomId != $this->selectedBooking->room_id) {
            $successMessage .= ' Room reassigned.';
        }

        session()->flash('success', $successMessage);
        $this->dispatch('close-modal', 'check-in');
        $this->resetCheckInForm();
    }

    private function resetCheckInForm()
    {
        $this->selectedBooking = null;
        $this->checkInNotes = '';
        $this->actualGuestsCount = null;
        $this->guestPhone = '';
        $this->idType = null;
        $this->idNumber = '';
        $this->officialFullName = '';
        $this->nationality = 'Maldivian';
        $this->dateOfBirth = '';
        $this->address = '';
        $this->availableRooms = [];
        $this->selectedNewRoomId = null;
        $this->roomChangeReason = '';
    }

    // Check-out operations
    public function openCheckOutModal($bookingId)
    {
        $this->selectedBooking = HotelBooking::with(['guest', 'room'])->findOrFail($bookingId);
        $this->checkOutNotes = '';
        $this->dispatch('open-modal', 'check-out');
    }

    public function confirmCheckOut()
    {
        $this->validate([
            'checkOutNotes' => 'nullable|string|max:1000',
        ]);

        $this->selectedBooking->checkOut(
            auth('staff')->id(),
            $this->checkOutNotes
        );

        session()->flash('success', 'Guest checked out successfully!');
        $this->dispatch('close-modal', 'check-out');
        $this->selectedBooking = null;
        $this->checkOutNotes = '';
    }

    // Late checkout approval operations
    public function openApproveLateCheckoutModal($requestId)
    {
        $this->selectedLateCheckoutRequest = LateCheckoutRequest::with(['booking.guest', 'booking.room', 'booking.hotel'])
            ->findOrFail($requestId);
        $this->lateCheckoutManagerNotes = '';
        $this->dispatch('open-modal', 'approve-late-checkout');
    }

    public function openRejectLateCheckoutModal($requestId)
    {
        $this->selectedLateCheckoutRequest = LateCheckoutRequest::with(['booking.guest', 'booking.room', 'booking.hotel'])
            ->findOrFail($requestId);
        $this->lateCheckoutManagerNotes = '';
        $this->dispatch('open-modal', 'reject-late-checkout');
    }

    public function approveLateCheckout()
    {
        $this->validate([
            'lateCheckoutManagerNotes' => 'nullable|string|max:500',
        ]);

        $this->selectedLateCheckoutRequest->approve(
            auth('staff')->id(),
            $this->lateCheckoutManagerNotes
        );

        session()->flash('success', 'Late checkout request approved successfully!');
        $this->dispatch('close-modal', 'approve-late-checkout');
        $this->selectedLateCheckoutRequest = null;
        $this->lateCheckoutManagerNotes = '';
    }

    public function rejectLateCheckout()
    {
        $this->validate([
            'lateCheckoutManagerNotes' => 'required|string|max:500',
        ], [
            'lateCheckoutManagerNotes.required' => 'Please provide a reason for rejection.',
        ]);

        $this->selectedLateCheckoutRequest->reject(
            auth('staff')->id(),
            $this->lateCheckoutManagerNotes
        );

        session()->flash('success', 'Late checkout request rejected.');
        $this->dispatch('close-modal', 'reject-late-checkout');
        $this->selectedLateCheckoutRequest = null;
        $this->lateCheckoutManagerNotes = '';
    }

    public function render()
    {
        return view('livewire.hotel.operations.dashboard', [
            'assignedHotels' => $this->assignedHotels,
            'todayArrivals' => $this->todayArrivals,
            'todayDepartures' => $this->todayDepartures,
            'inHouseGuests' => $this->inHouseGuests,
            'upcomingBookings' => $this->upcomingBookings,
            'occupancyStats' => $this->occupancyStats,
            'todayRevenue' => $this->todayRevenue,
            'pendingLateCheckoutRequests' => $this->pendingLateCheckoutRequests,
        ])->layout('layouts.hotel');
    }
}
