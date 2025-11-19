<x-modal-stars title="Ajouter une catégorie d'évenement" id='categorie-evenement-add-modal'>
    <form method="POST" action="{{ route('evenement.categorie.store') }}">
        @csrf

        <div>
            <label>{{__('Nom de la catégorie')}}: <input type="text" name="nom_categorie" id="addNomCategorie" class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"></label>
            <span id="errorNomCategorieAdd" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <div class="mt-[3%]">
            <label for="couleur_categorie">{{__('Couleur associée à la catégorie')}}:</label>
            <input type="color" id="addCouleurCategorie" name="terrain_couleur"
                class="border rounded-lg w-full h-10 mb-2 cursor-pointer border border-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                value="#000000">
            <span id="errorCouleurCategorieAdd" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <input type="hidden" id="addCategorieId" name="id">
        <button type="button" id="addSaveCategorie" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mt-2 w-full">
            {{__('Créer la catégorie')}}
        </button>
    </form>
</x-modal-stars>
