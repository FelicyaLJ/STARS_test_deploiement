<x-modal-stars title="Application pour rejoindre l'équipe" id="modalDemandeEquipe">
    <form
        id="equipeDemandeForm"
        method="POST"
    >
        @csrf

        <div>
            <label
                for="raison"
                class="block text-gray-50 font-medium mb-1"
            >
                {{ __('Raison de la demande') }}
            </label>

            <textarea
                id="raison"
                name="raison"
                required
                rows="4"
                placeholder="Expliquez brièvement pourquoi vous souhaitez rejoindre cette équipe..."
                class="w-full rounded-lg border-red-300 focus:border-red-500 focus:ring focus:ring-red-300 focus:ring-opacity-50 text-gray-800 p-3 resize-none"
            ></textarea>

            <p
                id="formErrorRaison"
                class="text-red-500 text-sm mt-1 hidden"
            >
                {{ __('La raison de la demande doit contenir au moins 5 caractères.') }}
            </p>
        </div>

        <div class="pt-2">
            <button
                type="submit"
                id="btnSendJoinRequest"
                class="adhesion-btn w-full bg-red-400 hover:bg-red-700 focus:ring-4 focus:ring-red-300 text-white font-semibold py-2.5 rounded-lg transition-colors duration-200"
            >
                {{ __('Envoyer la demande') }}
            </button>
        </div>
    </form>
</x-modal-stars>
