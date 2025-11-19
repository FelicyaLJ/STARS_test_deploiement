@props([
    'selected' => null,
    'calendarType' => 'terrain',
    'min' => null,
    'max' => null,
    'locale' => 'fr',
])

@php
    $initialSelected = $selected === null
        ? []
        : (is_array($selected) ? $selected : [$selected]);
@endphp

<div
    x-data="calendarComponent({
        initialYear: {{ now()->year }},
        initialMonth: {{ now()->month - 1 }},
        initialSelected: @json($initialSelected),
        calendarType: '{{ $calendarType }}',
        min: {{ $min ? "'$min'" : 'null' }},
        max: {{ $max ? "'$max'" : 'null' }},
        locale: '{{ $locale }}'
    })"
    x-init="init()"
    x-on:keydown.window.prevent.shift.arrow-left="prevMonth()"
    x-on:keydown.window.prevent.shift.arrow-right="nextMonth()"
    {{ $attributes->merge(['class' => 'max-w-md w-full rounded-lg shadow pt-4 relative']) }}
>

    <div class="flex flex-col">

        <div class="text-sm text-center text-gray-50">
            <button type="button" @click="goToToday()" class="px-2 py-1 rounded transform duration-300 ease-out hover:bg-white/10">{{__('Aujourd\'hui')}}</button>
        </div>

        <div class="flex items-center justify-between mb-3">
            <div class="text-lg text-gray-50 text-left font-medium invisible" x-text="yearLabel"></div>

            <div class="flex items-center gap-2 text-gray-50">
                <button type="button" @click="prevMonth()" aria-label="Previous month"
                    class="p-2 rounded transform duration-300 ease-out hover:bg-white/10">
                    ‹
                </button>
                <div class="text-lg font-medium" x-text="monthLabel"></div>
                <button type="button" @click="nextMonth()" aria-label="Next month"
                    class="p-2 rounded transform duration-300 ease-out hover:bg-white/10">
                    ›
                </button>
            </div>

            <div class="text-lg text-gray-50 text-right font-medium" x-text="yearLabel"></div>
        </div>
    </div>

    <!-- Jours de la semaine -->
    <div class="grid grid-cols-7 gap-1 text-xs text-center text-gray-300 mb-1">
        <template x-for="d in weekdays" :key="d">
            <div x-text="d"></div>
        </template>
    </div>

    <!-- Jours -->
    <div class="grid grid-cols-7 gap-1" x-show="!loading">
        <template x-for="(day, idx) in days" :key="idx">
            <button
                type="button"
                class="relative flex flex-col items-center justify-between h-12 rounded-md bg-black/10 text-sm focus:outline-none transition"
                :class="{
                    'text-gray-400 hover:bg-white/10': day.isOtherMonth,
                    'bg-red-400 text-white': isSelected(day.date),
                    'border border-gray-200 text-gray-50 hover:bg-white/10': !isSelected(day.date) && !day.isOtherMonth,
                    'opacity-50 cursor-not-allowed': day.isDisabled || day.isPast
                }"
                @click="!(day.isDisabled || day.isPast) && select(day)"
            >
                <!-- Day label -->
                <span class="z-10 text-shadow-lg mt-0.5 h-2" x-text="day.label"></span>

                <!-- Dots container -->
                <div class="relative w-full h-full pt-4 mb-0.5 overflow-hidden">
                    <div
                        class="flex flex-row justify-center gap-1 px-0.5 min-w-max will-change-transform"
                        :style="{
                            animation: day.reservations.length > 2
                                ? `scrollDots ${Math.max(6, day.reservations.length)}s linear infinite`
                                : 'none'
                        }"
                    >
                        <!-- Double dots for seamless scroll -->
                        <template x-for="(res, i) in (day.reservations.length > 2 ? [...day.reservations, ...day.reservations] : day.reservations)" :key="i + '-' + res.id">
                            <span
                                class="w-2.5 h-2.5 ring-1 ring-white/70 shadow-md rounded-full flex-shrink-0"
                                :style="{ backgroundColor: res.color }"
                            ></span>
                        </template>
                    </div>
                </div>
            </button>
        </template>
    </div>

    <!-- Squelette jours -->
    <div class="grid grid-cols-7 gap-1 animate-pulse-strong" x-show="loading">
        <template x-for="i in 42" :key="i">
            <div class="h-12 rounded-md bg-black/30"></div>
        </template>
    </div>
</div>

<script src="{{asset('js/calendrier.js')}}"></script>

<style>
@keyframes scrollDots {
  0%, 10% {
    transform: translateX(0);
  }
  90%, 100% {
    transform: translateX(-50%);
  }
}

@keyframes pulse-strong {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
.animate-pulse-strong {
    animation: pulse-strong 1.2s ease-in-out infinite;
}
</style>
