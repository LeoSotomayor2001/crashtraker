@props(['type' => 'success','message'=> ''])

@php
    $colors=[
        'success' =>'border-green-400 bg-green-100 text-green-700 dark:border-green-800 dark:bg-green-950 dark:text-green-300',
        'error' => 'border-red-400 bg-red-100 py-3 text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-300'
    ];
    $class= $colors[$type] ?? $colors['success'];
@endphp

@if ($message)
    <p class="my-10 text-center border py-3 text-sm {{ $class }}">{{ $message }}</p>
@endif