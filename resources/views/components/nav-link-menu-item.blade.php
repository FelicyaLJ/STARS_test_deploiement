@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block px-4 py-2 bg-gradient-to-br from-red-800/60 to-red-500/70 font-bold text-red-300 text-sm focus:outline-none focus:text-red-200 focus:border-red-200 transition duration-150 ease-in-out'
            : 'block px-4 py-2 text-gray-300 text-sm hover:bg-red-800/50 hover:text-red-200 focus:outline-none focus:text-red-100 focus:bg-red-700/60 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
