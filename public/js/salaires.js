/*
    Titre : Page Javascript pour la gestion des salaires
    Créé par : Nathaniel LeBlanc
    Date de création : 2025-09-20
    Modification :  2025-09-19 : Ajout de l'ajout de salaires
                    2025-09-20 : Ajout de la suppression de salaires
                    2025-09-20 : Correction bug lors de l'ajout
*/
if (document.location.pathname === "/salaires") {
    let currentOpenId = null;
    const panel = document.getElementById('editPostePanel');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const form = document.getElementById('editPosteForm');

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.edit-poste-btn').forEach(btn => {
            btn.addEventListener('click', handleEditClick);
        });

        document.querySelectorAll('.delete-poste-form').forEach(form => {
            form.addEventListener('submit', handleDeleteSubmit);
        });

        document.querySelectorAll('.open-add-form').forEach(btn => {
            btn.addEventListener('click', function() {
                const actionUrl = btn.getAttribute('data-url');
                const ordre = btn.getAttribute('data-ordre');

                openCreatePanel(actionUrl, ordre);
            });
        });

        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                closeEditPanel();
            });
        }

        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }

    });

    /*

    */
    function handleEditClick() {
        const url = this.getAttribute('data-url');
        const mode = this.getAttribute('data-mode');
        const ordre = this.getAttribute('data-ordre');

        if (mode === 'create') {
            openCreatePanel(url, ordre);
        } else {
            const parentDiv = this.closest('.poste-item') || this.closest('div[data-id]');
            if (!parentDiv) return;

            const nom = parentDiv.querySelector('.poste-nom')?.textContent.trim();
            const salaire = parentDiv.querySelector('.poste-salaire')?.textContent.trim().replace(' $', '');

            if (!nom || !salaire) {
                return;
            }

            openEditPanel(url, nom, salaire, ordre);
        }
    }

    /*
       Requête assynchrone pour la suppression
    */
    async function handleDeleteSubmit(e) {
        e.preventDefault();
        const confirm = await Swal.fire({
            title: "Êtes-vous certain?",
            text: `Le poste sera supprimé définitivement.`,
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

        const form = e.target;
        const url = form.action;
        const posteContainer = this.closest('.poste-item');


        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: new FormData(form)
        })
        .then(response => response.json().then(data => {
            if (response.ok) {
                if (posteContainer) posteContainer.remove();
                toastr.success('Poste supprimé avec succès');
            } else {
                toastr.error(data.error || 'Erreur lors de la suppression');
            }
        }))
        .catch(error => {
            toastr.error('Une erreur s’est produite');
        });
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        const salaireInput = document.getElementById('salaireInput');
        let salaire = salaireInput.value.replace(',', '.');

        // Vérifie que le salaire contient max 5 chiffres avant la virgule#

        const regex = /^\d{1,5}(\.\d{1,2})?$/;
        if (!regex.test(salaire)) {
            toastr.error("Le salaire doit contenir au maximum 5 chiffres avant la virgule, et 2 décimales au plus.");
            return;
        }

        const formData = new FormData(form);
        const action = form.action;
        const method = form.querySelector('input[name="_method"]')?.value || 'POST';

        fetch(action, {
            method: method === 'PUT' ? 'POST' : method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json().then(data => {
            if (response.ok) {
                toastr.success(method === 'PUT' ? 'Poste modifié avec succès' : 'Poste ajouté avec succès');

                if (method === 'PUT') {
                    updateElementOnPage(data.poste);
                } else {
                    addElementToPage(data.poste);
                }

                closeEditPanel();
            } else {
                let msg = data.errors
                    ? Object.values(data.errors).flat().join("\n")
                    : (data.error || 'Erreur lors de l\'enregistrement');
                toastr.error(msg);
            }
        }))
        .catch(error => {
            toastr.error('Erreur réseau ou serveur.');
        });
    }

    /*
       Permet d'ouvrir le panneau d'ajout
    */
    function openCreatePanel(actionUrl, ordreAffichage = '1') {
        form.reset();
        form.action = actionUrl;
        removeMethodField(form);

        document.getElementById('nomPosteInput').value = '';
        document.getElementById('salaireInput').value = '0.00';
        document.getElementById('ordreAffichageInput').value = ordreAffichage;
        document.getElementById('editPostePanel-title').textContent = 'Ajouter un poste';

        showModal(panel);
        currentOpenId = null;
    }

    function showModal(modal) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.classList.add('scale-100');
        }, 10);
    }

    /*
       Permet d'ouvrir le panneau d'edit
    */
    function openEditPanel(actionUrl, nomPoste = '', salaire = '', ordre) {
        form.action = actionUrl;
        setMethodField(form, 'PUT');

        document.getElementById('nomPosteInput').value = nomPoste;
        document.getElementById('salaireInput').value = salaire;
        if (ordre !== undefined && document.getElementById('ordreAffichageInput')) {
            document.getElementById('ordreAffichageInput').value = ordre;
        }

        document.getElementById('editPostePanel-title').textContent = 'Modifier le poste ' + nomPoste;

        showModal(panel);
    }

    /*
        Permet de fermer le paneau d'edit
    */
    function closeEditPanel() {
        panel.classList.add('hidden');
        currentOpenId = null;
        form.reset();
        removeMethodField(form);
    }

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
        if (methodInput) {
            methodInput.remove();
        }
    }

    /*
        Affiche le message de succès ou d'échec
    */
    /*function showAlert(message, type) {
        const alertBox = document.getElementById('alert-message');
        if (!alertBox) return;

        alertBox.textContent = message;
        alertBox.style.display = 'block';

        if (type === 'success') {
            alertBox.style.backgroundColor = '#38a169'; // Vert
            alertBox.style.color = 'white';
        } else {
            alertBox.style.backgroundColor = '#e53e3e'; // Rouge
            alertBox.style.color = 'white';
        }

        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 5000);
    }*/

    function updateElementOnPage(poste) {
        const div = document.querySelector(`div[data-id='${poste.id}']`);
        if (!div) return;

        div.querySelector('.poste-nom').textContent = poste.nom_poste;
        div.querySelector('.poste-salaire').textContent = poste.salaire + ' $';
    }

    function addElementToPage(poste) {
        let containerId = 'postesContainer'; // défaut
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (poste.ordre_affichage == 0) {
            containerId = 'arbitresContainer';
        } else if (poste.ordre_affichage == 1) {
            containerId = 'postesContainer';
        } else if (poste.ordre_affichage == 2) {
            containerId = 'autresContainer';
        }

        const container = document.getElementById(containerId);
        if (!container) return;

        const div = document.createElement('div');
        div.className =
            "px-4 py-2 mb-2 bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition poste-item";
        div.setAttribute("data-id", poste.id);

        div.innerHTML = `
            <div class="flex justify-between items-center">
                <div class="flex flex-nowwrap justify-between items-center w-full mr-1 text-white">
                    <p class="font-semibold text-lg poste-nom">${poste.nom_poste}</p>
                    <p class="text-lg poste-salaire bg-white/10 rounded-full flex justify-end items-center min-w-[5rem] max-h-[2rem] px-2 py-1">
                        ${formatSalaire(poste.salaire)}
                    </p>
                </div>

                <div class="flex">
                    <div class="flex gap-2 pl-4">

                        <!-- Modification -->
                        <div class="modify">
                            <button type="button"
                                data-mode="edit"
                                data-url="/postes/modification/${poste.id}"
                                data-ordre="${poste.ordre_affichage}"
                                class="edit-poste-btn flex items-center justify-center w-10 h-10
                                    bg-orange-400/30 hover:bg-orange-600/70 text-white rounded-lg transition">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-pencil">
                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                    <path d="m15 5 4 4"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Suppression -->
                        <form method="POST" action="/suppression/poste/${poste.id}" class="inline delete-poste-form">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">

                            <button type="submit"
                                class="flex items-center justify-center w-10 h-10 bg-red-600/30
                                    hover:bg-red-700/70 text-white rounded-lg transition delete-poste"
                                title="Supprimer">

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-700 m-auto">
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                    <path d="M3 6h18"/>
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        `;


        container.appendChild(div);
        attachEventsToButtons(div);
    }

    function formatSalaire(value) {
        const number = parseFloat(value);
        if (isNaN(number)) return value;
        return number.toFixed(2) + ' $';
    }

    function attachEventsToButtons(container) {
        const editBtn = container.querySelector('.edit-poste-btn');
        const deleteForm = container.querySelector('.delete-poste-form');

        if (editBtn) {
            editBtn.addEventListener('click', handleEditClick);
        }

        if (deleteForm) {
            deleteForm.addEventListener('submit', handleDeleteSubmit);
        }
    }
}
