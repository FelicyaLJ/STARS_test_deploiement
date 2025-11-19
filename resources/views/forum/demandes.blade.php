<script>
    const demandes = @json($demandes);
    const canManageDemandes = @json(auth()->user()->can('gestion_demandes'));
</script>
<x-app-layout>

    <div class="max-w-7xl py-12 gap-4 mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row">
        <div id="forums-container" class="w-full flex flex-col xl:flex-row gap-4 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
            <div id="liste-container" class="flex-[1_1_100%] w-full transition-[flex,margin,width] duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                <div class="p-4 sm:p-8 bg-black/60 backdrop-blur text-gray-50 shadow-sm sm:rounded-lg">
                    <div class="flex flex-row items-center justify-between md:mx-6">
                        <h2 id="titreListe" class="font-semibold text-2xl truncate text-gray-50 leading-tight">{{"Demandes d'adhésion"}}</h2>
                        <div class="flex items-center gap-3">

                            <div id="deployFiltre" class="select-none rounded-lg cursor-pointer flex flex-col space-y-4 shadow-lg"
                                x-data="{ openFilters: false }">

                                    <button
                                        @click="openFilters = !openFilters"
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


                                     <div id="contentFiltre" class="filtreContent grid grid-cols-1 gap-4 max-h-0 transition-[max-height] duration-500">

                                        <div class="p-2">
                                            <div class="w-full py-2">
                                            <input type="text" id="rechercheForum" placeholder="Rechercher un forum..."
                                                class="border text-gray-700 rounded p-2">
                                            </div>


                                            <div class="w-full py-2">
                                            <label for="tri">Trier les demandes par :</label>
                                            </div>
                                            <div>
                                            <select id="tri" name="tri" class="w-full border text-gray-700 rounded p-2">
                                                <option value="0">Plus récentes</option>
                                                <option value="1">Plus anciennes</option>
                                                <option value="2">Alphabétique</option>
                                            </select>
                                            </div>

                                        <button type="button" class="w-full py-2 bg-gray-300 text-gray-700 border border-black rounded" id="reset">Réinitialiser</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="p-6 text-gray-900 space-y-4 overflow-auto max-h-[36rem]" id="listeDemandes">

                    </div>

                    <p class="border p-3 rounded hidden" id="noResultsMsg">Aucune correspondance</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="{{ asset('js/demandesForum.js') }}"></script>
