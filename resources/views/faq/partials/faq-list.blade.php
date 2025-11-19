<div
    x-data="{ activeTab: '{{ $categories->sortBy('ordre')->first()->id }}' }"
    class="flex flex-col gap-6"
>

    {{-- Onglets --}}
    <div class="relative border-b border-red-300 overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide sm:scrollbar-show pt-1 pr-12">
            <div id="bulle_onglet" class="flex min-w-full w-max">
                @foreach ($categories->sortBy('ordre') as $cat)
                    @include('faq.partials.onglet')
                @endforeach
            </div>
        </div>

        {{-- Ajout Catégorie --}}
        @can('gestion_faq')
            <button
                id="add-cat-faq"
                type="button"
                class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-300 flex justify-center items-center
                    transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]
                    hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-plus">
                    <path d="M5 12h14"/><path d="M12 5v14"/>
                </svg>
            </button>
        @endcan
    </div>


    {{-- Contenu onglet --}}
    <div class="relative h-[36rem] overflow-auto mx-3"
    >
        @foreach ($categories->sortBy('ordre') as $cat)
            <div
                x-show="activeTab === '{{ $cat->id }}'"
                x-transition:enter="transition transform ease-out duration-500"
                x-transition:enter-start="translate-x-10 opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition transform ease-in duration-400"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="-translate-x-10 opacity-0"
                class="absolute inset-0 flex flex-col gap-2 "
            >
                <div id="bulle_cat_faq_{{$cat->id}}" class="flex justify-between py-3">
                    <span class="font-bold text-xl">{{ $cat->nom_categorie }}</span>

                    @can('gestion_faq')
                    <button
                        id=""
                        type="button"
                        class="add-sujet-faq text-gray-300 flex justify-center items-center mr-2
                            transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]
                            hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus">
                            <path d="M5 12h14"/><path d="M12 5v14"/>
                        </svg>
                    </button>
                    @endcan
                </div>

                @foreach ($cat->sujets_faq->sortBy('ordre_affichage') as $sujet)
                    @include('faq.partials.sujet-faq')
                @endforeach
            </div>
        @endforeach
    </div>
</div>
