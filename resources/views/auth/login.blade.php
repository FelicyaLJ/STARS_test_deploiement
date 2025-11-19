<x-guest-layout>
    <div class="w-full">

        <div class="mb-4 text-2xl text-gray-50 font-semibold">
            {{ __('Connexion') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" class="!text-gray-50" :value="__('Courriel')" />
                <x-text-input id="email" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" class="!text-gray-50" :value="__('Mot de passe')" />

                <x-text-input id="password" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-300 hover:text-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié?') }}
                </a>
            @endif

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="border border-gray-400 rounded text-gray-50 bg-white/10 text-red-400 shadow-sm focus:ring-red-400" name="remember">
                    <span class="ms-2 text-sm text-gray-50">{{ __('Se souvenir de moi') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <div class="flex flex-col gap-2">

                    <a class="underline text-sm text-gray-300 hover:text-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400" href="{{ route('register') }}">
                        {{ __('Nouvel utilisateur? Inscrivez-vous ici') }}
                    </a>
                </div>

                <x-primary-button class="ms-3 bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded">
                    {{ __('Se connecter') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
