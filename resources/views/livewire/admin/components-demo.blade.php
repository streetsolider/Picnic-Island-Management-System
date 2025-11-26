<x-slot name="header">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" wire:navigate
               class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                UI Component Library - Demo & Documentation
            </h2>
        </div>
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

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

        {{-- Component Features --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Component Features</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Features List --}}
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Available Features</h4>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Three size variants: sm, md (default), lg</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Loading state with animated spinner</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Icon support (left/right positioning)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Disabled state styling</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Full dark mode support</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Livewire directive support (wire:click, wire:loading, etc.)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Can render as button or anchor tag (href support)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Focus rings and accessibility features</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Props Documentation --}}
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Available Props</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Prop</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Type</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Default</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-mono">type</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">string</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">'button'</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-mono">size</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">string</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">'md'</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-mono">disabled</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">bool</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">false</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-mono">loading</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">bool</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">false</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-mono">icon</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">string</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">null</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-mono">iconPosition</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">string</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">'left'</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-mono">href</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">string</td>
                                        <td class="px-3 py-2 text-gray-600 dark:text-gray-400">null</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Coming Soon --}}
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 overflow-hidden shadow-sm sm:rounded-lg border border-indigo-200 dark:border-indigo-800">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Coming Soon</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    The following components are planned and will be added to this demo page as they're completed:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-1">Cards</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Base, Stat, Info, Empty State</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-1">Alerts</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Success, Error, Warning, Info</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-1">Modals</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Base, Form, Confirmation</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-1">Badges</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Status, Role</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-1">Tables</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Wrapper, Header, Row</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm mb-1">Skeletons</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Card, Table, Text</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
