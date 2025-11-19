<x-modal-stars title="Modifier l'utilisateur" id='user-edit-modal'>
    <form method="POST" action="{{ route('users.update') }}">
        @csrf

        <div class="flex gap-4 justify-between">
            <div>
                <label>{{__('Prénom')}}: <input type="text" name="prenom" id="editPrenom" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"></label>
                <span id="errorPrenomEdit" class="hidden text-red-300 text-sm mt-1"></span>
            </div>
            <div>
                <label>{{__('Nom')}}: <input type="text" name="nom" id="editNom" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"></label>
                <span id="errorNomEdit" class="hidden text-red-300 text-sm mt-1"></span>
            </div>
        </div>

        <div>
            <label>{{__('Email')}}:
                <input type="email" name="email" id="editEmail" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
            </label>
            <span id="errorEmailEdit" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <!-- États -->
        <label>{{__('État')}}:
            <select class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" id="editEtat" name="etat">
                @foreach ($etats as $etat)
                <option class="text-gray-800" value="{{ $etat->id }}">{{$etat->nom_etat}}</option>
                @endforeach
            </select>
        </label>

        <!-- Rôles -->
        <label>{{ __('Rôles') }}:
            <select id="editRole" name="roles[]" multiple class="mb-2">
                @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->nom_role }}</option>
                @endforeach
            </select>
        </label>

        <!-- Équipes -->
        <label>{{ __('Équipes') }}:
            <select id="editEquipes" name="equipes[]" multiple class="mb-2">
                @foreach ($equipes as $equipe)
                <option value="{{ $equipe->id }}">{{ $equipe->nom_equipe }}</option>
                @endforeach
            </select>
        </label>

        <input type="hidden" id="editUserId" name="id">
        <div class="flex gap-2">
            <button type="button" id="editDelete" class="bg-red-500 text-white px-4 py-2 rounded mt-2 flex justify-center basis-1/6">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
            <button type="button" id="editSave" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mt-2 w-full">
                {{__('Enregistrer')}}
            </button>
        </div>
    </form>
</x-modal-stars>
