<script>
    const forums = @json($forums);
    const canManageForums = @json(auth()->user()->can('gestion_forums'));
    const userId = @json($userId);
    const autresForums = @json($autresForums);
    const mods = @json($mods);
</script>
<x-app-layout>

    <div>
        <x-modal-stars title="Demande d'adhésion" id="modalDemandeForum">
            <form id="forumDemandeForm" method="POST">
                @csrf
                <div>
                    <label for="raison" class="block text-gray-50 font-medium mb-1">{{ __('Raison de la demande') }}</label>
                    <textarea id="raison" name="raison" required rows="4" placeholder="Expliquez brièvement pourquoi vous souhaitez rejoindre ce forum..." class="w-full rounded-lg border-red-300 focus:border-red-500 focus:ring focus:ring-red-300 focus:ring-opacity-50 text-gray-800 p-3 resize-none" ></textarea>

                    <p id="formErrorRaison" class="text-red-500 text-sm mt-1 hidden">{{ __('La raison de la demande doit contenir au moins 5 caractères.') }}</p>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnSendJoinRequest" class="adhesion-btn w-full bg-red-400 hover:bg-red-700 focus:ring-4 focus:ring-red-300 text-white font-semibold py-2.5 rounded-lg transition-colors duration-200">{{ __('Envoyer la demande') }}</button>
                </div>
            </form>
        </x-modal-stars>
    </div>
    @can('gestion_forums')
    <div>

        <x-modal-stars title="Ajouter un forum" id="forumFormModal">
            <form class="text-white" id="forumForm" action="">
                @csrf
                <div class="max-h-[36rem] overflow-auto px-2">
                    <label for="nomForum">Nom du forum</label>
                    <input class="text-black w-full" type="text" placeholder="Nom" name="nomForum" id="nomForum">
                    <p class="text-red-500 hidden mt-1" id="formErrorNomForum">
                        Le nom du forum ne peut pas être vide.
                    </p>

                    <label for="equipes" class="mt-4 block">Sélectionner une équipe ou un groupe :</label>
                    <select class="text-black w-full" id="equipes" name="equipes">
                        <option value="0">Aucun</option>
                        @foreach ($equipes as $equipe)
                            <option value="{{ $equipe->id }}">{{ $equipe->nom_equipe }}</option>
                        @endforeach
                    </select>

                    <fieldset class="mt-4">
                        <div>
                            <label for="rechercheMembre">Ajouter un membre</label>
                            <p id="erreurRechercheMembre" class="text-red-500 hidden mt-1">
                                Les caractères spéciaux ne sont pas autorisés dans la recherche.
                            </p>
                            <input class="text-black w-full" type="text" id="rechercheMembre" placeholder="Entrer un nom">
                            <ul class="text-black bg-white border rounded shadow max-h-32 overflow-y-auto" id="resultatRecherche"></ul>
                        </div>
                        <div class="mt-4">
                            <h4>Membres :</h4>
                            <div id="membres" class="space-y-2">
                                <div class="checkboxMembre flex items-center space-x-2" id="1">
                                    <p>Sylvain LeBlanc</p>
                                </div>
                            </div>
                            <p id="formErrorMembres" class="text-red-500 hidden mt-1">
                                Le forum doit contenir au moins un membre.
                            </p>
                        </div>
                    </fieldset>

                </div>
                <div id="divBtn" class="w-full self-center flex justify-between">
                    <button type="button" id="enregistrer" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 mt-[5%] w-4/5">
                        Enregistrer
                    </button>
                </div>
            </form>
        </x-modal-stars>

    </div>
    @endcan
    <div class="max-w-7xl py-12 gap-4 mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row">
        <div id="forums-container" class="w-full flex flex-col [@media(min-width:920px)]:flex-row gap-4 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
            <div id="liste-container" class="flex-[1_1_100%] w-full transition-[flex,margin,width] duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                <div class="p-4 sm:p-8 bg-black/60 backdrop-blur text-gray-50 shadow-sm sm:rounded-lg">
                    <div class="flex flex-row items-center justify-between md:mx-6">
                        <h2 id="titreListe" class="font-semibold text-2xl truncate text-gray-50 leading-tight">{{"Forums de " . auth()->user()->prenom}}</h2>
                        <div class="flex items-center gap-3">
                            <div class="w-14 md:w-full py-2 flex flex-col items-center text-center mx-auto">


                                <label for="switch" class="mb-2">
                                    Voir les autres forums
                                </label>


                                <label class="switch relative inline-block w-14 h-8">
                                    <input type="checkbox" id="switch" name="switch" class="opacity-0 w-0 h-0 peer " />
                                    <span class="absolute inset-0 bg-white/10 border border-white/20 rounded-full transition-colors duration-300 peer-checked:bg-red-400 cursor-pointer"></span>
                                    <span class="absolute left-1 bottom-1 w-6 h-6 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6 cursor-pointer"></span>
                                </label>

                            </div>

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
                                                    class="border text-gray-700 rounded">
                                            </div>


                                            <div class="w-full py-2">
                                            <label for="tri">Trier par :</label>
                                            </div>
                                            <div>
                                            <select id="tri" name="tri" class="w-full border text-gray-700 rounded p-2">
                                                <option value="0">Messages récents</option>
                                                <option value="1">Nouveaux groupes</option>
                                                <option value="2">Anciens groupes</option>
                                                <option value="3">Alphabétique</option>
                                            </select>
                                            </div>

                                        <button type="button" class="w-full bg-gray-300 text-gray-700 border border-black rounded" id="reset">Réinitialiser</button>
                                    </div>
                                </div>
                            </div>

                            @can('gestion_forums')
                                <button
                                    type="button"
                                    class="flex flex-col justify-center content-center text-gray-300 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1"
                                    id="ajoutForum"
                                >
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

                    <div class="p-6 text-gray-900 space-y-4 overflow-auto max-h-[36rem]" id="listeForums">

                    </div>

                    <p class="border p-3 rounded hidden" id="noResultsMsg">Aucune correspondance</p>
                </div>
            </div>

            <div id="include" class="opacity-0 scale-95 transform transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] flex-[0_1_0%] overflow-hidden">
                <div>
                    @include("forum.forum")
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
