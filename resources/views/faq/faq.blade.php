@php
$categories
@endphp

<x-app-layout>
    <div class="py-12">

        <div class="flex flex-col max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex-1 text-gray-50 bg-black/60 backdrop-blur shadow-sm sm:rounded-lg p-4 sm:p-8">

                <div class="flex justify-between mb-[3%] text-gray-50">
                    <h2 class="font-semibold text-2xl leading-tight">
                        {{ __('FAQ') }}
                    </h2>
                </div>

                {{-- Consultation du FAQ --}}
                @include('faq.partials.faq-list')

            </div>
        </div>

    </div>

    <!---Ajout des modals-->
    @include('faq.partials.faq-categorie-form')
    @include('faq.partials.faq-form')
</x-app-layout>

<script>
    let categories = @json($categories);
</script>
<script src="{{ asset('js/faq.js') }}"></script>
