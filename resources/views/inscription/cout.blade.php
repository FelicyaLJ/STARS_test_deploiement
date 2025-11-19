<script>
    const canManageCout = @json(auth()->user()->can('gestion_cout'));
</script>
<x-app-layout>
    <x-modal-stars title="Modifier l'exercice" id="modalTableau">
        <form class="text-white" id="form" method="POST" action="/cout/update" enctype="multipart/form-data">
        @csrf

        <label for="image">Changer l'image du tableau:</label>
        <input type="file" id="image" name="image" accept="image/*" required>
        <p class="text-red-500 hidden" id="formErrorImage">Le fichier doit être une image.</p>
        <div class="flex justify-between items-end gap-3">
            <button data-id="0" type="button" id="enregistrerModif" data-id="0" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 mt-[5%] w-full">{{__('Enregistrer')}}</button>
        </div>
    </form>
        </x-modal-stars>

    <div class="max-w-7xl py-12 gap-4 mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row">
        <div id="inscriptions-container" class="w-full flex flex-col xl:flex-row gap-4 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
            <div id="liste-container" class="flex-[1_1_100%] w-full transition-[flex,margin,width] duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                <div class="bg-black/60 backdrop-blur text-gray-50 shadow-sm sm:rounded-lg p-4 sm:p-8 max-w-3xl mx-auto">
                    <div class="pb-3 pr-1 flex justify-between">
                        @can("gestion_cout")
                            <div></div>
                        @endcan
                        <div>
                        <h1 class="font-semibold text-lg">Tableau des coûts d'inscription</h1>
                        </div>
                        <div>
                            @can("gestion_cout")
                                <button id="modifTableau" type="button" class="p-2 hover:bg-red-900 rounded"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button>
                            @endcan
                        </div>

                    </div>
                    <div id = "imageIci" class="clear-both">
                        @if($coutFile)
                            <img id="tableauImage" src="{{ asset('storage/' . $coutFile)}}" alt="Aucun tableau de coût." width="" height="">
                            @else
                            <p>Aucun tableau de coût.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="{{ asset('js/coutInscription.js') }}"></script>
