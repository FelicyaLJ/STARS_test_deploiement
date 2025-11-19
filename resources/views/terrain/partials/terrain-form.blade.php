<x-modal-stars title="Ajouter un nouveau terrain" id='modal_terrain'>
    <form id="form" method="POST" action="{{ route('terrain_create_api') }}" class="overflow-y-auto max-h-[36rem] px-2">
        @csrf
        <!--Nom du terrain-->
        <div>
            <label>{{__('Nom du terrain ')}}:
                <input type="text" name="nom_terrain" id="nom_terrain" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
            </label>
            <span id="error_nom_terrain" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Description-->
        <div>
            <label>{{__('Description ')}}:
                <textarea type="text" name="description" id="description" class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 min-h-[5rem] max-h-[20rem]"></textarea>
            </label>
            <span id="error_description" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Adresse-->
        <div>
            <label for="adresse_rue">Adresse</label>
            <div class="flex flex-col justify-between gap-3 mt-[5px]">
                <!--Rue-->
                <div>
                    <input type="text" id="adresse_rue" name="adresse_rue" placeholder="Adresse de rue (e.g. 4210 Boul Bourque)" maxlength="60" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                    <span id="error_adresse_rue" class="hidden text-red-300 text-sm"></span>
                </div>
                <!--Ville-->
                <div>
                    <input type="text" id="adresse_ville" name="adresse_ville" placeholder="Ville (e.g. Sherbrooke)" maxlength="80" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                    <span id="error_adresse_ville" class="hidden text-red-300 text-sm"></span>
                </div>
                <!--Postal-->
                <div>
                    <input type="text" id="adresse_postal" name="adresse_postal" placeholder="Code postal (e.g. J1L-1W6)" maxlength="7" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" oninput="this.value = this.value.toUpperCase()">
                    <span id="error_adresse_postal" class="hidden text-red-300 text-sm"></span>
                </div>
            </div>
        </div>
        <!--Couleur du terrain-->
        <div class="mt-[3%]">
            <label for="terrain_couleur">{{__('Couleur associée au terrain')}}:</label>
            <input type="color" id="terrain_couleur" name="terrain_couleur"
                class="border rounded-lg w-full h-10 mb-2 cursor-pointer border border-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                value="#000000">
            <span id="error_terrain_couleur" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Visible-->
        <div class="mt-[3%] flex items-center gap-2">
            <input type="checkbox" id="terrain_visible" name="terrain_visible" class="border border-gray-400 rounded text-gray-50 bg-white/10 text-red-400 shadow-sm focus:ring-red-400">
            <label for="terrain_visible" class="">{{__('Terrain visible')}}</label>
            <span id="error_terrain_visible" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!--Checkbox pour terrain parent -->
        <div id="bulle_checkbox_parent" class="mt-[3%] flex items-center gap-2">
            <input type="checkbox" id="terrain_est_enfant" name="terrain_est_enfant" class="border border-gray-400 rounded text-gray-50 bg-white/10 text-red-400 shadow-sm focus:ring-red-400">
            <label>{{__('Ce terrain a un terrain parent')}}
            </label>
            <span id="error_terrain_parent_check" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!-- Bulle Terrain parent -->
        <div id="bulle_terrain_parent" class="hidden">
            <label>{{__('Terrain parent')}}:
                <select id="etat_terrain_parent" name="etat_terrain_parent" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                    <option class="text-gray-800" value="0">{{__('Aucun terrain parent ')}}</option>
                    @foreach ($terrains->sortBy('nom_terrain') as $terrain)
                        @if (!$terrain->id_parent)
                            <option class="text-gray-800" value="{{$terrain->id}}">{{ $terrain->nom_terrain }}</option>
                        @endif
                    @endforeach
                </select>
            </label>
        </div>
        <!--Checkbox pour adresse longitude/latitude -->
        <div class="mt-[3%] flex items-center gap-2">
            <input type="checkbox" id="adresse_longi_lati" name="adresse_longi_lati" class="border border-gray-400 rounded text-gray-50 bg-white/10 text-red-400 shadow-sm focus:ring-red-400">
            <label>{{__('Ajouter une adresse avec longitude/latitude')}}
            </label>
            <span id="error_terrain_etat" class="hidden text-red-300 text-sm mt-1"></span>
        </div>
        <!-- Bulle adresse longitude/latitude -->
        <div id="bulle_longi_lati" class="hidden">
            <div>
                <label>{{__('Latitude ')}}:
                    <input type="text" name="terrain_latitude" id="terrain_latitude" maxlength="9" placeholder="46.050858" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" value="0">
                </label>
                <span id="error_latitude" class="hidden text-red-300 text-sm mt-1"></span>
            </div>
            <div>
                <label>{{__('Longitude ')}}:
                    <input type="text" name="terrain_longitude" id="terrain_longitude" maxlength="9" placeholder="73.726255" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" value="0">
                </label>
                <span id="error_longitude" class="hidden text-red-300 text-sm mt-1"></span>
            </div>
        </div>
        <!--Id-->
        <input type="hidden" id="id_terrain" name="id_terrain"/>
        <!--Boutton-->
        <div id="bulle_buton">
        </div>
    </form>
</x-modal-stars>
