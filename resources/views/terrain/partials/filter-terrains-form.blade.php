<section class="bg-black/60 backdrop-blur p-4 sm:p-8 shadow sm:rounded-lg basis-1/3 flex flex-col justify-between h-fit">
    <h3 class="text-2xl font-semibold text-gray-50 mb-4 ">Filtrer</h3>
    <form class="flex flex-col gap-4 w-full pb-[1.5em]" id="filtre_form">
        @csrf
        <!-- Barre de recherche pour nom/description/adresse -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-50 mb-1" for="search">{{__('Recherche') }}</label>
            <input type="text" name="search" id="search" placeholder="Rechercher" class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
            <span id="error_search" class="hidden text-red-500 text-sm mt-1"></span>
        </div>
        <!-- Filtrer par état -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-50 mb-1" for="filtre_etat">{{__('État')}}</label>
            <select id="filtre_etat" name="filtre_etat" class="border border-gray-400 rounded w-full p-2 mb-1 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                <option class="text-gray-800" value="0">{{__('Tous')}}</option>
                <option class="text-gray-800" value="1">{{__('Disponible')}}</option>
                <option class="text-gray-800" value="2">{{__('Réservé')}}</option>
                @if (auth()->user()->can('gestion_terrains'))
                <option class="text-gray-800" value="2">{{__('Non visible')}}</option>
                @endif
            </select>
            <span id="error_filtre_etat" class="hidden text-red-500 text-sm mt-1"></span>
        </div>
        <!-- Par relation parent/enfant -->
        <div class="flex flex-col text-gray-50">
            <div class="flex items-center">
                <input class="appearance-none w-4 h-4 border border-gray-400 rounded-full bg-white/10 checked:bg-red-300 checked:ring-2 checked:ring-red-400 checked:ring-offset-2 checked:ring-offset-transparent transition-all duration-200 focus:outline-none cursor-pointer" type="radio" id="tous" name="parent_enfant" value="tous" checked="checked">
                <label class="pl-[1em]" for="tous">{{__('Tous')}}</label>
            </div>
            <div class="flex items-center">
                <input class="appearance-none w-4 h-4 border border-gray-400 rounded-full bg-white/10 checked:bg-red-300 checked:ring-2 checked:ring-red-400 checked:ring-offset-2 checked:ring-offset-transparent transition-all duration-200 focus:outline-none cursor-pointer" type="radio" id="est_parent" name="parent_enfant" value="est_parent">
                <label class="pl-[1em]" for="est_parent">{{__('Est un terrain parent')}}</label>
            </div>
            <div class="flex items-center">
                <input class="appearance-none w-4 h-4 border border-gray-400 rounded-full bg-white/10 checked:bg-red-300 checked:ring-2 checked:ring-red-400 checked:ring-offset-2 checked:ring-offset-transparent transition-all duration-200 focus:outline-none cursor-pointer" type="radio" id="est_enfant" name="parent_enfant" value="est_enfant">
                <label class="pl-[1em]" for="est_enfant">{{__('Est un terrain enfant')}}</label>
            </div>
        </div>
    </form>
    <div class="w-full">
        <hr class="" />

        <div class="flex justify-center mb-auto mt-[2em]"
            x-data
            @calendar-ready.window="window.calendarTerrain = $event.detail.calendar"
        >
            <x-calendar x-ref="calendarTerrain" calendar-type="terrain"></x-calendar>
        </div>
    </div>
</section>
