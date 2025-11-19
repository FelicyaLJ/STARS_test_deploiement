<button
    id="categorie_{{ $cat->id }}"
    @click="activeTab = '{{ $cat->id }}'"
    :class="activeTab === '{{ $cat->id }}'
        ? 'bg-red-800/70 text-red-300 font-bold shadow-md'
        : 'text-gray-300 hover:text-red-300 hover:bg-red-800/30'"
    class="flex items-center justify-center gap-2 flex-1 min-w-[10rem] px-4 py-2 text-center rounded-t-xl transition-all duration-300 relative group"
>
    {{-- Nom catégorie --}}
    <span class="truncate nom_categorie_faq">{{ $cat->nom_categorie }}</span>

    {{-- Modification catégorie --}}
    @can('gestion_faq')
    <span
        class="edit_categorie_faq opacity-0 group-hover:opacity-100 text-gray-50 transition-opacity duration-300 hover:scale-110 transform cursor-pointer"
        onclick="show_form_edit_categorie_faq({{$cat->id}})"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-pencil hover:text-orange-300">
            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
            <path d="m15 5 4 4"/>
        </svg>
    </span>
    @endcan
</button>
