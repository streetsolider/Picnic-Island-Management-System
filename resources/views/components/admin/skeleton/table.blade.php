{{-- Table Skeleton matching current table wrapper styling --}}
<x-admin.table.wrapper>
    <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
            <x-admin.table.header>
                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-20 animate-pulse"></div>
            </x-admin.table.header>
            <x-admin.table.header>
                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-24 animate-pulse"></div>
            </x-admin.table.header>
            <x-admin.table.header>
                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-16 animate-pulse"></div>
            </x-admin.table.header>
            <x-admin.table.header>
                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-20 animate-pulse"></div>
            </x-admin.table.header>
        </tr>
    </thead>
    <tbody>
        @for ($i = 0; $i < 5; $i++)
            <x-admin.table.row>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse"></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-40 animate-pulse"></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-16 animate-pulse"></div>
                </td>
            </x-admin.table.row>
        @endfor
    </tbody>
</x-admin.table.wrapper>