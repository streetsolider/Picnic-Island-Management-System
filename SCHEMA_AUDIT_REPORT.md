# Theme Park Database Schema Audit Report
**Date:** 2025-12-05
**Purpose:** Simplify activity booking system to support flexible walk-up access for continuous rides

---

## Executive Summary

The current theme park ticketing system is over-engineered with rigid scheduling that doesn't match real-world operations. The main issue is the `theme_park_activity_schedules` table which enforces time-slot booking for ALL activities, when only scheduled shows should require this.

**Key Changes Needed:**
- Remove rigid session scheduling for continuous rides (bumper cars, carousel, etc.)
- Add activity type distinction (continuous_ride vs scheduled_show)
- Remove capacity enforcement when purchasing tickets for continuous rides
- Simplify to: Buy ticket → Walk to ride → Show QR → Operator admits when space available

---

## Current Schema Overview

### 1. **theme_park_zones** ✅ KEEP AS-IS
```sql
id
name                          -- Zone instance name
zone_type                     -- Zone type (Adventure, Water Park, etc.)
description
capacity_limit
opening_time
closing_time
assigned_staff_id            -- Theme Park Staff assigned to zone
is_active
timestamps
```
**Status:** This table is fine. Zones group activities together.

---

### 2. **theme_park_activities** ⚠️ MODIFY

**Current Structure:**
```sql
id
theme_park_zone_id           ✅ Keep
assigned_staff_id            ❓ Evaluate (staff assigned to zone, not individual activity?)
name                         ✅ Keep
description                  ✅ Keep
ticket_cost                  ⚠️ Rename or clarify (tickets as currency)
ticket_price_mvr             ⚠️ Clarify relationship with ticket_cost
capacity_per_session         ⚠️ Keep for reference only, not enforcement
duration_minutes             ✅ Keep
min_age                      ✅ Keep
max_age                      ✅ Keep
height_requirement_cm        ✅ Keep
is_active                    ✅ Keep
timestamps                   ✅ Keep
```

**Problems Identified:**
1. ❌ No `activity_type` field to distinguish continuous rides from scheduled shows
2. ❓ Confusing dual pricing: `ticket_cost` (tickets) vs `ticket_price_mvr` (MVR)
3. ⚠️ `capacity_per_session` suggests session-based booking (wrong for continuous rides)
4. ❌ Missing `operating_hours_start` and `operating_hours_end` for individual activities
5. ❓ `assigned_staff_id` - Should staff be assigned to zone or individual activity?

**Recommended Changes:**
```sql
ADD: activity_type ENUM('continuous_ride', 'scheduled_show') NOT NULL
ADD: operating_hours_start TIME NULL
ADD: operating_hours_end TIME NULL
RENAME: capacity_per_session → capacity (for operator reference)
CLARIFY: Pricing model (see recommendations below)
REMOVE: assigned_staff_id (staff assigned at zone level, not activity level)
```

---

### 3. **theme_park_activity_schedules** ❌ MAJOR PROBLEM

**Current Structure:**
```sql
id
activity_id
schedule_date
start_time
end_time
available_slots              ❌ Enforces capacity at booking time
booked_slots                 ❌ Tracks bookings, prevents walk-up access
timestamps
UNIQUE(activity_id, schedule_date, start_time)
```

