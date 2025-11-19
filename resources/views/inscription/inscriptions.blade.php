<script>
    const equipes = @json($equipes->toArray());
    const canManageActivites = @json(auth()->user()->can('gestion_inscriptions'));
    const evenements = @json($evenements);
</script>
<x-app-layout>
    @can('gestion_forums')
    <div>
        <x-modal-stars title="Inscription" id="modalInscription">
             <form class="text-white" id="inscriptionForm" method="POST">
                @csrf
                <h2 id="activiteInscription"></h2>

                <label for="raison">Commentaire aux responsables</label>
                 <textarea name="raison" id="raison" required></textarea>
                <p class="text-red-500 hidden mt-1" id="formErrorCommentaire">
                    La raison contient des caractères invalides.
                </p>

                <div>
                    <p>L'administrateur vous contactera pour régler le paiement, s'il y a lieu.</p>
                </div>

                <button type="submit" class="btn btn-primary">Envoyer l'inscription'</button>
            </form>
        </x-modal-stars>

        <x-modal-stars title="Ajouter une activité" id="activiteModal">
            <form class="text-white overflow-y-auto max-h-80" id="activiteForm" action="">
                @csrf
                <input type="hidden" id="idActivite" value="0"></input>

                <label for="nomActivite">Nom de l'activité: </label>
                <input class="text-black w-full" type="text" placeholder="Nom" name="nomActivite" id="nomActivite">
                <p class="text-red-500 hidden mt-1" id="formErrorNomActivite">
                    Le nom de l'activité ne peut pas être vide.
                </p>
                <label for="descriptionEquipe">Description: </label>
                <textarea class="text-black" name="descriptionEquipe" id="descriptionEquipe" required></textarea>
                <p class="text-red-500 hidden mt-1" id="formErrorDescription">
                    La description contient des caractères invalides.
                </p>
                <div>
                    <!-- Date -->
                    <div>
                        <label>{{__('Date')}}:
                            <input type="date" name="date" id="date_add" value="{{ old('date') }}" class="border rounded w-full p-2 mb-2 text-gray-800" />
                        </label>
                        <p class="text-red-500 hidden mt-1" id="formErrorDate"></p>
                    </div>
                    <!-- heure de départ -->
                    <div>
                        <label>{{__('Début de l\'activité')}}:
                            <input type="time" name="heure_debut" id="heure_debut_add" value="{{ old('heure_debut') }}" class="border rounded w-full p-2 mb-2 text-gray-800" />
                        </label>
                        <p class="text-red-500 hidden mt-1" id="formErrorDebut"></p>
                    </div>
                    <!-- heure de la fin -->
                    <div>
                        <label>{{__('Fin de l\'activité')}}:
                            <input type="time" name="heure_fin" id="heure_fin_add" value="{{ old('heure_fin') }}" class="border rounded w-full p-2 mb-2 text-gray-800" />
                        </label>
                        <p class="text-red-500 hidden mt-1" id="formErrorFin"></p>
                    </div>
                </div>
                <p class="bg-blue-500"> demander répétition</p>

                <div class="mt-[3%]">
                    <label>{{__('Ajouter un coût d\'inscription')}}:
                        <input type="checkbox" id="si_evenement_prix_add" name="si_evenement_prix">
                    </label>
                    <p class="text-red-500 hidden mt-1" id="formErrorCout"></p>
                </div>

               <div id="bulle_evenement_prix_add" class="hidden">
                    <label>{{__('Prix d\'inscription')}}:
                        <input type="text" name="prix_evenement" id="prix_evenement_add" maxlength="5" placeholder="19.99" class="border rounded w-full p-2 mb-2 text-gray-800" value="0">
                    </label>
                    <p class="text-red-500 hidden mt-1" id="formErrorCout"></p>
                </div>

                <div>
                    <label>{{__('Catégorie de l\'équipe')}}:
                        <select class="border rounded w-full p-2 mb-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400" id="categorie_equipe_add" name="categorie_equipe">
                            <option value="0">{{__('Choisir une catégorie')}}</option>
                            @foreach ($categories as $categorie_equipe)
                                <option value="{{ $categorie_equipe->id }}">{{$categorie_equipe->nom_categorie}}</option>
                            @endforeach
                        </select>
                    </label>
                    <p class="text-red-500 hidden mt-1" id="formErrorCategorie"></p>
                </div>

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
                        </div>
                        <p id="formErrorMembres" class="text-red-500 hidden mt-1">
                            Le forum doit contenir au moins un membre.
                        </p>
                    </div>
                </fieldset>

                <div class="flex justify-between items-end gap-3">
                    <button data-id="0" id="supprimer" type="button" class="bg-red-500 text-white px-4 py-2 rounded mt-2 mt-[5%] w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-700 m-auto" ><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                    <div class="w-full">
                        <button data-id="0" type="button" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 mt-[5%] w-full" id="enregistrer">{{__('Enregistrer')}}</button>
                    </div>
                </div>
            </form>
        </x-modal-stars>

    </div>
    @endcan
    <div id="activites-container" class="max-w-7xl py-12 gap-4 mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row ap-4 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
        <div id="liste-container" class="text-gray-50 w-[70%] transition-[flex,margin,width] duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
            <div class="p-4 sm:p-8 bg-black/60 backdrop-blur text-gray-50 shadow-sm sm:rounded-lg">
                <div class="flex flex-row items-center justify-between md:mx-6 ">
                    @can('gestion_inscriptions')
                        <button
                            type="button"
                            class="flex flex-col justify-center content-center text-gray-300 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1"
                            id="ajoutActivite"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-plus-icon lucide-plus">
                                <path d="M5 12h14"/>
                                <path d="M12 5v14"/>
                            </svg>
                        </button>
                    @endcan

                    <div id="deployFiltre" class="select-none mx-2 mb-4 p-4 rounded-lg cursor-pointer bg-gray-700 flex flex-col space-y-4 shadow-lg">


                            <div class="flex flex-row justify-between">
                                <p class="font-bold text-lg text-red-500">Filtres</p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-down-dash-icon lucide-arrow-big-down-dash"><path d="M15 11a1 1 0 0 0 1 1h2.939a1 1 0 0 1 .75 1.811l-6.835 6.836a1.207 1.207 0 0 1-1.707 0L4.31 13.81a1 1 0 0 1 .75-1.811H8a1 1 0 0 0 1-1V9a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1z"/><path d="M9 4h6"/></svg>
                            </div>


                            <div id="contentFiltre" class="filtreContent grid grid-cols-1 gap-4 max-h-0 transition-[max-height] duration-500">

                                <div class="p-5">
                                    <div class="w-40 py-2">
                                    <input type="text" id="rechercheActivite" placeholder="Rechercher un forum..."
                                        class="border text-gray-700 rounded p-2">
                                    </div>


                                    <div class="w-40 py-2">
                                    <label for="tri">Trier par :</label>
                                    </div>
                                    <div>
                                    <select id="tri" name="tri" class="w-full border text-gray-700 rounded p-2">
                                        <option value="0">Plus récents</option>
                                        <option value="1">Plus anciens</option>
                                        <option value="2">Coût (Croissant)</option>
                                        <option value="3">Coût (Décroissant)</option>
                                        <option value="4">Catégorie d'événement</option>
                                        <option value="5">Catégorie d'équipe</option>
                                        <option value="6">Nom (Alphabétique)</option>
                                    </select>
                                    </div>
                                <button type="button" class="bg-gray-300 text-gray-700 border border-black rounded" id="reset">Réinitialiser</button>
                            </div>
                            </div>
                        </div>
                    </div>
                <div class="p-6 space-y-4" id="listeActivites">

                </div>
                <p class="border p-3 rounded hidden" id="noResultsMsg">Aucune correspondance</p>
            </div>
        </div>
        <div id="include" class="hidden w-full md:w-[30%]">
            <div>
                @include("inscription/inscription")
            </div>
        </div>
    </div>
</x-app-layout>
