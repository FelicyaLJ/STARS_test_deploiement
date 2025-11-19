<section>
    <div id="no-select" class="flex flex-col text-gray-50 justify-center items-center">
        <span class="px-4 py-3 text-center text-2xl">{{__('Veuillez appuyer sur un rôle à consulter.')}}</span>
        <span class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-mouse-pointer-icon lucide-square-mouse-pointer"><path d="M12.034 12.681a.498.498 0 0 1 .647-.647l9 3.5a.5.5 0 0 1-.033.943l-3.444 1.068a1 1 0 0 0-.66.66l-1.067 3.443a.5.5 0 0 1-.943.033z"/><path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6"/></svg>
        </span>
    </div>

    <div class="invisible text-gray-50 mx-2 mb-4 p-4 rounded-lg shadow-md bg-white/10 border border-white/20 rounded-xl transition-opacity duration-300 ease-in-out opacity-0 min-h-[10rem]" id="roleDetails">

        <!-- Panneau d'information -->
        <div class="space-y-2">
            <div class="text-3xl font-semibold mb-1 flex justify-between">
                <h2 id="roleNom"></h2>
                <button type="button" id="edit-role" name="idRole" value="" class="transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil hover:text-orange-300">
                        <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        <path d="m15 5 4 4"/>
                    </svg>
                </button>
            </div>
            <p class="font-semibold"> {{__('Description : ')}}
                <span class="text-gray-200 font-thin break-all" id="roleDesc"></span>
            </p>
            <p class="font-semibold"> {{__('Permissions : ')}}
                <span class="text-gray-200 font-thin flex gap-2 flex-wrap" id="rolePermissions"></span>
            </p>
            <p class="font-semibold"> {{__('Membre du conseil d\'administration : ')}}
                <span class="text-gray-200 font-thin" id="roleMembreCA"></span>
            </p>
            <hr class="mt-5">
        </div>

        <!-- Formulaire modification -->
        <div id="formEdit" class="max-h-0 opacity-0 overflow-y-auto transition-all duration-500 ease-in-out px-2">
            <form method="POST" action="{{ route('roles.update') }}">
                @csrf
                <!-- Nom -->
                <div>
                    <label>{{__('Nom du rôle')}}: <input type="text" name="nom" id="editNom" class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"></label>
                    <span id="errorNomEdit" class="hidden text-red-300 text-sm mt-1"></span>
                </div>

                <!-- Description -->
                <div>
                    <label for="editDesc">
                        {{ __('Description') }}:
                        <textarea
                            name="editDesc"
                            id="editDesc"
                            class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400 min-h-[5rem] max-h-[20rem]"
                        ></textarea>
                    </label>
                    <span id="errorDescEdit" class="hidden text-red-300 text-sm mt-1"></span>
                </div>

                <!-- Permissions -->
                <label>{{ __('Permissions') }}:
                    <select id="editPermissions" name="permissions[]" multiple class="text-gray-50">
                        @foreach ($permissions as $permission)
                        <option value="{{ $permission->id }}"
                                data-couleur="{{ $permission->couleur }}"
                                data-formatted="{{ $permission->formatted_name }}">
                            {{ $permission->formatted_name }}
                        </option>
                        @endforeach
                    </select>
                    <span id="errorPermEdit" class="hidden text-red-300 text-sm mt-1"></span>
                </label>

                <!-- Membre CA -->
                <div class="flex flex-col">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            id="editMembreCA"
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
                    <span id="errorCAEdit" class="hidden text-red-300 text-sm mt-1"></span>
                </div>

                <!-- Soumettre -->
                <input type="hidden" id="editRoleId" name="id">
                <div class="flex gap-2 mt-2">
                    <button type="button" id="editDelete" class="bg-red-500 text-white px-4 py-2 rounded mt-2 flex justify-center basis-1/6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                    <button type="button" id="editSave" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full">
                        {{__('Modifier le rôle')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
