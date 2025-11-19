<script>
    let actualite = @json($actualite)
</script>

<x-app-layout>

    <div>
        <!-- Bloc pour l'actualité  -->
        <article class="m-auto p-auto min-h-170px h-170px relative mt-[35px] sm:max-w-7xl">
            <div class="">

                @can('gestion_actualites')
                    <div class="flex justify-evenly items-center w-full gap-0.5">
                        @if(count($actualite) > 0)
                            <!-- Boutton enlever  -->
                            <div class="group flex bg-black/60 backdrop-blur shadow-sm rounded-t-xl w-full items-center justify-center p-[0.6em] transition-all duration-300 ease-out hover:bg-white/10 hover:cursor-pointer text-red-600">
                                <button type="button" id="delete_actualite" class="group transform-origin:bottom w-full" value="{{ $actualite[0]->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-gray-50 m-auto" ><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </div>
                            <!--Bouton modifier-->
                            <div class="group flex bg-black/60 backdrop-blur shadow-sm rounded-t-xl w-full items-center justify-center p-[0.6em] transition-all duration-300 ease-out hover:bg-white/10 hover:cursor-pointer text-orange-500">
                                <button value="{{ $actualite[0]->id }}" id="mod_actualite" class="group transform-origin:bottom w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-gray-50 m-auto"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                                </button>
                            </div>
                        @endif
                        <!--Bouton ajouter-->
                        <div class="group flex bg-black/60 backdrop-blur shadow-sm rounded-t-xl w-full items-center justify-center p-[0.6em] transition-all duration-300 ease-out hover:bg-white/10 hover:cursor-pointer text-red-600">
                            <button id="add_actualite" class="group transform-origin:bottom w-full" >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-gray-50 m-auto"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            </button>
                        </div>
                    </div>
                @endcan
                <div class="mx-auto flex flex-col justify-center items-center overflow-hidden relative p-auto sm:min-h-[10rem]">
                    <div class="bg-black/60 backdrop-blur text-gray-50 shadow-sm sm:rounded-b-xl  w-full">

                        <div class="flex justify-center items-center w-full ">

                            @if (count($actualite) > 0)
                            <!-- Boutton naviguer gauche -->
                            <button class="flex items-center justify-center w-20 h-20 rounded-lg transform transition-transform duration-25 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-125 hover:text-gray-300" id="actualite_gauche">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
                            </button>
                            <div id="parent_span" class="p-6 w-[85%] flex items-center min-h-[10rem] gap-4 flex-col sm:flex-row transition-all duration-300 ease-out rounded-lg m-2 mt-8 hover:bg-white/10">
                                <!-- Titre -->
                                <span id="titre_actualite" class="text-center basis-1/4 min-h-[8rem] flex items-center justify-center m-auto mr-1 ml-5 block font-semibold text-lg">
                                    {{ $actualite[0]->titre }}
                                </span>
                                <!-- Texte -->
                                <span id="texte_actualite" class="text-center basis-3/4 min-h-[8rem] flex items-center justify-center m-auto mr-1 ml-5 block">
                                    {!! $actualite[0]->texte!!}
                                </span>
                            </div>
                            <!-- Boutton naviguer droite -->
                            <button class="flex items-center justify-center w-20 h-20 rounded-lg transform transition-transform duration-25 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-125 hover:text-gray-300" id="actualite_droite">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                            @else
                            <div id="parent_span" class="p-6 w-[85%] flex items-center justify-center min-h-[10rem] gap-4 flex-col sm:flex-row transition-all duration-300 ease-out rounded-lg m-2 mt-8 hover:bg-white/10">
                                <p class="font-semibold">
                                    {{__('Il n\'y a pas d\'actualités pour le moment. Revenez plus tard!')}}
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- Points de navigation -->
                        <div id="dots_container" class="flex gap-2 min-h-6 justify-center"></div>
                    </div>


                </div>
            </div>
        </article>

        <div class="flex flex-col gap-4 m-auto p-auto pb-8 min-h-170px h-170px relative mt-[35px] sm:max-w-7xl sm:flex-row">
            <div class="flex gap-4 justify-evenly flex-col basis sm:basis-1/4">


                <!-- Widget météo -->
                <article>
                    @include('accueil.partials.weather')
                </article>


                <!-- Lien "Je dénonce" obligatoire -->
                <article class="justify-center hidden sm:flex">
                    <a data-testid="linkElement" href="https://www.quebec.ca/tourisme-et-loisirs/encadrement-gouvernance-gestion-loisir-sport/porter-plainte-sport-loisir" target="_blank" rel="noreferrer noopener" class="">
                        <img id="" class="max-w-[20rem] rounded-lg" src="https://static.wixstatic.com/media/01ce12_a8e0cf1e102b443aa074503a7dec494d~mv2.jpg/v1/fill/w_459,h_366,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/01ce12_a8e0cf1e102b443aa074503a7dec494d~mv2.jpg" alt="BoutonWeb_PILS_JeDenonce_800x640.jpg">
                    </a>
                </article>
            </div>

            {{-- Événements --}}
            <div class="bg-black/60 backdrop-blur text-gray-50 overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-8 sm:basis-3/4">

                <div class="flex justify-between mb-4" x-data="{ openFilters: false }">
                    <h2 class="font-semibold text-xl leading-tight">
                        {{ __('Événements à venir') }}
                    </h2>
                    <button
                        @click="openFilters = !openFilters"
                        id="filtre-toggle"
                        class="flex gap-2 text-gray-50 text-sm bg-white/10 px-3 py-1 rounded-lg hover:bg-red-400/80 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-funnel-icon lucide-funnel"
                            >
                            <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"/>
                        </svg>
                        <span x-text="openFilters ? 'Masquer' : 'Filtres'"></span>
                    </button>
                </div>

                <div id="filtre-evenements" class="overflow-hidden max-h-0 transition-[max-height] duration-500 flex flex-col gap-1 break-all">
                    @include('partials.evenement-filter-form')
                </div>

                @include('accueil.partials.evenements')

                @include('partials.pagination')
            </div>

            <article class="justify-center flex sm:hidden">
                <a data-testid="linkElement" href="https://www.quebec.ca/tourisme-et-loisirs/encadrement-gouvernance-gestion-loisir-sport/porter-plainte-sport-loisir" target="_blank" rel="noreferrer noopener" class="">
                    <img id="" class="max-w-[20rem] rounded-lg" src="https://static.wixstatic.com/media/01ce12_a8e0cf1e102b443aa074503a7dec494d~mv2.jpg/v1/fill/w_459,h_366,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/01ce12_a8e0cf1e102b443aa074503a7dec494d~mv2.jpg" alt="BoutonWeb_PILS_JeDenonce_800x640.jpg">
                </a>
            </article>
        </div>
    </div>

    <!--En attendant une meilleure condition pour vérifier if user == admin -->
    @if(True)
        <!-- Modal ajout d'actualité -->
        @include('accueil.partials.actualite-form')
    @endif

</x-app-layout>

<script src="{{ asset('js/evenements.js') }}"></script>
