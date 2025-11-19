@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-red-300 text-start text-base bg-gradient-to-br from-red-800/60 to-red-500/70 font-bold text-red-300 focus:outline-none focus:text-red-600 focus:border-red-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-300 hover:text-red-300 hover:bg-red-800/30 hover:border-red-400 focus:outline-none focus:text-red-600 focus:border-red-600 focus:bg-red-200 transition duration-150 ease-in-out';
@endphp

<a  {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
