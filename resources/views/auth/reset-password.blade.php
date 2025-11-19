<x-guest-layout>
    <div class="w-full">

        <div class="mb-4 text-2xl text-gray-50 font-semibold">
            {{ __('Modification du mot de passe') }}
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-input-label class="!text-gray-50" for="email" :value="__('Courriel')" />
                <x-text-input id="email" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label class="!text-gray-50" for="password" :value="__('Mot de passe')" />
                <x-text-input id="password" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" type="password" name="password" required autocomplete="new-password" />
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

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-3 bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded">
                    {{ __('Réinitialiser le mot de passe') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
