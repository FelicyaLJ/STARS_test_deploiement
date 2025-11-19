<x-modal-stars title="Ajouter un nouveau partenaire" id='modal_partenaire'>
    <form method="POST" action="{{ route('partenaire_edit_api') }}" enctype="multipart/form-data" id="form_partenaire">
        @csrf
        <!--Nom de la catégorie-->
        <div>
            <label>{{__('Nom du partenaire ')}}:
                <input type="text" name="nom" id="nom_partenaire" class="border rounded w-full p-2 mb-2 text-gray-800">
            </label>
            <span id="error_nom" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Image-->
        <div>
            <label>{{__('Inclure une image')}}:
                <input type="file" class="block" id="image_partenaire" name="image" accept=".jpg,.jpeg,.png" class="mt-[1%]">
            </label>
            <span id="error_image" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Lien-->
        <div class="mt-[2%]">
            <label for="lien">{{__('Lien vers une page externe (Facultatif)')}}
                <input type="text" placeholder="exemple.com" name="lien" id="lien_partenaire" class="border rounded w-full p-2 mb-2 text-gray-800">
            </label>
            <span id="error_lien" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Ordre-->
        <div id="bulle_ordre_affichage">
            <label>{{__('Ordre du partenaire')}}:
                <select id="ordre_affichage_partenaire" name="ordre_affichage" class="border rounded w-full p-2 mb-2 text-gray-800">
                    <option value="0">{{__('Première place')}}</option>
                </select>
            </label>
        </div>
        <!--Id-->
        <input type="hidden" id="id_partenaire" name="id"/>
        <!--Boutton-->
        <div class="flex justify-between items-end gap-3">
            <button type="button" id="delete" class="bg-red-500 text-white px-4 py-2 rounded mt-2 mt-[5%] w-fit hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-700 m-auto" ><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
            <div id="bulle_bouton_partenaire" class="w-full">
                <button type="button" id="ajouter" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 mt-[5%]">
                    {{__('Ajouter le nouveau partenaire')}}
                </button>
            </div>
        </div>
    </form>
</x-modal-stars>
