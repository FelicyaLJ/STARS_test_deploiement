<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-4 flex-col xl:flex-row">

                <!-- Consultation des rôles -->
                <div class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg basis-1/3">
                    <div class="flex justify-between mb-2">
                        <h2 class="font-semibold text-2xl text-gray-50 leading-tight">
                            {{ __('Rôles') }}
                        </h2>
                        @can('gestion_roles')
                        <button id="add-role" type="button" class="text-gray-50 transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        </button>
                        @endcan
                    </div>
                    @include('roles.partials.roles-list')
                </div>

                <!-- Consultation d'un rôle + modification/suppression -->
                <div class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg basis-2/3">
                    <div class="">
                        @include('roles.partials.role-form-edit')
                    </div>
                </div>

            </div>

            <!-- Ajout d'un role -->
            @include('roles.partials.role-form-add')
        </div>
    </div>

</x-app-layout>

<script src="{{ asset('js/roles.js') }}"></script>
