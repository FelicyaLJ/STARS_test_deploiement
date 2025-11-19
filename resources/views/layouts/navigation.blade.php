<nav x-data="{ open: false, scrolled: false }"
    x-init="scrolled = window.scrollY > 20; window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
    :class="scrolled ? 'bg-black/60 backdrop-blur' : 'bg-black'"
    class="border-b border-white/20 z-30 sticky top-0 transition-all duration-300 bg-black ">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div x-cloak.nav
            x-init="$el.removeAttribute('x-cloak.nav')"
            class="flex justify-between transition-all duration-300"
            :class="scrolled ? 'h-16' : 'h-20'">
            <div class="flex">

                <div class="flex gap-4 flex-row md:flex-row-reverse">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('accueil') }}">
                            <x-application-logo class="block h-12 w-auto fill-current text-gray-800" />
                        </a>
                    </div>

                    <!-- Social media links -->
                    <div class="flex flex-row md:flex-col gap-6 text-gray-50 my-auto md:gap-2 md:pl-2">
                        <a class="flex items-center justify-center transform min-w-6 max-w-10 scale-150 md:scale-100 transition-transform duration-25 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 pr-0 md:pr-1" href="https://www.facebook.com/associationdesoccerstars">
                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook-icon lucide-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a class="flex items-center justify-center transform min-w-6 max-w-10 scale-150 md:scale-100 transition-transform duration-25 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110" href="https://www.instagram.com/assocciationsoccerstars/">
                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram-icon lucide-instagram"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        </a>
                        <a class="flex items-center justify-center transform min-w-6 max-w-10 scale-150 md:scale-100 transition-transform duration-25 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110" href="https://twitter.com/A_S_STARS">
                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden  md:-my-px md:ms-10 md:flex">
                    <x-nav-link :href="route('accueil')" :active="request()->routeIs('accueil')">
                        {{ __('Accueil') }}
                    </x-nav-link>

                    <x-nav-link :href="route('evenements.list')" :active="request()->routeIs('evenements.list')">
                        {{ __('Événements') }}
                    </x-nav-link>

                    <x-nav-link-menu :active="request()->routeIs('forums.demandes.index') || request()->routeIs('forums.demandes.all') || request()->routeIs('forums')">
                        {{ __('Messages') }}

                        <x-slot name="content">
                            <x-nav-link-menu-item :href="route('forums')" :active="request()->routeIs('forums')">
                                {{ __('Groupes de discussion') }}
                            </x-nav-link-menu-item >

                            @can('gestion_demandes')
                                <x-nav-link-menu-item  :href="route('forums.demandes.all')" :active="request()->routeIs('forums.demandes.all')">
                                    {{ __('Voir les demandes d\'adhésion') }}
                                </x-nav-link-menu-item >
                            @else
                                <x-nav-link-menu-item  :href="route('forums.demandes.index')" :active="request()->routeIs('forums.demandes.index')">
                                    {{ __('Vos demandes d\'adhésion') }}
                                </x-nav-link-menu-item >
                            @endcan
                        </x-slot>
                    </x-nav-link-menu>

                    @can('consultation_entrainements')
                        <x-nav-link :href="route('exercices')" :active="request()->routeIs('exercices') || request()->routeIs('exercice.show')">
                            {{ __('Entrainements') }}
                        </x-nav-link>
                    @endcan

                    <x-nav-link-menu :active="request()->routeIs('inscription.index') || request()->routeIs('inscription.show')
                        || request()->routeIs('inscriptions.demandes.all') || request()->routeIs('inscriptions.demandes.index')
                        || request()->routeIs('cout.tableau')">
                        {{ __('Inscriptions') }}

                        <x-slot name="content">
                            <x-nav-link-menu-item href='https://page.spordle.com/fr/rawdon' target="_blank">
                                {{ __('Inscription soccer intérieur (Spordle)') }}
                            </x-nav-link-menu-item >
                            <x-nav-link-menu-item  :href="route('cout.index')" :active="request()->routeIs('cout.index')">
                                {{ __('Coûts d\'inscriptions') }}
                            </x-nav-link-menu-item >
                            <x-nav-link-menu-item  :href="route('inscription.index')" :active="request()->routeIs('inscription.index')">
                                {{ __('Activités locales') }}
                            </x-nav-link-menu-item >
                            <x-nav-link-menu-item  :href="route('inscription.show')" :active="request()->routeIs('inscription.show')">
                                {{ __('Vos activités') }}
                            </x-nav-link-menu-item >
                            @can('gestion_demandes')
                                <x-nav-link-menu-item  :href="route('inscriptions.demandes.all')" :active="request()->routeIs('inscriptions.demandes.all')">
                                    {{ __('Voir les demandes d\'inscription') }}
                                </x-nav-link-menu-item >
                            @else
                                <x-nav-link-menu-item  :href="route('inscriptions.demandes.index')" :active="request()->routeIs('inscriptions.demandes.index')">
                                    {{ __('Vos demandes d\'inscription') }}
                                </x-nav-link-menu-item >
                            @endcan
                        </x-slot>
                    </x-nav-link-menu>

                    <x-nav-link :href="route('equipes.index')" :active="request()->routeIs('equipes.index')">
                        {{ __('Équipes') }}
                    </x-nav-link>

                    {{-- Modifier onglet contactez-nous --}}
                    <x-nav-link-menu :active="request()->routeIs('terrain') || request()->routeIs('notre.club')
                        || request()->routeIs('salaires') || request()->routeIs('faq.list') || request()->routeIs('contactez.nous')
                        || request()->routeIs('candidatures.index')">
                        {{ __('Le club') }}

                        <x-slot name="content">
                            <x-nav-link-menu-item  :href="route('notre.club')" :active="request()->routeIs('notre.club')">
                                {{ __('Notre club') }}
                            </x-nav-link-menu-item >
                            <x-nav-link-menu-item  :href="route('terrain')" :active="request()->routeIs('terrain')">
                                {{ __('Terrains') }}
                            </x-nav-link-menu-item >
                            <x-nav-link-menu-item  :href="'https://affiliated-sports.com/fr/collections/association-de-soccer-stars'">
                                {{ __('Boutique') }}
                            </x-nav-link-menu-item >
                            <x-nav-link-menu-item  :href="route('salaires')" :active="request()->routeIs('salaires')">
                                {{ __('Salaires') }}
                            </x-nav-link-menu-item >
                            <x-nav-link-menu-item  :href="route('faq.list')" :active="request()->routeIs('faq.list')">
                                {{ __('FAQ') }}
                            </x-nav-link-menu-item >
                            <x-nav-link-menu-item  :href="route('contactez.nous')" :active="request()->routeIs('contactez.nous')">
                                {{ __('Nous contacter') }}
                            </x-nav-link-menu-item >
                            <x-nav-link-menu-item :href="route('candidatures.index')" :active="request()->routeIs('candidatures.index')">
                                {{ __('Candidatures') }}
                            </x-nav-link-menu-item>
                        </x-slot>
                    </x-nav-link-menu>

                    @canany(['gestion_users', 'gestion_roles'])
                        <x-nav-link-menu :active="request()->routeIs('users.list') || request()->routeIs('roles.list')
                            || request()->routeIs('admin.settings.edit')">
                            {{ __('Admin') }}

                            <x-slot name="content">
                                @can('gestion_users')
                                <x-nav-link-menu-item :href="route('users.list')" :active="request()->routeIs('users.list')">
                                    {{ __('Utilisateurs') }}
                                </x-nav-link-menu-item>
                                @endcan

                                @can('gestion_roles')
                                <x-nav-link-menu-item :href="route('roles.list')" :active="request()->routeIs('roles.list')">
                                    {{ __('Rôles') }}
                                </x-nav-link-menu-item>
                                @endcan

                                @can('gestion_settings')
                                <x-nav-link-menu-item :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.edit')">
                                    {{ __('Paramètres') }}
                                </x-nav-link-menu-item>
                                @endcan
                            </x-slot>
                        </x-nav-link-menu>
                    @endcanany

                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
                <x-nav-link-menu :active="request()->routeIs('profile.edit')" class="hidden md:flex md:ms-12">
                    {{ Auth::user()->prenom }}

                    <x-slot name="content">
                        <x-nav-link-menu-item :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                            {{ __('Profil') }}
                        </x-nav-link-menu-item>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <x-nav-link-menu-item :href="route('logout')" :active="request()->routeIs('logout')"
                                @click.prevent="
                                    $root.fadingOut = true;
                                    setTimeout(() => $el.closest('form').submit(), 200);
                                "
                            >
                                {{ __('Se déconnecter') }}
                            </x-nav-link-menu-item>
                        </form>

                    </x-slot>
                </x-nav-link-menu>

            @else
                <div class="hidden md:flex md:items-center md:ms-6">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 hover:text-red-200 focus:text-red-500 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __('Se connecter') }}</div>
                    </a>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open"
                    @mouseenter="hover = true"
                    @mouseleave="hover = false"
                    x-data="{ hover: false }"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 transform transition-transform duration-25 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110 focus:outline-none transition duration-150 ease-in-out">

                    <svg class="h-6 w-6" viewBox="0 0 24 24">

                        <!-- Top line -->
                        <line
                            x1="4" y1="9" x2="20" y2="9"
                            stroke-width="2" stroke-linecap="round"
                            :stroke="open
                                ? (hover ? '#b91c1c' : '#fca5a5')
                                : 'currentColor'"
                            :style="open
                                ? 'transform: translateY(4px) rotate(45deg); transform-origin: 12px 14px; transition: 250ms;'
                                : 'transform: none; transition: 250ms;'"
                        />

                        <!-- Middle line -->
                        <line
                            x1="4" y1="14" x2="20" y2="14"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            :style="open
                                ? 'opacity: 0; transition: 150ms;'
                                : 'opacity: 1; transition: 150ms;'"
                        />

                        <!-- Bottom line -->
                        <line
                            x1="4" y1="19" x2="20" y2="19"
                            stroke-width="2" stroke-linecap="round"
                            :stroke="open
                                ? (hover ? '#b91c1c' : '#fca5a5')
                                : 'currentColor'"
                            :style="open
                                ? 'transform: translateY(-3.5px) rotate(-45deg); transform-origin: 12px 14px; transition: 250ms;'
                                : 'transform: none; transition: 250ms;'"
                        />

                    </svg>
                </button>

            </div>

        </div>
    </div>



    <!-- Responsive Navigation Menu -->
    <div
        x-ref="menu"
        x-data
        x-cloak.nav-mobile
        x-init="$el.removeAttribute('x-cloak.nav-mobile')"
        :style="open
            ? 'max-height:' + $refs.menu.scrollHeight + 'px; opacity: 1;'
            : 'max-height: 0px; opacity: 0;'"
        class="md:hidden overflow-hidden transition-all duration-300 ease-out"
    >
        <div class="transition-all duration-300 overflow-x-hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('accueil')" :active="request()->routeIs('accueil')">
                    {{ __('Accueil') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('evenements.list')" :active="request()->routeIs('evenements.list')">
                    {{ __('Événements') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link-menu :active="request()->routeIs('forums') || request()->routeIs('forums.demandes.all') || request()->routeIs('forums.demandes.index')">
                    {{__('Messages')}}

                    <x-slot name="content">
                        <x-responsive-nav-link :href="route('forums')" :active="request()->routeIs('forums')">
                            {{__('Groupes de discussion')}}
                        </x-responsive-nav-link>

                        @can('gestion_demandes')
                            <x-responsive-nav-link :href="route('forums.demandes.all')" :active="request()->routeIs('forums.demandes.all')">
                                {{__('Toutes les demandes')}}
                            </x-responsive-nav-link>
                        @else
                            <x-responsive-nav-link :href="route('forums.demandes.index')" :active="request()->routeIs('forums.demandes.index')">
                                {{__('Vos demandes d\'adhésion')}}
                            </x-responsive-nav-link>
                        @endcan
                    </x-slot>
                </x-responsive-nav-link-menu>

                @can('consultation_entrainements')
                    <x-responsive-nav-link :href="route('exercices')" :active="request()->routeIs('exercices') || request()->routeIs('exercice.show')">
                        {{ __('Entrainements') }}
                    </x-responsive-nav-link>
                @endcan

                <x-responsive-nav-link-menu :active="request()->routeIs('inscription.index') || request()->routeIs('inscription.show')
                    || request()->routeIs('inscriptions.demandes.all') || request()->routeIs('inscriptions.demandes.index')
                    || request()->routeIs('cout.tableau')">
                    {{ __('Inscriptions') }}

                    <x-slot name="content">
                        <x-responsive-nav-link href='https://page.spordle.com/fr/rawdon' target="_blank">
                            {{ __('Soccer intérieur') }}
                        </x-responsive-nav-link >
                        <x-responsive-nav-link  :href="route('inscription.index')" :active="request()->routeIs('inscription.index')">
                            {{ __('Activités locales') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link  :href="route('inscription.show')" :active="request()->routeIs('inscription.show')">
                            {{ __('Vos activités') }}
                        </x-responsive-nav-link>
                        @can('gestion_demandes')
                            <x-responsive-nav-link :href="route('inscriptions.demandes.all')" :active="request()->routeIs('inscriptions.demandes.all')">
                                {{ __('Toutes les demandes') }}
                            </x-responsive-nav-link>
                        @else
                            <x-responsive-nav-link  :href="route('inscriptions.demandes.index')" :active="request()->routeIs('inscriptions.demandes.index')">
                                {{ __('Vos demandes d\'inscription') }}
                            </x-responsive-nav-link >
                        @endcan
                    </x-slot>
                </x-responsive-nav-link-menu>

                <x-responsive-nav-link :href="route('equipes.index')" :active="request()->routeIs('equipes.index')">
                    {{ __('Équipes') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link-menu :active="request()->routeIs('terrain') || request()->routeIs('notre.club')
                    || request()->routeIs('salaires') || request()->routeIs('faq.list') || request()->routeIs('contactez.nous')
                    || request()->routeIs('candidatures.index')">
                    {{ __('Le club') }}

                    <x-slot name="content">
                        <x-responsive-nav-link  :href="route('notre.club')" :active="request()->routeIs('notre.club')">
                            {{ __('Notre club') }}
                        </x-responsive-nav-link >
                        <x-responsive-nav-link  :href="route('terrain')" :active="request()->routeIs('terrain')">
                            {{ __('Terrains') }}
                        </x-responsive-nav-link >
                        <x-responsive-nav-link  :href="'https://affiliated-sports.com/fr/collections/association-de-soccer-stars'">
                            {{ __('Boutique') }}
                        </x-responsive-nav-link >
                        <x-responsive-nav-link  :href="route('salaires')" :active="request()->routeIs('salaires')">
                            {{ __('Salaires') }}
                        </x-responsive-nav-link >
                        <x-responsive-nav-link :href="route('faq.list')" :active="request()->routeIs('faq.list')">
                            {{ __('FAQ') }}
                        </x-responsive-nav-link >
                        <x-responsive-nav-link  :href="route('contactez.nous')" :active="request()->routeIs('contactez.nous')">
                            {{ __('Nous contacter') }}
                        </x-responsive-nav-link >
                        <x-responsive-nav-link :href="route('candidatures.index')" :active="request()->routeIs('candidatures.index')">
                            {{ __('Candidatures') }}
                        </x-responsive-nav-link >
                    </x-slot>
                </x-responsive-nav-link-menu>

                @canany(['gestion_users', 'gestion_roles'])
                    <x-responsive-nav-link-menu :active="request()->routeIs('users.list') || request()->routeIs('roles.list')
                        || request()->routeIs('admin.settings.edit')">
                        {{ __('Admin') }}

                        <x-slot name="content">
                            @can('gestion_users')
                            <x-responsive-nav-link :href="route('users.list')" :active="request()->routeIs('users.list')">
                                {{ __('Utilisateurs') }}
                            </x-responsive-nav-link>
                            @endcan

                            @can('gestion_roles')
                            <x-responsive-nav-link :href="route('roles.list')" :active="request()->routeIs('roles.list')">
                                {{ __('Rôles') }}
                            </x-responsive-nav-link>
                            @endcan

                            @can('gestion_settings')
                            <x-responsive-nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.edit')">
                                {{ __('Paramètres') }}
                            </x-responsive-nav-link>
                            @endcan
                        </x-slot>
                    </x-responsive-nav-link-menu>
                @endcanany
                <!-- Ajouter liens ici -->




            </div>



            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-4 border-t border-white/30">
                @auth
                    <x-responsive-nav-link-menu :active="request()->routeIs('profile.edit')">
                        <div class="">
                            <span class="font-medium text-base text-red-400">{{ Auth::user()->prenom }}</span>
                            <span class="font-medium text-sm text-red-100">{{ Auth::user()->email }}</span>
                        </div>

                        <x-slot name="content">
                            <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                                {{ __('Profile') }}
                            </x-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')" :active="request()->routeIs('logout')"
                                    @click.prevent="
                                        $root.fadingOut = true;
                                        setTimeout(() => $el.closest('form').submit(), 200);
                                    "
                                >
                                    {{ __('Se déconnecter') }}
                                </x-responsive-nav-link>
                            </form>
                        </x-slot>
                    </x-responsive-nav-link-menu>
                @else
                    <x-responsive-nav-link :href="route('login')">
                            {{ __('Se connecter') }}
                    </x-responsive-nav-link>
                @endauth
            </div>
        </div>
    </div>
</nav>
