<x-modal-stars title="Ajouter une publication d'actualité" id='modal_publication'>
    <form id="form_actualite" method="POST" action="{{ route('actualite_create') }}">
        @csrf
        <!--Titre-->
        <div>
            <label>{{__('Titre ')}}:
                <input type="text" name="titre" id="titre" class="border rounded w-full p-2 mb-2 text-gray-800">
            </label>
            <span id="error_titre" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Message-->
        <div>
            <label>{{__('Message ')}}:</label>
            <div id="editor-wrapper" class="border rounded w-full h-[20em] p-0 mb-2 text-gray-800 bg-white overflow-auto">
                <div id="editor"></div>
            </div>
            <span id="error_texte" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <input type="hidden" id="id_actualite" name="id_actualite"/>
        <!--Boutton-->
        <div id="bulle_bouton_actualite">
            <button type="button" id="ajouter_actualite" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full">
                {{__('Ajouter la publication d\'actualité')}}
            </button>
        </div>
    </form>
</x-modal-stars>
