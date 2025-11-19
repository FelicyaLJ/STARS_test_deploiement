 <script>
    const canManageMessages = @json(auth()->user()->can('gestion_messages'));
</script>
<x-modal-stars title="Signalement d'un message" id="modalSignalement">
            <form id="formSignalement" method="POST">
                @csrf
                <div>
                    <label for="raisonSignalement" class="block text-gray-50 font-medium mb-1">{{ __('Raison du signalement') }}</label>
                    <textarea id="raisonSignalement" name="raisonSignalement" required rows="4" placeholder="Expliquez brièvement la raison du signalement..." class="w-full rounded-lg border-red-300 focus:border-red-500 focus:ring focus:ring-red-300 focus:ring-opacity-50 text-gray-800 p-3 resize-none"></textarea>

                    <p id="formErrorRaisonSignalement" class="text-red-500 text-sm mt-1 hidden" >{{ __('La raison ne peut pas être vide.') }} </p>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnSendSignalement" class="adhesion-btn w-full bg-red-400 hover:bg-red-700 focus:ring-4 focus:ring-red-300 text-white font-semibold py-2.5 rounded-lg transition-colors duration-200">{{ __('Envoyer') }}</button>
                </div>
            </form>
        </x-modal-stars>
    <div class="flex flex-col max-w-7xl mx-auto text-gray-50">
        <div class="flex-1 bg-black/60 backdrop-blur shadow-sm sm:rounded-lg">
            <section class="rounded-t-lg p-3  mb-0 bg-white/10 border border-white/20 shadow-lg backdrop-blur">
                <div class="px-3 pt-6 flex justify-between">
                    <h2 id="titrePage" class="font-semibold text-2xl text-gray-50 leading-tight">
                        {{ __('Forum ') }}
                    </h2>


                    @if (request()->routeIs('forums'))
                    <button title="Fermer la conversation" class="closeForum text-red-300 hover:text-red-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                        </svg>
                    </button>
                    @endif


                </div>
                <div class="py-5 px-3 w-full flex flex-col sm:flex-row justify-center gap-2">
                    <div class="flex flex-col w-full">
                        <label for="rechercheUser">Rechercher par utilisateur</label>
                        <input type="text" class="bg-white/80 text-gray-800 rounded-md px-3 py-2 leading-tight focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-300 transition-all duration-200" id="rechercheUser" placeholder="Entrer un nom">
                    </div>
                    <div class="flex flex-col w-full">
                        <label for="rechercheMotCle">Rechercher par mot-clé</label>
                        <input type="text" class="bg-white/80 text-gray-800 rounded-md px-3 py-2 leading-tight focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-300 transition-all duration-200" id="rechercheMotCle" placeholder="Entrer un mot, partie de phrase, etc.">
                    </div>
                </div>
            </section>

            <div id="boiteMessages"
                class="relative max-h-[calc(100vh-20rem)] min-h-[20rem] flex flex-col overflow-hidden text-gray-900">

                <div id="listeMessages"
                    class="flex-1 overflow-y-auto space-y-4 p-6 pb-36">
                </div>

                <form id="formNouveauMessage"
                    class="absolute bottom-0 left-0 right-0 px-6 py-4 flex justify-center z-50">

                    <input type="hidden" id="forumId" value="0">


                    <div class="rounded-lg py-3 px-6 bg-white/10 border border-white/20 flex flex-col justify-center gap-3 w-full max-w-3xl shadow-lg backdrop-blur">
                        <div id="responseArea" class="hidden">
                            <input type="checkbox" id="checkboxReponse" name="checkboxReponse" value="" checked>
                            <label class="text-blue-500 underline" id="reponseNom" for="responseId" name="reponseId"></label>
                            <input type="hidden" id="reponseId" value="">
                        </div>
                        <div class="flex w-full gap-3 ">
                            <textarea id="nouveauMessage"
                                rows="1"
                                maxlength="1000"
                                placeholder="Message..."
                                class="w-full resize-none overflow-hidden bg-white/80 text-gray-800 rounded-md px-3 py-2 leading-tight focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-300 transition-all duration-200"></textarea>
                            <button type="button" id="envoyerMessage"
                                    class="text-red-300 hover:text-red-400 transition-transform transform hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-send-horizontal">
                                    <path d="M3.714 3.048a.498.498 0 0 0-.683.627l2.843 7.627a2 2 0 0 1 0 1.396l-2.842 7.627a.498.498 0 0 0 .682.627l18-8.5a.5.5 0 0 0 0-.904z"/>
                                    <path d="M6 12h16"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-red-500 hidden" id="formError">{{__('Charactères spéciaux non-acceptés')}}</p>
                    </div>
                </form>
            </div>


        </div>
    </div>

    <script src="{{ asset('js/forums.js') }}"></script>
