@props([
    'equipe',
])

<div class="equipe-item p-4 bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition"
    data-equipe-id="{{ $equipe->id }}">

    <div class="flex justify-between">
        <div class="flex gap-1 sm:gap-4 items-start sm:items-center flex-col sm:flex-row">
            <p class="text-lg font-semibold text-gray-100 nom-equipe">{{ $equipe->nom_equipe }}</p>

            @auth
                @if (!$equipe->joueurs->contains(auth()->user()->id))
                <button class="rejoindre-equipe-button underline text-sm text-gray-400 hover:text-gray-500"
                    data-equipe-id="{{ $equipe->id }}"
                    data-equipe-nom="{{ $equipe->nom_equipe }}">
                    {{__('Appliquer pour rejoindre l\'équipe')}}
                </button>
                @else
                <form method="POST" action="/equipes/{{ $equipe->id }}/quitter"
                    class="quit-form inline-flex items-center ml-2 mb-0">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="quitter-equipe-button underline text-sm text-red-300 hover:text-red-400"
                        data-equipe-id="{{ $equipe->id }}"
                        data-equipe-nom="{{ $equipe->nom_equipe }}">
                        {{__('Quitter l\'équipe')}}
                    </button>
                </form>
                @endif
            @endauth
        </div>
        <p class="text-gray-400 categorie">{{ $equipe->categorie->nom_categorie }}</p>
    </div>
    <div class="flex justify-between text-sm text-gray-400 mt-1">
        <p class="genre">{{ $equipe->genre->nom_genre }}</p>
        <p class="etat">{{ $equipe->etat->nom_etat }}</p>
    </div>

    @can('gestion_equipes')
    <div class="flex gap-2 justify-end mt-3">
        <!-- Modifier -->
        <button type="button"
                data-mode="edit"
                data-url="{{ route('equipes.edit', $equipe->id) }}"
                class="edit-equipe-btn flex items-center justify-center w-10 h-10 bg-orange-400/30 hover:bg-orange-600/70 text-white rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-pencil">
                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                <path d="m15 5 4 4"/>
            </svg>
        </button>

        <!-- Supprimer -->
        <form method="POST" action="{{ route('equipes.destroy', $equipe->id) }}"
                class="delete-form" data-equipe-id="{{$equipe->id}}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="flex items-center justify-center w-10 h-10 bg-red-600/30 hover:bg-red-700/70 text-white rounded-lg transition"
                    title="Supprimer l'équipe">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-trash">
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                    <path d="M3 6h18"/>
                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
            </button>
        </form>
    </div>

    <!-- Ajouter un joueur -->
    <form method="POST" action="{{ route('equipes.joueurs.email', $equipe->id) }}" class="mt-4 form-ajout-joueur relative">
        @csrf
        <div class="text-sm text-gray-300 mb-1">Ajouter un joueur</div>
        <div class="flex gap-2 items-center relative">
            <div class="relative w-full">
                <input data-equipe-id="{{ $equipe->id }}"
                    type="email"
                    name="email"
                    placeholder="Adresse e-mail"
                    required
                    class="email-search-input border border-white/20 bg-white/10 rounded-lg px-2 py-1 w-full text-gray-100 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                <div class="email-suggestions text-gray-800 hidden absolute left-0 top-full mt-1 bg-white/90 backdrop-blur border border-gray-300 rounded-md shadow-lg max-h-40 overflow-auto z-50 w-full"></div>
            </div>
            <button type="submit"
                    class="text-gray-300 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-plus">
                    <path d="M5 12h14"/>
                    <path d="M12 5v14"/>
                </svg>
            </button>
        </div>
    </form>

    @endcan


    {{-- Ajouter demandes d'admission ici --}}


    <!-- Voir les joueurs -->
    <button type="button"
            class="toggle-joueurs mt-2 text-sm text-red-400 hover:text-red-300"
            data-equipe-id="{{ $equipe->id }}">
        ▼ Voir les joueurs
    </button>

    <div class="joueurs-list hidden mt-2 p-2 border border-white/20 rounded-lg bg-white/10 text-white max-h-64 overflow-auto">
        <!-- Contenu injecté dynamiquement via JS -->
    </div>
</div>
