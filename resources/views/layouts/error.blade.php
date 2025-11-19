<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

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
        if ($refs.bg && $refs.bg.complete) bgLoaded = true;

        document.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', e => {
                const url = link.getAttribute('href');
                if (!url.startsWith('#') && !link.target && url !== window.location.href) {
                    e.preventDefault();
                    fadingOut = true;
                    setTimeout(() => window.location = url, 200);
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
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <img
                src="{{ $bgImage }}"
                alt="Background"
                class="w-full h-full object-cover"
            />
        </div>
    @endif

    <div class="fixed inset-0 -z-10 bg-black/40"></div>

    <!-- Page container -->
    <div class="min-h-screen relative">

        @include('layouts.navigation')

        <main
            class="transition-opacity duration-300 ease-out flex items-center justify-center px-4
                {{ $isBrowser ? 'opacity-0' : 'opacity-100' }}"
            @if($isBrowser)
                x-data="{ bgLoaded: false, fadingOut: false }"
                x-init="() => { if ($refs.bg && $refs.bg.complete) bgLoaded = true;
                                document.querySelectorAll('a[href]').forEach(link => {
                                    link.addEventListener('click', e => {
                                        const url = link.getAttribute('href');
                                        if (!url.startsWith('#') && !link.target && url !== window.location.href) {
                                            e.preventDefault();
                                            fadingOut = true;
                                            setTimeout(() => window.location = url, 200);
                                        }
                                    });
                                });
                            }"
                :class="bgLoaded && !fadingOut && 'opacity-100'"
            @endif
        >
            <!-- Error card -->
            <div
                class="bg-black/60 m-auto backdrop-blur shadow-lg sm:rounded-2xl overflow-hidden transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]
                    p-10 w-full max-w-xl text-gray-50 border border-white/10 mt-10"
            >
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-4xl font-bold tracking-wide">@yield('code')</h1>
                </div>

                <p class="text-lg text-gray-300 leading-relaxed mb-6">
                    @yield('message')
                </p>

                <div>
                    <a href="{{ url('/') }}"
                    class="w-full block text-center bg-red-800/30 hover:bg-red-400/70 transition-colors duration-300 text-white px-4 py-3 rounded">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </main>

    </div>

</body>

@include('layouts.footer')
</html>
