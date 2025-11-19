<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                x-data="{ openSidebar: true, activeTab: 'background' }"
                class="flex flex-col xl:flex-row gap-4 transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
            >

                <!-- Sidebar -->
                <div
                    class="bg-black/60 backdrop-blur shadow sm:rounded-lg overflow-hidden transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
                    :class="openSidebar
                        ? 'basis-full xl:basis-1/3 p-4 sm:p-8 opacity-100'
                        : 'basis-0 xl:basis-0 p-0 opacity-0'"
                >
                    <div class="flex justify-between items-center mb-4 text-gray-50">
                        <h3 class="text-2xl font-semibold">{{__('Paramètres')}}</h3>
                        <button
                            @click="openSidebar = false"
                            class="text-red-300 hover:text-red-700 transition"
                            title="Masquer le menu"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Sidebar Tabs -->
                    <nav x-show="openSidebar" x-transition>
                        <ul class="space-y-2">
                            <li>
                                <button
                                    @click="activeTab = 'background'"
                                    :class="activeTab === 'background'
                                        ? 'w-full text-left px-3 py-2 bg-red-600/50 text-red-100 rounded transition'
                                        : 'w-full text-left px-3 py-2 hover:bg-red-600/20 text-gray-200 rounded transition'"
                                >
                                    {{__('Image d\'arrière-plan')}}
                                </button>
                            </li>

                            {{-- Ajouter onglets ici --}}
                            {{--<li>
                                <button
                                    @click="activeTab = 'other'"
                                    :class="activeTab === 'other'
                                        ? 'w-full text-left px-3 py-2 bg-red-600/50 text-red-100 rounded transition'
                                        : 'w-full text-left px-3 py-2 hover:bg-red-600/20 text-gray-200 rounded transition'"
                                >
                                    Autres paramètres
                                </button>
                            </li>--}}
                        </ul>
                    </nav>
                </div>

                <!-- Main Content -->
                <div
                    class="bg-black/60 backdrop-blur shadow sm:rounded-lg transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] text-gray-50"
                    :class="openSidebar
                        ? 'basis-full xl:basis-2/3 p-4 sm:p-8'
                        : 'basis-full xl:basis-full p-4 sm:p-8'"
                >
                    <div class="flex justify-between mb-6">
                        <h2 class="font-semibold text-xl leading-tight">
                            <span x-text="activeTab === 'background' ? 'Image d’arrière-plan' : 'Paramètres'"></span>
                        </h2>

                        <button
                            @click="openSidebar = !openSidebar"
                            class="flex gap-2 text-sm bg-white/10 px-3 py-1 rounded-lg hover:bg-red-600/50 transition"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-settings"
                            >
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                            </svg>
                            <span x-text="openSidebar ? 'Masquer' : 'Ouvrir'"></span>
                        </button>
                    </div>

                    <!-- Background Tab -->
                    @include('admin.partials.bg-tab')

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
