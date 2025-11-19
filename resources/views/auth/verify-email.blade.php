<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 text-gray-50 lg:px-8 py-12 space-y-8">

        {{-- Header Section --}}
        <div class="flex gap-4 items-center mb-12">
            <x-application-logo class="w-28"/>
            <div>
                <h2 class="font-semibold text-2xl pb-2 text-[#e3c14e] leading-tight">
                    {{ __('Association de soccer STARS') }}
                </h2>
                <hr class="border-gray-600">
                <h4 class="font-semibold text-lg pt-2 leading-tight">
                    {{ __('Club de Soccer de Lanaudière') }}
                </h4>
            </div>
        </div>

        {{-- Verification Box --}}
        <div class="p-6 sm:p-10 bg-black/60 backdrop-blur shadow-lg sm:rounded-2xl flex flex-col justify-center text-center space-y-6">
            <h2 class="font-semibold text-2xl text-red-400">
                {{ __('Vérification de votre adresse e-mail') }}
            </h2>

            <p class="text-lg text-gray-200">
                {{ __("Merci de vous être inscrit! Avant de commencer, veuillez confirmer votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer.") }}
            </p>
            <p class="text-gray-300 text-md">
                {{ __("Si vous n'avez pas reçu le courriel, vous pouvez en demander un nouveau ci-dessous.") }}
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="bg-green-700/40 text-green-300 px-4 py-3 rounded-lg font-medium">
                    {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-6">
                {{-- Resend verification form --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button class="bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded mt-4 w-full">
                        {{ __('Renvoyer le lien de vérification') }}
                    </x-primary-button>
                </form>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="ring-2 ring-red-400/30 transition-colors duration-300 hover:bg-red-400/70 text-white text-xs px-4 py-2 rounded mt-4 w-full">
                        {{ __('Se déconnecter') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

