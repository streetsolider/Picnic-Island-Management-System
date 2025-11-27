{{-- Card Grid Skeleton matching current card styling --}}
<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    @for ($i = 0; $i < 4; $i++)
        <div class="card p-6 animate-pulse">
            <div class="flex items-center justify-between mb-4">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
                <div class="h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
            </div>
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20 mb-2"></div>
            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
        </div>
    @endfor
</div>