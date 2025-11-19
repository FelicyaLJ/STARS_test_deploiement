<x-app-layout>

    <div class="max-w-7xl mx-auto sm:px-6 text-gray-50 lg:px-8 py-12 space-y-4">

        <div class="flex gap-4 items-center">

            @if (session('status') === 'mail-sent')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-300"
                >{{ __('Courriel envoyé.') }}</p>
            @endif

            <div class="p-4 sm:p-8 bg-black/60 backdrop-blur basis-1/2 shadow sm:rounded-lg flex flex-col justify-center items-center text-center">
                Veuillez remplir ce formulaire si vous désirez vous impliquer dans notre club.
            </div>

            <div class="p-4 sm:p-8 bg-black/60 backdrop-blur basis-1/2 shadow sm:rounded-lg flex flex-col">

                <div class="flex justify-between mb-6">
                    <h2 class="font-semibold text-2xl leading-tight">
                        {{ __('Appliquer à un poste') }}
                    </h2>

                    <p class="text-xs">{{__('* Champs requis')}}</p>
                </div>

                <div>
                    <form  method="POST" action="{{route('send_candidature.mail')}}" enctype="multipart/form-data">
                        @csrf

                        {{-- Informations personnelles --}}
                        <div class="flex gap-4 justify-between">
                            <div>
                                <label>{{ __('Prénom') }}*
                                    <input type="text" name="prenom" id="prenomCandidature"
                                        class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10"
                                        value="{{ old('prenom', auth()->check() ? auth()->user()->prenom : '') }}">
                                </label>
                                <x-input-error class="mb-2" :messages="$errors->get('prenom')" />
                            </div>

                            <div>
                                <label>{{ __('Nom') }}*
                                    <input type="text" name="nom" id="nomCandidature"
                                        class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10"
                                        value="{{ old('nom', auth()->check() ? auth()->user()->nom : '') }}">
                                </label>
                                <x-input-error class="mb-2" :messages="$errors->get('nom')" />
                            </div>
                        </div>

                        <div>
                            <label>{{ __('Adresse courriel') }}*
                                <input type="email" name="email" id="emailCandidature"
                                    class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10"
                                    value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}">
                            </label>
                            <x-input-error class="mb-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="flex gap-4 justify-between">
                            <div>
                                <label>{{ __('Téléphone') }}
                                    <input type="tel" name="tel" id="telCandidature"
                                        class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10"
                                        value="{{ old('tel', '') }}">
                                </label>
                                <x-input-error class="mb-2" :messages="$errors->get('tel')" />
                            </div>

                            <div>
                                <label>{{ __('Adresse') }}
                                    <input type="text" name="adresse" id="adresseCandidature"
                                        class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10"
                                        value="{{ old('adresse', '') }}">
                                </label>
                                <x-input-error class="mb-2" :messages="$errors->get('adresse')" />
                            </div>
                        </div>

                        {{-- Sélection du poste --}}
                        <div>
                            <label>{{ __('Poste visé') }}*
                                <select name="poste_id" id="posteCandidature"
                                        class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10">

                                    <option value="" disabled selected hidden>-- Sélectionnez un poste --</option>

                                    @if(isset($message))
                                        <option disabled>{{ $message }}</option>

                                    @elseif($postes->isEmpty())
                                        <option disabled>Aucun poste trouvé.</option>

                                    @else
                                        @foreach ($postes as $poste)
                                            <option value="{{ $poste->id }}" class="text-black">{{ $poste->nom_poste }}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </label>
                            <x-input-error class="mb-2" :messages="$errors->get('poste_id')" />
                        </div>

                        {{-- Téléversement du CV --}}
                        <div>
                            <label>{{ __('Curriculum Vitae (.pdf,.doc,.docx)') }}*
                                <input type="file" name="cv" id="cvCandidature"
                                    class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10"
                                    accept=".pdf,.doc,.docx">
                            </label>
                            <x-input-error class="mb-2" :messages="$errors->get('cv')" />
                        </div>

                        {{-- Message ou lettre de motivation --}}
                        <div>
                            <label for="message">{{ __('Lettre de motivation / Message') }}
                                <textarea name="message" id="messageCandidature"
                                    class="border border-gray-400 rounded w-full p-2 text-gray-50 bg-white/10 min-h-[6rem] max-h-[20rem]"
                                >{{ old('message', '') }}</textarea>
                            </label>
                            <x-input-error class="mb-2" :messages="$errors->get('message')" />
                        </div>

                        {{-- Captcha si non connecté --}}
                        @guest
                            <div class="mt-4">
                                <label>{{ __('Captcha') }}*</label><br>
                                <x-captcha class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10" />
                                @error('captcha')
                                    <x-input-error class="mb-2" :messages="$message" />
                                @enderror
                            </div>
                        @endguest

                        <button type="submit" id="sendCandidature"
                            class="bg-red-800/30 transition-colors duration-300 hover:bg-red-400/70 text-white px-4 py-2 rounded mt-4 w-full">
                            {{ __('Envoyer ma candidature') }}
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
