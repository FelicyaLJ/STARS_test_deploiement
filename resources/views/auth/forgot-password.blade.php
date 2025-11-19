<x-guest-layout>
    <div class="w-full">
        <div class="mb-4 text-2xl text-gray-50 font-semibold">
            {{ __('Mot de passe oublié? ') }}
        </div>
        <div class="mb-4 text-sm text-gray-300">
            {{ __('Pas de problème. Veuillez nous indiquer votre addresse courriel et nous allons vous envoyer un lien pour réinitialiser votre mot de passe par courriel.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div x-data="{ email: '' }" class="space-y-4">
                <div>
                    <x-input-label class="!text-gray-50" for="email" :value="__('Courriel')" />
                    <x-text-input
                        id="email"
                        name="email"
                        type="email"
                        x-model="email"
                        class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                        placeholder="Entrez votre adresse courriel"
                        required autofocus
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button
                        type="submit"
                        class="ms-3 bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded break-all"
                    >
                        <span x-text="email ? `Envoyer un lien à ${email}` : 'Envoyer un lien'"></span>
                    </x-primary-button>
                </div>
            </div>

        </form>
    </div>
</x-guest-layout>
