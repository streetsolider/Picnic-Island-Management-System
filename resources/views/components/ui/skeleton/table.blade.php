<div class="w-full overflow-hidden rounded-lg shadow-xs animate-pulse">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr
                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
                    </th>
                    <th class="px-4 py-3">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-32"></div>
                    </th>
                    <th class="px-4 py-3">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-20"></div>
                    </th>
                    <th class="px-4 py-3">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                @for ($i = 0; $i < 5; $i++)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-32"></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-48"></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>