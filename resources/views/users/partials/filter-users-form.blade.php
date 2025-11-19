<section>
    <form class="p-4 sm:p-6 flex flex-col gap-4" id="filtreForm">
    @csrf

    <!-- Barre de recherche -->
    <div class="flex flex-col">
        <label class="font-semibold text-gray-50 mb-1" for="search">{{__('Recherche') }}</label>
        <input class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" type="text" name="search" id="search" placeholder="Rechercher">
        <span id="errorSearch" class="hidden text-red-500 text-sm mt-1"></span>
    </div>

    <!-- Ordre alphabétique -->
    <div class="flex flex-col md:hidden">
        <label class="font-semibold text-gray-50 mb-1" for="sort">{{__('Classer par')}}</label>
        <select id="sort" name="orders[]" multiple>
            <option value="0">{{ __('Plus anciens') }}</option>
            <option value="1">{{ __('Plus récents') }}</option>
            <option value="2">{{ __('Prénom (A → Z)') }}</option>
            <option value="3">{{ __('Prénom (Z → A)') }}</option>
            <option value="4">{{ __('Nom (A → Z)') }}</option>
            <option value="5">{{ __('Nom (Z → A)') }}</option>
            <option value="6">{{ __('Courriel (A → Z)') }}</option>
            <option value="7">{{ __('Courriel (Z → A)') }}</option>
        </select>
        <span id="errorSort" class="hidden text-red-500 text-sm mt-1"></span>
    </div>

    <!-- Filtrer par état -->
    <div class="flex flex-col">
        <label class="font-semibold text-gray-50 mb-1" for="filtre-etat">{{__('État')}}</label>
        <select id="filtre-etat" name="etats[]" multiple>
            @foreach ($etats as $etat)
                <option value="{{ $etat->id }}">{{$etat->nom_etat}}</option>
            @endforeach
        </select>
        <span id="errorFiltreEtat" class="hidden text-red-500 text-sm mt-1"></span>
    </div>

    <!-- Filtrer par roles -->
    <div class="flex flex-col">
        <label for="filtre-role" class="font-semibold text-gray-50 mb-1">{{ __('Rôles') }}</label>
        <select id="filtre-role" name="roles[]" multiple>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->nom_role }}</option>
            @endforeach
        </select>
        <span id="errorFiltreRole" class="hidden text-red-500 text-sm mt-1"></span>
    </div>
</form>

</section>
