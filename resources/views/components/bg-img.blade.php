@props(['src'])

<div
    x-data="{ loaded: false }"
    class="fixed inset-0 -z-10 overflow-hidden"
    x-init="if ($refs.bg.complete) loaded = true"
>
    <img
        x-ref="bg"
        src="{{ $src }}"
        alt="Background"
        class="w-full h-full object-cover transition-all duration-700 ease-out opacity-0 blur-md scale-105"
        :class="loaded && 'opacity-100 blur-0 scale-100'"
        @load.once="loaded = true"
    />
</div>
