<x-app-layout>

    <div class="max-w-2xl mx-auto sm:px-6 text-gray-50 lg:px-8 py-12 space-y-8">

        <div class="flex gap-4 items-center justify-center md:justify-start mb-12">
            <x-application-logo class="w-28 md:w-32"/>
            <div class="hidden md:block">
                <h2 class="font-semibold text-2xl pb-2 text-[#e3c14e] leading-tight">
                    {{ __('Association de soccer STARS') }}
                </h2>
                <hr class="border-gray-600">
                <h4 class="font-semibold text-lg pt-2 leading-tight">
                    {{ __('Club de Soccer de Lanaudière') }}
                </h4>
            </div>
        </div>

        <div class="p-6 sm:p-10 bg-black/60 backdrop-blur shadow-lg sm:rounded-2xl flex flex-col justify-center space-y-6">
            <div class="mb-4 text-sm text-gray-50">
                {{ __('Des données sensibles sont présentes dans cette partie de l\'application. Confirmez votre mot de passe avant de continuer.') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label class="!text-gray-50" for="password" :value="__('Mot de passe')" />

                    <x-text-input id="password" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-4">
                    <x-primary-button class="ms-3 bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded break-all">
                        {{ __('Confirmer') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
