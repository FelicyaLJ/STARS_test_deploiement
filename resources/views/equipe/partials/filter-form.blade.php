<div
    class="bg-black/60 backdrop-blur shadow sm:rounded-lg overflow-hidden transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
    :class="openSidebar
        ? 'basis-full xl:basis-1/3 p-8 opacity-100'
        : 'basis-0 xl:basis-0 p-0 opacity-0'"
>
    <div class="flex justify-between items-center mb-4 text-gray-50">
        <h3 class="text-2xl font-semibold">Filtrer</h3>
        <button
            @click="openSidebar = false"
            class="text-red-300 hover:text-red-600 transition"
            title="Masquer le filtre"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>
        </button>
    </div>

    <!-- Filtre form -->
    <div x-show="openSidebar" x-transition>
        <form id="filtrerEquipes" method="GET" class="space-y-4">
            @csrf
            @method('GET')

            <div>
                <label class="block font-medium text-gray-300 mb-1">Nom de l'équipe</label>
                <input type="text" name="search" id="nomPosteInput"
                    class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition">
            </div>

            <div>
                <label class="block font-medium text-gray-300 mb-1">Catégorie</label>
                <select id="id_categorie_filtre" name="id_categorie_filtre"
                        class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition">
                    <option class="text-gray-800" value="">Toutes les catégories</option>
                    @foreach ($categories as $categorie)
                        <option class="text-gray-800" value="{{$categorie->id}}">{{$categorie->nom_categorie}}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-300 mb-1">Genre</label>
                <select id="id_genre_filtre" name="id_genre_filtre"
                        class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition">
                    <option class="text-gray-800" value="">Tous les genres</option>
                    @foreach ($genres as $genre)
                        <option class="text-gray-800" value="{{$genre->id}}">{{$genre->nom_genre}}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-300 mb-1">État</label>
                <select id="id_etat_filtre" name="id_etat_filtre"
                        class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition">
                    <option class="text-gray-800" value="">Tous les états</option>
                    @foreach ($etats as $etat)
                        <option class="text-gray-800" value="{{$etat->id}}">{{$etat->nom_etat}}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="ordre_affichage" id="ordreAffichageInput" value="1">
            <input type="hidden" name="id_etat" value="1">

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="cancelEditBtn"
                        class="bg-gray-500/30 transition-colors duration-300 hover:bg-gray-400/70 text-white px-4 py-2 rounded mt-4 w-full">
                    Réinitialiser
                </button>
                <button type="submit"
                        class="bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded mt-4 w-full">
                    Filtrer
                </button>
            </div>
        </form>
    </div>
</div>
