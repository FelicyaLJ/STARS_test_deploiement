@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-red-300 text-start text-base bg-gradient-to-br from-red-800/60 to-red-500/70 font-bold text-red-300 focus:outline-none focus:text-red-600 focus:border-red-700 transition duration-150 ease-in-out'
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-300 hover:text-red-300 hover:bg-red-800/30 hover:border-red-400 focus:outline-none focus:text-red-600 focus:border-red-600 focus:bg-red-200 transition duration-150 ease-in-out';
@endphp

<div x-data="{ open: false }" class="relative">

    <!-- Trigger (exact same styling as your other links) -->
    <button
        type="button"
        @click="open = true"
        class="{{ $classes }} flex items-center justify-between text-left"
    >
        <span class="truncate">{{ $slot }}</span>

        <!-- Chevron ( > ) -->
        <svg
            class="w-4 h-4 transition-transform duration-300"
            :class="{ 'rotate-90': open }"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M9 6l6 6-6 6" />
        </svg>
    </button>

    <!-- Backdrop -->
    <div
        x-show="open"
        @click="open = false"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40"
        x-transition.opacity
    ></div>

    <!-- Slide-in panel -->
    <div
        x-show="open"
        class="fixed top-0 right-0 w-[20rem] max-w-full h-full bg-black/70 backdrop-blur border-l border-white/20 z-50 shadow-2xl transform overflow-y-auto overflow-x-hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
    >
        <!-- Back button header -->
        <div class="flex items-center gap-2 p-4  border-b border-gray-700">
            <button @click="open = false" class="text-red-300 hover:text-red-700">
                <svg width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 6L9 12L15 18"/>
                </svg>
            </button>

            <!-- The label from the trigger -->
            <span class="font-semibold text-gray-100">{{ $slot }}</span>
        </div>

        <!-- Content — clicking a link closes the panel -->
        <div class=""
             @click.stop="if ($event.target.closest('a')) open = false">
            {{ $content }}
        </div>
    </div>

</div>
