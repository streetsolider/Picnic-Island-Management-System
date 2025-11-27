<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4 animate-pulse">
    @for ($i = 0; $i < 4; $i++)
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="p-3 mr-4 bg-gray-200 rounded-full dark:bg-gray-700 w-12 h-12"></div>
            <div class="w-full">
                <div class="h-4 mb-2 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
                <div class="h-6 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
            </div>
        </div>
    @endfor
</div>