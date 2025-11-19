<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div
                x-data="{ openFilters: false }"
                class="flex flex-col xl:flex-row gap-4 transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
            >

                <!-- Filtres -->
                <div
                    class="bg-black/60 backdrop-blur shadow sm:rounded-lg overflow-hidden transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
                    :class="openFilters
                        ? 'basis-full xl:basis-1/3 p-4 sm:p-8 opacity-100'
                        : 'basis-0 xl:basis-0 p-0 opacity-0'"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-50">Filtres</h3>
                        <button
                            @click="openFilters = false"
                            class="text-red-300 hover:text-red-700 transition"
                            title="Masquer les filtres"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="openFilters" x-transition>
                        @include('users.partials.filter-users-form')
                    </div>
                </div>

                <!-- Table -->
                <div
                    class="bg-black/60 backdrop-blur shadow sm:rounded-lg transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
                    :class="openFilters
                        ? 'basis-full xl:basis-2/3 p-4 sm:p-8 '
                        : 'basis-full xl:basis-full p-4 sm:p-8 '"
                >
                    <div class="flex justify-between mb-[3%] text-gray-50">
                        <h2 class="font-semibold text-2xl leading-tight">
                            {{ __('Utilisateurs') }}
                        </h2>

                        <div class="flex items-center gap-3">
                            <button
                                @click="openFilters = !openFilters"
                                class="flex gap-2 text-sm bg-white/10 px-3 py-1 rounded-lg hover:bg-red-400/80 transition"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-funnel-icon lucide-funnel"
                                    >
                                    <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"/>
                                </svg>
                                <span x-text="openFilters ? 'Masquer' : 'Filtres'"></span>
                            </button>

                            <button id="add-user" type="button"
                                class="text-gray-300 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-plus">
                                    <path d="M5 12h14"/>
                                    <path d="M12 5v14"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Tables --}}
                    <div>
                        <div id="tableWrapper" class="hidden md:block">
                            @include('users.partials.users-table')
                        </div>
                        <div class="md:hidden overflow-y-auto max-h-[500px]">
                            @include('users.partials.users-table-mobile')
                        </div>
                        @include('partials.pagination')
                    </div>

                </div>
            </div>

            </div>


            <!-- Formulaire ajout/modif -->
            @include('users.partials.user-form-add')
            @include('users.partials.user-form-edit')

        </div>
    </div>

</x-app-layout>

<script src="{{ asset('js/users.js') }}"></script>
