
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

    <div class="flex flex-col bg-black/60 backdrop-blur sm:rounded-lg shadow-sm p-10 max-w-7xl mx-auto sm:px-6 lg:px-8 px-10">
        <div class="w-full flex justify-end gap-2">
            @can('gestion_entrainements')
            <button type="button" id="modifierExercice" class="text-gray-300 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen">
                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>
                </svg>
            </button>
            @endcan

            <button id="closeExercice" type="button" class="closeExercice text-red-300 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
        <div id="exercice" class="flex flex-col text-gray-50">

        </div>
    </div>
