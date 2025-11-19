<x-modal-stars title="Ajouter un rôle" id='role-add-modal'>
    <form method="POST" action="{{ route('roles.store') }}" class=" space-y-2">
        @csrf

        <!-- Nom -->
        <div>
            <label>{{__('Nom du rôle')}}: <input type="text" name="nom" id="addNom" class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"></label>
            <span id="errorNomAdd" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <!-- Description -->
        <div>
            <label for="addDesc">
                {{ __('Description') }}:
                <textarea
                    name="desc"
                    id="addDesc"
                    class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 min-h-[5rem] max-h-[20rem]"
                ></textarea>
            </label>
            <span id="errorDescAdd" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <!-- Permissions -->
        <label>{{ __('Permissions') }}:
            <select id="addPermissions" name="permissions[]" multiple class="text-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                @foreach ($permissions as $permission)
                <option value="{{ $permission->id }}"
                        data-couleur="{{ $permission->couleur }}"
                        data-formatted="{{ $permission->formatted_name }}">
                    {{ $permission->formatted_name }}
                </option>
                @endforeach
            </select>
            <span id="errorPermAdd" class="hidden text-red-300 text-sm mt-1"></span>
        </label>

        <!-- Membre CA -->
        <div class="flex flex-col">
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input
                    type="checkbox"
                    id="membreCa"
                    name="membre_ca"
                    value="1"
                    class="h-5 w-5 border border-gray-400 rounded text-gray-50 bg-white/10 text-red-400 shadow-sm focus:ring-red-400 cursor-pointer"
                >
                <div>
                    {{ __('Membre') }}
                    <abbr title="Conseil d'administration" class="cursor-help hover:font-semibold">
                        {{__('CA')}}
                    </abbr>
                </div>
            </label>
            <span id="errorCAAdd" class="hidden text-red-300 text-sm mt-1"></span>
        </div>

        <!-- Soumettre -->
        <div>
            <input type="hidden" id="addRoleId" name="id">
            <button type="button" id="addSave" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full">
                {{__('Créer le rôle')}}
            </button>
        </div>
    </form>
</x-modal-stars>
