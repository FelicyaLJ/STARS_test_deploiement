<x-modal-stars title="Ajouter un nouveau FAQ" id='modal_faq'>
    <form method="POST" action="{{ route('faq_create_api') }}" enctype="multipart/form-data" id="form_faq">
        @csrf
        <!--Nom de la catégorie-->
        <div>
            <label>{{__('Nom du FAQ ')}}:
                <input type="text" name="nom_faq" id="nom_faq" class="border rounded w-full p-2 mb-2 text-gray-800">
            </label>
            <span id="error_nom_faq" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Texte-->
         <div>
            <label>{{__('Texte')}}:</label>
            <div id="editor-wrapper" class="border rounded w-full h-[20em] p-0 mb-2 text-gray-800 bg-white overflow-auto">
                <div id="editor"></div>
            </div>
            <span id="error_texte" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Fichier-->
        <div>
            <label>{{__('Inclure un fichier')}}:
                <input type="file" class="block" id="fichier_faq" name="fichier" accept=".pdf,.jpg,.jpeg,.png" class="mt-[1%]">
            </label>
            <span id="error_fichier" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Lien-->
        <div class="mt-[2%]">
            <label for="lien">{{__('Lien vers une page externe (Facultatif)')}}
                <input type="text" placeholder="exemple.com" name="lien_faq" id="lien_faq" class="border rounded w-full p-2 mb-2 text-gray-800">
            </label>
            <span id="error_lien" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Catégorie--->
        <div>
            <label>{{__('Catégorie du FAQ')}}:
                <select id="categorie_faq" name="categorie_faq" class="border rounded w-full p-2 mb-2 text-gray-800">
                    <option value="0">{{__('Choisir une catégorie')}}</option>
                    @foreach ($categories->sortBy('ordre') as $cat)
                        <option value="{{$cat->id}}">{{ $cat->nom_categorie}}</option>
                    @endforeach
                </select>
            </label>
            <span id="error_categorie" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Ordre-->
        <div id="bulle_ordre_faq" class="hidden">
            <label>{{__('Ordre du FAQ dans sa catégorie')}}:
                <select id="ordre_faq" name="ordre_faq" class="border rounded w-full p-2 mb-2 text-gray-800">
                    <option value="0">{{__('Première place')}}</option>
                </select>
            </label>
        </div>
        <!--Id-->
        <input type="hidden" id="id_faq" name="id_faq"/>
        <!--Boutton-->
        <div class="flex justify-between items-end gap-3">
            <button type="button" id="delete_faq" class="bg-red-500 text-white px-4 py-2 rounded mt-2 mt-[5%] w-fit hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-700 m-auto" ><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
            <div id="bulle_bouton_faq" class="w-full">
                <button type="button" id="ajouter_faq" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 mt-[5%]">
                    {{__('Ajouter le nouveau FAQ')}}
                </button>
            </div>
        </div>
    </form>
</x-modal-stars>
