<section class="overflow-y-auto max-h-[30rem] transition-all my-1 duration-500 ease-in-out scroll-smooth" id="listRoles">
    @foreach ($roles as $role)
    <div id="" class="mx-2 transition-colors duration-200 text-gray-200 mb-4 p-4 cursor-pointer bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition" data-role='@json($role)'>
        <span class="font-semibold">{{ $role->nom_role }}</span>

        <div class="flex flex-wrap gap-1 mt-1 md:mt-0">
            @if ($role->permissions->isNotEmpty())
                @foreach ($role->permissions->take(4) as $permission)
                    <span class="bg-red-200 text-red-900 font-semibold text-xs px-2 py-1 rounded-full">
                        {{ __($permission->formatted_name) }}
                    </span>
                @endforeach

                @if ($role->permissions->count() > 4)
                    <span class="bg-red-200 text-red-900 font-semibold text-xs px-2 py-1 rounded-full">...</span>
                @endif

            @else
                <span class="text-xs">{{__('Consultation seulement')}}</span>
            @endif
        </div>
    </div>
    @endforeach
</section>

