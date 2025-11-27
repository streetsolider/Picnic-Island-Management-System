<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- Page Header --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">UI Component Library - Demo & Documentation</h2>
            </div>
        </div>

        {{-- Introduction --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Component Library Overview</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    This page demonstrates all available UI components in the Picnic Island Management System.
                    All components are built with Tailwind CSS v4, support dark mode, and are fully compatible with Livewire 3.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                        <h4 class="font-semibold text-indigo-900 dark:text-indigo-300 mb-2">✅ Completed</h4>
                        <p class="text-sm text-indigo-700 dark:text-indigo-400">Buttons (6 variants), Cards (4 types)</p>
                    </div>
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <h4 class="font-semibold text-yellow-900 dark:text-yellow-300 mb-2">🚧 In Progress</h4>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">Alerts, Modals</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-300 mb-2">📋 Planned</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-400">Badges, Tables, Skeletons</p>
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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Primary Button (Indigo)</h4>
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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Secondary Button (Gray)</h4>
                        <div class="flex flex-wrap gap-4 items-center">
                            <x-ui.button.secondary>Default Secondary</x-ui.button.secondary>
                            <x-ui.button.secondary size="sm">Small Secondary</x-ui.button.secondary>
                            <x-ui.button.secondary size="lg">Large Secondary</x-ui.button.secondary>
                            <x-ui.button.secondary disabled>Disabled</x-ui.button.secondary>
                            <x-ui.button.secondary :loading="$secondaryLoading" wire:click="sampleAction('secondary')">
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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Success Button (Green)</h4>
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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Danger Button (Red)</h4>
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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Warning Button (Orange)</h4>
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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Link Button (Transparent)</h4>
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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">All Variants (Medium Size)</h4>
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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Form Button Types</h4>
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
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Flexible card component with optional title, body, and footer slots.</p>

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
                                This card uses all available slots: title, body, and footer. It's great for forms or detailed content sections.
                                <x-slot name="footer">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Last updated: 2 hours ago</span>
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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Stat Card - Badge Style</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Statistics cards with badge-style indicators.</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-ui.card.stat
                                label="Total Revenue"
                                value="$45,231"
                                change="+12.5%"
                                changeType="increase"
                                :badgeStyle="true"
                            />
                            <x-ui.card.stat
                                label="Active Users"
                                value="2,345"
                                change="-3.2%"
                                changeType="decrease"
                                :badgeStyle="true"
                                color="blue"
                            />
                            <x-ui.card.stat
                                label="Pending Orders"
                                value="156"
                                change="No change"
                                changeType="neutral"
                                :badgeStyle="true"
                                color="purple"
                            />
                        </div>

                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded">
                            <code class="text-sm text-gray-800 dark:text-gray-200">
                                &lt;x-ui.card.stat label="Total Revenue" value="$45,231" change="+12.5%" changeType="increase" :badgeStyle="true" /&gt;
                            </code>
                        </div>
                    </div>

                    {{-- Stat Cards - Side Icon Style --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Stat Card - Side Icon Style</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Statistics cards with side icon layout.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-ui.card.stat
                                label="Total Sales"
                                value="$125,430"
                                change="+18.2%"
                                changeType="increase"
                                icon="📈"
                            />
                            <x-ui.card.stat
                                label="New Customers"
                                value="892"
                                change="+5.4%"
                                changeType="increase"
                                icon="👥"
                                color="green"
                            />
                            <x-ui.card.stat
                                label="Support Tickets"
                                value="23"
                                change="-12%"
                                changeType="decrease"
                                icon="🎫"
                                color="yellow"
                            />
                            <x-ui.card.stat
                                label="Server Uptime"
                                value="99.9%"
                                change="Stable"
                                changeType="neutral"
                                icon="⚡"
                                color="indigo"
                            />
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
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Informational cards with colored left border accents.</p>

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
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Empty State Cards</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Cards for displaying empty states and "no data" scenarios.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-ui.card.empty-state
                                icon="📭"
                                title="No Messages"
                                description="You don't have any messages yet. Check back later!"
                            />

                            <x-ui.card.empty-state
                                icon="🎫"
                                title="No Bookings Found"
                                description="Start by creating your first booking to see it here."
                            >
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

    </div>
</div>
