<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl gap-4 mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row">

                <!-- Catégories + Calendrier -->
                <section class="bg-black/60 backdrop-blur shadow sm:rounded-lg p-4 sm:p-8 basis-1/3 text-gray-50 flex flex-col justify-between items-center h-fit">

                    <!-- Catégories -->
                    <div class="w-full">
                        <div class="flex justify-between mb-2">
                            <h2 class="font-semibold text-2xl text-gray-50 leading-tight">
                                {{ __('Catégories') }}
                            </h2>
                            @can('gestion_categorie_evenement')
                            <button id="addCategorieEvenement" type="button" class="text-gray-300 flex transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            </button>
                            @endcan
                        </div>

                        <!-- Bulles catégories -->
                        <div id="listCategories" class="flex flex-wrap justify-evenly gap-3 ml-4 my-4">
                            @include('evenement.partials.categorie-list')
                        </div>
                    </div>

                    <!-- Calendrier -->
                    <div class="w-full">
                        <hr class="" />
                        <div class="flex justify-center"
                            x-data
                            @calendar-ready.window="window.calendarEvenement = $event.detail.calendar"
                        >
                            <x-calendar x-ref="calendarEvenement" calendar-type="evenement"></x-calendar>
                        </div>
                    </div>
                </section>

                <!-- Événements -->
                <div class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg basis-2/3">

                    <div class="flex justify-between mb-4">
                        <div class="flex flex-col sm:flex-row gap-6">
                            <h2 class="font-semibold text-2xl text-gray-50 leading-tight">
                                {{ __('Événements') }}
                            </h2>

                            <div class="flex gap-3 pb-2">
                                <button
                                    id="btnPast"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition bg-white/20 text-gray-400 hover:text-gray-200">
                                    {{__('Passés')}}
                                </button>

                                <button
                                    id="btnUpcoming"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition bg-red-400 text-white">
                                    {{__('À venir')}}
                                </button>
                            </div>
                        </div>
                        <div class="flex gap-2 items-center" x-data="{ openFilters: false }">
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
                            @can('gestion_evenements')
                            <button id="add_evenement" type="button" class="text-gray-300 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-plus-icon lucide-plus">
                                    <path d="M5 12h14"/>
                                    <path d="M12 5v14"/>
                                </svg>
                            </button>
                            @endcan
                        </div>
                    </div>

                    <div id="filtre-evenements" class="overflow-auto max-h-0 transition-[max-height] duration-500 flex flex-col gap-1 break-all">
                        @include('partials.evenement-filter-form')
                    </div>

                    <div id="listEvenements" class="h-[36rem] overflow-auto space-y-2">
                        @include('partials.evenements-list')
                    </div>

                    @include('partials.pagination')
                </div>
        </div>

    </div>
    @can('gestion_evenements')
        <!-- Modal ajout d'événement -->
        @include('evenement.partials.evenement-form')
    @endcan

    @can('gestion_categorie_evenement')
        @include('evenement.partials.categorie-form-add')
        @include('evenement.partials.categorie-form-edit')
    @endcan
</x-app-layout>

<script>
    let evenements = @json($evenements);
    let terrains = @json($terrains);
    let categorie_evenements = @json($categories);
</script>
<script src="{{ asset('js/evenements.js') }}"></script>