**Problems:**
1. ❌ Forces ALL activities to have rigid time-slot scheduling
2. ❌ Enforces capacity limits at booking time (should be operator's decision for rides)
3. ❌ Prevents flexible walk-up access for continuous rides
4. ❌ Makes simple rides (bumper cars) overly complex

**This is the ROOT CAUSE of over-engineering!**

**Recommended Solution:**

**Option A: Create Separate Table for Shows Only**
```sql
-- Keep this table ONLY for scheduled shows
-- Rename to: theme_park_show_schedules
-- Add constraint: activity.activity_type must be 'scheduled_show'

CREATE TABLE theme_park_show_schedules (
    id
    activity_id                -- FK to activities where activity_type = 'scheduled_show'
    show_date
    show_time
    venue_capacity
    tickets_sold               -- Enforced at purchase time
    timestamps
    UNIQUE(activity_id, show_date, show_time)
)
```

**Option B: Add Type Filter to Existing Table**
```sql
-- Add activity_type check
-- Only populate schedules for activities where activity_type = 'scheduled_show'
-- Continuous rides don't need schedule records at all

-- This is less clean than Option A
```

**Recommendation:** **Use Option A** - create separate `theme_park_show_schedules` table

---

### 4. **theme_park_ticket_redemptions** ⚠️ MODIFY & RENAME

**Current Structure:**
```sql
id
user_id                      ✅ Keep
activity_id                  ✅ Keep
tickets_redeemed             ⚠️ Rename to quantity or tickets_used
number_of_persons            ✅ Keep
status                       ✅ Keep ('pending', 'validated', 'cancelled')
validated_by                 ✅ Keep (staff who scanned QR)
validated_at                 ✅ Keep
redemption_reference         ✅ Keep (QR code identifier)
cancellation_reason          ✅ Keep
timestamps                   ✅ Keep
```

**Problems:**
1. ❌ Missing: `show_schedule_id` for shows (which showtime was booked)
2. ❌ Missing: `purchase_datetime` (when ticket was purchased)
3. ❌ Missing: `valid_until` (ticket expiry - day-specific or unlimited?)
4. ⚠️ Ambiguous name: "redemption" suggests already used, but includes "pending" status

**Recommended Changes:**
```sql
RENAME TABLE: theme_park_ticket_redemptions → theme_park_activity_tickets

ADD: show_schedule_id (FK to theme_park_show_schedules, NULL for continuous rides)
ADD: purchase_datetime TIMESTAMP NOT NULL
ADD: valid_until TIMESTAMP NULL (for day-specific tickets)
ADD: total_credits_paid DECIMAL(10,2) (for audit trail)
RENAME: tickets_redeemed → tickets_used
RENAME: redemption_reference → ticket_reference or qr_code
UPDATE: status ENUM('valid', 'redeemed', 'expired', 'cancelled')
```

**New Structure:**
```sql
CREATE TABLE theme_park_activity_tickets (
    id
    user_id
    activity_id
    show_schedule_id          -- NULL for continuous rides, set for shows
    tickets_used              -- How many tickets were spent
    number_of_persons         -- How many people this ticket is for
    total_credits_paid        -- Audit trail
    status                    -- 'valid', 'redeemed', 'expired', 'cancelled'
    ticket_reference          -- QR code (e.g., TPT-ABC12345)
    purchase_datetime
    valid_until               -- NULL = valid indefinitely, or specific date/time
    redeemed_by_staff_id      -- Was validated_by
    redeemed_at               -- Was validated_at
    cancellation_reason
    timestamps
)
```

---

### 5. **theme_park_wallets** ⚠️ CLARIFY CURRENCY MODEL

**Current Structure:**
```sql
id
user_id
balance_mvr                  -- Maldivian Rufiyaa (real money)
ticket_balance               -- Virtual ticket credits
total_topped_up_mvr
total_tickets_purchased
total_tickets_redeemed
timestamps
```

**Questions to Clarify:**
1. ❓ What is the currency flow?
   - **Option A:** Top up MVR → Buy tickets with MVR → Redeem tickets for activities
   - **Option B:** Top up MVR → Directly purchase activities with MVR (no intermediate tickets)
   - **Current:** Seems to be Option A (dual currency: MVR + tickets)

2. ❓ Why dual currency instead of single currency?
   - Tickets as virtual credits might be for promotional bundles (e.g., "Buy 100 tickets, get 20 free")
   - Or tickets might have different pricing than 1 ticket = 1 MVR

3. ❓ What does `ticket_cost` in activities table represent?
   - Cost in ticket credits (e.g., bumper cars = 2 tickets)
   - And `ticket_price_mvr` = direct MVR price?

**Recommendation Based on User's Description:**

User said: *"I topup my wallet. I go near the bumper cars. I check the app and it says two tickets per person."*

This suggests:
- Top up wallet with credits (could call them "tickets" or "credits")
- Activities cost X credits per person
- Buy activity ticket by spending credits
- Show QR to operator

**Simplified Model (Single Currency):**
```sql
theme_park_wallets:
- balance (credits/tickets - single currency)
- total_topped_up
- total_spent
- total_redeemed

theme_park_activities:
- price_per_person (credits per person)

-- When purchasing:
-- Cost = activity.price_per_person × number_of_persons
-- Deduct from wallet balance
-- Create ticket with QR code
```

**Current Dual Currency Model:**
If keeping dual currency (MVR for top-up, tickets for activities):
```sql
User Journey:
1. Top up wallet: Add MVR (real money)
2. Buy ticket credits: Exchange MVR for tickets (e.g., 1 MVR = 1 ticket, or bulk discounts)
3. Purchase activity: Spend tickets
4. Redeem: Show QR at activity

theme_park_wallets:
- balance_mvr (money in wallet)
- ticket_balance (credits purchased with MVR)

theme_park_activities:
- ticket_cost (credits per person)

theme_park_wallet_transactions:
- top_up: Add MVR
- ticket_purchase: Convert MVR → tickets
- activity_purchase: Spend tickets (new transaction type needed!)
```

**Issue:** Current transactions table doesn't have "activity_purchase" type!

```sql
-- Current:
transaction_type ENUM('top_up', 'ticket_purchase')

-- Should be:
transaction_type ENUM('top_up', 'activity_ticket_purchase')
-- OR separate tickets_amount into tickets_purchased vs tickets_spent
```

**Recommendation:** Clarify with user which model they prefer:
1. **Single currency model** (simpler): Credits in wallet → Spend on activities
2. **Dual currency model** (current): MVR → Buy ticket credits → Spend tickets on activities

---

### 6. **theme_park_wallet_transactions** ⚠️ INCOMPLETE

**Current Structure:**
```sql
id
user_id
transaction_type             ⚠️ Missing activity_ticket_purchase type
amount_mvr                   -- For top-ups
tickets_amount               -- For ticket purchases
balance_before_mvr
balance_after_mvr
balance_before_tickets
balance_after_tickets
transaction_reference
payment_method
payment_reference
timestamps
```

**Problems:**
1. ❌ No transaction type for purchasing activity tickets
2. ⚠️ `tickets_amount` is ambiguous (tickets purchased or tickets spent?)

**Recommended Changes:**
```sql
UPDATE: transaction_type ENUM('top_up_mvr', 'purchase_ticket_credits', 'purchase_activity_ticket')

-- OR if simplified to single currency:
UPDATE: transaction_type ENUM('top_up', 'activity_purchase', 'refund')

ADD: activity_ticket_id (FK to theme_park_activity_tickets, NULL for top-ups)
```

---

### 7. **theme_park_settings** ✅ KEEP AS-IS
```sql
id
key
value
description
timestamps
```
**Status:** Global settings table, fine as-is.

---

## Proposed New Schema

### Summary of Changes:

| Action | Table | Reason |
|--------|-------|--------|
| ✅ Keep | `theme_park_zones` | Works fine |
| ⚠️ Modify | `theme_park_activities` | Add activity_type, operating_hours |
| ❌ Remove/Rename | `theme_park_activity_schedules` | Only for shows, not rides |
| ✅ Create | `theme_park_show_schedules` | Separate table for scheduled shows |
| ⚠️ Rename | `theme_park_ticket_redemptions` → `theme_park_activity_tickets` | Clearer naming |
| ⚠️ Modify | `theme_park_wallets` | Clarify currency model |
| ⚠️ Modify | `theme_park_wallet_transactions` | Add activity purchase type |

---

## Detailed New Schema Design

### **1. theme_park_activities** (Modified)
```sql
id
theme_park_zone_id
name
description
activity_type                ENUM('continuous_ride', 'scheduled_show') NOT NULL
price_per_person            DECIMAL(10,2) NOT NULL  -- Simplified pricing
capacity                    INT UNSIGNED  -- For operator reference, not enforced digitally
duration_minutes            INT UNSIGNED
operating_hours_start       TIME NULL  -- NULL = follows zone hours
operating_hours_end         TIME NULL  -- NULL = follows zone hours
min_age                     INT UNSIGNED NULL
max_age                     INT UNSIGNED NULL
height_requirement_cm       INT UNSIGNED NULL
is_active                   BOOLEAN DEFAULT true
timestamps

INDEX(theme_park_zone_id)
INDEX(activity_type)
INDEX(is_active)
```

**Changes:**
- ✅ Added `activity_type` to distinguish rides from shows
- ✅ Added `operating_hours_start/end` for individual activity hours
- ✅ Simplified pricing to single `price_per_person` field
- ✅ Removed `assigned_staff_id` (staff assigned at zone level)
- ✅ Renamed `capacity_per_session` → `capacity` (reference only)

---

### **2. theme_park_show_schedules** (New Table)
```sql
CREATE TABLE theme_park_show_schedules (
    id
    activity_id              -- FK to activities WHERE activity_type = 'scheduled_show'
    show_date                DATE NOT NULL
    show_time                TIME NOT NULL
    venue_capacity           INT UNSIGNED NOT NULL
    tickets_sold             INT UNSIGNED DEFAULT 0
    status                   ENUM('scheduled', 'cancelled', 'completed') DEFAULT 'scheduled'
    timestamps

    FOREIGN KEY (activity_id) REFERENCES theme_park_activities(id) ON DELETE CASCADE
    INDEX(activity_id)
    INDEX(show_date)
    UNIQUE(activity_id, show_date, show_time)
)
```

**Purpose:**
- Only for activities where `activity_type = 'scheduled_show'`
- Enforces capacity at booking time
- Tracks specific showtimes
- Continuous rides don't need this table at all

---

### **3. theme_park_activity_tickets** (Renamed & Modified)
```sql
CREATE TABLE theme_park_activity_tickets (
    id
    user_id                  -- Customer who purchased
    activity_id              -- Which activity
    show_schedule_id         -- NULL for rides, required for shows
    quantity                 -- Number of people
    credits_spent            -- How many credits were spent
    ticket_reference         -- QR code (e.g., TPT-ABC12345)
    status                   ENUM('valid', 'redeemed', 'expired', 'cancelled') DEFAULT 'valid'
    purchase_datetime        TIMESTAMP NOT NULL
    valid_until              TIMESTAMP NULL  -- For day-specific tickets
    redeemed_by_staff_id     -- Staff who validated
    redeemed_at              TIMESTAMP NULL
    cancellation_reason      TEXT NULL
    timestamps

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    FOREIGN KEY (activity_id) REFERENCES theme_park_activities(id) ON DELETE CASCADE
    FOREIGN KEY (show_schedule_id) REFERENCES theme_park_show_schedules(id) ON DELETE CASCADE
    FOREIGN KEY (redeemed_by_staff_id) REFERENCES users(id) ON DELETE SET NULL

    INDEX(user_id)
    INDEX(activity_id)
    INDEX(show_schedule_id)
    INDEX(status)
    INDEX(ticket_reference)
    UNIQUE(ticket_reference)
)
```

**Changes:**
- ✅ Renamed from `ticket_redemptions` to `activity_tickets`
- ✅ Added `show_schedule_id` for shows
- ✅ Added `purchase_datetime` and `valid_until`
- ✅ Added `credits_spent` for audit trail
- ✅ Renamed fields for clarity
- ✅ Updated status enum to match ticket lifecycle

---

### **4. theme_park_wallets** (Simplified - Pending User Confirmation)

**Option A: Single Currency (Recommended for Simplicity)**
```sql
CREATE TABLE theme_park_wallets (
    id
    user_id
    balance                  DECIMAL(10,2) DEFAULT 0.00  -- Single currency (credits)
    total_topped_up          DECIMAL(10,2) DEFAULT 0.00
    total_spent              DECIMAL(10,2) DEFAULT 0.00
    timestamps

    UNIQUE(user_id)
    INDEX(user_id)
)
```

**Option B: Keep Dual Currency (Current)**
```sql
-- Keep as-is, but clarify purpose:
-- balance_mvr = Real money in wallet (for future purchases)
-- ticket_balance = Credits purchased with MVR (spend on activities)
```

---

### **5. theme_park_wallet_transactions** (Modified)
```sql
CREATE TABLE theme_park_wallet_transactions (
    id
    user_id
    transaction_type         ENUM('top_up', 'activity_purchase', 'refund')
    activity_ticket_id       -- FK to theme_park_activity_tickets (NULL for top-ups)
    amount                   DECIMAL(10,2)
    balance_before           DECIMAL(10,2)
    balance_after            DECIMAL(10,2)
    transaction_reference    VARCHAR(255) UNIQUE
    payment_method           VARCHAR(255) NULL
    payment_reference        VARCHAR(255) NULL
    timestamps

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    FOREIGN KEY (activity_ticket_id) REFERENCES theme_park_activity_tickets(id) ON DELETE SET NULL

    INDEX(user_id)
    INDEX(transaction_type)
    INDEX(transaction_reference)
    INDEX(created_at)
)
```

---

## Migration Plan

### Phase 1: Add New Columns to Existing Tables ✅
```sql
-- Add activity_type to theme_park_activities
ALTER TABLE theme_park_activities
ADD COLUMN activity_type ENUM('continuous_ride', 'scheduled_show') NOT NULL DEFAULT 'continuous_ride' AFTER description,
ADD COLUMN operating_hours_start TIME NULL AFTER duration_minutes,
ADD COLUMN operating_hours_end TIME NULL AFTER operating_hours_start;

-- Add price_per_person (optional if clarifying pricing model)
ALTER TABLE theme_park_activities
ADD COLUMN price_per_person DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER activity_type;

-- Remove assigned_staff_id from activities (staff assigned at zone level)
ALTER TABLE theme_park_activities
DROP FOREIGN KEY theme_park_activities_assigned_staff_id_foreign,
DROP COLUMN assigned_staff_id;
```

### Phase 2: Create New Show Schedules Table ✅
```sql
CREATE TABLE theme_park_show_schedules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    activity_id BIGINT UNSIGNED NOT NULL,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    venue_capacity INT UNSIGNED NOT NULL,
    tickets_sold INT UNSIGNED DEFAULT 0,
    status ENUM('scheduled', 'cancelled', 'completed') DEFAULT 'scheduled',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (activity_id) REFERENCES theme_park_activities(id) ON DELETE CASCADE,
    INDEX(activity_id),
    INDEX(show_date),
    UNIQUE KEY show_unique(activity_id, show_date, show_time)
);
```

### Phase 3: Migrate Show Data (if any exists)
```sql
-- If there are existing scheduled shows in activity_schedules:
INSERT INTO theme_park_show_schedules (activity_id, show_date, show_time, venue_capacity, tickets_sold, created_at, updated_at)
SELECT
    activity_id,
    schedule_date,
    start_time,
    available_slots,
    booked_slots,
    created_at,
    updated_at
FROM theme_park_activity_schedules
WHERE activity_id IN (
    SELECT id FROM theme_park_activities WHERE activity_type = 'scheduled_show'
);
```

### Phase 4: Rename & Modify Ticket Redemptions Table ✅
```sql
-- Rename table
RENAME TABLE theme_park_ticket_redemptions TO theme_park_activity_tickets;

-- Add new columns
ALTER TABLE theme_park_activity_tickets
ADD COLUMN show_schedule_id BIGINT UNSIGNED NULL AFTER activity_id,
ADD COLUMN purchase_datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER redemption_reference,
ADD COLUMN valid_until TIMESTAMP NULL AFTER purchase_datetime,
ADD COLUMN credits_spent DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER number_of_persons;

-- Rename columns
ALTER TABLE theme_park_activity_tickets
CHANGE tickets_redeemed quantity INT UNSIGNED NOT NULL,
CHANGE redemption_reference ticket_reference VARCHAR(255) NOT NULL,
CHANGE validated_by redeemed_by_staff_id BIGINT UNSIGNED NULL,
CHANGE validated_at redeemed_at TIMESTAMP NULL;

-- Update status enum
ALTER TABLE theme_park_activity_tickets
MODIFY status ENUM('valid', 'redeemed', 'expired', 'cancelled') DEFAULT 'valid';

-- Add foreign key for show schedules
ALTER TABLE theme_park_activity_tickets
ADD FOREIGN KEY (show_schedule_id) REFERENCES theme_park_show_schedules(id) ON DELETE CASCADE;
```

### Phase 5: Drop Old Activity Schedules Table ⚠️
```sql
-- CAUTION: Only do this after confirming all data is migrated
-- and system is working with new structure

DROP TABLE theme_park_activity_schedules;
```

### Phase 6: Update Models & Code 🔧
- Update `ThemeParkActivity` model
- Update `ThemeParkActivitySchedule` model → `ThemeParkShowSchedule`
- Update `ThemeParkTicketRedemption` model → `ThemeParkActivityTicket`
- Update relationships and methods
- Update Livewire components (when built)

---

## Business Logic Changes

### For Continuous Rides (Bumper Cars, Carousel, etc.):

**OLD (Over-Engineered):**
```
1. Staff creates activity schedules in advance
   - Session 1: 2:00-2:10 PM (16 slots)
   - Session 2: 2:10-2:20 PM (16 slots)
   - etc.

2. Guest must:
   - Pick a specific session time
   - Check if slots are available
   - Book that specific time slot
   - Arrive exactly at that time

3. System tracks:
   - Available slots per session
   - Booked slots per session
   - Prevents booking if full

4. Problems:
   - Guest must plan ahead
   - What if they arrive early/late?
   - Staff can't be flexible
   - Doesn't match real theme parks
```

**NEW (Simplified):**
```
1. Staff creates activity once:
   - Name: Bumper Cars
   - Type: Continuous Ride
   - Price: 2 credits per person
   - Capacity: 16 (for operator reference)
   - Duration: 10 minutes
   - Operating Hours: 10 AM - 6 PM

2. Guest can:
   - Purchase ticket anytime via app
   - Walk to ride anytime during operating hours
   - Show QR code to operator
   - Operator admits when space available

3. System tracks:
   - Ticket purchased (QR created)
   - Ticket redeemed (when validated)
   - No session booking, no capacity enforcement

4. Benefits:
   - Flexible walk-up access
   - No advance planning needed
   - Operator manages capacity on-site
   - Matches real theme park operations
```

### For Scheduled Shows (Dolphin Show, Magic Show, etc.):

**Stays the same:**
```
1. Staff creates show schedules:
   - Date: December 5, 2025
   - Times: 11:00 AM, 2:00 PM, 5:00 PM
   - Venue capacity: 200 seats

2. Guest must:
   - Pick a specific showtime
   - System checks capacity
   - Book that showtime
   - Arrive before show starts

3. System enforces:
   - Capacity limits at booking time
   - Prevents overbooking
   - Tracks tickets sold per showtime

4. This makes sense for shows:
   - Fixed seating
   - Scheduled performance
   - Can't just "walk up" anytime
```

---

## Rollback Plan

If something goes wrong during migration:

1. **Keep backup of old tables:**
   ```sql
   CREATE TABLE theme_park_activity_schedules_backup AS SELECT * FROM theme_park_activity_schedules;
   CREATE TABLE theme_park_ticket_redemptions_backup AS SELECT * FROM theme_park_ticket_redemptions;
   ```

2. **Restore from backup if needed:**
   ```sql
   DROP TABLE theme_park_activity_schedules;
   CREATE TABLE theme_park_activity_schedules AS SELECT * FROM theme_park_activity_schedules_backup;
   -- Restore foreign keys and indexes
   ```

---

## Testing Checklist

After migration, test:

### Continuous Rides:
- ✅ Create bumper cars activity (type: continuous_ride)
- ✅ Guest purchases ticket via app (no capacity check)
- ✅ Guest shows QR code to operator
- ✅ Operator validates ticket successfully
- ✅ Ticket status changes to 'redeemed'
- ✅ Guest can purchase another ticket immediately
- ✅ No schedule required for continuous rides

### Scheduled Shows:
- ✅ Create dolphin show activity (type: scheduled_show)
- ✅ Staff creates show schedules (11 AM, 2 PM, 5 PM)
- ✅ Guest books specific showtime
- ✅ System enforces capacity (can't book if full)
- ✅ Guest shows QR code before show
- ✅ Operator validates ticket
- ✅ Ticket linked to specific show schedule

### Wallet:
- ✅ Guest tops up wallet
- ✅ Purchase activity ticket
- ✅ Balance deducted correctly
- ✅ Transaction recorded
- ✅ Refund works if ticket cancelled

---

## Questions for User

Before proceeding with migration:

1. **Currency Model:** Should we simplify to single currency (credits) or keep dual currency (MVR + tickets)?
   - Single: Top up credits → Spend credits on activities
   - Dual: Top up MVR → Buy ticket credits → Spend tickets on activities

2. **Ticket Validity:** Should activity tickets be:
   - Valid indefinitely until used?
   - Valid only for the purchase date?
   - Valid for X days after purchase?

3. **Staff Assignment:** Should staff be assigned to:
   - Zones (current recommendation)
   - Individual activities (current implementation)
   - Both?

4. **Pricing Model:** Should activities have:
   - Single price per person (recommended)
   - Ticket cost + MVR price (current dual pricing)

5. **Migration Timing:** When should we migrate?
   - Now (before system launch)
   - After testing current system
   - Phased approach

---

## Estimated Impact

### Database Changes:
- 3 tables modified
- 1 table created
- 1 table dropped (or repurposed)
- ~20 columns added/modified/removed

### Code Changes:
- 4 models updated
- All Livewire components for theme park (when built)
- Validation logic simplified
- Booking flow simplified

### Timeline:
- Migration creation: 2-3 hours
- Testing: 4-6 hours
- Code updates: 8-12 hours
- Total: ~2 days

---

## Recommendation

**Proceed with the simplified schema:**

1. ✅ Add `activity_type` to distinguish rides from shows
2. ✅ Create `theme_park_show_schedules` for scheduled performances only
3. ✅ Rename and enhance `theme_park_ticket_redemptions` → `theme_park_activity_tickets`
4. ✅ Remove rigid scheduling for continuous rides
5. ✅ Simplify pricing to single `price_per_person` field
6. ✅ Clarify wallet currency model with user

This will result in a much simpler, more maintainable system that matches real-world theme park operations.

---

**Generated:** 2025-12-05
**Next Steps:** Review with user → Get approval → Create migrations → Test → Deploy
