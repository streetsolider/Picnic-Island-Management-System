# Picnic Island - Design System & Theme Guide

## Design Philosophy

**Minimalist White Design** - Clean, spacious, and user-friendly interface that puts content first while maintaining visual appeal for a tourism/entertainment platform.

---

## Color Palette

### Primary Colors

#### Ocean Blue (Primary Brand Color)
Represents the ocean, ferry rides, and island adventure.

- `primary-50`: #f0f9ff - Lightest tint for backgrounds
- `primary-100`: #e0f2fe - Light backgrounds
- `primary-200`: #bae6fd - Hover states
- `primary-300`: #7dd3fc - Borders
- `primary-400`: #38bdf8 - Active states
- **`primary-500`: #0ea5e9** - Main brand color
- `primary-600`: #0284c7 - Hover states for buttons
- `primary-700`: #0369a1 - Active/pressed states
- `primary-800`: #075985 - Dark variations
- `primary-900`: #0c4a6e - Text on light backgrounds

**Usage**: Main CTAs, links, primary buttons, navigation highlights

---

#### Coral Sunset (Secondary Color)
Vibrant and energetic, representing fun activities and beach events.

- `secondary-50`: #fff7ed - Lightest tint
- `secondary-100`: #ffedd5 - Light backgrounds
- `secondary-200`: #fed7aa - Hover states
- `secondary-300`: #fdba74 - Borders
- `secondary-400`: #fb923c - Active states
- **`secondary-500`: #f97316** - Main secondary color
- `secondary-600`: #ea580c - Hover states
- `secondary-700`: #c2410c - Active states
- `secondary-800`: #9a3412 - Dark variations
- `secondary-900`: #7c2d12 - Text

**Usage**: Secondary buttons, promotional tags, featured events, accent elements

---

#### Island Teal (Accent Color)
Fresh and tropical, representing nature and island paradise.

- `accent-50`: #f0fdfa - Lightest tint
- `accent-100`: #ccfbf1 - Light backgrounds
- `accent-200`: #99f6e4 - Hover states
- `accent-300`: #5eead4 - Borders
- `accent-400`: #2dd4bf - Active states
- **`accent-500`: #14b8a6** - Main accent color
- `accent-600`: #0d9488 - Hover states
- `accent-700`: #0f766e - Active states
- `accent-800`: #115e59 - Dark variations
- `accent-900`: #134e4a - Text

**Usage**: Beach events, nature activities, success messages, highlights

---

### Neutral Colors (Grays)

Essential for a minimalist design - clean and sophisticated.

- `gray-50`: #fafafa - Page backgrounds
- `gray-100`: #f5f5f5 - Card backgrounds
- `gray-200`: #e5e5e5 - Borders
- `gray-300`: #d4d4d4 - Dividers
- `gray-400`: #a3a3a3 - Disabled states
- `gray-500`: #737373 - Placeholder text
- `gray-600`: #525252 - Secondary text
- `gray-700`: #404040 - Body text
- `gray-800`: #262626 - Headings
- `gray-900`: #171717 - Primary text

---

### Semantic Colors

#### Success (Green)
- `success-50`: #f0fdf4 - Background
- **`success-500`: #22c55e** - Main
- `success-600`: #16a34a - Hover
- `success-700`: #15803d - Active

**Usage**: Booking confirmations, successful payments, availability status

#### Warning (Amber)
- `warning-50`: #fffbeb - Background
- **`warning-500`: #f59e0b** - Main
- `warning-600`: #d97706 - Hover
- `warning-700`: #b45309 - Active

**Usage**: Capacity warnings, pending bookings, important notices

#### Error (Red)
- `error-50`: #fef2f2 - Background
- **`error-500`: #ef4444** - Main
- `error-600`: #dc2626 - Hover
- `error-700`: #b91c1c - Active

**Usage**: Validation errors, booking failures, unavailable dates

#### Info (Blue)
- `info-50`: #eff6ff - Background
- **`info-500`: #3b82f6** - Main
- `info-600`: #2563eb - Hover
- `info-700`: #1d4ed8 - Active

**Usage**: Information messages, tips, ferry schedules

---

## Typography

### Font Families

- **Sans Serif (Body)**: Inter - Clean, modern, highly readable
- **Display (Headings)**: Plus Jakarta Sans - Friendly and distinctive

### Font Sizes

```css
h1: text-4xl md:text-5xl (36px/48px)
h2: text-3xl md:text-4xl (30px/36px)
h3: text-2xl md:text-3xl (24px/30px)
h4: text-xl md:text-2xl (20px/24px)
```

### Font Weights
- Regular: 400 (body text)
- Medium: 500 (buttons, labels)
- Semibold: 600 (headings, emphasis)
- Bold: 700 (important headings)

---

## Spacing System

Consistent spacing creates visual rhythm and hierarchy.

- `xs`: 0.5rem (8px)
- `sm`: 0.75rem (12px)
- `md`: 1rem (16px)
- `lg`: 1.5rem (24px)
- `xl`: 2rem (32px)
- `2xl`: 3rem (48px)

---

## Border Radius

Soft, rounded corners for a friendly, modern feel.

- `sm`: 0.375rem (6px) - Small elements
- `md`: 0.5rem (8px) - Inputs
- `lg`: 0.75rem (12px) - Buttons
- `xl`: 1rem (16px) - Cards
- `2xl`: 1.5rem (24px) - Large containers
- `full`: 9999px - Pills, badges

