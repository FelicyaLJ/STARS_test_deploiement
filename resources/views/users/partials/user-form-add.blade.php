<x-modal-stars title="Ajouter un utilisateur" id='user-add-modal'>
    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="flex gap-4 justify-between">
            <div>
                <label>{{__('Prénom')}}: <input type="text" name="prenom" id="addPrenom" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"></label>
                <span id="errorPrenomAdd" class="hidden text-red-300 text-sm mt-1"></span>
            </div>
            <div>
                <label>{{__('Nom')}}: <input type="text" name="nom" id="addNom" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"></label>
                <span id="errorNomAdd" class="hidden text-red-300 text-sm mt-1"></span>
            </div>
        </div>

        <div>
            <label>{{__('Email')}}:
                <input type="email" name="email" id="addEmail" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
            </label>
            <span id="errorEmailAdd" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <div>
            <label>{{__('Mot de passe')}}:
                <input type="password" name="password" id="addMdp" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
            </label>
            <span id="errorPasswordAdd" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <label>{{__('Confirmer mot de passe')}}:
            <input type="password" name="password_confirmation" id="addMdpConfirm" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
        </label>

        <!-- États -->
        <label>{{__('État')}}:
            <select class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" id="addEtat" name="etat">
                @foreach ($etats as $etat)
                <option class="text-gray-800" value="{{ $etat->id }}">{{$etat->nom_etat}}</option>
                @endforeach
            </select>
        </label>

        <!-- Rôles -->
        <label>{{ __('Rôles') }}:
            <select id="addRole" name="roles[]" multiple class="mb-2">
                @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->nom_role }}</option>
                @endforeach
            </select>
        </label>

        <input type="hidden" id="addUserId" name="id">
        <button type="button" id="addSave" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mt-2 w-full">
            {{__('Créer l\'utilisateur')}}
        </button>
    </form>
</x-modal-stars>
