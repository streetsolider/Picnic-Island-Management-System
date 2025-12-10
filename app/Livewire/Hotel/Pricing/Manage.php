<?php

namespace App\Livewire\Hotel\Pricing;

use App\Livewire\Hotel\Traits\HasHotelSelection;
use App\Models\DayTypePricing;
use App\Models\PromotionalDiscount;
use App\Models\Hotel;
use App\Models\RoomTypePricing;
use App\Models\SeasonalPricing;
use App\Models\ViewPricing;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Manage extends Component
{
    use HasHotelSelection;
    public $activeTab = 'room_types'; // room_types, views, seasonal, day_types, promotions
    public $refreshKey = 0;

    // Room Types (enums)
    public $roomTypes = [
        'standard' => 'Standard',
        'superior' => 'Superior',
        'deluxe' => 'Deluxe',
        'suite' => 'Suite',
        'family' => 'Family',
    ];

    // Views available
    public $viewOptions = ['Garden View', 'Beach View'];

    // Modifier types
    public $modifierTypes = [
        'fixed' => 'Fixed Amount (MVR)',
        'percentage' => 'Percentage (%)',
    ];

    // Days of week for Day Type Pricing
    public $daysOfWeek = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    // ==================== Room Type Pricing ====================
    public $selectedRoomType = '';
    public $basePrice = '';
    public $roomTypeIsActive = true;
    public $editingRoomTypePricingId = null;
    public $showRoomTypePricingForm = false;
    public $deletingRoomTypePricingId = null;

    // ==================== View Pricing ====================
    public $selectedView = '';
    public $viewModifierType = 'fixed';
    public $viewModifierValue = '';
    public $viewIsActive = true;
    public $editingViewPricingId = null;
    public $showViewPricingForm = false;
    public $deletingViewPricingId = null;

    // ==================== Seasonal Pricing ====================
    public $seasonName = '';
    public $seasonStartDate = '';
    public $seasonEndDate = '';
    public $seasonModifierType = 'percentage';
    public $seasonModifierValue = '';
    public $seasonPriority = 1;
    public $seasonIsActive = true;
    public $editingSeasonalPricingId = null;
    public $showSeasonalPricingForm = false;
    public $deletingSeasonalPricingId = null;

    // ==================== Day Type Pricing ====================
    public $dayTypeName = '';
    public $dayTypeApplicableDays = [];
    public $dayTypeModifierType = 'percentage';
    public $dayTypeModifierValue = '';
    public $dayTypeIsActive = true;
    public $editingDayTypePricingId = null;
    public $showDayTypePricingForm = false;
    public $deletingDayTypePricingId = null;

    // ==================== Promotional Discounts ====================
    public $promotionName = '';
    public $promotionDescription = '';
    public $promotionDiscountType = 'percentage';
    public $promotionDiscountValue = '';
    public $promotionStartDate = '';
    public $promotionEndDate = '';
    public $promotionMinimumRooms = '';
    public $promotionMaximumRooms = '';
    public $promotionMinimumNights = '';
    public $promotionMaximumNights = '';
    public $promotionBookingAdvanceDays = '';
    public $promotionApplicableRoomTypes = [];
    public $promotionPromoCode = '';
    public $promotionPriority = 0;
    public $promotionIsActive = true;
    public $editingPromotionId = null;
    public $showPromotionForm = false;
    public $deletingPromotionId = null;

    public function mount()
    {
        $this->initializeHotelSelection();
    }

    public function onHotelChanged()
    {
        $this->refreshKey++;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // ==================== ROOM TYPE PRICING METHODS ====================

    protected function roomTypePricingRules()
    {
        return [
            'selectedRoomType' => 'required|string',
            'basePrice' => 'required|numeric|min:0',
            'roomTypeIsActive' => 'boolean',
        ];
    }

    public function openRoomTypePricingForm()
    {
        $this->showRoomTypePricingForm = true;
        $this->reset(['selectedRoomType', 'basePrice', 'editingRoomTypePricingId']);
        $this->roomTypeIsActive = true;
    }

    public function closeRoomTypePricingForm()
    {
        $this->showRoomTypePricingForm = false;
        $this->reset(['selectedRoomType', 'basePrice', 'roomTypeIsActive', 'editingRoomTypePricingId']);
    }

    public function saveRoomTypePricing()
    {
        $this->validate($this->roomTypePricingRules());

        if ($this->editingRoomTypePricingId) {
            $pricing = RoomTypePricing::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingRoomTypePricingId);

            $pricing->update([
                'room_type' => $this->selectedRoomType,
                'base_price' => $this->basePrice,
                'is_active' => $this->roomTypeIsActive,
            ]);

            session()->flash('success', 'Room type pricing updated successfully!');
        } else {
            // Check if already exists
            $existing = RoomTypePricing::where('hotel_id', $this->hotel->id)
                ->where('room_type', $this->selectedRoomType)
                ->first();

            if ($existing) {
                $this->addError('selectedRoomType', 'Pricing for this room type already exists. Please edit the existing one.');
                return;
            }

            RoomTypePricing::create([
                'hotel_id' => $this->hotel->id,
                'room_type' => $this->selectedRoomType,
                'base_price' => $this->basePrice,
                'currency' => 'MVR',
                'is_active' => $this->roomTypeIsActive,
            ]);

            session()->flash('success', 'Room type pricing created successfully!');
        }

        $this->refreshKey++;
        $this->closeRoomTypePricingForm();
    }

    public function editRoomTypePricing($id)
    {
        $pricing = RoomTypePricing::where('hotel_id', $this->hotel->id)->findOrFail($id);

        $this->editingRoomTypePricingId = $pricing->id;
        $this->selectedRoomType = $pricing->room_type;
        $this->basePrice = $pricing->base_price;
        $this->roomTypeIsActive = $pricing->is_active;
        $this->showRoomTypePricingForm = true;
    }

    public function confirmDeleteRoomTypePricing($id)
    {
        $this->deletingRoomTypePricingId = $id;
    }

    public function deleteRoomTypePricing()
    {
        if (!$this->deletingRoomTypePricingId) {
            return;
        }

        $pricing = RoomTypePricing::where('hotel_id', $this->hotel->id)
            ->findOrFail($this->deletingRoomTypePricingId);

        $pricing->delete();

        session()->flash('success', 'Room type pricing deleted successfully!');
        $this->deletingRoomTypePricingId = null;
        $this->refreshKey++;
    }

    public function toggleRoomTypePricingStatus($id)
    {
        $pricing = RoomTypePricing::where('hotel_id', $this->hotel->id)->findOrFail($id);
        $pricing->update(['is_active' => !$pricing->is_active]);
        $this->refreshKey++;
    }

    public function getRoomTypePricings()
    {
        return RoomTypePricing::where('hotel_id', $this->hotel->id)
            ->orderBy('room_type')
            ->get();
    }

    // ==================== VIEW PRICING METHODS ====================

    protected function viewPricingRules()
    {
        return [
            'selectedView' => 'required|string',
            'viewModifierType' => 'required|in:fixed,percentage',
            'viewModifierValue' => 'required|numeric|min:0',
            'viewIsActive' => 'boolean',
        ];
    }

    public function openViewPricingForm()
    {
        $this->showViewPricingForm = true;
        $this->reset(['selectedView', 'viewModifierType', 'viewModifierValue', 'editingViewPricingId']);
        $this->viewModifierType = 'fixed';
        $this->viewIsActive = true;
    }

    public function closeViewPricingForm()
    {
        $this->showViewPricingForm = false;
        $this->reset(['selectedView', 'viewModifierType', 'viewModifierValue', 'viewIsActive', 'editingViewPricingId']);
    }

    public function saveViewPricing()
    {
        $this->validate($this->viewPricingRules());

        if ($this->editingViewPricingId) {
            $pricing = ViewPricing::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingViewPricingId);

            $pricing->update([
                'view' => $this->selectedView,
                'modifier_type' => $this->viewModifierType,
                'modifier_value' => $this->viewModifierValue,
                'is_active' => $this->viewIsActive,
            ]);

            session()->flash('success', 'View pricing updated successfully!');
        } else {
            // Check if already exists
            $existing = ViewPricing::where('hotel_id', $this->hotel->id)
                ->where('view', $this->selectedView)
                ->first();

            if ($existing) {
                $this->addError('selectedView', 'Pricing for this view already exists. Please edit the existing one.');
                return;
            }

            ViewPricing::create([
                'hotel_id' => $this->hotel->id,
                'view' => $this->selectedView,
                'modifier_type' => $this->viewModifierType,
                'modifier_value' => $this->viewModifierValue,
                'is_active' => $this->viewIsActive,
            ]);

            session()->flash('success', 'View pricing created successfully!');
        }

        $this->refreshKey++;
        $this->closeViewPricingForm();
    }

    public function editViewPricing($id)
    {
        $pricing = ViewPricing::where('hotel_id', $this->hotel->id)->findOrFail($id);

        $this->editingViewPricingId = $pricing->id;
        $this->selectedView = $pricing->view;
        $this->viewModifierType = $pricing->modifier_type;
        $this->viewModifierValue = $pricing->modifier_value;
        $this->viewIsActive = $pricing->is_active;
        $this->showViewPricingForm = true;
    }

    public function confirmDeleteViewPricing($id)
    {
        $this->deletingViewPricingId = $id;
    }

    public function deleteViewPricing()
    {
        if (!$this->deletingViewPricingId) {
            return;
        }

        $pricing = ViewPricing::where('hotel_id', $this->hotel->id)
            ->findOrFail($this->deletingViewPricingId);

        $pricing->delete();

        session()->flash('success', 'View pricing deleted successfully!');
        $this->deletingViewPricingId = null;
        $this->refreshKey++;
    }

    public function toggleViewPricingStatus($id)
    {
        $pricing = ViewPricing::where('hotel_id', $this->hotel->id)->findOrFail($id);
        $pricing->update(['is_active' => !$pricing->is_active]);
        $this->refreshKey++;
    }

    public function getViewPricings()
    {
        return ViewPricing::where('hotel_id', $this->hotel->id)
            ->orderBy('view')
            ->get();
    }

    // ==================== SEASONAL PRICING METHODS ====================

    protected function seasonalPricingRules()
    {
        return [
            'seasonName' => 'required|string|max:255',
            'seasonStartDate' => 'required|date',
            'seasonEndDate' => 'required|date|after_or_equal:seasonStartDate',
            'seasonModifierType' => 'required|in:fixed,percentage',
            'seasonModifierValue' => 'required|numeric',
            'seasonPriority' => 'required|integer|min:1|max:10',
            'seasonIsActive' => 'boolean',
        ];
    }

    public function openSeasonalPricingForm()
    {
        $this->showSeasonalPricingForm = true;
        $this->reset(['seasonName', 'seasonStartDate', 'seasonEndDate', 'seasonModifierType', 'seasonModifierValue', 'seasonPriority', 'editingSeasonalPricingId']);
        $this->seasonModifierType = 'percentage';
        $this->seasonPriority = 1;
        $this->seasonIsActive = true;
    }

    public function closeSeasonalPricingForm()
    {
        $this->showSeasonalPricingForm = false;
        $this->reset(['seasonName', 'seasonStartDate', 'seasonEndDate', 'seasonModifierType', 'seasonModifierValue', 'seasonPriority', 'seasonIsActive', 'editingSeasonalPricingId']);
    }

    public function saveSeasonalPricing()
    {
        $this->validate($this->seasonalPricingRules());

        if ($this->editingSeasonalPricingId) {
            $pricing = SeasonalPricing::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingSeasonalPricingId);

            $pricing->update([
                'season_name' => $this->seasonName,
                'start_date' => $this->seasonStartDate,
                'end_date' => $this->seasonEndDate,
                'modifier_type' => $this->seasonModifierType,
                'modifier_value' => $this->seasonModifierValue,
                'priority' => $this->seasonPriority,
                'is_active' => $this->seasonIsActive,
            ]);

            session()->flash('success', 'Seasonal pricing updated successfully!');
        } else {
            SeasonalPricing::create([
                'hotel_id' => $this->hotel->id,
                'season_name' => $this->seasonName,
                'start_date' => $this->seasonStartDate,
                'end_date' => $this->seasonEndDate,
                'modifier_type' => $this->seasonModifierType,
                'modifier_value' => $this->seasonModifierValue,
                'priority' => $this->seasonPriority,
                'is_active' => $this->seasonIsActive,
            ]);

            session()->flash('success', 'Seasonal pricing created successfully!');
        }

        $this->refreshKey++;
        $this->closeSeasonalPricingForm();
    }

    public function editSeasonalPricing($id)
    {
        $pricing = SeasonalPricing::where('hotel_id', $this->hotel->id)->findOrFail($id);

        $this->editingSeasonalPricingId = $pricing->id;
        $this->seasonName = $pricing->season_name;
        $this->seasonStartDate = $pricing->start_date->format('Y-m-d');
        $this->seasonEndDate = $pricing->end_date->format('Y-m-d');
        $this->seasonModifierType = $pricing->modifier_type;
        $this->seasonModifierValue = $pricing->modifier_value;
        $this->seasonPriority = $pricing->priority;
        $this->seasonIsActive = $pricing->is_active;
        $this->showSeasonalPricingForm = true;
    }

    public function confirmDeleteSeasonalPricing($id)
    {
        $this->deletingSeasonalPricingId = $id;
    }

    public function deleteSeasonalPricing()
    {
        if (!$this->deletingSeasonalPricingId) {
            return;
        }

        $pricing = SeasonalPricing::where('hotel_id', $this->hotel->id)
            ->findOrFail($this->deletingSeasonalPricingId);

        $pricing->delete();

        session()->flash('success', 'Seasonal pricing deleted successfully!');
        $this->deletingSeasonalPricingId = null;
        $this->refreshKey++;
    }

    public function toggleSeasonalPricingStatus($id)
    {
        $pricing = SeasonalPricing::where('hotel_id', $this->hotel->id)->findOrFail($id);
        $pricing->update(['is_active' => !$pricing->is_active]);
        $this->refreshKey++;
    }

    public function getSeasonalPricings()
    {
        return SeasonalPricing::where('hotel_id', $this->hotel->id)
            ->orderBy('priority', 'desc')
            ->orderBy('start_date')
            ->get();
    }

    // ==================== DAY TYPE PRICING METHODS ====================

    protected function dayTypePricingRules()
    {
        return [
            'dayTypeName' => 'required|string|max:255',
            'dayTypeApplicableDays' => 'required|array|min:1',
            'dayTypeApplicableDays.*' => 'integer|between:0,6',
            'dayTypeModifierType' => 'required|in:fixed,percentage',
            'dayTypeModifierValue' => 'required|numeric',
            'dayTypeIsActive' => 'boolean',
        ];
    }

    public function openDayTypePricingForm()
    {
        $this->showDayTypePricingForm = true;
        $this->reset(['dayTypeName', 'dayTypeApplicableDays', 'dayTypeModifierType', 'dayTypeModifierValue', 'editingDayTypePricingId']);
        $this->dayTypeModifierType = 'percentage';
        $this->dayTypeIsActive = true;
    }

    public function closeDayTypePricingForm()
    {
        $this->showDayTypePricingForm = false;
        $this->reset(['dayTypeName', 'dayTypeApplicableDays', 'dayTypeModifierType', 'dayTypeModifierValue', 'dayTypeIsActive', 'editingDayTypePricingId']);
    }

    public function saveDayTypePricing()
    {
        $this->validate($this->dayTypePricingRules());

        if ($this->editingDayTypePricingId) {
            $pricing = DayTypePricing::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingDayTypePricingId);

            $pricing->update([
                'day_type_name' => $this->dayTypeName,
                'applicable_days' => $this->dayTypeApplicableDays,
                'modifier_type' => $this->dayTypeModifierType,
                'modifier_value' => $this->dayTypeModifierValue,
                'is_active' => $this->dayTypeIsActive,
            ]);

            session()->flash('success', 'Day type pricing updated successfully!');
        } else {
            DayTypePricing::create([
                'hotel_id' => $this->hotel->id,
                'day_type_name' => $this->dayTypeName,
                'applicable_days' => $this->dayTypeApplicableDays,
                'modifier_type' => $this->dayTypeModifierType,
                'modifier_value' => $this->dayTypeModifierValue,
                'is_active' => $this->dayTypeIsActive,
            ]);

            session()->flash('success', 'Day type pricing created successfully!');
        }

        $this->refreshKey++;
        $this->closeDayTypePricingForm();
    }

    public function editDayTypePricing($id)
    {
        $pricing = DayTypePricing::where('hotel_id', $this->hotel->id)->findOrFail($id);

        $this->editingDayTypePricingId = $pricing->id;
        $this->dayTypeName = $pricing->day_type_name;
        $this->dayTypeApplicableDays = $pricing->applicable_days;
        $this->dayTypeModifierType = $pricing->modifier_type;
        $this->dayTypeModifierValue = $pricing->modifier_value;
        $this->dayTypeIsActive = $pricing->is_active;
        $this->showDayTypePricingForm = true;
    }

    public function confirmDeleteDayTypePricing($id)
    {
        $this->deletingDayTypePricingId = $id;
    }

    public function deleteDayTypePricing()
    {
        if (!$this->deletingDayTypePricingId) {
            return;
        }

        $pricing = DayTypePricing::where('hotel_id', $this->hotel->id)
            ->findOrFail($this->deletingDayTypePricingId);

        $pricing->delete();

        session()->flash('success', 'Day type pricing deleted successfully!');
        $this->deletingDayTypePricingId = null;
        $this->refreshKey++;
    }

    public function toggleDayTypePricingStatus($id)
    {
        $pricing = DayTypePricing::where('hotel_id', $this->hotel->id)->findOrFail($id);
        $pricing->update(['is_active' => !$pricing->is_active]);
        $this->refreshKey++;
    }

    public function getDayTypePricings()
    {
        return DayTypePricing::where('hotel_id', $this->hotel->id)
            ->orderBy('day_type_name')
            ->get();
    }

    // ==================== PROMOTIONAL DISCOUNTS METHODS ====================

    protected function promotionRules()
    {
        return [
            'promotionName' => 'required|string|max:255',
            'promotionDescription' => 'nullable|string',
            'promotionDiscountType' => 'required|in:fixed,percentage',
            'promotionDiscountValue' => 'required|numeric|min:0',
            'promotionStartDate' => 'nullable|date',
            'promotionEndDate' => 'nullable|date|after_or_equal:promotionStartDate',
            'promotionMinimumRooms' => 'nullable|integer|min:1',
            'promotionMaximumRooms' => 'nullable|integer|min:' . ($this->promotionMinimumRooms ?: 1),
            'promotionMinimumNights' => 'nullable|integer|min:1',
            'promotionMaximumNights' => 'nullable|integer|min:' . ($this->promotionMinimumNights ?: 1),
            'promotionBookingAdvanceDays' => 'nullable|integer|min:1',
            'promotionApplicableRoomTypes' => 'nullable|array',
            'promotionPromoCode' => 'nullable|string|max:50',
            'promotionPriority' => 'required|integer|min:0|max:100',
            'promotionIsActive' => 'boolean',
        ];
    }

    public function openPromotionForm()
    {
        $this->showPromotionForm = true;
        $this->reset([
            'promotionName', 'promotionDescription', 'promotionDiscountType', 'promotionDiscountValue',
            'promotionStartDate', 'promotionEndDate', 'promotionMinimumRooms', 'promotionMaximumRooms',
            'promotionMinimumNights', 'promotionMaximumNights', 'promotionBookingAdvanceDays',
            'promotionApplicableRoomTypes', 'promotionPromoCode', 'promotionPriority', 'editingPromotionId'
        ]);
        $this->promotionDiscountType = 'percentage';
        $this->promotionPriority = 0;
        $this->promotionIsActive = true;
    }

    public function closePromotionForm()
    {
        $this->showPromotionForm = false;
        $this->reset([
            'promotionName', 'promotionDescription', 'promotionDiscountType', 'promotionDiscountValue',
            'promotionStartDate', 'promotionEndDate', 'promotionMinimumRooms', 'promotionMaximumRooms',
            'promotionMinimumNights', 'promotionMaximumNights', 'promotionBookingAdvanceDays',
            'promotionApplicableRoomTypes', 'promotionPromoCode', 'promotionPriority',
            'promotionIsActive', 'editingPromotionId'
        ]);
    }

    public function savePromotion()
    {
        $this->validate($this->promotionRules());

        $data = [
            'promotion_name' => $this->promotionName,
            'promotion_description' => $this->promotionDescription,
            'discount_type' => $this->promotionDiscountType,
            'discount_value' => $this->promotionDiscountValue,
            'start_date' => $this->promotionStartDate ?: null,
            'end_date' => $this->promotionEndDate ?: null,
            'minimum_rooms' => $this->promotionMinimumRooms ?: null,
            'maximum_rooms' => $this->promotionMaximumRooms ?: null,
            'minimum_nights' => $this->promotionMinimumNights ?: null,
            'maximum_nights' => $this->promotionMaximumNights ?: null,
            'booking_advance_days' => $this->promotionBookingAdvanceDays ?: null,
            'applicable_room_types' => !empty($this->promotionApplicableRoomTypes) ? $this->promotionApplicableRoomTypes : null,
            'promo_code' => $this->promotionPromoCode ?: null,
            'priority' => $this->promotionPriority,
            'is_active' => $this->promotionIsActive,
        ];

        if ($this->editingPromotionId) {
            $promotion = PromotionalDiscount::where('hotel_id', $this->hotel->id)
                ->findOrFail($this->editingPromotionId);

            $promotion->update($data);

            session()->flash('success', 'Promotional discount updated successfully!');
        } else {
            $data['hotel_id'] = $this->hotel->id;
            PromotionalDiscount::create($data);

            session()->flash('success', 'Promotional discount created successfully!');
        }

        $this->refreshKey++;
        $this->closePromotionForm();
    }

    public function editPromotion($id)
    {
        $promotion = PromotionalDiscount::where('hotel_id', $this->hotel->id)->findOrFail($id);

        $this->editingPromotionId = $promotion->id;
        $this->promotionName = $promotion->promotion_name;
        $this->promotionDescription = $promotion->promotion_description;
        $this->promotionDiscountType = $promotion->discount_type;
        $this->promotionDiscountValue = $promotion->discount_value;
        $this->promotionStartDate = $promotion->start_date?->format('Y-m-d') ?? '';
        $this->promotionEndDate = $promotion->end_date?->format('Y-m-d') ?? '';
        $this->promotionMinimumRooms = $promotion->minimum_rooms ?? '';
        $this->promotionMaximumRooms = $promotion->maximum_rooms ?? '';
        $this->promotionMinimumNights = $promotion->minimum_nights ?? '';
        $this->promotionMaximumNights = $promotion->maximum_nights ?? '';
        $this->promotionBookingAdvanceDays = $promotion->booking_advance_days ?? '';
        $this->promotionApplicableRoomTypes = $promotion->applicable_room_types ?? [];
        $this->promotionPromoCode = $promotion->promo_code ?? '';
        $this->promotionPriority = $promotion->priority;
        $this->promotionIsActive = $promotion->is_active;
        $this->showPromotionForm = true;
    }

    public function confirmDeletePromotion($id)
    {
        $this->deletingPromotionId = $id;
    }

    public function deletePromotion()
    {
        if (!$this->deletingPromotionId) {
            return;
        }

        $promotion = PromotionalDiscount::where('hotel_id', $this->hotel->id)
            ->findOrFail($this->deletingPromotionId);

        $promotion->delete();

        session()->flash('success', 'Promotional discount deleted successfully!');
        $this->deletingPromotionId = null;
        $this->refreshKey++;
    }

    public function togglePromotionStatus($id)
    {
        $promotion = PromotionalDiscount::where('hotel_id', $this->hotel->id)->findOrFail($id);
        $promotion->update(['is_active' => !$promotion->is_active]);
        $this->refreshKey++;
    }

    public function getPromotions()
    {
        return PromotionalDiscount::where('hotel_id', $this->hotel->id)
            ->orderBy('priority', 'desc')
            ->orderBy('promotion_name')
            ->get();
    }

    #[Layout('layouts.hotel')]
    public function render()
    {
        return view('livewire.hotel.pricing.manage', [
            'assignedHotels' => $this->assignedHotels,
        ]);
    }
}
