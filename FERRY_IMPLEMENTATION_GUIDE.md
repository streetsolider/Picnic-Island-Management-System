# Ferry System Implementation Guide

## ✅ Completed Components

### Phase 1 & 2: Foundation (100%)
- ✅ Database migration
- ✅ FerryTicket model
- ✅ FerryTicketService
- ✅ Model relationships

### Phase 3: Ferry Operator Interface (60%)
- ✅ Dashboard
- ✅ Routes management
- ✅ Schedules management
- ⏳ Ticket validation
- ⏳ Passenger list

### Phase 4: Guest Interface (0%)
- ⏳ Browse schedules
- ⏳ Create booking
- ⏳ Confirmation
- ⏳ My Tickets
- ⏳ Show ticket

### Phase 5: Routes
- ✅ All routes configured

---

## 📋 Remaining Components Implementation

### 1. Ticket Validation (Ferry Operator)

**File**: `app/Livewire/Ferry/Tickets/Validate.php`

```php
<?php
namespace App\Livewire\Ferry\Tickets;

use App\Models\Ferry\FerryVessel;
use App\Services\FerryTicketService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Validate extends Component
{
    public $vessel;
    public $ticketReference = '';
    public $ticket = null;
    public $validationResult = null;

    public function mount()
    {
        $this->vessel = FerryVessel::where('operator_id', auth('staff')->id())->first();
        if (!$this->vessel) {
            abort(403, 'No ferry vessel assigned to you.');
        }
    }

    public function validateTicket()
    {
        $this->validate([
            'ticketReference' => 'required|string',
        ]);

        $service = app(FerryTicketService::class);
        $this->validationResult = $service->validateTicket(
            $this->ticketReference,
            auth('staff')->id()
        );

        if ($this->validationResult['success']) {
            $this->ticket = $this->validationResult['ticket'];
            session()->flash('success', $this->validationResult['message']);
        } else {
            $this->ticket = $this->validationResult['ticket'];
            session()->flash('error', $this->validationResult['message']);
        }
    }

    public function reset()
    {
        $this->ticketReference = '';
        $this->ticket = null;
        $this->validationResult = null;
    }

    #[Layout('layouts.ferry')]
    public function render()
    {
        return view('livewire.ferry.tickets.validate');
    }
}
```

**View**: `resources/views/livewire/ferry/tickets/validate.blade.php`

```blade
<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Validate Ferry Ticket</h2>

    <x-admin.card.base>
        <div class="max-w-2xl mx-auto">
            {{-- Input Form --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Ticket Reference
                </label>
                <div class="flex space-x-2">
                    <input
                        type="text"
                        wire:model="ticketReference"
                        wire:keydown.enter="validateTicket"
                        placeholder="Enter ticket reference (e.g., FT-XXXXXXXX)"
                        class="flex-1 px-4 py-3 text-lg border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <x-admin.button.primary wire:click="validateTicket" size="lg">
                        Validate
                    </x-admin.button.primary>
                </div>
            </div>

            {{-- Validation Result --}}
            @if($ticket)
                <div class="mt-6 p-6 {{ $validationResult['success'] ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' }} rounded-lg">
                    <h3 class="text-lg font-semibold {{ $validationResult['success'] ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-100' }} mb-4">
                        {{ $validationResult['message'] }}
                    </h3>

                    @if($ticket)
                        <div class="space-y-2 text-sm">
                            <div><strong>Ticket:</strong> {{ $ticket->ticket_reference }}</div>
                            <div><strong>Passenger:</strong> {{ $ticket->guest->name }}</div>
                            <div><strong>Route:</strong> {{ $ticket->route->origin }} → {{ $ticket->route->destination }}</div>
                            <div><strong>Travel Date:</strong> {{ $ticket->travel_date->format('M d, Y') }}</div>
                            <div><strong>Passengers:</strong> {{ $ticket->number_of_passengers }}</div>
                            <div><strong>Status:</strong> {{ ucfirst($ticket->status) }}</div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <x-admin.button.secondary wire:click="reset">
                            Validate Another Ticket
                        </x-admin.button.secondary>
                    </div>
                </div>
            @endif
        </div>
    </x-admin.card.base>
</div>
```

---

### 2. Passenger List (Ferry Operator)

**File**: `app/Livewire/Ferry/Tickets/PassengerList.php`

```php
<?php
namespace App\Livewire\Ferry\Tickets;

use App\Models\Ferry\FerrySchedule;
use App\Models\Ferry\FerryVessel;
use App\Services\FerryTicketService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PassengerList extends Component
{
    public $vessel;
    public $schedules;
    public $selectedSchedule = null;
    public $selectedDate = null;
    public $passengers = [];

    public function mount()
    {
        $this->vessel = FerryVessel::where('operator_id', auth('staff')->id())->first();
        if (!$this->vessel) {
            abort(403, 'No ferry vessel assigned to you.');
        }

        $this->schedules = FerrySchedule::with('route')
            ->where('ferry_vessel_id', $this->vessel->id)
            ->get();

        $this->selectedDate = now()->format('Y-m-d');
    }

    public function loadPassengers()
    {
        $this->validate([
            'selectedSchedule' => 'required|exists:ferry_schedules,id',
            'selectedDate' => 'required|date',
        ]);

        $service = app(FerryTicketService::class);
        $this->passengers = $service->getPassengerList($this->selectedSchedule, $this->selectedDate);
    }

    #[Layout('layouts.ferry')]
    public function render()
    {
        return view('livewire.ferry.tickets.passenger-list');
    }
}
```

