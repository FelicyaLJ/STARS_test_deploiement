@props([
    'evenement',
    'lightBg' => false,
])

@php
$textTitleColor = ($lightBg ?? false)
                    ?   'text-gray-800'
                    :   'text-gray-50';

$ringColor = ($lightBg ?? false)
                    ?   'ring-gray-800'
                    :   'ring-gray-100';

$textInfoColor = ($lightBg ?? false)
                    ?   'text-gray-500'
                    :   'text-gray-400';

$hoverColor = ($lightBg ?? false)
                    ?   'group-hover:bg-black/10'
                    :   'group-hover:bg-white/10';

$textMoreColor = ($lightBg ?? false)
                    ?   'text-gray-600'
                    :   'text-gray-200';

@endphp

@php
    $eventDate = \Carbon\Carbon::parse($evenement->date);
    $isPast = $eventDate->isBefore(\Carbon\Carbon::today());
    $ringColor = $isPast ? '#9ca3af' : ($evenement->categorie->couleur ?? '#ffffff');
    $titleColor = $isPast ? 'text-gray-500' : $textTitleColor;

    $fromAccueil = request('from') === 'accueil';
    $fromEvenement = (request()->routeIs('evenements.list') || request()->routeIs('evenements.render') && !$fromAccueil);
    $contentWidth =  $fromEvenement ? 'max-w-lg' : 'max-w-2xl';
@endphp

<div id="evenement_{{$evenement->id}}" class="flex items-center gap-4 pr-2 group {{$titleColor}} bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition" data-evenement='@json($evenement)'>

    {{-- Date --}}
    <div class="flex justify-center ml-2 basis-1/6">
        <button class="button-date flex flex-col ring-2 min-w-14 pt-1 items-center font-semibold text-center min-h-14 rounded-lg transform duration-300 ease-out group-hover:ring-4"
                style="--tw-ring-color: {{ $ringColor }}"
                data-date="{{\Carbon\Carbon::parse($evenement->date)->format('Y-m-d')}}">
            <span class="jour_evenement"> {{ \Carbon\Carbon::parse($evenement->date)->locale('fr')->translatedFormat('d') }} </span>
            <span class="mois_evenement"> {{ strtoupper(Str::ascii(\Carbon\Carbon::parse($evenement->date)->locale('fr')->translatedFormat('M'))) }} </span>
        </button>
    </div>

    {{-- Contenu Événement --}}
    <div id="" class="flex flex-col mr-2 transition-colors {{$contentWidth}} duration-200 p-4 rounded-lg basis-5/6">
        <span class="font-semibold text-xl nom_evenement truncate">{{ $evenement->nom_evenement }}</span>

        <div class="flex flex-col md:gap-6 md:flex-row {{$textInfoColor}}">
            <div class="flex flex-col justify-evenly">
                <span class="flex items-center gap-2">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-icon lucide-calendar"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    </span>
                    <span class="date_evenement">
                        {{ ucfirst(\Carbon\Carbon::parse($evenement->date)->locale('fr')->translatedFormat('l, d F, Y')) }}
                    </span>
                </span>
                <span class="flex items-center gap-2">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock"><path d="M12 6v6l4 2"/><circle cx="12" cy="12" r="10"/></svg>
                    </span>
                    <span class="heure_evenement">
                        {{ \Carbon\Carbon::parse($evenement->heure_debut)->translatedFormat('h:i A') . ' - ' . \Carbon\Carbon::parse($evenement->heure_fin)->translatedFormat('h:i A') }}
                    </span>
                </span>
            </div>

            <div class="flex flex-col justify-evenly">
                <span class="flex items-center gap-2">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                    </span>
                    <a class="nom_terrain_evenement underline hover:font-semibold hover:{{$textMoreColor}}" href="https://www.google.com/maps/search/?api=1&query={{urlencode($evenement->terrain->adresse)}}">
                        {{ $evenement->terrain->nom_terrain }}
                    </a>
                </span>
                <span class="flex items-center gap-2">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flag-triangle-right-icon lucide-flag-triangle-right"><path d="M6 22V2.8a.8.8 0 0 1 1.17-.71l11.38 5.69a.8.8 0 0 1 0 1.44L6 15.5"/></svg>
                    </span>
                    <span class="nom_categorie_evenement">
                        {{ $evenement->categorie->nom_categorie }}
                    </span>
                </span>
            </div>

            <div class="flex flex-col justify-evenly">
                @if (!empty($evenement->prix) && $evenement->prix > 0)
                    <span class="flex items-center gap-2">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dollar-sign-icon lucide-circle-dollar-sign"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
                        </span>
                        <span class="prix_evenement">
                            {{ $evenement->prix . '$' }}
                        </span>
                    </span>
                @endif

                @if ($evenement->equipes && $evenement->equipes->count() > 0)
                <span class="flex items-center gap-2">

                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                    </span>
                    <span class="break-word">
                    @foreach ($evenement->equipes as $index => $equipe)
                        {{ $equipe->nom_equipe }}@if (!$loop->last), @endif
                    @endforeach
                    </span>
                </span>
                @endif

            </div>
        </div>

        {{-- Description dans Voir plus --}}
        @if ($evenement->description)
        <span class="flex flex-col gap-2">
            <div class="more-button flex gap-1 items-center {{$textMoreColor}} hover:font-semibold cursor-pointer">
                <span class="arrow transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-down">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </span>
                <button type="button">{{ __('Plus') }}</button>
            </div>

            <span class="more-div overflow-hidden max-h-0 transition-[max-height] duration-500 flex flex-col gap-1 break-all">
                <span class="description_evenement text-sm font-thin"> {{$evenement->description}} </span>
            </span>
        </span>
        @endif
    </div>

    @if ($fromEvenement)
        @can('gestion_evenements')
        <div class="">
            <button value="{{$evenement->id}}" type="button" id="mod_evenement" class="hover:bg-white/20 text-orange-300 hover:text-gray-50 hover:scale-110 px-4 py-2 rounded flex justify-center basis-1/6 bouton_modification transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex lucide lucide-plus-icon lucide-plus"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
            </button>
            <button value="{{$evenement->id}}" type="button" id="delete_evenement" class="hover:bg-white/20 text-red-400 hover:text-gray-50 hover:scale-110 px-4 py-2 rounded mt-2 flex justify-center basis-1/6 bouton_suppression transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
        @endcan
    @endif
</div>

<script>
    document.querySelectorAll(".more-button").forEach(btnDiv => {
        btnDiv.addEventListener("click", () => {
            const btn = btnDiv.querySelector("button");
            const moreDiv = btnDiv.nextElementSibling;
            const arrow = btnDiv.querySelector(".arrow");

            // toggle arrow
            arrow.classList.toggle("rotate-180");

            const isExpanded = parseInt(getComputedStyle(moreDiv).maxHeight) > 0;
            if (isExpanded) {
                moreDiv.style.maxHeight = "0";
                btn.innerText = "Plus";
            } else {
                moreDiv.style.maxHeight = "none";
                const fullHeight = moreDiv.scrollHeight + "px";
                moreDiv.style.maxHeight = "0"; // reset
                setTimeout(() => {
                    moreDiv.style.maxHeight = fullHeight;
                }, 10);
                btn.innerText = "Moins";
            }
        });
    });
</script>
