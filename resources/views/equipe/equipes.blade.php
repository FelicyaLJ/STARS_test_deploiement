<script>
    let equipes = @json($equipes);
    const canManageEquipes = @json(auth()->user()->can('gestion_equipes'));
</script>

<x-app-layout>

    @include('equipe.partials.demande-equipe-form')

    <div id="messageContainer" class="mt-4 max-w-7xl mx-auto sm:px-6 lg:px-8"></div>
    <div x-data="{ openSidebar: true }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white flex flex-col xl:flex-row gap-6">

                <!-- Sidebar (Filtre) -->
                @include('equipe.partials.filter-form')

                <!-- LISTE DES ÉQUIPES -->
                <div
                    class="bg-black/60 backdrop-blur p-8 shadow sm:rounded-lg transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
                    :class="openSidebar
                        ? 'basis-full xl:basis-2/3 '
                        : 'basis-full xl:basis-full '"
                >
                    <div class="flex justify-between mb-4">
                        <h2 class="text-2xl font-semibold text-gray-100 ">Équipes</h2>
                        <div class="flex gap-4 items-center">
                            <button
                                @click="openSidebar = true"
                                x-show="!openSidebar"
                                class="flex gap-2 text-sm bg-white/10 px-3 py-1 rounded-lg hover:bg-red-400/80 transition"
                                title="Afficher le filtre"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-funnel-icon lucide-funnel"
                                    >
                                    <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"/>
                                </svg>
                                <span x-text="openSidebar ? 'Masquer' : 'Filtres'"></span>
                            </button>

                            @can('gestion_equipes')
                            <button type="button"
                                    data-url="{{ route('equipes.store') }}"
                                    class="text-gray-300 open-add-form transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-plus">
                                    <path d="M5 12h14"/>
                                    <path d="M12 5v14"/>
                                </svg>
                            </button>
                            @endcan
                        </div>
                    </div>

                    <div id="listEquipes" class="equipes-container space-y-3 overflow-auto max-h-[36rem]">
                        @include('partials.equipes-list')
                    </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- FORMULAIRE AJOUT -->
    @include('equipe.partials.equipe-form')

    <script src="{{ asset('js/equipes.js') }}"></script>
</x-app-layout>
