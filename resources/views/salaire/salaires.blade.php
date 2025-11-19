@php
$titles = [
    1 => 'Poste',
    0 => 'Arbitre',
    2 => 'Arbitre (Autre)'
];
$subtitles = [
    1 => 'Salaire par séance',
    0 => 'Salaire par match',
    2 => 'Salaire par match'
];
@endphp
<x-app-layout>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div id="alert-message" style="display: none;" class="px-4 py-2 text-white font-semibold mb-4 rounded-md"></div>

            @foreach ($groupedPostes as $ordre => $items)
                <div id="{{ $ordre == 1 ? 'postesContainer' : ($ordre == 0 ? 'arbitresContainer' : 'autresContainer') }}"
                    class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg {{ $ordre != 1 ? 'mt-8' : '' }}">

                    <div class="flex flex-nowwrap items-center">
                        <div class="flex justify-between w-full text-white mb-2 mt-2">
                            <p class="text-center text-2xl font-bold">{{ $titles[$ordre] }}</p>
                            <p class="text-center text-2xl font-bold">{{ $subtitles[$ordre] }}</p>
                        </div>

                        @can('gestion_salaires')
                        <div class="addSalaire" class="w-full flex justify-end">
                            <button type="button" data-url="{{ route('postes.store') }}" data-ordre="{{ $ordre }}" class="open-add-form text-gray-300 ml-2 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            </button>
                        </div>
                        @endcan
                    </div>

                    @foreach ($items as $poste)
                        <div class="px-4 py-2 mb-2 bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition poste-item" data-id="{{ $poste->id }}">
                            <div class="flex justify-between items-center">
                                <div class="flex flex-nowwrap justify-between items-center w-full mr-1 text-white">
                                    <p class="font-semibold text-lg poste-nom">{{ $poste->nom_poste }}</p>
                                    <p class="text-lg poste-salaire bg-white/10 rounded-full flex justify-end items-center min-w-[5rem] max-h-[2rem] px-2 py-1">{{ $poste->salaire }} $</p>
                                </div>
                                <div class="flex">
                                    @can('gestion_salaires')
                                    <div class="flex gap-2 pl-4">
                                        <!-- Modification -->
                                        <div class="modify">
                                            <button type="button" data-mode="edit" data-url="{{ route('postes.update', $poste->id) }}" data-ordre="{{ $ordre }}" class="edit-poste-btn flex items-center justify-center w-10 h-10 bg-orange-400/30 hover:bg-orange-600/70 text-white rounded-lg transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                                <path d="m15 5 4 4"/>
                                            </svg>
                                            </button>
                                        </div>
                                        <!-- Suppression -->
                                        <form method="POST" action="{{ route('postes.destroy', $poste->id) }}" class="inline delete-poste-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center justify-center w-10 h-10 bg-red-600/30 hover:bg-red-700/70 text-white rounded-lg transition delete-poste" title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-700 m-auto" ><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

        </div>
    </div>

    <!-- Formulaire flottant d'ajout/édition -->
    <x-modal-stars title="Ajouter une équipe" id='editPostePanel'>
        <form id="editPosteForm" method="POST">
            @csrf
            @method('POST') <!-- sera remplacé par JS si besoin -->

            <div class="mb-4">
                <label class="block font-semibold">Nom du poste</label>
                <input type="text" name="nom_poste" id="nomPosteInput" required class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
            </div>
            <div class="mb-4">
                <label class="block font-semibold">Salaire</label>
                <input type="number" name="salaire" id="salaireInput" value="0.00" min="0" step="0.01" required class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
            </div>
            <input type="hidden" name="ordre_affichage" id="ordreAffichageInput" value="1">
            <input type="hidden" name="id_etat" value="1">

            <div class="flex justify-end gap-2">
                <button type="submit" class="px-4 py-2 w-full bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Enregistrer</button>
            </div>
        </form>
    </x-modal-stars>

    <script src="{{asset("js/salaires.js")}}"></script>
</x-app-layout>
