<section>
    <form class="p-4 sm:p-6 flex flex-col gap-4" id="filtreForm">
    @csrf

        <!-- Barre de recherche -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-50 mb-1" for="search">{{__('Recherche') }}</label>
            <input class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" type="text" name="search" id="search" placeholder="Rechercher">
            <span id="errorSearch" class="hidden text-red-500 text-sm mt-1"></span>
        </div>


        <!-- Options de filtrage -->

        <div class="flex flex-col justify-evenly gap-4 md:flex-row">

            <!-- Filtrer par terrain -->
            <div class="flex flex-col basis-1/3">
                <label class="font-semibold text-gray-50 mb-1" for="filtre-terrain">{{__('Terrains')}}</label>
                <select id="filtre-terrain" class="text-gray-800" name="terrains[]" multiple>
                    @foreach ($terrains as $terrain)
                        <option value="{{ $terrain->id }}">{{$terrain->nom_terrain}}</option>
                    @endforeach
                </select>
                <span id="errorFiltreTerrain" class="hidden text-red-500 text-sm mt-1"></span>
            </div>

            <!-- Filtrer par état -->
            <div class="flex flex-col basis-1/3">
                <label class="font-semibold text-gray-50 mb-1" for="filtre-etat">{{__('États')}}</label>
                <select id="filtre-etat" class="text-gray-800" name="etats[]" multiple>
                    @foreach ($etats as $etat)
                        <option value="{{ $etat->id }}">{{$etat->nom_etat}}</option>
                    @endforeach
                </select>
                <span id="errorFiltreEtat" class="hidden text-red-500 text-sm mt-1"></span>
            </div>

            <!-- Filtrer par categorie -->
            <div class="flex flex-col basis-1/3">
                <label class="font-semibold text-gray-50 mb-1" for="filtre-categorie">{{__('Catégories')}}</label>
                <select id="filtre-categorie" class="text-gray-800" name="categories[]" multiple>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{$categorie->nom_categorie}}</option>
                    @endforeach
                </select>
                <span id="errorFiltreCategorie" class="hidden text-red-500 text-sm mt-1"></span>
            </div>

        </div>


    </form>
</section>
