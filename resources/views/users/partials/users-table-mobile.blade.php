<section>
    <div class="overflow-x-auto scroll-smooth">
        <table class="min-w-full ring-1 ring-gray-200 shadow-sm table-fixed">
            <thead class="bg-gray-100 text-gray-700 uppercase text-sm hidden md:table-header-group">
                <tr>
                    <th class="px-4 py-3 text-left">{{ __('Prénom') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Nom') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Courriel') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('État') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('Rôles') }}</th>
                    <th class="px-4 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-red-300 text-gray-50" id="listUsersMobile">
                @forelse ($users as $user)
                <tr class="block md:table-row group hover:bg-white/30 text-lg transition-colors duration-200 p-4 md:p-0" data-user='@json($user)'>
                    <td class="block md:table-cell px-4 py-3">
                        <span class="text-gray-300 md:hidden font-semibold">{{ __('Prénom') }}:</span> {{ $user->prenom }}
                    </td>
                    <td class="block md:table-cell px-4 py-3">
                        <span class="text-gray-300 md:hidden font-semibold">{{ __('Nom') }}:</span> {{ $user->nom }}
                    </td>
                    <td class="block md:table-cell px-4 py-3 text-blue-600">
                        <span class="text-gray-300 md:hidden font-semibold">{{ __('Courriel') }}:</span> <span class="transition-colors duration-200 group-hover:text-blue-300">{{ $user->email }}</span>
                    </td>
                    <td class="block md:table-cell px-4 py-3">
                        <span class="text-gray-300 md:hidden font-semibold">{{ __('État') }}:</span>
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
                    <td class="block md:table-cell px-4 py-3">
                        <span class="text-gray-300 md:hidden font-semibold">{{ __('Rôles') }}:</span>
                        <div class="flex flex-wrap gap-1 mt-1 md:mt-0">
                            @if (!empty($user->roles))
                                @foreach ($user->roles as $role)
                                    <span class="bg-blue-100 text-gray-700 font-semibold text-xs px-2 py-1 rounded-full">
                                        {{ $role->nom_role }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-400 text-xs">{{__('Aucun rôle')}}</span>
                            @endif
                        </div>
                    </td>
                    <td class="flex md:table-cell px-4 py-3 justify-end">
                        <a href="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr class="block md:table-row">
                    <td class="px-4 py-3 text-center text-gray-500 block md:table-cell" colspan="5">
                        {{__('Aucun utilisateur ne correspond à la recherche.')}}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
