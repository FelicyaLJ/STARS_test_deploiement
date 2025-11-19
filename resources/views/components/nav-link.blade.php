@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link inline-flex items-center text-center overflow-hidden w-20 lg:w-24 xl:w-28 font-bold justify-center px-2 pt-1 border-b-2 border-red-300 bg-gradient-to-br from-red-800/60 to-red-500/70 text-sm leading-5 text-red-300 focus:outline-none focus:text-red-200 focus:border-red-200 transition duration-150 ease-in-out'
            : 'nav-link inline-flex items-center text-center overflow-hidden w-20 lg:w-24 xl:w-28 justify-center px-2 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-300 hover:text-red-300 hover:bg-red-800/30 hover:border-red-300 focus:outline-none focus:text-red-500 focus:border-red-500 transition duration-150 ease-in-out ';
@endphp

<a onclick="event.preventDefault();
        const href = '{{ $attributes->get('href') }}';
        const scrollToTop = () => {
            const current = window.scrollY;
            if (current > 5) {
                window.scrollTo(0, current - current / 8);
                requestAnimationFrame(scrollToTop);
            } else {
                window.location = href;
            }
        };
        scrollToTop();"
        {{ $attributes->merge(['class' => $classes]) }}>
    <span class="truncate">{{ $slot }}</span>
</a>
