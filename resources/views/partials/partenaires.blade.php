<div class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg flex flex-col text-center relative select-none">
    <div class="flex justify-between mb-6">
        <div class="flex gap-4 items-center ">
            <h2 class="font-semibold text-2xl leading-tight text-white">{{__('Partenaires')}}</h2>
            <button id="devenir-partenaire-button" class="underline text-gray-400">{{__('Devenez partenaire')}}</button>
        </div>

        @can('gestion_partenaires')
        <div>
            <button id="edit_partenaire" type="button"
                class="text-gray-300 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil">
                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                    <path d="m15 5 4 4"></path>
                </svg>
            </button>
            <button id="add_partenaire" type="button"
                class="text-gray-300 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 hover:text-red-300 bg-red-900/30 backdrop-blur-sm rounded-full p-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-plus">
                    <path d="M5 12h14"/>
                    <path d="M12 5v14"/>
                </svg>
            </button>
        </div>
        @endcan
    </div>

    <div class="relative overflow-hidden">
        <button class="absolute left-0 top-1/2 ml-2 -translate-y-1/2 p-2 bg-black/20 backdrop-blur-xl rounded-full transform transition-transform duration-25 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-125 hover:text-gray-300 z-10" id="prev-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
        </button>

        <div id="carousel" class="flex my-4 mx-4 items-center gap-6 will-change-transform transition-transform duration-700 ease-in-out">
        </div>

        <button class="absolute right-0 top-1/2 mr-2 -translate-y-1/2 p-2 bg-black/20 backdrop-blur-xl rounded-full transform transition-transform duration-25 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-125 hover:text-gray-300 z-10" id="next-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
        </button>
    </div>
</div>
<script src="{{ asset('js/partenaires.js') }}" defer></script>





