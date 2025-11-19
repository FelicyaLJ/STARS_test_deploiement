<x-app-layout>
    <x-modal-stars title="Devenez partenaire" id="devenir-partenaire-modal">
        <div class="max-h-[40rem] overflow-y-auto space-y-4">
            <p class="">{{__('Si vous êtes intéressés à devenir partenaire de l\'Association de Soccer STARS, voici les différents programmes de commandites disponibles pour la saison 2026 et les saisons suivantes. Si l\'un de nos programmes vous intéresse,')}} <a class="underline text-gray-300" href="{{route('contactez.nous')}}">{{__('contactez-nous')}}</a> {{__('pour nous faire part de votre projet.')}}</p>
            <img src="{{asset('images/Programme de commandite 2022.png')}}" alt="Programme de commandite 2022">
        </div>
    </x-modal-stars>

    {{-- Partenaires Form --}}
    @include('partials.partenaire-form')

    <div class="max-w-7xl mx-auto sm:px-6 text-gray-50 lg:px-8 py-12 space-y-4">

        <div class="flex gap-4 items-center justify-center md:justify-start mb-12">
            <x-application-logo class="w-28 md:w-32"/>
            <div class="hidden md:block">
                <h2 class="font-semibold text-2xl pb-2 text-[#e3c14e] leading-tight">
                    {{ __('Association de soccer STARS') }}
                </h2>
                <hr class="border-gray-600">
                <h4 class="font-semibold text-lg pt-2 leading-tight">
                    {{ __('Club de Soccer de Lanaudière') }}
                </h4>
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-black/60 backdrop-blur  shadow sm:rounded-lg flex flex-col justify-center text-center">
            <div class="flex justify-between mb-6">
                <h2 class="font-semibold text-2xl leading-tight">
                    {{ __('Notre Club') }}
                </h2>
                <hr>
            </div>
            <div class="flex flex-col gap-4 text-lg px-10 justify-center items-center">
                <span>{{__('L\'Association de Soccer STARS regroupe des joueurs des municipalités de Rawdon, Sainte-Alphonse-Rodriguez et Saint-Côme, ainsi que les municipalités environnantes, dans Lanaudière.')}}</span>
                <span>{{__('Nous offrons des programmes de développement encadrés et motivant pour jeunes et moins jeunes.')}}</span>
            </div>


        </div>


        {{-- Partenaires --}}
        @include('partials.partenaires')

        {{-- Conseil Admin --}}
        @include('partials.conseil-admin')


    </div>
</x-app-layout>


