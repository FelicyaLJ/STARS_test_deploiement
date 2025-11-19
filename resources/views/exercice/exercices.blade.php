<script>
        const categories = @json($categories);
        const canManageEntrainements = @json(auth()->user()->can('gestion_entrainements'));
        const urlImageBase = "{{ asset('storage/exercices/images')}}/";
        const urlFichierBase = "{{ asset('storage/exercices/files')}}/";
        const maxOrdreExercice = @json($maxOrdreExercice);
        const maxOrdreCategorie = @json($maxOrdreCategorie);
        const userId = @json($userId);
</script>
<x-app-layout>
    @can('gestion_entrainements')
    <div>
        <x-modal-stars title="Ajouter un" id="formModal">
                <form class="text-white" id="form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div id="formContent" class="flex flex-col">

                    </div>
                </form>
        </x-modal-stars>
    </div>
    @endcan

    @can('gestion_entrainements')
    <div>
        <x-modal-stars title="Modifier l'exercice" id="formModalModif">
                <form class="text-white" id="form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div id="formContentEdit" class="flex flex-col">
                        <input type="hidden" name="exerciceId" id="exerciceId" value="0">
                        <label for="nom_exercice">Nom de l'exercice</label>
                        <input value="0" class="text-black" type="text" placeholder="Nom" name="nom_exercice" id="nom_exercice">
                        <p class="text-red-500 hidden" id="formErrorNom">Charactères spéciaux non-acceptés.</p>

                        <label for="texte">Description (facultatif)</label>
                        <input value="0" class="text-black" type="text" placeholder="Description" name="texte" id="texte">
                        <p class="text-red-500 hidden" id="formErrorDesc">Charactères spéciaux non-acceptés.</p>

                        <label for="image">Changer l'image de couverture:</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <p class="text-red-500 hidden" id="formErrorImage">Le fichier doit être une image.</p>

                        <label for="fichier">Changer le fichier à inclure (facultatif)</label>
                        <input type="file" id="fichier" name="fichier" accept="image/*,video/*,.pdf">
                        <p class="text-red-500 hidden" id="formErrorFichier">Le fichier doit être une image, vidéo ou PDF.</p>

                        <label for="lien">Lien vers une page externe(facultatif)</label>
                        <input value="0" class="text-black" type="text" placeholder="exemple.com" name="lien" id="lien">
                        <p class="text-red-500 hidden" id="formErrorLien">Le lien doit respecter un format classique.</p>

                        <label for="id_categorie">Catégorie:</label>
                        <select class="text-black" id="id_categorie" name="id_categorie">
                            @foreach ($categories as $c)
                            <option value="{{$c->id}}">{{$c->nom_categorie}}</option>
                            @endforeach
                        </select>

                        <label for="ordre_affichage">Ordre d'affichage:</label>
                        <select class="text-black" id="ordre_affichage" name="ordre_affichage">

                        </select>
                        <div class="flex justify-between items-end gap-3">
                            <div id="bulle_bouton_faq" class="w-4/5">
                                <button data-id="0" type="button" id="enregistrerModif" data-id="0" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 mt-[5%] w-full">{{__('Enregistrer')}}</button>
                            </div>
                            <button data-id="0" id="supprimerExercice" type="button" class="bg-red-500 text-white px-4 py-2 rounded mt-2 mt-[5%] w-fit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-700 m-auto" ><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                    </div>
                </form>
        </x-modal-stars>
    </div>
    @endcan

    <div class="max-w-7xl py-12 gap-4 mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row overflow-hidden">

        <div id="categoriesPanel" class="flex-[1_1_100%] flex flex-col xl:flex-row gap-4 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
            <div class="flex-1 p-4 sm:p-8 bg-black/60 backdrop-blur shadow-sm sm:rounded-lg transition-all duration-500 ease-in-out">
                <div class="p-5 flex justify-between">
                    <h2 id="titreCategories" class="font-semibold text-2xl truncate text-gray-50 leading-tight">{{__('Entraînements')}}</h2>
                    <div class="flex items-center gap-3">

                        <div id="deployFiltre" class="select-none cursor-pointer flex flex-col space-y-4 shadow-lg"
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

                            <div id="contentFiltre" class="filtreContent grid grid-cols-1 gap-4 max-h-0 overflow-hidden transition-[max-height] duration-500">
                                <div class="p-5">
                                    <div class="w-40">
                                        <label for="tri">Trier par</label>
                                        <select class="w-full text-gray-800" id="tri" name="tri">
                                            <option value="0" selected>Par défaut</option>
                                            <option value="1">Ajout récent</option>
                                            <option value="2">Nombre d'exercices (croissants)</option>
                                            <option value="3">Nombre d'exercices (décroissants)</option>
                                            <option value="4">Alphabétique</option>
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label for="rechercheCategorie">Rechercher par catégorie</label>
                                        <input class="text-gray-800" type="text" id="rechercheCategorie" placeholder="Catégorie">
                                    </div>

                                    <div class="w-40">
                                        <label for="rechercheMotCle">Rechercher par exercice</label>
                                        <input class="text-gray-800" type="text" id="rechercheMotCle" placeholder="Exercice">
                                    </div>

                                    <fieldset class="w-40">
                                        <legend>Type de fichier:</legend>
                                        <div class="grid grid-cols-2">
                                            <div><input type="radio" id="typeTous" name="type" value="4" checked><label for="typeTous">Tous</label></div>
                                            <div><input type="radio" id="typeImage" name="type" value="1"><label for="typeImage">Image</label></div>
                                            <div><input type="radio" id="typeVideo" name="type" value="2"><label for="typeVideo">Vidéo</label></div>
                                            <div><input type="radio" id="typePDF" name="type" value="3"><label for="typePDF">PDF</label></div>
                                        </div>
                                        <div><input type="radio" id="typeNone" name="type" value="0"><label for="typeNone">Aucun fichier</label></div>
                                    </fieldset>

                                    <button type="button" class="bg-gray-300 border border-black rounded" id="reset">Réinitialiser</button>
                                </div>
                            </div>
                        </div>

                        @can('gestion_entrainements')
                            <button id="ajoutCategorie" type="button" class="addCategorie text-gray-300 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1">
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

                <div id="boiteCategories" class="overflow-y-auto max-h-[56rem] p-6 text-gray-900 space-y-4">
                    <div id="listeCategories" class="space-y-4"></div>
                </div>
            </div>
        </div>

        <div
            id="include"
            class="opacity-0 scale-95 flex-[0_1_0%] transform transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] space-y-4 origin-right rounded-lg overflow-auto"
        >
            <div>
                @include("exercice.exercice")
            </div>
            <div class="max-h-[10rem]">
                @include("forum.forum")
            </div>
        </div>
    </div>

</x-app-layout>
<script src="{{ asset('js/exercices.js') }}"></script>
