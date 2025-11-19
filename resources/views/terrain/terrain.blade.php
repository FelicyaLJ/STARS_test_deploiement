<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl gap-4 mx-auto sm:px-6 lg:px-8 flex flex-col xl:flex-row">
            @include('terrain.partials.filter-terrains-form')

            <div class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg h-full basis-2/3">

                <div class="flex justify-between mb-2">
                    <h2 class="font-semibold text-2xl text-gray-50 leading-tight">
                        {{ __('Terrains') }}
                    </h2>
                    @can('gestion_terrains')
                    <button id="add_terrain" type="button" class="text-gray-300 flex
                    transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]
                    hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                    @endcan
                </div>
                <div id="bulle_terrain" class="mt-[5%] mb-[4%] h-[36rem] overflow-auto">
                    @foreach ($terrains as $terrain)
                        @if ($terrain->visible || auth()->user()->can('gestion_terrains'))
                        <div id="terrain_{{$terrain->id}}"
                            class="mb-[5%] relative flex gap-2 rounded-lg p-4 mr-2 flex flex-col sm:flex-row"
                            style="
                                background-color: {{ $terrain->couleur }}33;
                                border: 1px solid {{ $terrain->couleur }}66;
                            ">
                            <div class="w-full rounded-lg p-3 bg-black/60 basis-5/6">
                                <h3 class="font-semibold text-xl text-gray-50 leading-tight w-full nom_terrain">
                                    {{$terrain->nom_terrain}}
                                </h3>

                                <p class="underline text-blue-600 adresse">
                                    @if (!$terrain->longitude > 0)
                                        <a href="https://www.google.com/maps/search/?api=1&query={{$terrain->adresse}}" target="_blank">
                                            {{$terrain->adresse}}
                                        </a>
                                    @else
                                        <a href="https://www.google.com/maps/search/?api=1&query={{$terrain->latitude . ',' . $terrain->longitude}}" target="_blank">
                                            {{$terrain->adresse}}
                                        </a>
                                    @endif
                                </p>

                                <p class="text-gray-300 description">{{$terrain->description}}</p>

                                <div class="flex text-gray-300 justify-end terrain_parent">
                                    @if($terrain->id_parent)
                                        @php
                                            $parent = $terrains->firstWhere('id', $terrain->id_parent);
                                        @endphp
                                        @if($parent)
                                            <p class="mr-[1%]">Terrain parent :</p>
                                            <p class="cible_enfant">{{$parent->nom_terrain}}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col bulle_ext basis-1/6">
                                <div class="bg-black/60 rounded-lg flex justify-center py-2 px-3">
                                    @if ($terrain->visible)
                                    <span class="span_etat m-auto text-green-600 font-bold uppercase text-sm">{{__('Disponible')}}</span>
                                    @else
                                    <span class="span_etat m-auto text-yellow-600 font-bold uppercase text-sm">{{__('Non visible')}}</span>
                                    @endif
                                </div>

                                @can('gestion_terrains')
                                <div class="flex flex-row sm:flex-col justify-evenly mt-3">
                                    <button class="m-auto group w-full text-white mod_terrain transition-all duration-300 ease-out rounded-lg py-2 hover:bg-white/10 basis-1/2" value="{{$terrain->id}}" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-amber-600 m-auto">
                                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                    </button>

                                    <button class="m-auto group w-full text-white delete_terrain transition-all duration-300 ease-out rounded-lg py-2 hover:bg-white/10 basis-1/2" type="button" value="{{$terrain->id}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-600 m-auto">
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                            <path d="M3 6h18"/>
                                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                                @endcan
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!--En attendant une meilleure condition pour vérifier if user == admin -->
    @can('gestion_terrains')
        <!-- Modal ajout d'actualité -->
        @include('terrain.partials.terrain-form')
    @endcan
</x-app-layout>

<script>
    let terrains = @json($terrains);
    let etat_terrains = null;
    const canManageTerrains = @json(auth()->user()->can('gestion_terrains'));
</script>
<script src="{{ asset('js/terrain.js') }}"></script>
