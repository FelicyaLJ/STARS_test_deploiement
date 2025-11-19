<x-modal-stars title="Modifier une catégorie d'évenement" id='categorie-evenement-edit-modal'>
    <form method="POST" action="{{ route('evenement.categorie.store') }}">
        @csrf

        <div>
            <label>{{__('Nom de la catégorie')}}: <input type="text" name="nom_categorie" id="editNomCategorie" class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"></label>
            <span id="errorNomCategorieEdit" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <div class="mt-[3%]">
            <label for="couleur_categorie">{{__('Couleur associée à la catégorie')}}:</label>
            <input type="color" id="editCouleurCategorie" name="terrain_couleur"
                class="border rounded-lg w-full h-10 mb-2 cursor-pointer border border-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                value="#000000">
            <span id="errorCouleurCategorieEdit" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <input type="hidden" id="editCategorieId" name="id">
        <div class="flex gap-2">
            <button type="button" id="deleteCategorieEvenement" class="bg-red-500 text-white px-4 py-2 rounded mt-2 flex justify-center basis-1/6">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
            <button type="button" id="editSaveCategorie" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mt-2 w-full">
                {{__('Enregistrer')}}
            </button>
        </div>
    </form>
</x-modal-stars>
