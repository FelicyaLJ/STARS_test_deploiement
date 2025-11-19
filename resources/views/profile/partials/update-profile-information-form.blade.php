<section>
    <header>
        <h2 class="text-2xl font-semibold text-gray-50">
            {{ __('Informations de ') . $user->prenom }}
        </h2>

        <p class="mt-1 text-sm text-gray-300">
            {{ __("Modifiez les informations de votre profil.") }}
        </p>
    </header>

    <div class="max-w-xl m-auto mt-10">

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div class="flex gap-2 justify-between">
                <div>
                    <x-input-label class="!text-gray-50" for="prenom" :value="__('Prénom')" />
                    <x-text-input id="prenom" name="prenom" type="text" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" :value="old('prenom', $user->prenom)" required autofocus autocomplete="prenom" />
                    <x-input-error class="mt-2" :messages="$errors->get('prenom')" />
                </div>
                <div>
                    <x-input-label class="!text-gray-50" for="nom" :value="__('Nom')" />
                    <x-text-input id="nom" name="nom" type="text" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" :value="old('nom', $user->nom)" required autofocus autocomplete="nom" />
                    <x-input-error class="mt-2" :messages="$errors->get('nom')" />
                </div>
            </div>

            <div>
                <x-input-label class="!text-gray-50" for="email" :value="__('Courriel')" />
                <x-text-input id="email" name="email" type="email" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-50">
                            {{ __('Votre adresse courriel n\'est pas vérifiée') }}

                            <button form="send-verification" class="underline w-fit text-left text-sm text-gray-300 hover:text-gray-400 transition-colors duration-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                                {{ __('Appuyez ici pour renvoyer un courriel de vérification.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-400"
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)">
                                {{ __('Un nouveau lien de vérification a été envoyé à votre addresse courriel!') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4 justify-end">
                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-400"
                    >{{ __('Enregistré!') }}</p>
                @endif

                <x-primary-button class=" bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded">{{ __('Enregistrer') }}</x-primary-button>
            </div>
        </form>
    </div>
</section>
