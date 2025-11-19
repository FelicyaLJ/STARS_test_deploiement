@if (Session::has('info'))
    <div role="alert">
        <div class="bg-blue-500 text-white font-bold rounded-t px-4 py-2">{{__('Information')}}</div>
        <div class="border border-t-0 border-blue-400 rounded-b bg-blue-100 px-4 py-3 text-blue-700">
            <p>{{ Session::get('info') }}</p>
        </div>
    </div>
@elseif (Session::has('succes'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-5 right-5 z-50 w-full max-w-xs"
        role="alert"
    >
        <div class="bg-green-300/60 text-white rounded-lg shadow-lg overflow-hidden pointer-events-auto">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-semibold">{{ __('Succès') }}</p>
                    <p class="mt-1 text-sm text-white/90">{{ Session::get('success') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button
                        @click="show = false"
                        class="inline-flex text-white/70 hover:text-white focus:outline-none"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
@elseif (Session::has('alerte'))
    <div role="alert">
        <div class="bg-yellow-500 text-black font-bold rounded-t px-4 py-2">{{__('Avertissement')}}</div>
        <div class="border border-t-0 border-yellow-400 rounded-b bg-yellow-100 px-4 py-3 text-yellow-700">
            <p>{{ Session::get('alerte') }}</p>
        </div>
    </div>
@elseif (Session::has('erreur'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-5 right-5 z-50 w-full max-w-xs"
        role="alert"
    >
        <div class="bg-red-500/60 text-white rounded-lg shadow-lg overflow-hidden pointer-events-auto">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-semibold">{{ __('Erreur') }}</p>
                    <p class="mt-1 text-sm text-white/90">{{ Session::get('erreur') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button
                        @click="show = false"
                        class="inline-flex text-white/70 hover:text-white focus:outline-none"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
{{--
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <p>{{__('Veuillez corriger l\'erreur ou les erreurs suivante(s) :')}}</p>
        <ul class="list-disc list-inside pl-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}

@if (Session::has('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-5 right-5 z-50 w-full max-w-xs"
        role="alert"
    >
        <div class="bg-green-300/60 text-white rounded-lg shadow-lg overflow-hidden pointer-events-auto">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-semibold">{{ __('Succès') }}</p>
                    <p class="mt-1 text-sm text-white/90">{{ Session::get('success') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button
                        @click="show = false"
                        class="inline-flex text-white/70 hover:text-white focus:outline-none"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