---

## Shadows

Subtle shadows for depth in a minimalist design.

- `sm`: Subtle - Inputs, small cards
- `md`: Standard - Cards, dropdowns
- `lg`: Elevated - Modals, important cards
- `xl`: Floating - Overlays, popovers

---

## Component Classes

### Buttons

```html
<!-- Primary Button -->
<button class="btn btn-primary">Book Now</button>

<!-- Secondary Button -->
<button class="btn btn-secondary">View Details</button>

<!-- Outline Button -->
<button class="btn btn-outline">Learn More</button>

<!-- Ghost Button -->
<button class="btn btn-ghost">Cancel</button>
```

### Cards

```html
<!-- Basic Card -->
<div class="card">
    <div class="p-6">
        Content here
    </div>
</div>

<!-- Hoverable Card -->
<div class="card card-hover">
    <div class="p-6">
        Interactive content
    </div>
</div>
```

### Inputs

```html
<!-- Text Input -->
<input type="text" class="input" placeholder="Enter your name">

<!-- With Label -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Email Address
    </label>
    <input type="email" class="input" placeholder="you@example.com">
</div>
```

### Badges

```html
<!-- Status Badges -->
<span class="badge badge-primary">Available</span>
<span class="badge badge-success">Confirmed</span>
<span class="badge badge-warning">Limited</span>
<span class="badge badge-error">Sold Out</span>
```

---

## Usage Guidelines

### Backgrounds
- **Main**: White (#ffffff)
- **Subtle**: gray-50 (#fafafa)
- **Sections**: gray-100 (#f5f5f5)

### Text Hierarchy
- **Primary headings**: gray-900
- **Body text**: gray-700
- **Secondary text**: gray-600
- **Disabled text**: gray-400

### Interactive Elements
- **Primary actions**: primary-500 → primary-600 (hover)
- **Secondary actions**: secondary-500 → secondary-600 (hover)
- **Links**: primary-600 → primary-700 (hover)

### Status Indicators
- **Available/Open**: success-500
- **Limited/Pending**: warning-500
- **Unavailable/Closed**: error-500
- **Information**: info-500

---

## Role-Specific Color Recommendations

### Visitor Dashboard
- Primary: Ocean Blue (main navigation, booking CTAs)
- Accent: Island Teal (beach events, activities)
- Background: White with gray-50 sections

### Hotel Manager
- Primary: Ocean Blue (room management)
- Secondary: Coral (promotional offers)
- Cards: White with soft shadows

### Ferry Operator
- Primary: Ocean Blue (schedules, tickets)
- Success: Green (validated bookings)
- Info: Blue (passenger information)

### Theme Park Management
- Secondary: Coral (events, activities)
- Accent: Teal (beach events)
- Warning: Amber (capacity alerts)

### Admin Dashboard
- Neutral focus: Grays with primary accents
- All semantic colors for analytics
- Clean data visualization

---

## Accessibility

### Contrast Ratios (WCAG AA Compliant)
- All text colors meet 4.5:1 contrast ratio minimum
- Large text (18px+) meets 3:1 minimum
- Interactive elements have clear focus states

### Focus States
All interactive elements include visible focus rings:
```css
focus:ring-2 focus:ring-primary-200 focus:border-primary-500
```

---

## Examples

### Booking Card
```html
<div class="card card-hover">
    <img src="hotel.jpg" alt="Hotel" class="w-full h-48 object-cover">
    <div class="p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-2xl font-semibold text-gray-900">Ocean View Hotel</h3>
            <span class="badge badge-success">Available</span>
        </div>
        <p class="text-gray-600 mb-4">
            Luxurious beachfront accommodation with stunning views
        </p>
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold text-primary-600">$299/night</span>
            <button class="btn btn-primary">Book Now</button>
        </div>
    </div>
</div>
```

### Alert Messages
```html
<!-- Success -->
<div class="bg-success-50 border-l-4 border-success-500 p-4 rounded-lg">
    <p class="text-success-700 font-medium">Booking confirmed successfully!</p>
</div>

<!-- Warning -->
<div class="bg-warning-50 border-l-4 border-warning-500 p-4 rounded-lg">
    <p class="text-warning-700 font-medium">Only 3 spots remaining!</p>
</div>

<!-- Error -->
<div class="bg-error-50 border-l-4 border-error-500 p-4 rounded-lg">
    <p class="text-error-700 font-medium">Booking failed. Please try again.</p>
</div>
```

---

## Design Principles

1. **White Space**: Embrace generous spacing - don't cram content
2. **Visual Hierarchy**: Use size, weight, and color to guide users
3. **Consistency**: Maintain consistent spacing, sizing, and patterns
4. **Simplicity**: Remove unnecessary elements - less is more
5. **Accessibility**: Ensure all users can interact with the system
6. **Performance**: Keep animations smooth and subtle
7. **Mobile-First**: Design for mobile, enhance for desktop

---

## Next Steps

1. Import fonts (Inter & Plus Jakarta Sans) from Google Fonts
2. Build component library with Livewire components
3. Create reusable Blade components for common UI patterns
4. Test color contrast across all combinations
5. Implement dark mode variant (optional)
