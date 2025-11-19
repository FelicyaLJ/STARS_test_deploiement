if (document.location.pathname === "/users" || document.location.pathname === "/users/search") {
    document.addEventListener('DOMContentLoaded', () => {
        // Filters
        const filtreForm = document.getElementById('filtreForm');
        const inputSearch = document.getElementById('search');
        const etatFiltre = document.getElementById('filtre-etat');
        const roleFiltre = document.getElementById('filtre-role');

        // Tables
        const listUsers = document.getElementById('listUsers');
        const listUsersMobile = document.getElementById('listUsersMobile');

        const pagination = document.getElementById('pagination');

        // Error divs
        const errorSearch = document.getElementById('errorSearch');
        const errorSort = document.getElementById('errorSort');
        const errorEtat = document.getElementById('errorFiltreEtat');
        const errorRole = document.getElementById('errorFiltreRole');
        const errorNomAdd = document.getElementById('errorNomAdd');
        const errorPrenomAdd = document.getElementById('errorPrenomAdd');
        const errorEmailAdd = document.getElementById('errorEmailAdd');
        const errorPasswordAdd = document.getElementById('errorPasswordAdd');
        const errorNomEdit = document.getElementById('errorNomEdit');
        const errorPrenomEdit = document.getElementById('errorPrenomEdit');
        const errorEmailEdit = document.getElementById('errorEmailEdit');

        // Modal forms
        const editForm = document.getElementById('user-edit-modal');
        const addForm = document.getElementById('user-add-modal');

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        let timer = null;
        let currentSorts = [];

        const urlParams = new URLSearchParams(window.location.search);
        let page = parseInt(urlParams.get('page')) || 1;
        let perPage = parseInt(urlParams.get('perPage')) || 10;
        const fromEmail = urlParams.has('from') && urlParams.get('from') === 'email';
        let lastFetchedData;

        const loader = document.createElement("div");
        loader.className = `
            absolute inset-0 flex items-center justify-center
            bg-transparent backdrop-blur-sm rounded-lg z-10
        `;
        loader.innerHTML = `
            <div class="main-fader" responsive-height-comments>
                <div class="loader">
                <svg viewBox="0 0 866 866" xmlns="http://www.w3.org/2000/svg">
                        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 164.83 151.5">
                            <path class="path-0" d="M117.24,69.24A8,8,0,0,0,115.67,67c-4.88-4-9.8-7.89-14.86-11.62A4.93,4.93,0,0,0,96.93,55c-5.76,1.89-11.4,4.17-17.18,6a4.36,4.36,0,0,0-3.42,4.12c-1,6.89-2.1,13.76-3,20.66a4,4,0,0,0,1,3.07c5.12,4.36,10.39,8.61,15.68,12.76a3.62,3.62,0,0,0,2.92.75c6.29-2.66,12.52-5.47,18.71-8.36a3.49,3.49,0,0,0,1.68-2.19c1.34-7.25,2.54-14.55,3.9-22.58Z"
                                fill="#ffffffc5" />
                            <path class="path-1" d="M97.55,38.68A43.76,43.76,0,0,1,98,33.44c.41-2.36-.5-3.57-2.57-4.64C91.1,26.59,87,24,82.66,21.82a6.18,6.18,0,0,0-4-.71C73.45,22.55,68.32,24.25,63.22,26c-3.63,1.21-6.08,3.35-5.76,7.69a26.67,26.67,0,0,1-.6,4.92c-1.08,8.06-1.08,8.08,5.86,11.92,3.95,2.19,7.82,5.75,11.94,6.08s8.76-2.41,13.12-3.93c9.33-3.29,9.33-3.3,9.78-14Z"
                                fill="#ffffffc5" />
                            <path class="path-2" d="M66.11,126.56c5.91-.91,11.37-1.7,16.81-2.71a3.3,3.3,0,0,0,1.87-2.17c1-4.06,1.73-8.19,2.84-12.24.54-2-.11-3-1.55-4.15-5-4-9.9-8.12-15-12a6.19,6.19,0,0,0-4.15-1.1c-5.35.66-10.7,1.54-16,2.54A4,4,0,0,0,48.34,97a109.13,109.13,0,0,0-3,12.19,4.47,4.47,0,0,0,1.34,3.6c5.54,4.36,11.23,8.53,16.91,12.69a10.84,10.84,0,0,0,2.57,1.11Z"
                                fill="#ffffffc5" />
                            <path class="path-3" d="M127.42,104.12c4.1-2.1,8-3.93,11.72-6a6,6,0,0,0,2.27-3,58.22,58.22,0,0,0,3.18-29.92c-.26-1.7-8-7.28-9.71-6.85A5,5,0,0,0,133,59.65c-2.81,2.49-5.71,4.88-8.33,7.56a9.46,9.46,0,0,0-2.47,4.4c-1.29,6.49-2.38,13-3.35,19.55a5.73,5.73,0,0,0,.83,3.91c2.31,3.08,5,5.88,7.7,9Z"
                                fill="#ffffffc5" />
                            <path class="path-4" d="M52.58,29.89c-2.15-.36-3.78-.54-5.39-.9-2.83-.64-4.92.1-7,2.32A64.1,64.1,0,0,0,26.09,54.64c-2.64,7.92-2.62,7.84,5.15,10.87,1.76.69,2.73.45,3.93-1C39.79,59,44.54,53.65,49.22,48.2a4.2,4.2,0,0,0,1.13-2c.8-5.32,1.49-10.68,2.24-16.34Z"
                                fill="#ffffffc5" />
                            <path class="path-5" fill="#ffffffc5" d="M23,68.13c0,2.51,0,4.7,0,6.87a60.49,60.49,0,0,0,9.75,32.15c1.37,2.13,6.4,3,7,1.2,1.55-5,2.68-10.2,3.82-15.34.13-.58-.58-1.38-.94-2.06-2.51-4.77-5.47-9.38-7.45-14.37C32.94,71,28.22,69.84,23,68.13Z" />
                            <path class="path-6" fill="#ffffffc5" d="M83.91,12.86c-.32.36-.66.71-1,1.07.9,1.13,1.57,2.62,2.73,3.33,4.71,2.84,9.56,5.48,14.39,8.1a9.29,9.29,0,0,0,3.13.83c5.45.69,10.89,1.38,16.35,1.94a10.41,10.41,0,0,0,3.07-.71c-11.48-9.9-24.26-14.61-38.71-14.56Z"
                            />
                            <path class="path-7" fill="#ffffffc5" d="M66.28,132.51c13.36,3.78,25.62,3.5,38-.9C91.68,129.59,79.36,128,66.28,132.51Z" />
                            <path class="path-8" fill="#ffffffc5" d="M127.2,30.66l-1.27.37a18.58,18.58,0,0,0,1,3.08c3,5.52,6.21,10.89,8.89,16.54,1.34,2.83,3.41,3.82,6.49,4.9a60.38,60.38,0,0,0-15.12-24.9Z" />
                            <path class="bb-9" fill="#ffffffc5" d="M117.35,125c5.58-2.32,16.9-13.84,18.1-19.2-2.41,1.46-5.18,2.36-6.78,4.23-4.21,5-7.89,10.37-11.32,15Z" />
                        </svg>
                    </svg>
                </div>
            </div>
            <style>
                .main-fader{
                    width:100%;
                    position: absolute;
                    .loader {
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        svg {
                        height: 100px;
                        display: block;
                        margin: 0 auto;
                        path {
                            animation-duration: 1s;
                            animation-name: pulse;
                            animation-iteration-count: infinite;
                            color: #ffffff70;

                            &.path-7 {
                            animation-delay: -1s
                            }
                            &.path-6 {
                            animation-delay: -.875s
                            }
                            &.path-5 {
                            animation-delay: -.75s
                            }
                            &.path-4 {
                            animation-delay: -.625s
                            }
                            &.path-3 {
                            animation-delay: -.5s
                            }
                            &.path-2 {
                            animation-delay: -.375s
                            }
                            &.path-1 {
                            animation-delay: -.25s
                            }
                            &.path-0 {
                            animation-delay: -.125s
                            }
                        }
                        }
                    }
                    }


                    @keyframes pulse {
                    0%     { opacity: .1; }
                    30%    { opacity: .8; }
                    100%   { opacity: .1; }
                    }
            </style>
        `;

        const loaderMobile = loader.cloneNode(true);


        /****************************************
        *   -------- Async functions --------   *
        ****************************************/

        /**
         *
         * @param {*} search
         * @param {*} ordres
         * @param {*} etats
         * @param {*} roles
         * @returns
         */
        async function fetchUsers(search = "", ordres = [], etats = [], roles = []) {
            try {

                if (!Array.isArray(roles)) {
                    roles = roles ? [roles] : [];
                }
                if (!Array.isArray(ordres)) {
                    ordres = ordres ? [ordres] : [];
                }
                if (!Array.isArray(etats)) {
                    etats = etats ? [etats] : [];
                }
                const params = new URLSearchParams({
                    search: search,
                    perPage: perPage,
                    page: page
                });
                ordres.forEach(ordre => params.append('orders[]', ordre));
                roles.forEach(role => params.append('roles[]', role));
                etats.forEach(etat => params.append('etats[]', etat));

                const response = await fetch(`/users/search?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        if (data.errors.search)
                            showError(errorSearch, data.errors.search[0]);
                        if (data.errors.orders)
                            showError(errorSort, data.errors.orders[0]);
                        if (data.errors.etat)
                            showError(errorEtat, data.errors.etat[0]);
                        if (data.errors.roles)
                            showError(errorRole, data.errors.roles[0]);
                    }
                    return data;
                }
                lastFetchedData = data;
                updateTable(data.data);
                updatePagination(data);
                const newUrl = `${window.location.pathname}?page=${page}&perPage=${perPage}`;
                window.history.replaceState({}, '', newUrl);
                document.getElementById('perPage').value = perPage;

                clearError(errorSearch);
                clearError(errorSort);
                clearError(errorEtat);
                clearError(errorRole);
            } catch (e) {
                console.error('Erreur lors de la récupération des usagers:', e);
            }
        }

        /**
         * Envoi une requête PATCH au controlleur
         * @param {*} userData
         * @returns
         */
        async function updateUser(userData) {
            const url = '/users/update'
            const options = {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(userData)
            }

            try {
                const response = await fetch(url, options);
                const data = await response.json();

                clearError(errorNomEdit);
                clearError(errorPrenomEdit);
                clearError(errorEmailEdit);
                if (!response.ok) {
                    if (data.errors) {
                        if (data.errors.nom)
                            showError(errorNomEdit, data.errors.nom[0]);
                        if (data.errors.prenom)
                            showError(errorPrenomEdit, data.errors.prenom[0]);
                        if (data.errors.email)
                            showError(errorEmailEdit, data.errors.email[0]);
                    }
                    return data;
                }
                return data;
            } catch (err) {
                return { success: false, message: "Une erreur s'est produite." };
            }
        }

        /**
         * Envoi une requête DELETE au controlleur
         * @param {*} userId Id de l'utilisateur à supprimer
         * @returns Données JSON de la réponse de la requête
         */
        async function deleteUser(userId) {
            const url = '/users/delete'
            const options = {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(userId)
            }

            try {
                const response = await fetch(url, options);
                const data = await response.json();
                return data;
            } catch (err) {
                console.error(err);
                return { success: false, message: "Une erreur s'est produite." };
            }
        }

        /**
         * Envoi une requête POST au controlleur
         * @param {*} userData
         * @returns
         */
        async function addUser(userData) {
            const url = '/users/add'
            const options = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(userData)
            }

            try {
                const response = await fetch(url, options);
                const data = await response.json();

                clearError(errorNomAdd);
                clearError(errorPrenomAdd);
                clearError(errorEmailAdd);
                clearError(errorPasswordAdd);
                if (!response.ok) {
                    if (data.errors) {

                        if (data.errors.nom)
                            showError(errorNomAdd, data.errors.nom[0]);
                        if (data.errors.prenom)
                            showError(errorPrenomAdd, data.errors.prenom[0]);
                        if (data.errors.email)
                            showError(errorEmailAdd, data.errors.email[0]);
                        if (data.errors.mdp)
                            showError(errorPasswordAdd, data.errors.mdp[0]);
                    }
                    return data;
                }

                resetAddUserForm();
                return data;
            } catch (err) {
                console.error(err);
                return { success: false, message: "Une erreur s'est produite." };
            }
        }

        /**********************************
        *   -------- Functions --------   *
        **********************************/

        /**
         *
         * @param {*} errorSpan
         * @param {*} message
         */
        function showError(errorSpan, message) {
            errorSpan.textContent = message;
            errorSpan.classList.remove('hidden');
        }

        /**
         *
         */
        function clearError(errorSpan) {
            errorSpan.textContent = '';
            errorSpan.classList.add('hidden');
        }

        /**
         *
         */
        function resetAddUserForm() {
            document.getElementById('addUserId').value = '';
            document.getElementById('addPrenom').value = '';
            document.getElementById('addNom').value = '';
            document.getElementById('addEmail').value = '';
            document.getElementById('addMdp').value = '';
            document.getElementById('addMdpConfirm').value = '';
            document.getElementById('addEtat').value = 1;
            Array.from(document.getElementById('addRole').selectedOptions).forEach(option => option.selected = false);
        }

        /**
         *
         * @returns
         */
        function getCurrentOrders() {
            return currentSorts
                .filter(s => s.direction !== 'neutral')
                .map(s => s.direction === 'asc' ? s.orderValueAsc : s.orderValueDesc);
        }

        // Update TomSelect from currentSorts
        /**
         *
         */
        function updateSortSelect() {
            const sortSelect = document.getElementById('sort');

            // Clear all selected options
            Array.from(sortSelect.options).forEach(o => o.selected = false);

            // Set selected options from currentSorts
            getCurrentOrders().forEach(val => {
                const option = sortSelect.querySelector(`option[value="${val}"]`);
                if (option) option.selected = true;
            });

            // Refresh TomSelect UI
            if (sortSelect.tomselect) {
                sortSelect.tomselect.clearOptions(false);
                sortSelect.tomselect.refreshOptions(false);
                sortSelect.tomselect.setValue(getCurrentOrders());
            }
        }

        // Update <thead> indicators
        /**
         *
         */
        function updateTableHeaders() {
            document.querySelectorAll("thead th[data-order-asc]").forEach(th => {
                const s = currentSorts.find(s => s.col === th);
                const indicator = th.querySelector('.sort-indicator');
                if (!indicator) return;

                if (!s || s.direction === 'neutral') {
                    indicator.textContent = '';
                } else {
                    const index = currentSorts.indexOf(s) + 1;
                    const arrow = s.direction === 'asc' ? '↑' : '↓';
                    indicator.textContent = currentSorts.length > 1 ? `${arrow}${index}` : arrow;
                }
            });
        }

        /**
         *
         * @param {*} user
         * @returns
         */
        function renderDesktopRow(user) {
            let rolesHTML = Array.isArray(user.roles) && user.roles.length > 0
                ? user.roles.map(role =>
                    `<span class="bg-blue-100 text-gray-700 text-center text-xs font-semibold px-2 py-1 rounded-full">${role.nom_role}</span>`
                ).join('')
                : '<span class="text-gray-400 text-xs">Aucun rôle</span>';

            const statusClass =
                user.etat?.nom_etat === 'Actif'
                    ? 'bg-green-100 text-green-700'
                    : user.etat?.nom_etat === 'Inactif'
                    ? 'bg-yellow-100 text-yellow-700'
                    : 'bg-red-100 text-red-700';

            const createdAt = new Date(user.created_at); // assume this comes from the backend in UTC

            // Format in Montreal time
            const formattedDate = createdAt.toLocaleString('en-CA', {
                timeZone: 'America/Montreal',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(',', '');

            const tr = document.createElement('tr');
            tr.className = 'min-h-[2rem] text-gray-100 group hover:bg-white/30 transition-colors duration-200';
            tr.dataset.user = JSON.stringify({
                id: user.id,
                prenom: user.prenom,
                nom: user.nom,
                email: user.email,
                etat: user.etat,
                roles: user.roles?.map(r => r.id) ?? [],
                created_at: user.created_at,
                equipes: user.equipes?.map(e => e.id) ?? []
            });

            tr.innerHTML = `
                <td class="px-4 py-3 font-semibold">${user.prenom}</td>
                <td class="px-4 py-3 font-semibold">${user.nom}</td>
                <td class="px-4 py-3 text-blue-600 font-bold transition-colors duration-200 group-hover:text-blue-300">${user.email}</td>
                <td class="px-4 py-3">
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                        ${user.etat?.nom_etat || 'Inconnu'}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex flex-wrap gap-1">
                        ${rolesHTML || 'Aucun rôle'}
                    </div>
                </td>
                <td class="px-4 py-3 font-semibold">${formattedDate}</td>
                <td class="pr-4">
                    <button class="edit-user w-full flex justify-center items-center transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110" name="id_user" value="${user.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                    </button>
                </td>
            `;
            return tr;
        }

        /**
         *
         * @param {*} user
         * @returns
         */
        function renderMobileRow(user) {
            let rolesHTML = Array.isArray(user.roles) && user.roles.length > 0
                ? user.roles.map(role =>
                    `<span class="bg-blue-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded-full">${role.nom_role}</span>`
                ).join('')
                : '<span class="text-gray-400 text-xs">Aucun rôle</span>';

            const statusClass =
                user.etat?.nom_etat === 'Actif'
                    ? 'bg-green-100 text-green-700'
                    : user.etat?.nom_etat === 'Inactif'
                    ? 'bg-yellow-100 text-yellow-700'
                    : 'bg-red-100 text-red-700';

            const tr = document.createElement('tr');
            tr.className = 'block md:table-row group hover:bg-white/30 text-lg transition-colors duration-200 p-4 md:p-0';
            tr.dataset.user = JSON.stringify({
                id: user.id,
                prenom: user.prenom,
                nom: user.nom,
                email: user.email,
                etat: user.etat,
                roles: user.roles?.map(r => r.id) ?? [],
                created_at: user.created_at,
                equipes: user.equipes?.map(e => e.id) ?? []
            });

            tr.innerHTML = `
                <td class="block md:table-cell px-4 py-3">
                    <span class="text-gray-300 md:hidden font-semibold">Prénom:</span> ${user.prenom}
                </td>
                <td class="block md:table-cell px-4 py-3">
                    <span class="text-gray-300 md:hidden font-semibold">Nom:</span> ${user.nom}
                </td>
                <td class="block md:table-cell px-4 py-3 text-blue-600 font-bold">
                    <span class="text-gray-300 md:hidden font-semibold">Courriel:</span> <span class="transition-colors duration-200 group-hover:text-blue-300">${user.email}</span>
                </td>
                <td class="block md:table-cell px-4 py-3">
                    <span class="text-gray-300 md:hidden font-semibold">État:</span>
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                        ${user.etat?.nom_etat || 'Inconnu'}
                    </span>
                </td>
                <td class="block md:table-cell px-4 py-3">
                    <span class="text-gray-300 md:hidden font-semibold">Rôles:</span>
                    <div class="flex flex-wrap gap-1 mt-1 md:mt-0">
                        ${rolesHTML}
                    </div>
                </td>
                <td class="flex md:table-cell px-4 py-3 justify-end">
                    <button class="edit-user transform transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-110" name="id_user" value="${user.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                    </button>
                </td>
            `;
            return tr;
        }

        function updatePagination(data) {
            pagination.innerHTML = '';
            const totalPages = data.last_page;
            const currentPage = data.current_page;

            pagination.className = "flex items-center justify-center gap-1 text-sm";

            const makeButton = (label, disabled, onClick, active = false) => {
                const btn = document.createElement('button');
                btn.textContent = label;
                btn.disabled = disabled;
                btn.className = [
                    'px-3 py-1 rounded-md transition-colors duration-150',
                    active
                        ? 'bg-red-400 text-white font-semibold'
                        : 'bg-white/10 text-gray-300 hover:bg-red-800/20',
                    disabled ? 'opacity-40 cursor-not-allowed' : ''
                ].join(' ');
                if (!disabled) btn.addEventListener('click', onClick);
                return btn;
            };

            let maxVisible;
            if (window.innerWidth >= 1024) maxVisible = 5;       // Desktop
            else if (window.innerWidth >= 640) maxVisible = 3;   // Tablet
            else maxVisible = 2;

            pagination.appendChild(makeButton('‹', currentPage === 1, () => {
                page--;
                fetchUsers(
                    inputSearch.value,
                    getCurrentOrders(),
                    Array.from(etatFiltre.selectedOptions).map(o => o.value),
                    Array.from(roleFiltre.selectedOptions).map(o => o.value)
                );
            }));

            let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
            let end = Math.min(totalPages, start + maxVisible - 1);
            if (end - start < maxVisible - 1) start = Math.max(1, end - maxVisible + 1);

            if (start > 1) {
                pagination.appendChild(makeButton('1', false, () => {
                    page = 1;
                    fetchUsers(
                        inputSearch.value,
                        getCurrentOrders(),
                        Array.from(etatFiltre.selectedOptions).map(o => o.value),
                        Array.from(roleFiltre.selectedOptions).map(o => o.value)
                    );
                }));
                if (start > 2) pagination.appendChild(makeButton('…', true));
            }

            for (let i = start; i <= end; i++) {
                pagination.appendChild(makeButton(i, false, () => {
                    page = i;
                    fetchUsers(
                        inputSearch.value,
                        getCurrentOrders(),
                        Array.from(etatFiltre.selectedOptions).map(o => o.value),
                        Array.from(roleFiltre.selectedOptions).map(o => o.value)
                    );
                }, i === currentPage));
            }

            if (end < totalPages) {
                if (end < totalPages - 1) pagination.appendChild(makeButton('…', true));
                pagination.appendChild(makeButton(totalPages, false, () => {
                    page = totalPages;
                    fetchUsers(
                        inputSearch.value,
                        getCurrentOrders(),
                        Array.from(etatFiltre.selectedOptions).map(o => o.value),
                        Array.from(roleFiltre.selectedOptions).map(o => o.value)
                    );
                }));
            }

            // Next button
            pagination.appendChild(makeButton('›', currentPage === totalPages, () => {
                page++;
                fetchUsers(
                    inputSearch.value,
                    getCurrentOrders(),
                    Array.from(etatFiltre.selectedOptions).map(o => o.value),
                    Array.from(roleFiltre.selectedOptions).map(o => o.value)
                );
            }));
        }

        function smoothScrollToTop(element) {
            if (!element) return;
            element.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }

        /**
         *
         * @param {*} users
         * @returns
         */
        function updateTable(users) {

            listUsers.style.position = "relative";
            listUsers.parentElement.appendChild(loader);
            listUsersMobile.style.position = "relative";
            listUsersMobile.parentElement.appendChild(loaderMobile);

            requestAnimationFrame(() => {
                listUsers.parentElement.appendChild(loader);
                listUsersMobile.parentElement.appendChild(loaderMobile);
            });

            listUsers.innerHTML = ''
            listUsersMobile.innerHTML = ''

            if (users.length === 0) {
                listUsers.innerHTML = `
                    <tr>
                        <td class="px-4 py-3 text-center text-gray-300" colspan="5">
                            Aucun utilisateur ne correspond à la recherche.
                        </td>
                    </tr>
                `;
                listUsersMobile.innerHTML = `
                    <tr class="block md:table-row">
                        <td class="px-4 py-3 text-center text-gray-300 block md:table-cell" colspan="5">
                            Aucun utilisateur ne correspond à la recherche.
                        </td>
                    </tr>
                `;
                setTimeout(() => {
                    loader.remove();
                    loaderMobile.remove();
                }, 300);
                return;
            }

            users.forEach(user => {
                listUsers.appendChild(renderDesktopRow(user));
                listUsersMobile.appendChild(renderMobileRow(user));
            });

            smoothScrollToTop(listUsers.parentElement.parentElement);
            smoothScrollToTop(listUsersMobile.parentElement.parentElement.parentElement.parentElement);

            setTimeout(() => {
                loader.remove();
                loaderMobile.remove();
            }, 300);
        }

        /**
         *
         * @param {*} user
         */
        function updateTableRow(user) {
            const rows = Array.from(document.querySelectorAll('tr[data-user]'))
                .filter(r => JSON.parse(r.dataset.user).id == user.id);

            rows.forEach(oldRow => {
                const parent = oldRow.parentNode;
                const isMobile = oldRow.classList.contains('block');
                const newRow = isMobile ? renderMobileRow(user) : renderDesktopRow(user);
                parent.replaceChild(newRow, oldRow);
            });

        }

        /**
         *
         * @param {*} data
         */
        function openUserEditForm(data) {
            document.getElementById('editPrenom').value = data.prenom;
            document.getElementById('editNom').value = data.nom;
            document.getElementById('editEmail').value = data.email;
            document.getElementById('editEtat').value = data.etat?.id;
            document.getElementById('editUserId').value = data.id;

            const rolesSelect = document.getElementById('editRole');
            Array.from(rolesSelect.options).forEach(option => {
                option.selected = data.roles.includes(parseInt(option.value));
            });

            const equipesSelect = document.getElementById('editEquipes');
            Array.from(equipesSelect.options).forEach(option => {
                option.selected = data.equipes.includes(parseInt(option.value));
            });

            editForm.classList.remove('hidden');
            setTimeout(() => {
                editForm.classList.add('opacity-100');
                editForm.classList.add('scale-100');
            }, 10);
        }

        /**
         *
         */
        function openUserAddForm() {
            addForm.classList.remove('hidden');
            setTimeout(() => {
                addForm.classList.add('opacity-100');
                addForm.classList.add('scale-100');
            }, 10);
        }

        /**
         *
         * @param {*} modal
         */
        function closeModal(modal) {
            modal.classList.remove('opacity-100');
            modal.classList.remove('scale-100');
            modal.classList.add('opacity-0');
            modal.classList.add('scale-100');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }


        /****************************************
        *   -------- Event listeners --------   *
        ****************************************/


        // Filtres asynchrones
        inputSearch.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                page = 1;
                fetchUsers(
                    inputSearch.value,
                    getCurrentOrders(),
                    Array.from(etatFiltre.selectedOptions).map(o => o.value),
                    Array.from(roleFiltre.selectedOptions).map(o => o.value)
                );
            }, 300);
        });

        etatFiltre.addEventListener('change', () => {
            page = 1;
            fetchUsers(
                inputSearch.value,
                getCurrentOrders(),
                Array.from(etatFiltre.selectedOptions).map(o => o.value),
                Array.from(roleFiltre.selectedOptions).map(o => o.value)
            );
        });

        roleFiltre.addEventListener('change', () => {
            page = 1;
            fetchUsers(
                inputSearch.value,
                getCurrentOrders(),
                Array.from(etatFiltre.selectedOptions).map(o => o.value),
                Array.from(roleFiltre.selectedOptions).map(o => o.value)
            );
        });

        document.getElementById('perPage').addEventListener('change', () => {
            perPage = parseInt(document.getElementById('perPage').value);
            page = 1;
            fetchUsers(
                inputSearch.value,
                getCurrentOrders(),
                Array.from(etatFiltre.selectedOptions).map(o => o.value),
                Array.from(roleFiltre.selectedOptions).map(o => o.value)
            );
        });

        document.querySelectorAll("thead th[data-order-asc]").forEach(th => {
            th.addEventListener('click', () => {
                let sort = currentSorts.find(s => s.col === th);

                if (!sort) {
                    sort = {
                        col: th,
                        direction: 'asc',
                        orderValueAsc: parseInt(th.dataset.orderAsc),
                        orderValueDesc: parseInt(th.dataset.orderDesc)
                    };
                    currentSorts.push(sort);
                } else {
                    if (sort.direction === 'asc') sort.direction = 'desc';
                    else if (sort.direction === 'desc') sort.direction = 'neutral';
                    else sort.direction = 'asc';
                }

                currentSorts = currentSorts.filter(s => s.direction !== 'neutral');

                updateTableHeaders();
                updateSortSelect();

                fetchUsers(
                    inputSearch.value,
                    getCurrentOrders(),
                    Array.from(etatFiltre.selectedOptions).map(o => o.value),
                    Array.from(roleFiltre.selectedOptions).map(o => o.value)
                );
            });
        });

        // Prevenir soumission formulaire
        if (filtreForm) {
            filtreForm.addEventListener('submit', evt => {
                evt.preventDefault();
            });
        }

        // Cliquer sur modifier
        document.addEventListener('click', evt => {
            const button = evt.target.closest('.edit-user');
            if (!button) return;

            const tr = button.closest('tr');
            if (!tr) return;

            const userData = JSON.parse(tr.dataset.user);
            openUserEditForm(userData);
        });

        document.getElementById('add-user').addEventListener('click', () => {
            openUserAddForm();
        });

        // Gestion de modification utilisateur
        document.getElementById('editSave').addEventListener('click', async () => {
            const userData = {
                id: document.getElementById('editUserId').value,
                prenom: document.getElementById('editPrenom').value,
                nom: document.getElementById('editNom').value,
                email: document.getElementById('editEmail').value,
                etat: document.getElementById('editEtat').value,
                roles: Array.from(document.getElementById('editRole').selectedOptions).map(o => o.value),
                equipes: Array.from(document.getElementById('editEquipes').selectedOptions).map(o => o.value),
                mdp: document.getElementById('editMdp')?.value || null,
                tel: document.getElementById('editTel')?.value || null
            };

            const result = await updateUser(userData);

            if (result.success) {
                closeModal(editForm);
                updateTableRow(result.user);
                toastr.success(result.message);
            } else if (result.errors) {
                toastr.error(result.message);
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }
        });

        // Gestion de suppression utilisateur
        document.getElementById('editDelete').addEventListener('click', async () => {
            const userId = {
                id: document.getElementById('editUserId').value
            };

            const confirm = await Swal.fire({
                title: "Êtes-vous certain?",
                text: `"${document.getElementById('editEmail').value}" sera supprimé définitivement!`,
                icon: "warning",
                color: "#f9fafb",
                background: "rgba(1, 1, 1, 0.6)",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmer la suppression",
                cancelButtonText: "Annuler",

                customClass: {
                    popup: "rounded-lg shadow-lg backdrop-blur",
                },

                didOpen: (popup) => {
                    popup.style.border = "1px solid rgba(255, 255, 255, 0.2)";
                    popup.style.backdropFilter = "blur(10px)";
                },
            });

            if (!confirm.isConfirmed) return;

            const result = await deleteUser(userId);

            if (result.success) {
                closeModal(editForm);
                fetchUsers(
                    inputSearch.value,
                    getCurrentOrders(),
                    Array.from(etatFiltre.selectedOptions).map(o => o.value),
                    Array.from(roleFiltre.selectedOptions).map(o => o.value)
                );
                toastr.success(result.message);
            } else if (result.errors) {
                toastr.error(result.message);
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }
        });

        // Gestion d'ajout utilisateur
        document.getElementById('addSave').addEventListener('click', async () => {
            const userData = {
                id: document.getElementById('addUserId').value,
                prenom: document.getElementById('addPrenom').value,
                nom: document.getElementById('addNom').value,
                email: document.getElementById('addEmail').value,
                etat: document.getElementById('addEtat').value,
                roles: Array.from(document.getElementById('addRole').selectedOptions).map(o => o.value),
                mdp: document.getElementById('addMdp').value,
                mdp_confirmation: document.getElementById('addMdpConfirm').value,
                tel: document.getElementById('addTel')?.value || null
            };

            const result = await addUser(userData);

            if (result.success) {
                closeModal(addForm);
                fetchUsers(
                    inputSearch.value,
                    getCurrentOrders(),
                    etatFiltre.value,
                    Array.from(roleFiltre.selectedOptions).map(o => o.value)
                );
                toastr.success(result.message);
            } else if (result.errors) {
                toastr.error(result.message);
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }
        });

        function styleTomSelect(selector, dropdownParent, placeholder) {
            const options = {
                plugins: ['remove_button'],
                placeholder: placeholder,

                render: {
                    option: function(data, escape) {
                        return `<div class="flex items-center gap-2 px-2 py-1">
                                    <span class="inline-block w-3 h-3 rounded-full" style="background-color:${data.couleur};"></span>
                                    <span class="text-gray-800">${escape(data.text)}</span>
                                </div>`;
                    },
                    item: function(data, escape) {
                        return `<div class="flex items-center gap-1 bg-white/10 rounded px-2 py-0.5 mr-1">
                                    <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color:${data.couleur};"></span>
                                    ${escape(data.text)}
                                </div>`;
                    }
                },

                onInitialize: function() {
                    const control = this.wrapper.querySelector('.ts-control');

                    control.classList.add(
                        'border', 'border-gray-400', 'rounded', 'w-full', 'p-2', 'mb-1',
                        'text-gray-50', 'bg-white/10',
                        'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-red-400',
                        'focus:border-red-400', 'focus:outline-none'
                    );

                    // Helper to apply classes / inline style to any input inside given root
                    const applyInputStyling = (root) => {
                        if (!root) return;
                        const inputs = root.querySelectorAll('input, textarea');
                        inputs.forEach(input => {
                            input.classList.add('text-gray-50', 'placeholder-gray-400');
                            input.style.color = '#F9FAFB';
                            input.style.background = 'transparent';
                            if (input.placeholder) {
                                input.style.setProperty('caret-color', '#F9FAFB');
                            }
                        });
                    };

                    applyInputStyling(this.wrapper);
                    applyInputStyling(this.dropdown);

                    if (this.dropdown) {
                        const obs = new MutationObserver(mutations => {
                            mutations.forEach(m => {
                                if (m.addedNodes && m.addedNodes.length) {
                                    applyInputStyling(this.dropdown);
                                }
                            });
                        });
                        obs.observe(this.dropdown, { childList: true, subtree: true });

                        this._ts_inputObserver = obs;
                    }
                }

            };

            if (dropdownParent) {

                if (dropdownParent === "body") {
                    options.dropdownParent = "body";
                }

                else if (typeof dropdownParent === "string") {
                    const el = document.querySelector(dropdownParent);
                    if (el) options.dropdownParent = el;
                }

                else if (dropdownParent instanceof Element) {
                    options.dropdownParent = dropdownParent;
                }
            }

            const tom = new TomSelect(selector, options);

            (function installForcePlacement(tsInstance) {

                const reposition = () => {
                    const dropdown = tsInstance.dropdown;
                    const wrapper = tsInstance.wrapper;

                    if (!dropdown || !wrapper) return;

                    // append dropdown to body if not already
                    if (dropdown.parentElement !== document.body) {
                        document.body.appendChild(dropdown);
                    }

                    const rect = wrapper.getBoundingClientRect();
                    const scrollTop = window.scrollY || window.pageYOffset;
                    const scrollLeft = window.scrollX || window.pageXOffset;

                    Object.assign(dropdown.style, {
                        position: 'absolute',
                        left: `${rect.left + scrollLeft}px`,
                        top: `${rect.bottom + scrollTop - 4}px`,
                        width: `${rect.width}px`,
                        minWidth: `${rect.width}px`,
                        maxWidth: `${rect.width}px`,
                        zIndex: '100000',
                        transform: 'none',
                    });
                };


                // When dropdown opens, position it
                tsInstance.on('dropdown_open', () => {
                    // small timeout to allow TomSelect to render dropdown content
                    setTimeout(() => {
                        reposition();
                    }, 0);
                });

                // Also reposition on resize/scroll and when the dropdown content changes
                const onScrollResize = () => {
                    if (tsInstance.isOpen && tsInstance.dropdown) reposition();
                };
                window.addEventListener('scroll', onScrollResize, true); // capture to catch scrolling containers
                window.addEventListener('resize', onScrollResize);

                // Observe dropdown children for size changes (ex: dynamic search box height)
                const mo = new MutationObserver(() => {
                    if (tsInstance.isOpen) reposition();
                });
                mo.observe(document.body, { childList: true, subtree: true });

                // cleanup when TomSelect is destroyed (best-effort)
                tsInstance.on('destroy', () => {
                    window.removeEventListener('scroll', onScrollResize, true);
                    window.removeEventListener('resize', onScrollResize);
                    try { mo.disconnect(); } catch(e){}
                });

            })(tom);

            return tom;
        }

        function clearNativeSelects() {
            document.querySelectorAll('select').forEach(select => {
                select.querySelectorAll('option[selected]').forEach(o => o.selected = false);

                if (select.multiple) {
                    select.querySelectorAll('option[selected], option:checked').forEach(o => o.selected = false);
                    select.selectedIndex = -1;
                } else {
                    select.selectedIndex = 0;
                }
            });
        }

        clearNativeSelects();

        // Multiselect roles
        styleTomSelect("#filtre-etat", "body", "Sélectionnez des états");
        styleTomSelect("#filtre-role", "body", "Sélectionnez des rôles");
        styleTomSelect("#editRole", null, "Sélectionnez des rôles");
        styleTomSelect("#addRole", null, "Sélectionnez des rôles");
        styleTomSelect("#editEquipes", null, "Sélectionnez des équipes");
        const sortFiltre = new TomSelect("#sort", {
            plugins: ['remove_button'],
            placeholder: "Sélectionnez un ordre",
            dropdownParent: "body",
            render: {
                option: function(data, escape) {
                    return `<div class="flex items-center gap-2 px-2 py-1">
                                <span class="inline-block w-3 h-3 rounded-full" style="background-color:${data.couleur};"></span>
                                <span class="text-gray-800">${escape(data.text)}</span>
                            </div>`;
                },
                item: function(data, escape) {
                    return `<div class="flex items-center gap-1 bg-white/10 rounded px-2 py-0.5 mr-1">
                                <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color:${data.couleur};"></span>
                                ${escape(data.text)}
                            </div>`;
                }
            },
            onInitialize: function() {
                const control = this.wrapper.querySelector('.ts-control');

                control.classList.add(
                    'border', 'border-gray-400', 'rounded', 'w-full', 'p-2', 'mb-1',
                    'text-gray-50', 'bg-white/10',
                    'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-red-400',
                    'focus:border-red-400', 'focus:outline-none'
                );

                // Helper to apply classes / inline style to any input inside given root
                const applyInputStyling = (root) => {
                    if (!root) return;
                    const inputs = root.querySelectorAll('input, textarea');
                    inputs.forEach(input => {
                        input.classList.add('text-gray-50', 'placeholder-gray-400');
                        input.style.color = '#F9FAFB';
                        input.style.background = 'transparent';
                        if (input.placeholder) {
                            input.style.setProperty('caret-color', '#F9FAFB');
                        }
                    });
                };

                applyInputStyling(this.wrapper);
                applyInputStyling(this.dropdown);

                if (this.dropdown) {
                    const obs = new MutationObserver(mutations => {
                        mutations.forEach(m => {
                            if (m.addedNodes && m.addedNodes.length) {
                                applyInputStyling(this.dropdown);
                            }
                        });
                    });
                    obs.observe(this.dropdown, { childList: true, subtree: true });

                    this._ts_inputObserver = obs;
                }
            },
            onChange: () => {
                const selectedOrders = Array.from(sortFiltre.getValue()).map(Number);

                currentSorts = selectedOrders.map(val => {
                    const th = document.querySelector(`thead th[data-order-asc='${val}'], thead th[data-order-desc='${val}']`);
                    if (!th) return null;
                    const direction = th.dataset.orderAsc == val ? 'asc' : 'desc';
                    return {
                        col: th,
                        direction,
                        orderValueAsc: parseInt(th.dataset.orderAsc),
                        orderValueDesc: parseInt(th.dataset.orderDesc)
                    };
                }).filter(Boolean);

                updateTableHeaders();

                fetchUsers(
                    inputSearch.value,
                    getCurrentOrders(),
                    Array.from(etatFiltre.selectedOptions).map(o => o.value),
                    Array.from(roleFiltre.selectedOptions).map(o => o.value)
                );
            }
        });

        if (typeof TomSelect !== 'undefined' && TomSelect.instances) {
            Object.values(TomSelect.instances).forEach(inst => inst.clear());
        }

        window.addEventListener('resize', () => {
            if (typeof lastFetchedData !== 'undefined') {
                updatePagination(lastFetchedData);
            }
        });

        // Fetch
        if (!fromEmail) {
            fetchUsers(
                inputSearch.value,
                getCurrentOrders(),
                Array.from(etatFiltre.selectedOptions).map(o => o.value),
                Array.from(roleFiltre.selectedOptions).map(o => o.value)
            );
        }

    });
}
