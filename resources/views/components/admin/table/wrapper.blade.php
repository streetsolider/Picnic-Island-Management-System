@props([
    'striped' => false,
    'hoverable' => false,
])

@php
    $tableId = 'table-' . uniqid();
    $tableClasses = 'min-w-full divide-y divide-gray-200 dark:divide-gray-700';
@endphp

<div class="overflow-x-auto">
    <table id="{{ $tableId }}" {{ $attributes->merge(['class' => $tableClasses]) }}>
        {{ $slot }}
    </table>
</div>

<style>
    /* Row borders for all tables */
    #{{ $tableId }} tbody tr {
        border-top: 1px solid rgb(229 231 235);
    }
    .dark #{{ $tableId }} tbody tr {
        border-top: 1px solid rgb(55 65 81);
    }
    
    @if($striped)
    #{{ $tableId }} tbody tr:nth-child(even) {
        background-color: rgb(248 250 252);
    }
    .dark #{{ $tableId }} tbody tr:nth-child(even) {
        background-color: rgb(45 55 72);
    }
    @endif
    
    @if($hoverable)
    #{{ $tableId }} tbody tr:hover {
        background-color: rgb(243 244 246);
    }
    .dark #{{ $tableId }} tbody tr:hover {
        background-color: rgb(55 65 81);
    }
    @endif
</style>
