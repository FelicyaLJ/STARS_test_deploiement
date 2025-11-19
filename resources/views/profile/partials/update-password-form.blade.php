<section>
    <header>
        <h2 class="text-2xl font-semibold text-gray-50">
            {{ __('Modification du mot de passe') }}
        </h2>

        <p class="mt-1 text-sm text-gray-300">
            {{ __('Assurez vous d\'avoir un mot de passe qui comprend au moins 8 caractères, des lettres majuscules et minuscules, des chiffres et un caractère spécial.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label class="!text-gray-50" for="update_password_current_password" :value="__('Mot de passe actuel')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label class="!text-gray-50" for="update_password_password" :value="__('Nouveau mot de passe')" />
            <x-text-input id="update_password_password" name="password" type="password" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label class="!text-gray-50" for="update_password_password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 justify-end">
            @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-green-400 break-words"
            >{{ __('Mot de passe modifié!') }}</p>
            @endif

            <x-primary-button class=" bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded">{{ __('Changer le mot de passe') }}</x-primary-button>
        </div>
    </form>
</section>
