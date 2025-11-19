<x-modal-stars title="Ajouter une équipe" id='addEquipePanel'>
    <form id="addEquipeForm" method="POST" action="{{ route('equipes.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-medium text-gray-300 mb-1">Nom de l'équipe</label>
            <input type="text" name="nom_equipe" id="nomEquipeInput"
                    class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
            <div id="nomErreur" class="text-red-400 text-sm mt-1 hidden">Le nom de l'équipe est requis.</div>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-300 mb-1">Description</label>
            <input type="text" name="description" id="description"
                    class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-300 mb-1">Catégorie</label>
            <select name="id_categorie" id="id_categorie"
                    class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                @foreach ($categories as $categorie)
                    <option class="text-gray-800" value="{{ $categorie->id }}">{{ $categorie->nom_categorie }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-300 mb-1">Genre</label>
            <select name="id_genre" id="id_genre"
                    class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                @foreach ($genres as $genre)
                    <option class="text-gray-800" value="{{ $genre->id }}">{{ $genre->nom_genre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-300 mb-1">État</label>
            <select name="id_etat" id="id_etat"
                    class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                @foreach ($etats as $etat)
                    <option class="text-gray-800" value="{{ $etat->id }}">{{ $etat->nom_etat }}</option>
                @endforeach
            </select>
        </div>

        <div id="formSuccess" class="text-green-400 text-sm mt-2 hidden text-center">
            Équipe ajoutée avec succès !
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <button type="submit" class="px-4 py-2 w-full bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                Enregistrer
            </button>
        </div>
    </form>
</x-modal-stars>
