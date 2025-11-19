    <div class="flex flex-col max-w-7xl mx-auto text-gray-50">
        <div class="flex-1 bg-black/60 backdrop-blur shadow-sm sm:rounded-lg">
            <section class="rounded-t-lg p-3  mb-0 bg-white/30 shadow-lg backdrop-blur">
                <div class="px-3 pt-6 flex justify-between">
                    <h2 id="titrePage" class="font-semibold text-2xl text-gray-50 leading-tight">
                        {{ __('Activité ') }}
                    </h2>


                    @if (request()->routeIs('inscriptions'))
                    <button title="Fermer la conversation" class="closeActivite text-red-300 hover:text-red-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                        </svg>
                    </button>
                    @endif


                </div>
            </section>

            <div id="detailActivite"
                class="p-3 relative max-h-[calc(100vh-20rem)] flex flex-col overflow-hidden text-white">
                <div class="flex space-x-2 py-1">
                    <p>Description: </p>
                    <p id="detailDescription">desc</p>
                </div>
                <div class="flex space-x-2 py-1">
                    <p>Date de début: </p>
                    <p id="detailDateDebut">date</p>
                </div>
                <div class="flex space-x-2 py-1">
                    <p>Date de fin: </p>
                    <p>À venir</p>
                </div>
                <div class="flex space-x-2 py-1">
                    <p>Jour(s) de la semaine:
                    <p id="jourSemaine">jour</p>
                </div>
                <div class="flex space-x-2 py-1">
                    <p>Heure de début: </p>
                    <p id="detailDebut">heured</p>
                </div>
                <div class="flex space-x-2 py-1">
                    <p>Heure de fin: </p>
                    <p id="detailFin">heuref</p>
                </div>
                <div class="flex space-x-2 py-1">
                    <p>Coût de l'inscription: </p>
                    <p id="detailPrix">prix</p>
                </div>
                <div class="flex space-x-2 py-1">
                    <p>Catégorie de l'activité: </p>
                    <p id="detailCategorie">categorie</p>
                </div>
                <div class="self-center">
                    <button id="envoiInscription" type="button" class="bg-yellow-600 flex p-1 border border-white rounded">
                        S'inscrire
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pl-2 lucide lucide-mail-icon lucide-mail"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                    </button>
                    <div id="dejaInscrit" class="hidden bg-gray-500 flex p-1 border border-white rounded">
                    <p >Demande envoyée.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/inscriptions.js') }}"></script>
