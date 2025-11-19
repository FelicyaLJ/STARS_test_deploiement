<x-modal-stars title="Ajouter une nouvelle catégorie FAQ" id='modal_categorie_faq'>
    <form method="POST" action="{{ route('categorie_faq_create_api') }}" id="form_categorie_faq">
        @csrf
        <!--Nom de la catégorie-->
        <div>
            <label>{{__('Nom de la catégorie ')}}:
                <input type="text" name="nom_categorie_faq" id="nom_categorie_faq" class="border rounded w-full p-2 mb-2 text-gray-800">
            </label>
            <span id="error_nom_categorie" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Ordre-->
        <div id="bulle_ordre_categorie_faq" class="hidden mt-[2%]">
            <label>{{__('Ordre du FAQ dans sa catégorie')}}:
                <select id="ordre_categorie_faq" name="ordre_categorie_faq" class="border rounded w-full p-2 mb-2 text-gray-800">
                    <option value="0">{{__('Première place')}}</option>
                </select>
            </label>
        </div>
        <input type="hidden" id="id_categorie" name="id_categorie"/>
        <!--Boutton-->
        <div class="flex justify-between items-end gap-3">
            <button type="button" id="delete_categorie_faq" class="bg-red-500 text-white px-4 py-2 rounded mt-2 w-3 mt-[5%] hidden w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-700 m-auto" ><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
            <div id="bulle_bouton_categorie_faq" class="w-full">
                <button type="button" id="ajouter_categorie_faq" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                    {{__('Ajouter la catégorie FAQ')}}
                </button>
            </div>
        </div>
    </form>
</x-modal-stars>
