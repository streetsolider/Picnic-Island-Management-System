{{-- Toast Container - Fixed position at top-right corner --}}
<div
    aria-live="assertive"
    class="fixed top-0 right-0 z-50 flex flex-col items-end px-4 py-6 pointer-events-none sm:p-6 space-y-4"
    id="toast-container"
>
    {{ $slot }}
</div>
