<section>
    <div class="flex-auto mx-auto">
        <div
            class="overflow-y-auto max-h-[500px] rounded-lg ring-2 ring-red-300 shadow-sm scroll-smooth
                    transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
            :class="openFilters ? 'xl:max-w-3xl' : 'xl:max-w-7xl'"
        >

            <table class="min-w-full table-fixed rounded-lg shadow-sm overflow-hidden">

                <thead class="bg-red-900/80 text-red-300 uppercase shadow text-sm sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-red-700/30" data-order-asc="2" data-order-desc="3">
                            <div class="flex justify-between items-center">
                                {{ __('Prénom') }}
                                <span class="sort-indicator"></span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-red-700/30" data-order-asc="4" data-order-desc="5">
                            <div class="flex justify-between items-center">
                                {{ __('Nom') }}
                                <span class="sort-indicator"></span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-red-700/30" data-order-asc="6" data-order-desc="7">
                            <div class="flex justify-between items-center">
                                {{ __('Courriel') }}
                                <span class="sort-indicator"></span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left">{{ __('État') }}</th>
                        <th class="px-4 py-3 text-left">{{ __('Rôles') }}</th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-red-700/30" data-order-asc="0" data-order-desc="1">
                            <div class="flex justify-between items-center">
                                {{ __('Date création') }}
                                <span class="sort-indicator"></span>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left">{{ __('Modifier') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-300 " id="listUsers">
                    @forelse ($users as $user)
                    <tr class="min-h-[2rem] text-gray-100 group hover:bg-white/30 transition-colors duration-200" data-user='@json($user)'>
                        <td class="px-4 py-3 font-semibold">{{ $user->prenom }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $user->nom }}</td>
                        <td class="px-4 py-3 text-blue-600 font-bold transition-colors duration-200 group-hover:text-blue-300">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                {{ $user->etat->nom_etat === 'Actif'
                                    ? 'bg-green-100 text-green-700'
                                    : ($user->etat->nom_etat === 'Inactif'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-red-100 text-red-700')
                                }}">
                                {{ $user->etat->nom_etat ?? 'Inconnu' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @if (count($user->roles) > 0)
                                    @foreach ($user->roles as $role)
                                        <span class="bg-blue-100 text-gray-700 text-center text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ $role->nom_role }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-400 text-xs">Aucun rôle</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 font-semibold">{{$user->created_at}}</td>
                        <td class="pr-4">
                            <button type="button"
                                class="edit-user w-full flex justify-center items-center transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110"
                                name="id_user" value="{{ $user->id }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-pencil-icon lucide-pencil"
                                >
                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                    <path d="m15 5 4 4"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-4 py-3 text-center text-gray-500" colspan="5">
                            {{__('Aucun utilisateur ne correspond à la recherche.')}}
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</section>
