@props([
    'cat'
])

<div class="rounded-full block px-3 py-1 bg-white/20" data-cat='@json($cat)'>
    <div class="flex gap-1 items-center">
        <span class="w-3 h-3 rounded-full inline-block mr-2" style="background-color: {{ $cat->couleur }}"></span>
        <span class="break-all">{{ $cat->nom_categorie }}</span>

        @can('gestion_categorie_evenement')
        <button class="editCategorieEvenement transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
        </button>
        @endcan

    </div>
</div>
