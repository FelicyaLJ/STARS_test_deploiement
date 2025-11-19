<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Association de Soccer STARS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter-tight:400,500,600&display=swap" rel="stylesheet" /> --}}

        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=hind:400,500,600&display=swap" rel="stylesheet" /> --}}

        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cabin:400,500,600&display=swap" rel="stylesheet" /> --}}

        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=pontano-sans:400,500,600&display=swap" rel="stylesheet" /> --}}

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
                        setTimeout(() => window.location = url, 200); // match fade duration
                    }
                });
            });
        }"
        class="font-sans antialiased scroll-smooth bg-black overflow-x-hidden"
    >
        <!-- Background -->
        @php
            $bgImage = asset('storage/bg/' . ($backgroundImage ?? 'default.jpg'));
            $isBrowser = !app()->runningInConsole() && str_contains(request()->header('User-Agent', ''), 'Mozilla');
        @endphp
        @if($isBrowser)
            <!-- Browser: Animated background with AlpineJS -->
            <div class="fixed inset-0 -z-10 overflow-hidden">
                <img
                    x-ref="bg"
                    src="{{ $bgImage }}"
                    alt="Background"
                    class="w-full h-full object-cover transition-all duration-200 ease-out opacity-0 blur-md scale-105"
                    :class="bgLoaded && !fadingOut ? 'opacity-100 blur-0 scale-100' : 'opacity-0 blur-md scale-105'"
                    x-on:load.once="bgLoaded = true"
                    x-on:error.once="bgLoaded = true"
                />
            </div>
        @else
            <!-- Non-browser client: static background -->
            <div class="fixed inset-0 -z-10 overflow-hidden">
                <img
                    src="{{ $bgImage }}"
                    alt="Background"
                    class="w-full h-full object-cover"
                />
            </div>
        @endif

        <div class="fixed inset-0 -z-10 bg-black/40 "></div>

        <!-- Contenu page -->
        <div class="min-h-screen relative">
            @include('layouts.navigation')

            <div>
                <div id="contentdiv" class="transition-opacity duration-300 ease-in-out opacity-100">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-gradient-to-br from-[#d4af37] via-[#f9d876] to-[#c9a43b]">
                            <div class="max-w-7xl mx-auto py-7 px-4 sm:px-6 lg:px-8 drop-shadow-xl">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main
                        class="transition-opacity duration-200 ease-out {{ $isBrowser ? 'opacity-0' : 'opacity-100' }}"
                        @if ($isBrowser)
                        :class="bgLoaded && !fadingOut && 'opacity-100'"
                        @endif
                    >
                        <div class="mt-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
                            @include('messageFlash')
                        </div>
                        <div>
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>

            <!-- Cookie consent bar -->
            @include('layouts.cookie-consent')
        </div>
    </body>
    @include('layouts.footer')
</html>
