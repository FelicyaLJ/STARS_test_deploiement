<x-modal-stars title="Ajouter un nouvel évenement" id='modal_evenement'>
    <form id="form_evenement" method="POST" action="{{ route('evenements.create.api') }}" class="max-w-md mx-auto space-y-4 ">
        @csrf
        <div class="max-h-[36rem] overflow-auto px-2">
            <!-- Nom + Description -->
            <div class="space-y-2 mb-2">
                <div>
                    <label>{{__('Nom de l\'évenement')}}:
                        <input type="text" name="nom_evenement" id="nom_evenement"
                            class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                    </label>
                    <span id="error_nom_evenement" class="hidden text-red-300 text-sm"></span>
                </div>

                <div>
                    <label>{{__('Description')}}:
                        <textarea name="description" id="description"
                            class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 min-h-[5rem] max-h-[20rem]"></textarea>
                    </label>
                    <span id="error_description" class="hidden text-red-300 text-sm"></span>
                </div>
            </div>

            <!-- Type -->
            <div id="type_wrapper">
                <label class="block font-medium text-gray-50">
                    {{ __("Type d'événement") }}:
                </label>

                <div class="flex items-center gap-6 mb-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="radio"
                            name="type_evenement"
                            id="type_simple"
                            value="simple"
                            checked
                            class="appearance-none w-4 h-4 border border-gray-400 rounded-full bg-white/10 checked:bg-red-300 checked:ring-2 checked:ring-red-400 checked:ring-offset-2 checked:ring-offset-transparent transition-all duration-200 focus:outline-none cursor-pointer"
                        >
                        <span class="text-gray-100 select-none">{{__('Unique')}}</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="radio"
                            name="type_evenement"
                            id="type_recurrent"
                            value="recurrent"
                            class="appearance-none w-4 h-4 border border-gray-400 rounded-full bg-white/10 checked:bg-red-300 checked:ring-2 checked:ring-red-400 checked:ring-offset-2 checked:ring-offset-transparent transition-all duration-200 focus:outline-none cursor-pointer"
                        >
                        <span class="text-gray-100 select-none">{{__('Récurrent')}}</span>
                    </label>
                </div>
            </div>


            <!-- Dates -->
            <div id="date_simple_wrapper" class="mb-2">
                <label>{{ __('Date') }}:
                    <input type="date" name="date" id="date" value="{{ old('date') }}"
                        class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                </label>
                <span id="error_date" class="hidden text-red-300 text-sm"></span>
            </div>

            <div id="date_range_wrapper" class="hidden space-y-2 mb-2">
                <div class="grid grid-cols-2 gap-2 ">
                    <label>{{ __('Date de début') }}:
                        <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut') }}"
                            class="border border-gray-400 rounded w-full p-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                    </label>
                    <label>{{ __('Date de fin') }}:
                        <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin') }}"
                            class="border border-gray-400 rounded w-full p-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                    </label>
                </div>
                <span id="error_date_range" class="hidden text-red-300 text-sm"></span>

                <div class="">
                    <label class="block font-medium text-gray-50 mb-1">{{ __('Répéter les jours suivants') }}:</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @php
                            $days = [
                                'monday' => 'Lundi',
                                'tuesday' => 'Mardi',
                                'wednesday' => 'Mercredi',
                                'thursday' => 'Jeudi',
                                'friday' => 'Vendredi',
                                'saturday' => 'Samedi',
                                'sunday' => 'Dimanche',
                            ];
                        @endphp
                        @foreach ($days as $key => $label)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="jours[]" value="{{ $key }}"
                                    class="border jours border-gray-400 rounded bg-white/10 text-red-400 shadow-sm focus:ring-red-400">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <span id="error_jours" class="hidden text-red-300 text-sm"></span>
                </div>
            </div>

            <!-- Heures -->
            <div class="grid grid-cols-2 gap-2 mb-2">
                <div>
                    <label>{{__('Temps de départ')}}:
                        <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut') }}"
                            class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                    </label>
                    <span id="error_heure_debut" class="hidden text-red-300 text-sm"></span>
                </div>
                <div>
                    <label>{{__('Temps de la fin')}}:
                        <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin') }}"
                            class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                    </label>
                    <span id="error_heure_fin" class="hidden text-red-300 text-sm"></span>
                </div>
            </div>

            <!-- Catégorie + Terrain -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label>{{ __('Catégorie d\'évenement') }}:
                        <select id="categorie_evenement" name="categorie_evenement"
                            class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 focus:border-red-400 focus:outline-none">
                            <option value="0" class="text-gray-800">{{ __('Choisir une catégorie') }}</option>
                            @foreach ($categories as $categorie_evenement)
                                <option value="{{ $categorie_evenement->id }}" class="text-gray-800">
                                    {{ $categorie_evenement->nom_categorie }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <span id="error_categorie" class="hidden text-red-300 text-sm"></span>
                </div>

                <div>
                    <label>{{ __('Terrain') }}:
                        <select id="terrain_evenement" name="terrain_evenement"
                            class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 focus:border-red-400 focus:outline-none">
                            <option value="0" class="text-gray-800">{{ __('Choisir un terrain') }}</option>
                            @foreach ($terrains->sortBy('nom_terrain') as $terrain)
                                <option value="{{ $terrain->id }}" class="text-gray-800">
                                    {{ $terrain->nom_terrain }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <span id="error_terrain" class="hidden text-red-300 text-sm"></span>
                </div>
            </div>

            <!-- Équipes -->
            <div>
                <label>{{ __('Équipes') }}:
                    <select id="equipe_evenement" name="equipes[]" multiple
                        class="text-gray-50">
                        @foreach ($equipes as $equipe)
                            <option value="{{ $equipe->id }}">{{ $equipe->nom_equipe }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <!-- Prix -->
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="si_evenement_prix" name="si_evenement_prix"
                    class="border border-gray-400 rounded bg-white/10 text-red-400 shadow-sm focus:ring-red-400">
                <label for="si_evenement_prix">{{__('Ajouter un coût d\'inscription')}}</label>
            </div>

            <div id="bulle_evenement_prix" class="hidden">
                <label>{{__('Prix d\'inscription')}}:
                    <input type="text" name="prix_evenement" id="prix_evenement" maxlength="5" placeholder="19.99"
                        class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 focus:border-red-400 focus:outline-none"
                        value="0">
                </label>
                <span id="error_prix" class="hidden text-red-300 text-sm"></span>
            </div>
        </div>

        <!-- Bouton -->
        <input type="hidden" id="id_evenement" name="id_evenement" />
        <div id="bulle_bouton_evenement">
            <button type="button" id="ajouter_evenement"
                class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full hover:bg-blue-600 transition">
                {{__('Ajouter l\'évenement')}}
            </button>
        </div>
    </form>

</x-modal-stars>
