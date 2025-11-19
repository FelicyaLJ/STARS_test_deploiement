<div id="faq_{{$sujet->id}}" class="flex flex-col gap-4 rounded-xl bg-white/10 p-4">
    <div class="flex flex-col">

        <div class="flex gap-2 items-center">
            <span class="font-semibold text-lg text-gray-100 underline text-red-400 titre_faq"> {{ $sujet->titre }} </span>
            @can('gestion_faq')
            <button
                class="edit_sujet_faq duration-300 hover:scale-110 transform cursor-pointer"
                onclick="show_form_edit_faq({{$sujet->id}})"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-pencil hover:text-orange-600 hover:scale-110">
                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    <path d="m15 5 4 4"/>
                </svg>
            </button>
            @endcan
        </div>


        <span class="text-gray-300 text-md texte_faq"> {!!$sujet->texte!!} </span>

        @if ($sujet->fichier)
            @php
                $path = $sujet->fichier;
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $fileUrl = asset('storage/faq/files/' . $path);
            @endphp

            <div class="mt-4" id="file">
                {{-- Images --}}
                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <img
                        src="{{ $fileUrl }}"
                        alt="Image liée à {{ $sujet->titre }}"
                        class="rounded-lg max-h-96 object-contain mx-auto"
                    >

                {{-- Vidéos --}}
                @elseif (in_array($extension, ['mp4', 'webm', 'ogg']))
                    <video
                        src="{{ $fileUrl }}"
                        controls
                        class="rounded-lg w-full max-h-96 bg-black/30"
                    ></video>

                {{-- PDFs --}}
                @elseif ($extension === 'pdf')
                <div class="w-full flex justify-center">
                    <iframe
                        src="{{ $fileUrl }}#view=fitH"
                        class="w-full aspect-[8.5/11] rounded-lg border border-gray-700 bg-black/20"
                    ></iframe>
                </div>

                {{-- Autres --}}
                @else
                    <a
                        href="{{ $fileUrl }}"
                        target="_blank"
                        class="text-red-300 underline hover:text-red-400"
                    >
                        Télécharger le fichier ({{ strtoupper($extension) }})
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
