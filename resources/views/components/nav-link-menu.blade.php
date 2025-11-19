@props(['active'])

@php
$classes = ($active ?? false)
    ? 'nav-link inline-flex gap-2 items-center text-center overflow-hidden w-20 lg:w-24 xl:w-28 h-full font-bold justify-center px-2 pt-1 border-b-2 border-red-300 bg-gradient-to-br from-red-800/60 to-red-500/70 text-sm leading-5 text-red-300 focus:outline-none focus:text-red-200 focus:border-red-200 transition duration-150 ease-in-out cursor-pointer'
    : 'nav-link inline-flex gap-2 items-center text-center overflow-hidden w-20 lg:w-24 xl:w-28 h-full justify-center px-2 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-300 hover:text-red-300 hover:bg-red-800/30 hover:border-red-300 focus:outline-none focus:text-red-500 focus:border-red-500 transform transition duration-150 ease-in-out cursor-pointer';
@endphp

<div class="relative group">
    <!-- Main link -->
    <div {{ $attributes->merge(['class' => $classes]) }}>
        <span class="truncate">{{ $slot }}</span>
        <span class="transition-transform duration-300 group-hover:rotate-180">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
        </span>
    </div>

    <div class="absolute left-0 w-40 rounded-b-md bg-black/60 backdrop-blur text-gray-300 shadow-lg overflow-hidden max-h-0 opacity-50 transition-all duration-300 ease-in-out group-hover:max-h-96 group-hover:opacity-100">
        {{ $content }}
    </div>

</div>
