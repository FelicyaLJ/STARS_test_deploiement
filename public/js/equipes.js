if (window.location.pathname === "/equipes") {
    const panel = document.getElementById('addEquipePanel');
    const form = document.getElementById('addEquipeForm');
    const nomInput = document.getElementById('nomEquipeInput');
    const nomErreur = document.getElementById('nomErreur');
    const formSuccess = document.getElementById('formSuccess');
    const listEquipes = document.getElementById('listEquipes');
    let isUpdate = false;

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

    // Open form (edit)
    document.querySelectorAll('.edit-equipe-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const url = btn.getAttribute('data-url');
            isUpdate = true;

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!response.ok) {
                    toastr.error('Erreur lors de la récupération des données.');
                    return;
                }

                const equipe = await response.json();

                form.action = `/equipes/modification/${equipe.id}`;
                setMethodField(form, 'PUT');

                form.querySelector('input[name="nom_equipe"]').value = equipe.nom_equipe;
                form.querySelector('select[name="id_categorie"]').value = equipe.id_categorie;
                form.querySelector('select[name="id_genre"]').value = equipe.id_genre;
                form.querySelector('select[name="id_etat"]').value = equipe.id_etat;
                form.querySelector('input[name="description"]').value = equipe.description ?? '';

                document.getElementById('addEquipePanel-title').textContent = 'Modifier ' + equipe.nom_equipe;
                showModal(panel);
            } catch (error) {
                console.error(error);
                toastr.error("Impossible de charger les données de l’équipe.");
            }
        });
    });

    // Open form (add)
    document.querySelectorAll('.open-add-form').forEach(btn => {
        btn.addEventListener('click', () => {
            isUpdate = false;
            form.reset();
            removeMethodField(form);
            form.action = btn.getAttribute('data-url');// || '/equipes/';
            document.getElementById('addEquipePanel-title').textContent = 'Ajouter une équipe';
            showModal(panel)
        });
    });

    function showModal(modal) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.classList.add('scale-100');
        }, 10);
    }

    // Show form on errors
    const errorMessagesInForm = panel.querySelectorAll('.text-red-600');
    const visibleErrors = Array.from(errorMessagesInForm).filter(el => el.offsetParent !== null);
    if (visibleErrors.length > 0) {
        panel.classList.remove('hidden');
    }

    // Submit form via AJAX
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        nomErreur.classList.add('hidden');
        formSuccess.classList.add('hidden');

        const formData = new FormData(form);
        fetch(form.action, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.errors) {
                // Show inline error if present
                if (data.errors.nom_equipe) {
                    nomErreur.textContent = data.errors.nom_equipe[0];
                    nomErreur.classList.remove('hidden');
                }
                // Also show toast for any error
                toastr.error('Erreur dans le formulaire.');
                return;
            }

            // Success case
            toastr.success(data.message || 'Équipe enregistrée avec succès.');

            fetchEquipes();

            form.reset();
            removeMethodField(form);
            panel.classList.add('hidden');
            loadTogglePlayers();
        })
        .catch(error => {
            if (error.errors && error.errors.nom_equipe) {
                nomErreur.textContent = error.errors.nom_equipe[0];
                nomErreur.classList.remove('hidden');
            } else {
                toastr.error("Erreur réseau ou serveur.", error.message);
            }
        });
    });

    // Live error clearing
    nomInput.addEventListener('input', () => {
        if (nomInput.value.trim() !== '') {
            nomErreur.classList.add('hidden');
        }
    });

    // DELETE de l'équipe asynchrone sans async/await
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            const url = form.getAttribute("action");
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const confirmation = await Swal.fire({
                title: "Êtes-vous certain?",
                text: `L'équipe sera supprimée définitivement!`,
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
            if (!confirmation.isConfirmed) return;

            console.log(url)

            try {
                const response = await fetch(url, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    }
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error(errorText);
                    toastr.error("Une erreur est survenue lors de la suppression.", errorText);
                    return;
                }

                const equipeDiv = form.closest(".equipe-item");
                if (equipeDiv) equipeDiv.remove();

                toastr.success("Équipe supprimée avec succès !");
            } catch (error) {
                toastr.error("Erreur réseau ou serveur.");
                console.error(error);
            }
        });
    });



    /*
        Fonctionnalité Jouueur
    */
    function loadTogglePlayers() {
        document.querySelectorAll('.toggle-joueurs').forEach(button => {
            button.addEventListener('click', async () => {
                const equipeId = button.getAttribute('data-equipe-id');
                const parentDiv = button.closest('.equipe-item');
                const joueursContainer = parentDiv.querySelector('.joueurs-list');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                if (!joueursContainer.classList.contains('hidden')) {
                    joueursContainer.classList.add('hidden');
                    button.textContent = '▼ Voir les joueurs';
                    return;
                }

                // If already loaded, just show cached list
                if (joueursContainer.getAttribute('data-loaded') === 'true') {
                    joueursContainer.classList.remove('hidden');
                    button.textContent = '▲ Masquer les joueurs';
                    return;
                }

                joueursContainer.innerHTML = `
                    <div class="loading-message text-gray-200">Chargement...</div>
                `;
                joueursContainer.classList.remove('hidden');

                try {
                    const response = await fetch(`/equipes/${equipeId}/joueurs`);

                    if (!response.ok) throw new Error('Erreur réseau');
                    const joueurs = await response.json();

                    if (joueurs.length === 0) {
                        joueursContainer.innerHTML = '<p class="text-gray-200">Aucun joueur trouvé.</p>';
                    } else {
                        const listHtml = joueurs.map(joueur => `
                            <div class="border-b border-gray-200 py-2 px-1 flex justify-between items-center">
                                <span>${joueur.prenom} ${joueur.nom}</span>

                                ${
                                    canManageEquipes
                                        ? `
                                            <form action="/equipes/${equipeId}/joueurs/${joueur.id}"
                                                class="delete-joueur-form inline-flex items-center ml-2 mb-0">
                                                <input type="hidden" name="_token" value="${csrfToken}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit"
                                                        class=" text-red-400 hover:text-red-600 text-sm transition-colors duration-200"
                                                        title="Supprimer le joueur">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-trash">
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                                        <path d="M3 6h18"/>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        `
                                        : ''
                                }
                            </div>
                        `).join('');

                        joueursContainer.innerHTML = listHtml;
                        joueursContainer.setAttribute('data-loaded', 'true');

                        attachDeleteJoueurListeners();
                    }

                    button.textContent = '▲ Masquer les joueurs';
                } catch (error) {
                    joueursContainer.innerHTML = '<p class="text-red-600">Erreur lors du chargement des joueurs.</p>';
                }
            });
        });
    }

    function attachDeleteJoueurListeners() {
        document.querySelectorAll('.delete-joueur-form').forEach(form => {
            form.addEventListener("submit", async (event) => {
                event.preventDefault();

                const url = form.getAttribute("action");
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const confirmation = await Swal.fire({
                    title: "Supprimer ce joueur ?",
                    text: "Cette action est irréversible.",
                    icon: "warning",
                    color: "#f9fafb",
                    background: "rgba(1, 1, 1, 0.6)",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Oui, supprimer",
                    cancelButtonText: "Annuler",
                    customClass: { popup: "rounded-lg shadow-lg backdrop-blur" },
                    didOpen: (popup) => {
                        popup.style.border = "1px solid rgba(255, 255, 255, 0.2)";
                        popup.style.backdropFilter = "blur(10px)";
                    },
                });

                if (!confirmation.isConfirmed) return;

                try {
                    const response = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        }
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        toastr.error("Une erreur est survenue lors de la suppression du joueur.", errorText);
                        return;
                    }

                    const joueurDiv = form.closest('.border-b');
                    if (joueurDiv) joueurDiv.remove();

                    toastr.success("Le joueur a été supprimé avec succès.");

                } catch (error) {
                    toastr.error("Erreur réseau ou serveur.");
                    console.error(error);
                }
            });
        });
    }


    function attachEmailSearchListener(input) {
        const suggestionsBox = input.nextElementSibling;

        input.addEventListener('input', async () => {
            const query = input.value.trim();
            const equipeId = input.getAttribute('data-equipe-id');

            if (query.length < 2) {
                suggestionsBox.classList.add('hidden');
                suggestionsBox.innerHTML = '';
                return;
            }

            try {
                const response = await fetch(`/joueurs/search?query=${encodeURIComponent(query)}&id_equipe=${equipeId}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Erreur réseau');

                const joueurs = await response.json();

                if (!joueurs.length) {
                    suggestionsBox.innerHTML = '<div class="px-2 py-1 text-gray-500">Aucun joueur trouvé</div>';
                } else {
                    suggestionsBox.innerHTML = joueurs.map(joueur => `
                        <div class="px-2 py-1 hover:bg-blue-100 cursor-pointer select-email" data-email="${joueur.email}">
                            ${joueur.prenom} ${joueur.nom} — <span class="text-sm text-gray-600">${joueur.email}</span>
                        </div>
                    `).join('');
                }

                suggestionsBox.classList.remove('hidden');
            } catch (error) {
                suggestionsBox.innerHTML = '<div class="px-2 py-1 text-red-500">Erreur lors de la recherche</div>';
                suggestionsBox.classList.remove('hidden');
            }
        });
    }

    function attachAddJoueurByEmailListener(form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const emailInput = form.querySelector('.email-search-input');
            const email = emailInput.value.trim();
            const equipeId = emailInput.getAttribute('data-equipe-id');

            if (!email) {
                toastr.error('Veuillez entrer un email.');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email }),
                });

                if (!response.ok) {
                    const err = await response.json();
                    toastr.error(err.message || 'Erreur lors de l\'ajout du joueur.');
                    return;
                }

                const data = await response.json();

                toastr.success(data.message || 'Joueur ajouté avec succès.');

                // Recharge la liste des joueurs de l’équipe (si visible)
                const parentDiv = form.closest('.equipe-item');
                const toggleBtn = parentDiv.querySelector('.toggle-joueurs');
                if (toggleBtn && !parentDiv.querySelector('.joueurs-list').classList.contains('hidden')) {
                    toggleBtn.click();  // Masquer
                    toggleBtn.click();  // Réafficher (reload)
                }

                emailInput.value = '';
                form.querySelector('.email-suggestions').classList.add('hidden');
            } catch (error) {
                toastr.error('Erreur réseau ou serveur.');
            }
        });
    }

    // Choisir un email dans les suggestions
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('select-email')) {
            const email = e.target.getAttribute('data-email');
            const input = e.target.closest('.email-suggestions').previousElementSibling;
            input.value = email;
            e.target.closest('.email-suggestions').classList.add('hidden');
        } else {
            // Clic en dehors = cacher les suggestions
            document.querySelectorAll('.email-suggestions').forEach(el => el.classList.add('hidden'));
        }
    });

    document.querySelectorAll('.email-search-input').forEach(input => {
        attachEmailSearchListener(input);
    });

    document.querySelectorAll('.form-ajout-joueur').forEach(form => {
        const input = form.querySelector('.email-search-input');
        if (input) attachEmailSearchListener(input);
        attachAddJoueurByEmailListener(form);
    });


    /**
     * Filtre
     */
    const filtreForm = document.getElementById('filtrerEquipes');
    const equipesContainer = document.querySelector('.equipes-container');

    if (filtreForm) {
        filtreForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            fetchEquipes();
        });
    }

    // Bouton "Annuler" pour réinitialiser les filtres
    const cancelBtn = document.getElementById('cancelEditBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', async () => {
            filtreForm.reset(); // Réinitialise les inputs/selects

            // Supprime les éventuelles valeurs forcées (comme hidden inputs si besoin)
            document.getElementById('nomPosteInput').value = '';
            document.getElementById('id_categorie_filtre').value = '';
            document.getElementById('id_genre_filtre').value = '';
            document.getElementById('id_etat_filtre').value = '';

            try {
                const response = await fetch(`/equipes/filtrer`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Erreur lors du chargement.');

                const data = await response.json();

                if (data.equipes.length === 0) {
                    equipesContainer.innerHTML = '<p class="text-gray-200 p-4">Aucune équipe trouvée.</p>';
                    return;
                }

                fetchEquipes()
            } catch (error) {
                joueursContainer.innerHTML = '<p class="text-red-600">Erreur lors du chargement des joueurs.</p>';
                toastr.error('Impossible de charger les joueurs de cette équipe.');
            }
        });
    }

    loadTogglePlayers();

    function setMethodField(form, method) {
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = method;
    }

    function removeMethodField(form) {
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
    }

    let currentController = null;

    async function fetchEquipes() {
        // Annule la requête précédente si elle est toujours en cours
        if (currentController) {
            currentController.abort();
        }

        // Nouveau contrôleur pour cette requête
        currentController = new AbortController();
        const signal = currentController.signal;

        const params = new URLSearchParams(new FormData(filtreForm)).toString();

        try {
            const response = await fetch(`/equipes/filtrer?${params}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                signal  // ← important
            });

            if (!response.ok) {
                toastr.error('Erreur lors de la récupération des équipes.');
                return;
            }

            const data = await response.json();

            updateEquipesList(data.equipes);

        } catch (err) {
            if (err.name === 'AbortError') {
                return;
            }
            toastr.error("Erreur lors du filtrage des équipes.");
        }
    }

    function updateEquipesList(equipes) {
        listEquipes.style.position = "relative";
        listEquipes.parentElement.appendChild(loader);

        fetch(`/equipe/render`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ equipes: equipes.map(e => e.id) })
        })
        .then(response => response.text())
        .then(html => {
            loader.remove();

            if (!html.trim()) {
                listEquipes.innerHTML = `
                    <div class="text-gray-200 text-center p-6">
                        Aucune équipe trouvée.
                    </div>
                `;
                return;
            }

            // Inject new HTML
            listEquipes.innerHTML = html;

            // Smooth scroll to top
            listEquipes.scrollTo({
                top: 0,
                behavior: "smooth"
            });

            bindAllItemListeners();

        })
        .catch(error => {
            loader.remove();
            console.error("Erreur lors du chargement des équipes :", error);
            listEquipes.innerHTML = `
                <div class="text-red-300 text-center p-4">
                    Erreur lors du chargement des équipes.
                </div>
            `;
        });
    }

    function bindAllItemListeners() {
        // Rebind all item-specific listeners
        document.querySelectorAll('.equipe-item').forEach(div => {
            const equipeId = div.getAttribute('data-equipe-id');

            // === EDIT BUTTON ===
            const editBtn = div.querySelector('.edit-equipe-btn');
            if (editBtn) {
                editBtn.addEventListener('click', async () => {
                    const url = editBtn.getAttribute('data-url');
                    isUpdate = true;

                    try {
                        const response = await fetch(url, {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!response.ok) {
                            toastr.error('Erreur lors de la récupération des données.');
                            return;
                        }

                        const equipe = await response.json();

                        form.action = `/equipes/modification/${equipe.id}`;
                        setMethodField(form, 'PUT');

                        form.querySelector('input[name="nom_equipe"]').value = equipe.nom_equipe;
                        form.querySelector('select[name="id_categorie"]').value = equipe.id_categorie;
                        form.querySelector('select[name="id_genre"]').value = equipe.id_genre;
                        form.querySelector('select[name="id_etat"]').value = equipe.id_etat;
                        form.querySelector('input[name="description"]').value = equipe.description ?? '';

                        document.getElementById('addEquipePanel-title').textContent = 'Modifier ' + equipe.nom_equipe;
                        showModal(panel);
                    } catch (error) {
                        console.error(error);
                        toastr.error("Impossible de charger les données de l’équipe.");
                    }
                });
            }

        });

        document.querySelectorAll('.email-search-input').forEach(input => {
            attachEmailSearchListener(input);
        });

        document.querySelectorAll('.form-ajout-joueur').forEach(form => {
            const input = form.querySelector('.email-search-input');
            if (input) attachEmailSearchListener(input);
            attachAddJoueurByEmailListener(form);
        });

        // === PLAYER TOGGLE BUTTONS ===
        loadTogglePlayers();

        // === DELETE FORM LISTENERS ===
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener("submit", async (event) => {
                event.preventDefault();

                const url = form.getAttribute("action");
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const confirmation = await Swal.fire({
                    title: "Êtes-vous certain?",
                    text: `L'équipe sera supprimée définitivement!`,
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

                if (!confirmation.isConfirmed) return;

                try {
                    const response = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        }
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error(errorText);
                        toastr.error("Une erreur est survenue lors de la suppression.", errorText);
                        return;
                    }

                    const equipeDiv = form.closest(".equipe-item");
                    if (equipeDiv) equipeDiv.remove();

                    toastr.success("Équipe supprimée avec succès !");
                } catch (error) {
                    toastr.error("Erreur réseau ou serveur.");
                    console.error(error);
                }
            });
        });

        document.querySelectorAll('.rejoindre-equipe-button').forEach(button => {
            button.addEventListener('click', () => {

                const id = button.dataset.equipeId;
                const nom = button.dataset.equipeNom;
                equipeId = id;

                document.getElementById('modalDemandeEquipe-title').textContent =
                    "Application pour rejoindre l'équipe " + nom;

                showModal(document.getElementById('modalDemandeEquipe'));
            });
        });

        document.querySelectorAll('.quit-form').forEach(form => {
            const button = form.querySelector('.quitter-equipe-button');

            button.addEventListener('click', async e => {
                e.preventDefault();

                const confirmation = await Swal.fire({
                    title: "Voulez vous quitter cette équipe?",
                    text: "Cette action est irréversible.",
                    icon: "warning",
                    color: "#f9fafb",
                    background: "rgba(1, 1, 1, 0.6)",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Quitter",
                    cancelButtonText: "Annuler",
                    customClass: { popup: "rounded-lg shadow-lg backdrop-blur" },
                    didOpen: (popup) => {
                        popup.style.border = "1px solid rgba(255, 255, 255, 0.2)";
                        popup.style.backdropFilter = "blur(10px)";
                    },
                });

                if (!confirmation.isConfirmed) return;

                form.submit();
            });
        });

        document.querySelectorAll('.adhesion-btn').forEach(button => {
            button.addEventListener('click', e => {
                e.preventDefault();

                const form = document.getElementById('equipeDemandeForm');
                const raisonInput = document.getElementById('raison');
                const errorSpan = document.getElementById('formErrorRaison');
                const equipeId = button.dataset.equipeId;

                const raison = raisonInput.value.trim();
                const validPattern = /^(?=.*\S).{5,}$/;

                if (!validPattern.test(raison)) {
                    errorSpan.classList.remove('hidden');
                    return;
                }

                errorSpan.classList.add('hidden');

                const idPattern = /^\d+$/;
                if (!idPattern.test(forumId)) {
                    alert('Forum ID invalide.');
                    return;
                }

                form.action = `/equipes/${equipeId}/rejoindre`;
                form.submit();
            });
        });
    }

    let equipeId = null;
    document.querySelectorAll('.rejoindre-equipe-button').forEach(button => {
        button.addEventListener('click', () => {

            const id = button.dataset.equipeId;
            const nom = button.dataset.equipeNom;
            equipeId = id;

            document.getElementById('modalDemandeEquipe-title').textContent =
                "Application pour rejoindre l'équipe " + nom;

            showModal(document.getElementById('modalDemandeEquipe'));
        });
    });

    document.querySelectorAll('.quit-form').forEach(form => {
        const button = form.querySelector('.quitter-equipe-button');

        button.addEventListener('click', async e => {
            e.preventDefault();

            const confirmation = await Swal.fire({
                title: "Voulez vous quitter cette équipe?",
                text: "Cette action est irréversible.",
                icon: "warning",
                color: "#f9fafb",
                background: "rgba(1, 1, 1, 0.6)",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Quitter",
                cancelButtonText: "Annuler",
                customClass: { popup: "rounded-lg shadow-lg backdrop-blur" },
                didOpen: (popup) => {
                    popup.style.border = "1px solid rgba(255, 255, 255, 0.2)";
                    popup.style.backdropFilter = "blur(10px)";
                },
            });

            if (!confirmation.isConfirmed) return;

            form.submit();
        });
    });

    document.querySelectorAll('.adhesion-btn').forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();

            const form = document.getElementById('equipeDemandeForm');
            const raisonInput = document.getElementById('raison');
            const errorSpan = document.getElementById('formErrorRaison');

            const raison = raisonInput.value.trim();
            const validPattern = /^(?=.*\S).{5,}$/;

            if (!validPattern.test(raison)) {
                errorSpan.classList.remove('hidden');
                return;
            }

            errorSpan.classList.add('hidden');

            const idPattern = /^\d+$/;
            if (!idPattern.test(equipeId)) {
                alert('Equipe ID invalide.');
                return;
            }

            form.action = `/equipes/${equipeId}/rejoindre`;
            form.submit();
        });
    });


}
