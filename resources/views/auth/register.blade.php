<x-guest-layout>
    <div class="w-full">
        <div class="mb-4 text-2xl text-gray-50 font-semibold">
            {{ __('Inscription') }}
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="flex justify-evenly gap-2">
                <div>
                    <x-input-label class="!text-gray-50" for="prenom" :value="__('Prénom')" />
                    <x-text-input id="prenom" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" type="text" name="prenom" :value="old('prenom')" required autofocus autocomplete="prenom" />
                    <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                </div>
                <div>
                    <x-input-label class="!text-gray-50" for="nom" :value="__('Nom')" />
                    <x-text-input id="nom" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" type="text" name="nom" :value="old('nom')" required autofocus autocomplete="nom" />
                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                </div>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label class="!text-gray-50" for="email" :value="__('Courriel')" />
                <x-text-input id="email" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label class="!text-gray-50" for="password" :value="__('Mot de passe')" />

                <x-text-input id="password" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label class="!text-gray-50" for="password_confirmation" :value="__('Confirmer le mot de passe')" />

                <x-text-input id="password_confirmation" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Captcha -->
            <div class="mt-4">
                <x-input-label class="!text-gray-50" for="captcha" :value="__('Captcha')" />

                <x-captcha class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10" />

                <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-300 hover:text-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400" href="{{ route('login') }}">
                    {{ __('Déjà inscrit?') }}
                </a>

                <x-primary-button class="ms-3 bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded">
                    {{ __('S\'inscrire') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
