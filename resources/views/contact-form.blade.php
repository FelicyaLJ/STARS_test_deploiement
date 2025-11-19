<x-app-layout>

    <div class="max-w-7xl mx-auto sm:px-6 text-gray-50 lg:px-8 py-12 space-y-4">

        <div class="flex flex-col md:flex-row gap-4">

            @if (session('status') === 'mail-sent')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-300"
                >{{ __('Courriel envoyé.') }}</p>
            @endif

            <div class="p-4 sm:p-8 h-fit bg-black/60 backdrop-blur basis-1/2 shadow sm:rounded-lg flex flex-col justify-center items-center text-center">

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

                <div class="flex flex-col gap-4">
                    <span>C.P. 4695, Rawdon, QC, J0K1S0</span>

                    <span>soccer@associationdesoccerstars.com</span>

                    <span class="text-xl">
                        {{__('Cette page est pour communiquer avec le club de soccer')}} <span class="text-[#e3c14e] font-semibold">{{__('Association de Soccer STARS')}}</span>{{__('. Nous ne sommes pas affiliés avec les concepteurs du jeu pour appareils mobile')}} <span class="text-red-500 font-semibold">{{__('Soccer Stars')}}</span>{{__('. Si vous voulez récupérer des jetons non gagnés dans ce jeu, vous êtes à la mauvaise place.')}}
                    </span>
                </div>

            </div>

            <div class="p-4 sm:p-8 bg-black/60 backdrop-blur basis-1/2 shadow sm:rounded-lg flex flex-col">

                <div class="flex justify-between mb-6">
                    <h2 class="font-semibold text-2xl leading-tight">
                        {{ __('Envoyez nous un courriel') }}
                    </h2>

                    <p class="text-xs">{{__('* Champs requis')}}</p>
                </div>

                <div>
                    <form action="{{route('send.mail')}}" method="post">
                    @csrf
                        <div class="flex gap-4 justify-between">
                            <div>
                                <label>{{__('Prénom')}}* <input type="text" name="prenom" id="prenomContact" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10" value="{{ old('prenom', auth()->check() ? auth()->user()->prenom : '') }}"></label>
                                <x-input-error class="mb-2" :messages="$errors->get('prenom')" />
                            </div>
                            <div>
                                <label>{{__('Nom')}}* <input type="text" name="nom" id="nomContact" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10" value="{{ old('nom', auth()->check() ? auth()->user()->nom : '') }}"></label>
                                <x-input-error class="mb-2" :messages="$errors->get('nom')" />
                            </div>
                        </div>

                        <div>
                            <label>{{__('Adresse courriel')}}*
                                <input type="email" name="email" id="emailContact" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}">
                            </label>
                            <x-input-error class="mb-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="flex gap-4 justify-between">
                            <div>
                                <label>{{__('Téléphone')}} <input type="tel" name="tel" id="telContact" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10" value="{{ old('tel', '') }}"></label>
                                <x-input-error class="mb-2" :messages="$errors->get('tel')" />
                            </div>
                            <div>
                                <label>{{__('Adresse')}} <input type="text" name="addresse" id="addresseContact" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10" value="{{ old('addresse', '') }}"></label>
                                <x-input-error class="mb-2" :messages="$errors->get('addresse')" />
                            </div>
                        </div>

                        <div>
                            <label>{{__('Sujet')}}*
                                <input type="text" name="sujet" id="sujetContact" class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10" value="{{ old('sujet', '') }}">
                            </label>
                            <x-input-error class="mb-2" :messages="$errors->get('sujet')" />
                        </div>

                        <div>
                            <label for="message">
                                {{ __('Message') }}*
                                <textarea
                                    name="message"
                                    id="messageContact"
                                    class="border border-gray-400 rounded w-full p-2 text-gray-50 bg-white/10 min-h-[5rem] max-h-[20rem]"
                                >{{ old('message', '') }}</textarea>
                            </label>
                            <x-input-error class="mb-2" :messages="$errors->get('message')" />
                        </div>

                        @guest
                            <div class="mt-4">
                                <label>{{ __('Captcha') }}*</label><br>

                                <x-captcha class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10" />

                                @error('captcha')
                                    <x-input-error class="mb-2" :messages="$message" />
                                @enderror
                            </div>
                        @endguest

                        <input type="hidden">
                        <button type="submit" id="sendContact" class="bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded mt-4 w-full">
                            {{__('Envoyer')}}
                        </button>

                    </form>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>
