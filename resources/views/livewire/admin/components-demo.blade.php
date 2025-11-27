<div>
    {{-- Toast Container --}}
    <x-ui.toast.toast-container>
        @if($showToast)
            <x-ui.toast.toast wire:key="toast-{{ $showToast }}" :type="$toastType" :title="$toastTitle"
                :message="$toastMessage" />
        @endif
    </x-ui.toast.toast-container>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Page Header --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">UI Component Library - Demo &
                        Documentation</h2>
                </div>
            </div>

            {{-- Introduction --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Component Library Overview</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        This page demonstrates all available UI components in the Picnic Island Management System.
                        All components are built with Tailwind CSS v4, support dark mode, and are fully compatible with
                        Livewire 3.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                            <h4 class="font-semibold text-indigo-900 dark:text-indigo-300 mb-2">✅ Completed</h4>
                            <p class="text-sm text-indigo-700 dark:text-indigo-400">Buttons (6 variants), Cards (4
                                types), Alerts (4 types)</p>
                        </div>
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <h4 class="font-semibold text-yellow-900 dark:text-yellow-300 mb-2">🚧 In Progress</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-400">Modals</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-300 mb-2">📋 Planned</h4>
                            <p class="text-sm text-gray-700 dark:text-gray-400">Badges, Tables, Skeletons</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Navigation Components</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Responsive navigation templates for top bar and sidebar layouts.
                    </p>

                    <div class="space-y-8">
                        {{-- Top Navigation Preview --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Top Navigation</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Horizontal navigation bar with logo, links, and mobile menu.
                            </p>

                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <x-ui.navigation.top-nav appName="Demo App">
                                    <x-slot:navigation>
                                        <x-ui.navigation.nav-link href="#"
                                            :active="true">Dashboard</x-ui.navigation.nav-link>
                                        <x-ui.navigation.nav-link href="#">Users</x-ui.navigation.nav-link>
                                        <x-ui.navigation.nav-link href="#">Settings</x-ui.navigation.nav-link>
                                    </x-slot:navigation>

                                    <x-slot:actions>
                                        <x-ui.badge.status :active="true" />
                                        <x-ui.theme.toggle />
                                    </x-slot:actions>

                                    <x-slot:mobileMenu>
                                        <a href="#"
                                            class="block px-4 py-2 text-base font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20">Dashboard</a>
                                        <a href="#"
                                            class="block px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Users</a>
                                        <a href="#"
                                            class="block px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
                                    </x-slot:mobileMenu>
                                </x-ui.navigation.top-nav>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                    &lt;x-ui.navigation.top-nav&gt;<br>
                                    &nbsp;&nbsp;&lt;x-slot:navigation&gt;<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&lt;x-ui.navigation.nav-link :active="true"&gt;Dashboard&lt;/x-ui.navigation.nav-link&gt;<br>
                                    &nbsp;&nbsp;&lt;/x-slot:navigation&gt;<br>
                                    &lt;/x-ui.navigation.top-nav&gt;
                                </code>
                            </div>
                        </div>

                        {{-- Side Navigation Preview --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Side Navigation</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Collapsible sidebar layout with navigation links and content area.
                            </p>

                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden h-96">
                                <x-ui.navigation.side-nav appName="Demo App">
                                    <x-ui.navigation.nav-link href="#" :active="true">
                                        <x-slot:icon>
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </x-slot:icon>
                                        Dashboard
                                    </x-ui.navigation.nav-link>
                                    <x-ui.navigation.nav-link href="#">
                                        <x-slot:icon>
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </x-slot:icon>
                                        Users
                                    </x-ui.navigation.nav-link>
                                    <x-ui.navigation.nav-link href="#">
                                        <x-slot:icon>
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </x-slot:icon>
                                        Settings
                                    </x-ui.navigation.nav-link>

                                    <x-slot:topBar>
                                        <div class="flex-1"></div>
                                        <x-ui.theme.toggle />
                                    </x-slot:topBar>

                                    <x-slot:content>
                                        <div class="p-6">
                                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Page
                                                Content</h1>
                                            <p class="text-gray-600 dark:text-gray-400">This is where your main content
                                                would go.</p>
                                        </div>
                                    </x-slot:content>
                                </x-ui.navigation.side-nav>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                    &lt;x-ui.navigation.side-nav&gt;<br>
                                    &nbsp;&nbsp;&lt;x-ui.navigation.nav-link :active="true"&gt;Dashboard&lt;/x-ui.navigation.nav-link&gt;<br>
                                    &nbsp;&nbsp;&lt;x-slot:content&gt;...&lt;/x-slot:content&gt;<br>
                                    &lt;/x-ui.navigation.side-nav&gt;
                                </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Button Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Button Components</h3>

                    {{-- Button Variants --}}
                    <div class="space-y-8">

                        {{-- Primary Buttons --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Primary Button
                                (Indigo)</h4>
                            <div class="flex flex-wrap gap-4 items-center">
                                <x-ui.button.primary>Default Primary</x-ui.button.primary>
                                <x-ui.button.primary size="sm">Small Primary</x-ui.button.primary>
                                <x-ui.button.primary size="lg">Large Primary</x-ui.button.primary>
                                <x-ui.button.primary disabled>Disabled</x-ui.button.primary>
                                <x-ui.button.primary :loading="$primaryLoading" wire:click="sampleAction('primary')">
                                    {{ $primaryLoading ? 'Loading...' : 'Click to Load' }}
                                </x-ui.button.primary>
                            </div>
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.button.primary&gt;Default Primary&lt;/x-ui.button.primary&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Secondary Buttons --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Secondary Button
                                (Gray)</h4>
                            <div class="flex flex-wrap gap-4 items-center">
                                <x-ui.button.secondary>Default Secondary</x-ui.button.secondary>
                                <x-ui.button.secondary size="sm">Small Secondary</x-ui.button.secondary>
                                <x-ui.button.secondary size="lg">Large Secondary</x-ui.button.secondary>
                                <x-ui.button.secondary disabled>Disabled</x-ui.button.secondary>
                                <x-ui.button.secondary :loading="$secondaryLoading"
                                    wire:click="sampleAction('secondary')">
                                    {{ $secondaryLoading ? 'Loading...' : 'Click to Load' }}
                                </x-ui.button.secondary>
                            </div>
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.button.secondary&gt;Default Secondary&lt;/x-ui.button.secondary&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Success Buttons --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Success Button
                                (Green)</h4>
                            <div class="flex flex-wrap gap-4 items-center">
                                <x-ui.button.success>Default Success</x-ui.button.success>
                                <x-ui.button.success size="sm">Small Success</x-ui.button.success>
                                <x-ui.button.success size="lg">Large Success</x-ui.button.success>
                                <x-ui.button.success disabled>Disabled</x-ui.button.success>
                                <x-ui.button.success :loading="$successLoading" wire:click="sampleAction('success')">
                                    {{ $successLoading ? 'Loading...' : 'Click to Load' }}
                                </x-ui.button.success>
                            </div>
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.button.success&gt;Default Success&lt;/x-ui.button.success&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Danger Buttons --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Danger Button (Red)
                            </h4>
                            <div class="flex flex-wrap gap-4 items-center">
                                <x-ui.button.danger>Default Danger</x-ui.button.danger>
                                <x-ui.button.danger size="sm">Small Danger</x-ui.button.danger>
                                <x-ui.button.danger size="lg">Large Danger</x-ui.button.danger>
                                <x-ui.button.danger disabled>Disabled</x-ui.button.danger>
                                <x-ui.button.danger :loading="$dangerLoading" wire:click="sampleAction('danger')">
                                    {{ $dangerLoading ? 'Loading...' : 'Click to Load' }}
                                </x-ui.button.danger>
                            </div>
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.button.danger&gt;Default Danger&lt;/x-ui.button.danger&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Warning Buttons --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Warning Button
                                (Orange)</h4>
                            <div class="flex flex-wrap gap-4 items-center">
                                <x-ui.button.warning>Default Warning</x-ui.button.warning>
                                <x-ui.button.warning size="sm">Small Warning</x-ui.button.warning>
                                <x-ui.button.warning size="lg">Large Warning</x-ui.button.warning>
                                <x-ui.button.warning disabled>Disabled</x-ui.button.warning>
                                <x-ui.button.warning :loading="$warningLoading" wire:click="sampleAction('warning')">
                                    {{ $warningLoading ? 'Loading...' : 'Click to Load' }}
                                </x-ui.button.warning>
                            </div>
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.button.warning&gt;Default Warning&lt;/x-ui.button.warning&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Link Buttons --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Link Button
                                (Transparent)</h4>
                            <div class="flex flex-wrap gap-4 items-center">
                                <x-ui.button.link href="#">Default Link</x-ui.button.link>
                                <x-ui.button.link href="#" size="sm">Small Link</x-ui.button.link>
                                <x-ui.button.link href="#" size="lg">Large Link</x-ui.button.link>
                                <x-ui.button.link disabled>Disabled</x-ui.button.link>
                                <x-ui.button.link href="{{ route('admin.dashboard') }}" wire:navigate>
                                    Link with Wire Navigate
                                </x-ui.button.link>
                            </div>
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.button.link href="#"&gt;Default Link&lt;/x-ui.button.link&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Button Type Comparison --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">All Variants (Medium
                                Size)</h4>
                            <div class="flex flex-wrap gap-4 items-center">
                                <x-ui.button.primary>Primary</x-ui.button.primary>
                                <x-ui.button.secondary>Secondary</x-ui.button.secondary>
                                <x-ui.button.success>Success</x-ui.button.success>
                                <x-ui.button.danger>Danger</x-ui.button.danger>
                                <x-ui.button.warning>Warning</x-ui.button.warning>
                                <x-ui.button.link href="#">Link</x-ui.button.link>
                            </div>
                        </div>

                        {{-- Button with Form Types --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Form Button Types
                            </h4>
                            <div class="flex flex-wrap gap-4 items-center">
                                <x-ui.button.primary type="button">Type: Button</x-ui.button.primary>
                                <x-ui.button.primary type="submit">Type: Submit</x-ui.button.primary>
                                <x-ui.button.secondary type="reset">Type: Reset</x-ui.button.secondary>
                            </div>
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.button.primary type="submit"&gt;Type: Submit&lt;/x-ui.button.primary&gt;
                            </code>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Card Components --}}
            <div class="bg-gray-50 dark:bg-gray-800/50 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Card Components</h3>

                    <div class="space-y-8">
                        {{-- Base Card Examples --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Base Card</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Flexible card component with
                                optional title, body, and footer slots.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Simple Card --}}
                                <x-ui.card.base>
                                    This is a simple card with just body content. No title or footer.
                                </x-ui.card.base>

                                {{-- Card with Title --}}
                                <x-ui.card.base>
                                    <x-slot name="title">Card with Title</x-slot>
                                    This card has a title slot. Perfect for section headings and organized content.
                                </x-ui.card.base>

                                {{-- Card with Footer --}}
                                <x-ui.card.base>
                                    <x-slot name="title">Card with Footer</x-slot>
                                    This card demonstrates the footer slot, useful for actions or metadata.
                                    <x-slot name="footer">
                                        <div class="flex justify-end gap-2">
                                            <x-ui.button.secondary size="sm">Cancel</x-ui.button.secondary>
                                            <x-ui.button.primary size="sm">Save</x-ui.button.primary>
                                        </div>
                                    </x-slot>
                                </x-ui.card.base>

                                {{-- Card with All Slots --}}
                                <x-ui.card.base>
                                    <x-slot name="title">Complete Card</x-slot>
                                    This card uses all available slots: title, body, and footer. It's great for forms or
                                    detailed content sections.
                                    <x-slot name="footer">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Last updated: 2 hours
                                            ago</span>
                                    </x-slot>
                                </x-ui.card.base>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.card.base&gt;<br>
                                &nbsp;&nbsp;&lt;x-slot name="title"&gt;Title&lt;/x-slot&gt;<br>
                                &nbsp;&nbsp;Body content here<br>
                                &nbsp;&nbsp;&lt;x-slot name="footer"&gt;Footer content&lt;/x-slot&gt;<br>
                                &lt;/x-ui.card.base&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Stat Cards - Badge Style --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Stat Card - Badge
                                Style</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Statistics cards with badge-style
                                indicators.</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <x-ui.card.stat label="Total Revenue" value="$45,231" change="+12.5%"
                                    changeType="increase" :badgeStyle="true" />
                                <x-ui.card.stat label="Active Users" value="2,345" change="-3.2%" changeType="decrease"
                                    :badgeStyle="true" color="blue" />
                                <x-ui.card.stat label="Pending Orders" value="156" change="No change"
                                    changeType="neutral" :badgeStyle="true" color="purple" />
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.card.stat label="Total Revenue" value="$45,231" change="+12.5%" changeType="increase" :badgeStyle="true" /&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Stat Cards - Side Icon Style --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Stat Card - Side
                                Icon Style</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Statistics cards with side icon
                                layout.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-ui.card.stat label="Total Sales" value="$125,430" change="+18.2%"
                                    changeType="increase" icon="📈" />
                                <x-ui.card.stat label="New Customers" value="892" change="+5.4%" changeType="increase"
                                    icon="👥" color="green" />
                                <x-ui.card.stat label="Support Tickets" value="23" change="-12%" changeType="decrease"
                                    icon="🎫" color="yellow" />
                                <x-ui.card.stat label="Server Uptime" value="99.9%" change="Stable" changeType="neutral"
                                    icon="⚡" color="indigo" />
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.card.stat label="Total Sales" value="$125,430" change="+18.2%" changeType="increase" icon="📈" /&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Info Cards --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Info Cards</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Informational cards with colored
                                left border accents.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-ui.card.info type="info" title="Information">
                                    This is an informational message. Use it for general notifications and helpful tips.
                                </x-ui.card.info>

                                <x-ui.card.info type="success" title="Success">
                                    Operation completed successfully! Your changes have been saved.
                                </x-ui.card.info>

                                <x-ui.card.info type="warning" title="Warning">
                                    Please review this carefully. Some settings may affect system performance.
                                </x-ui.card.info>

                                <x-ui.card.info type="danger" title="Error">
                                    An error occurred while processing your request. Please try again.
                                </x-ui.card.info>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.card.info type="success" title="Success"&gt;Message here&lt;/x-ui.card.info&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Empty State Cards --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Empty State Cards
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Cards for displaying empty states
                                and "no data" scenarios.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-ui.card.empty-state icon="📭" title="No Messages"
                                    description="You don't have any messages yet. Check back later!" />

                                <x-ui.card.empty-state icon="🎫" title="No Bookings Found"
                                    description="Start by creating your first booking to see it here.">
                                    <x-slot name="action">
                                        <x-ui.button.primary>Create Booking</x-ui.button.primary>
                                    </x-slot>
                                </x-ui.card.empty-state>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.card.empty-state icon="📭" title="No Messages" description="Check back later!" /&gt;
                            </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alert Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Alert Components</h3>

                    <div class="space-y-8">
                        {{-- Basic Alerts --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Basic Alerts</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Alert messages with default icons
                                for different notification types.</p>

                            <div class="space-y-4">
                                <x-ui.alert.info title="Information">
                                    This is an informational alert. Use it to provide helpful tips or general
                                    information to users.
                                </x-ui.alert.info>

                                <x-ui.alert.success title="Success">
                                    Operation completed successfully! Your changes have been saved and are now live.
                                </x-ui.alert.success>

                                <x-ui.alert.warning title="Warning">
                                    Please review this carefully. This action may have unintended consequences on system
                                    performance.
                                </x-ui.alert.warning>

                                <x-ui.alert.danger title="Error">
                                    An error occurred while processing your request. Please check your input and try
                                    again.
                                </x-ui.alert.danger>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.alert.success title="Success"&gt;Message here&lt;/x-ui.alert.success&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Dismissible Alerts --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Dismissible Alerts
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Alerts with a close button that can
                                be dismissed by users.</p>

                            <div class="space-y-4">
                                <x-ui.alert.info title="Dismissible Info" :dismissible="true">
                                    Click the X button on the right to dismiss this alert.
                                </x-ui.alert.info>

                                <x-ui.alert.success :dismissible="true">
                                    This success alert has no title but can still be dismissed.
                                </x-ui.alert.success>

                                <x-ui.alert.warning title="Temporary Warning" :dismissible="true">
                                    This warning will disappear once you dismiss it.
                                </x-ui.alert.warning>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.alert.info title="Title" :dismissible="true"&gt;Message&lt;/x-ui.alert.info&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Alerts Without Icons --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Alerts Without Icons
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Simple alerts without icons for a
                                cleaner look.</p>

                            <div class="space-y-4">
                                <x-ui.alert.info title="Simple Info" :icon="null">
                                    This alert has no icon, just the colored background and border.
                                </x-ui.alert.info>

                                <x-ui.alert.success :icon="null">
                                    Success message without an icon or title - very minimal.
                                </x-ui.alert.success>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.alert.info :icon="null"&gt;Message&lt;/x-ui.alert.info&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Using Base Alert --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Base Alert Component
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Use the base alert for more control
                                over type and custom icons.</p>

                            <div class="space-y-4">
                                <x-ui.alert.base type="info" title="Custom Alert">
                                    You can use the base alert component and specify the type manually.
                                </x-ui.alert.base>

                                <x-ui.alert.base type="success" icon="🎉" title="Custom Icon" :dismissible="true">
                                    Use custom emoji or SVG icons for unique alerts!
                                </x-ui.alert.base>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.alert.base type="success" icon="🎉" title="Title"&gt;Message&lt;/x-ui.alert.base&gt;
                            </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Modal Components</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Modals are overlay windows that sit on top of the main content. They are useful for focused
                        tasks, confirmations, or displaying detailed information.
                    </p>

                    <div class="space-y-8">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Base Modal</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                A flexible modal component with Alpine.js integration, multiple sizes, and accessibility
                                features.
                            </p>

                            <div class="flex flex-wrap gap-4">
                                {{-- Trigger Button --}}
                                <div x-data>
                                    <x-ui.button.primary x-on:click="$dispatch('open-modal', 'demo-modal')">
                                        Open Demo Modal
                                    </x-ui.button.primary>
                                </div>
                            </div>

                            {{-- The Modal Component --}}
                            <x-ui.modal.base name="demo-modal" title="Demo Modal Title" maxWidth="lg">
                                <div class="space-y-4">
                                    <p class="text-gray-600 dark:text-gray-400">
                                        This is a demonstration of the base modal component. It features:
                                    </p>
                                    <ul
                                        class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                        <li>Smooth fade and zoom transitions</li>
                                        <li>Backdrop blur effect</li>
                                        <li>Focus trapping for accessibility</li>
                                        <li>Close on ESC or backdrop click</li>
                                        <li>Dark mode support</li>
                                    </ul>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        You can customize the width, title, and footer actions.
                                    </p>
                                </div>

                                <x-slot name="footer">
                                    <div class="flex justify-end gap-3">
                                        <x-ui.button.secondary x-on:click="$dispatch('close')">
                                            Cancel
                                        </x-ui.button.secondary>
                                        <x-ui.button.primary x-on:click="$dispatch('close')">
                                            Confirm Action
                                        </x-ui.button.primary>
                                    </div>
                                </x-slot>
                            </x-ui.modal.base>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.button.primary x-on:click="$dispatch('open-modal', 'my-modal')"&gt;Open&lt;/x-ui.button.primary&gt;<br><br>
                                &lt;x-ui.modal.base name="my-modal" title="Title"&gt;<br>
                                &nbsp;&nbsp;Modal Content...<br>
                                &lt;/x-ui.modal.base&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Form Modal Example --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Form Modal</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                A specialized modal for forms with built-in submit/cancel buttons and loading states.
                            </p>

                            <div class="flex flex-wrap gap-4">
                                <div x-data>
                                    <x-ui.button.primary x-on:click="$dispatch('open-modal', 'form-modal')">
                                        Open Form Modal
                                    </x-ui.button.primary>
                                </div>
                            </div>

                            {{-- Form Modal Component --}}
                            <x-ui.modal.form name="form-modal" title="Create New Item" wire:submit="saveForm"
                                loading="saveForm" submitText="Create">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                        <input type="text" wire:model="formName"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                            placeholder="Enter name">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                        <input type="email" wire:model="formEmail"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                            placeholder="Enter email">
                                    </div>
                                </div>
                            </x-ui.modal.form>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                    &lt;x-ui.modal.form name="form-modal" title="Create" wire:submit="save" loading="save"&gt;<br>
                                    &nbsp;&nbsp;&lt;input wire:model="name" /&gt;<br>
                                    &lt;/x-ui.modal.form&gt;
                                </code>
                            </div>
                        </div>

                        {{-- Confirmation Modal Example --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Confirmation Modal
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                A pre-styled modal for dangerous actions with a warning icon and confirmation flow.
                            </p>

                            <div class="flex flex-wrap gap-4">
                                <div x-data>
                                    <x-ui.button.danger x-on:click="$dispatch('open-modal', 'confirm-modal')">
                                        Delete Item
                                    </x-ui.button.danger>
                                </div>
                            </div>

                            {{-- Confirmation Modal Component --}}
                            <x-ui.modal.confirmation name="confirm-modal" title="Delete Item?"
                                description="Are you sure you want to delete this item? This action cannot be undone and all associated data will be permanently removed."
                                method="deleteItem" confirmText="Yes, Delete" />

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                    &lt;x-ui.modal.confirmation<br>
                                    &nbsp;&nbsp;name="delete-modal"<br>
                                    &nbsp;&nbsp;title="Delete Item?"<br>
                                    &nbsp;&nbsp;description="This cannot be undone."<br>
                                    &nbsp;&nbsp;method="deleteItem"<br>
                                    /&gt;
                                </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Badge Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Badge Components</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Badges are small status indicators for displaying information like active/inactive states or
                        user roles.
                    </p>

                    <div class="space-y-8">
                        {{-- Status Badges --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Status Badge</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Display active/inactive states with color-coded pills.
                            </p>

                            <div class="flex flex-wrap gap-4 items-center">
                                <x-ui.badge.status :active="true" />
                                <x-ui.badge.status :active="false" />
                                <x-ui.badge.status :active="true" activeText="Online" />
                                <x-ui.badge.status :active="false" inactiveText="Offline" />
                                <x-ui.badge.status :active="true" clickable wire:click="$set('showToast', uniqid())" />
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.badge.status :active="true" /&gt;<br>
                                &lt;x-ui.badge.status :active="false" /&gt;<br>
                                &lt;x-ui.badge.status :active="true" clickable wire:click="toggle" /&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Role Badges --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Role Badge</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Color-coded badges for different staff roles.
                            </p>

                            <div class="flex flex-wrap gap-3 items-center">
                                <x-ui.badge.role role="administrator" />
                                <x-ui.badge.role role="hotel_manager" />
                                <x-ui.badge.role role="ferry_operator" />
                                <x-ui.badge.role role="theme_park_staff" />
                                <x-ui.badge.role role="beach_staff" />
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.badge.role role="administrator" /&gt;<br>
                                &lt;x-ui.badge.role role="hotel_manager" /&gt;<br>
                                &lt;x-ui.badge.role :role="$staff-&gt;role" /&gt;
                            </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Table Components</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Responsive table components with striped rows, hover effects, and sortable headers.
                    </p>

                    <div class="space-y-8">
                        {{-- Basic Table --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Basic Table</h4>

                            <x-ui.table.wrapper>
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <x-ui.table.header>Name</x-ui.table.header>
                                        <x-ui.table.header>Email</x-ui.table.header>
                                        <x-ui.table.header>Role</x-ui.table.header>
                                        <x-ui.table.header>Status</x-ui.table.header>
                                    </tr>
                                </thead>
                                <tbody>
                                    <x-ui.table.row>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            John Doe</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            john@example.com</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-ui.badge.role
                                                role="administrator" /></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-ui.badge.status
                                                :active="true" /></td>
                                    </x-ui.table.row>
                                    <x-ui.table.row>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Jane Smith</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            jane@example.com</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-ui.badge.role
                                                role="hotel_manager" /></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-ui.badge.status
                                                :active="true" /></td>
                                    </x-ui.table.row>
                                    <x-ui.table.row>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Bob Johnson</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            bob@example.com</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-ui.badge.role
                                                role="ferry_operator" /></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-ui.badge.status
                                                :active="false" /></td>
                                    </x-ui.table.row>
                                </tbody>
                            </x-ui.table.wrapper>
                        </div>

                        {{-- Striped & Hoverable Table --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Striped & Hoverable
                                Table</h4>

                            <x-ui.table.wrapper striped hoverable>
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <x-ui.table.header sortable direction="asc">Name</x-ui.table.header>
                                        <x-ui.table.header sortable>Department</x-ui.table.header>
                                        <x-ui.table.header>Status</x-ui.table.header>
                                    </tr>
                                </thead>
                                <tbody>
                                    <x-ui.table.row clickable wire:click="$set('showToast', uniqid())">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Alice Williams</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            Theme Park</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-ui.badge.status
                                                :active="true" /></td>
                                    </x-ui.table.row>
                                    <x-ui.table.row clickable selected>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Charlie Brown</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            Beach Services</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-ui.badge.status
                                                :active="true" /></td>
                                    </x-ui.table.row>
                                    <x-ui.table.row clickable>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Diana Prince</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            Hotel Management</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-ui.badge.status
                                                :active="false" /></td>
                                    </x-ui.table.row>
                                </tbody>
                            </x-ui.table.wrapper>
                        </div>

                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                            <code class="text-sm text-gray-800 dark:text-gray-200">
                            &lt;x-ui.table.wrapper striped hoverable&gt;<br>
                            &nbsp;&nbsp;&lt;thead&gt;<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;tr&gt;<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;x-ui.table.header sortable&gt;Name&lt;/x-ui.table.header&gt;<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;<br>
                            &nbsp;&nbsp;&lt;/thead&gt;<br>
                            &nbsp;&nbsp;&lt;tbody&gt;<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&lt;x-ui.table.row clickable&gt;...&lt;/x-ui.table.row&gt;<br>
                            &nbsp;&nbsp;&lt;/tbody&gt;<br>
                            &lt;/x-ui.table.wrapper&gt;
                        </code>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Skeleton Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Skeleton Components</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Loading skeletons provide visual feedback while content is being fetched.
                    </p>

                    <div class="space-y-8">
                        {{-- Table Skeleton --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Table Skeleton</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Use while loading table data.
                            </p>

                            <x-ui.skeleton.table />

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.skeleton.table /&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Card Grid Skeleton --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Card Grid Skeleton
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Use while loading stat cards or dashboard widgets.
                            </p>

                            <x-ui.skeleton.card-grid />

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.skeleton.card-grid /&gt;
                            </code>
                            </div>
                        </div>

                        {{-- Form Skeleton --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Form Skeleton</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Use while loading form fields.
                            </p>

                            <x-ui.skeleton.form />

                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.skeleton.form /&gt;
                            </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Toast Notifications --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Toast Notifications</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Toast notifications are popup alerts that slide in from the top-right corner of the screen.
                        They auto-dismiss after 5 seconds and can be manually closed by clicking the X button.
                    </p>

                    <div class="space-y-6">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Try Toast
                                Notifications</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Click the buttons below to see toast notifications appear from the top-right corner:
                            </p>

                            <div class="flex flex-wrap gap-4">
                                <button wire:click="testToast" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    TEST (No Params)
                                </button>

                                <button wire:click="triggerToast('info')" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Show Info Toast
                                </button>

                                <button wire:click="triggerToast('success')" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Show Success Toast
                                </button>

                                <button wire:click="triggerToast('warning')" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    Show Warning Toast
                                </button>

                                <button wire:click="triggerToast('danger')" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Show Error Toast
                                </button>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Usage Example</h4>

                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                {{-- In your Livewire component --}}<br>
                                &lt;x-ui.toast.toast-container&gt;<br>
                                &nbsp;&nbsp;@if($showToast)<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&lt;x-ui.toast.toast<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;type="success"<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;title="Success!"<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;message="Operation completed"<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;/&gt;<br>
                                &nbsp;&nbsp;@endif<br>
                                &lt;/x-ui.toast.toast-container&gt;
                            </code>
                            </div>

                            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-sm text-blue-800 dark:text-blue-300">
                                    <strong>Note:</strong> Toast notifications automatically slide in from the
                                    top-right,
                                    stay visible for 5 seconds (configurable via <code
                                        class="bg-blue-100 dark:bg-blue-800 px-1 rounded">duration</code> prop),
                                    and can be dismissed manually. They stack on top of each other when multiple toasts
                                    are shown.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>