**View**: `resources/views/livewire/ferry/tickets/passenger-list.blade.php` - Create table showing passengers with ticket refs, names, passenger count, status

---

### 3. Guest Browse Schedules

**File**: `app/Livewire/Visitor/FerryTickets/Browse.php`

```php
public $travelDate;
public $routeId = '';
public $passengers = 1;
public $schedules = [];
public $hasValidBooking = false;
public $hotelBooking = null;
public $routes;

public function mount()
{
    $this->travelDate = now()->addDay()->format('Y-m-d');
    $this->routes = FerryRoute::where('is_active', true)->get();

    if (auth()->check()) {
        $service = app(FerryTicketService::class);
        $validation = $service->validateHotelBooking(auth()->id());
        $this->hasValidBooking = $validation['valid'];
        $this->hotelBooking = $validation['booking'];
    }
}

public function search()
{
    // If has booking, restrict to check-in or check-out dates
    if ($this->hotelBooking) {
        $checkIn = $this->hotelBooking->check_in_date->format('Y-m-d');
        $checkOut = $this->hotelBooking->check_out_date->format('Y-m-d');

        if ($this->travelDate !== $checkIn && $this->travelDate !== $checkOut) {
            session()->flash('error', "Travel date must be your check-in ({$checkIn}) or check-out ({$checkOut}) date.");
            return;
        }
    }

    $service = app(FerryTicketService::class);
    $this->schedules = $service->getAvailableSchedules(
        $this->travelDate,
        $this->routeId ?: null,
        $this->passengers
    );
}
```

**View**: Show schedule cards with Book Now buttons (disabled if no valid booking)

---

### 4. Guest Create Booking

**File**: `app/Livewire/Visitor/FerryTickets/Create.php`

```php
public FerrySchedule $schedule;
public $travelDate;
public $passengers;
public $hotelBooking;
public $totalPrice;

public function mount(FerrySchedule $schedule)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $this->schedule = $schedule->load(['route', 'vessel']);
    $this->travelDate = request()->query('date');
    $this->passengers = request()->query('passengers', 1);

    // Validate hotel booking
    $service = app(FerryTicketService::class);
    $validation = $service->validateHotelBooking(auth()->id());

    if (!$validation['valid']) {
        session()->flash('error', $validation['errors'][0]);
        return redirect()->route('ferry-tickets.browse');
    }

    $this->hotelBooking = $validation['booking'];
    $this->totalPrice = $this->schedule->route->base_price * $this->passengers;
}

public function confirmBooking()
{
    $service = app(FerryTicketService::class);

    try {
        $ticket = $service->createTicket([
            'guest_id' => auth()->id(),
            'hotel_booking_id' => $this->hotelBooking->id,
            'ferry_schedule_id' => $this->schedule->id,
            'travel_date' => $this->travelDate,
            'number_of_passengers' => $this->passengers,
            'payment_status' => 'paid',
            'payment_method' => 'online',
        ]);

        return redirect()->route('ferry-tickets.confirmation', $ticket->id);
    } catch (\Exception $e) {
        session()->flash('error', $e->getMessage());
    }
}
```

---

### 5-8. Remaining Guest Components

Follow these patterns:
- **Confirmation**: Show ticket reference, ferry details, instructions
- **MyTickets**: List with filters (upcoming, past, cancelled)
- **Show**: Individual ticket details with cancel button

Use visitor layout, gradient backgrounds, similar to hotel booking UI.

---

## 🎯 Quick Implementation Checklist

1. ✅ Ferry operator can manage routes
2. ✅ Ferry operator can manage schedules
3. ⏳ Ferry operator can validate tickets at boarding
4. ⏳ Ferry operator can view passenger lists
5. ⏳ Guests can browse ferry schedules
6. ⏳ Guests see hotel booking validation
7. ⏳ Guests can book tickets (date restricted to check-in/check-out)
8. ⏳ Guests receive ticket reference
9. ⏳ Guests can view and cancel tickets

---

## 🔑 Key Points

- All guest ferry routes use `auth:web` guard
- All operator routes use `auth:staff` + `role:ferry_operator`
- Travel date MUST match hotel check-in OR check-out date
- Ticket reference format: FT-XXXXXXXX
- Simple pricing: base_price × passengers
- No promotional discounts for ferry tickets

---

## 📚 Reference Files

- Service: `app/Services/FerryTicketService.php`
- Models: `app/Models/Ferry/FerryTicket.php`
- Hotel patterns: `app/Livewire/Visitor/Booking/*.php`
- Admin components: `resources/views/components/admin/**/*.blade.php`
