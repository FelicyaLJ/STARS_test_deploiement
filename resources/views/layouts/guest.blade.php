<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

        <!-- JQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- TomSelect -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

        <!-- Toastr -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Quilljs-->
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    </head>
    <body
        x-data="{ bgLoaded: false, fadingOut: false }"
        x-init="() => {
            // Handle background load
            if ($refs.bg && $refs.bg.complete) bgLoaded = true;

            // Intercept navigation clicks
            document.querySelectorAll('a[href]').forEach(link => {
                link.addEventListener('click', e => {
                    const url = link.getAttribute('href');
                    if (!url.startsWith('#') && !link.target && url !== window.location.href) {
                        e.preventDefault();
                        fadingOut = true;
                        setTimeout(() => window.location = url, 400); // match fade duration
                    }
                });
            });
        }"
        class="font-sans antialiased scroll-smooth bg-black"
    >
        <!-- Image arrière-plan -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <img
                x-ref="bg"
                src="{{ asset('storage/bg/' . ($backgroundImage ?? 'default.jpg')) }}"
                alt="Background"
                class="w-full h-full object-cover transition-all duration-700 ease-out opacity-0 blur-md scale-105"
                :class="bgLoaded && !fadingOut ? 'opacity-100 blur-0 scale-100' : 'opacity-0 blur-md scale-105'"
                @load.once="bgLoaded = true"
            />
        </div>

        <div class="fixed inset-0 -z-10 bg-black/40 "></div>


        <main
            class="transition-opacity duration-500 ease-out opacity-0"
            :class="bgLoaded && !fadingOut && 'opacity-100'"
        >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @include('messageFlash')
            </div>
            <div class="min-h-screen flex flex-col gap-6 sm:gap-0 sm:flex-row justify-center sm:justify-between items-center pt-6 sm:pt-0">
                <div class="rounded-2xl p-8 m-6 hover:bg-black/10 transition duration-300 group">
                    <a href="/" class="flex items-center justify-center flex-col md:flex-row gap-4 ml-0 md:ml-10">
                        <x-application-logo class="w-28 h-28 md:w-32 md:h-32 fill-current text-gray-500 group-hover:brightness-90 transition duration-300" />
                        <h2 class="text-6xl text-center md:text-left text-[#e3c14e] group-hover:brightness-90 transition duration-300 hidden sm:block">{{__('Association de Soccer STARS')}}</h2>
                    </a>
                </div>

                <div class="w-full sm:min-h-screen sm:max-w-md px-6 py-4 bg-black/60 backdrop-blur shadow-md overflow-hidden flex items-center justify-center">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </body>
</html>
