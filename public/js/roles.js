if (document.location.pathname === "/roles") {
    document.addEventListener('DOMContentLoaded', () => {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        // List
        const listRoles = document.getElementById('listRoles');

        // Modal form
        const addForm = document.getElementById('role-add-modal');

        const editForm = document.getElementById('formEdit');

        // Error divs
        const errorNomAdd = document.getElementById('errorNomAdd');
        const errorDescAdd = document.getElementById('errorDescAdd');
        const errorPermAdd = document.getElementById('errorPermAdd');
        const errorCAAdd = document.getElementById('errorCAAdd');
        const errorNomEdit = document.getElementById('errorNomEdit');
        const errorDescEdit = document.getElementById('errorDescEdit');
        const errorPermEdit = document.getElementById('errorPermEdit');
        const errorCAEdit = document.getElementById('errorCAEdit');

        let selectedRoleId = null;


        /****************************************
        *   -------- Async functions --------   *
        ****************************************/


        async function fetchRoles() {
            try {
                const response = await fetch(`roles/fetch`);
                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        // Faire quelque chose
                    }
                    return;
                }
                updateList(data);
            } catch (e) {
                console.error('Erreur lors de la récupération des roles:', e);
            }
        }

        /**
         * Envoi une requête POST au controlleur
         * @param {*} roleData
         * @returns
         */
        async function addRole(roleData) {
            const url = 'roles/add'
            const options = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(roleData)
            }

            try {
                const response = await fetch(url, options);
                const data = await response.json();

                clearError(errorNomAdd);
                clearError(errorDescAdd);
                clearError(errorPermAdd);
                clearError(errorCAAdd);
                if (!response.ok) {
                    if (data.errors) {

                        if (data.errors.nom_role)
                            showError(errorNomAdd, data.errors.nom_role[0]);
                        if (data.errors.description)
                            showError(errorDescAdd, data.errors.description[0]);
                        if (data.errors.permissions)
                            showError(errorPermAdd, data.errors.permissions[0]);
                        if (data.errors.membre_ca)
                            showError(errorCAAdd, data.errors.membre_ca[0]);
                    }
                    return data;
                }

                resetAddRoleForm();
                return data;
            } catch (err) {
                console.error(err);
                return { success: false, message: "Une erreur s'est produite." };
            }
        }

        /**
         * Envoi une requête POST au controlleur
         * @param {*} roleData
         * @returns
         */
        async function updateRole(roleData) {
            const url = 'roles/update'
            const options = {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(roleData)
            }

            try {
                const response = await fetch(url, options);
                const data = await response.json();

                clearError(errorNomEdit);
                clearError(errorDescEdit);
                clearError(errorPermEdit);
                clearError(errorCAEdit);
                if (!response.ok) {
                    if (data.errors) {

                        if (data.errors.nom_role)
                            showError(errorNomEdit, data.errors.nom_role[0]);
                        if (data.errors.description)
                            showError(errorDescEdit, data.errors.description[0]);
                        if (data.errors.permissions)
                            showError(errorPermEdit, data.errors.permissions[0]);
                        if (data.errors.membre_ca)
                            showError(errorCAEdit, data.errors.membre_ca[0]);
                    }
                    return data;
                }

                toggleRoleEditForm(data.role);
                return data;
            } catch (err) {
                return { success: false, message: "Une erreur s'est produite." };
            }
        }

        async function deleteRole(role) {
            const url = 'roles/delete'
            const options = {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(role)
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
         * @param {Object} role
         * @returns {HTMLDivElement}
         */
        function renderDesktopRow(role) {

            const div = document.createElement('div');
            div.className = 'mx-2 transition-colors duration-200 mb-4 p-4 rounded-lg text-gray-200 cursor-pointer bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition';
            div.dataset.role = JSON.stringify({
                id: role.id,
                nom_role: role.nom_role,
                description: role.description,
                permissions: role.permissions?.map(p => p.id) ?? [],
                membre_ca: role.membre_ca
            });

            const displayedPermissions = role.permissions?.slice(0, 4) ?? [];
            const hasMorePermissions = (role.permissions?.length ?? 0) > 4;

            if (displayedPermissions.length > 0) {
                permissionsHTML = displayedPermissions.map(p => `
                    <span class="bg-red-200 text-red-900 font-semibold text-xs px-2 py-1 rounded-full">
                        ${p.formatted_name}
                    </span>
                `).join('');

                if (hasMorePermissions) {
                    permissionsHTML += `
                        <span class="bg-red-200 text-red-900 font-semibold text-xs px-2 py-1 rounded-full">
                            ...
                        </span>
                    `;
                }
            } else {
                permissionsHTML = `<span class="text-xs">Consultation seulement</span>`;
            }

            div.innerHTML = `
                <span class="font-semibold">${role.nom_role}</span>
                <div class="flex flex-wrap gap-1 mt-1 md:mt-0">
                    ${permissionsHTML}
                </div>
            `;

            div.addEventListener('click', function() {
                selectRole(role);
                highlightSelected();
            });

            return div;
        }

        /**
         * Remplacer la liste de rôles par celle à jour
         * @param {Array} roles
         */
        function updateList(roles) {
            listRoles.innerHTML = ''
            roles.forEach(role => {
                listRoles.appendChild(renderDesktopRow(role));
            });

            highlightSelected();
        }

        /**
         *
         * @param {*} role
         */
        function selectRole(role) {
            document.getElementById('no-select').classList.add('hidden');
            selectedRoleId = role.id;
            roleDetails.dataset.role = JSON.stringify({
                id: role.id,
                nom_role: role.nom_role,
                description: role.description,
                permissions: role.permissions?.map(p => p.id) ?? [],
                membre_ca: role.membre_ca
            });

            if (!roleDetails.classList.contains('invisible')) {
                if (formEdit.style.maxHeight && formEdit.style.maxHeight !== '0px') {
                    setTimeout(() => {
                        requestAnimationFrame(() => {
                            toggleRoleEditForm(role);
                        });
                    }, 25);
                }
                setTimeout(() => {
                    roleDetails.classList.remove('opacity-100');
                    roleDetails.classList.add('invisible', 'opacity-0');
                }, 100);
                setTimeout(() => updateAndFadeIn(role), 300);
            } else {
                updateAndFadeIn(role)
            }

            /**
             *
             * @param {*} role
             */
            function updateAndFadeIn(role) {
                document.getElementById('roleNom').textContent = role.nom_role;
                document.getElementById('roleDesc').textContent = (role.description ?? false) ? role.description : 'Aucune description.';
                document.getElementById('roleMembreCA').textContent = role.membre_ca ? 'Oui' : 'Non';

                const permissionsHTML = role.permissions && role.permissions.length > 0
                    ? role.permissions.map(p => `
                        <span class="bg-red-200 text-red-900 font-semibold text-xs px-2 py-1 rounded-full">
                            ${p.formatted_name}
                        </span>
                    `).join('')
                    : `<span class="">Consultation seulement</span>`;

                document.getElementById('rolePermissions').innerHTML = permissionsHTML;

                roleDetails.classList.remove('invisible', 'opacity-0');
                roleDetails.classList.add('opacity-100');
            }
        }

        /**
         *
         */
        function highlightSelected() {
            document.querySelectorAll('#listRoles > div').forEach(item => {
                const role = JSON.parse(item.dataset.role);

                item.classList.remove('bg-white/20', 'border-white/40', 'hover:bg-white/20');

                if (parseInt(role.id) === selectedRoleId) {
                    item.classList.add('bg-white/20', 'border-white/40');
                } else {
                    item.classList.add('hover:bg-white/20');
                }
            });
        }

        /**
         *
         */
        function openRoleAddForm() {
            addForm.classList.remove('hidden');
            setTimeout(() => {
                addForm.classList.add('opacity-100');
                addForm.classList.add('scale-100');
            }, 10);
        }

        /**
         *
         * @param {*} role
         */
        function toggleRoleEditForm(role) {
            if (role) {
                document.getElementById('editNom').value = role.nom_role;
                document.getElementById('editDesc').value = role.description;
                document.getElementById('editMembreCA').checked = !!role.membre_ca;
                document.getElementById('editRoleId').value = role.id;

                const ts = document.getElementById('editPermissions').tomselect;
                ts.clear();
                role.permissions.forEach(id => {
                    ts.addItem(id.toString());
                });
            }

            // Expand/Collapse form
            const isExpanded = parseInt(getComputedStyle(editForm).maxHeight) > 0;
            if (isExpanded) {
                // Collapse
                editForm.style.maxHeight = editForm.scrollHeight + 'px';
                listRoles.style.maxHeight = '30rem';
                requestAnimationFrame(() => {
                    editForm.style.maxHeight = '0px';
                    editForm.classList.remove('pt-5');
                    editForm.classList.remove('opacity-100');
                    editForm.classList.add('opacity-0');
                });
            } else {
                // Expand
                editForm.classList.remove('opacity-0');
                editForm.classList.add('opacity-100');
                editForm.classList.add('pt-5');
                editForm.style.maxHeight = editForm.scrollHeight + 20 + 'px';

                const originalMaxHeight = parseInt(getComputedStyle(listRoles).maxHeight) || 0;
                const neededHeight = editForm.scrollHeight + roleDetails.scrollHeight;

                if (originalMaxHeight < neededHeight) {
                    listRoles.style.maxHeight = neededHeight + 'px';
                }
            }
        }

        /**
         *
         */
        function resetAddRoleForm() {
            document.getElementById('addNom').value = '';
            document.getElementById('addDesc').value = '';
            const ts = document.getElementById('addPermissions').tomselect;
            ts.clear();
            document.getElementById('membreCa').checked = false;
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

        /**
         *
         */
        function resetSelect() {
            selectedRoleId = null;

            if (!roleDetails.classList.contains('invisible')) {
                if (formEdit.style.maxHeight && formEdit.style.maxHeight !== '0px') {
                    setTimeout(() => {
                        requestAnimationFrame(() => {
                            toggleRoleEditForm();
                        });
                    }, 25);
                }
                setTimeout(() => {
                    roleDetails.classList.remove('opacity-100');
                    roleDetails.classList.add('invisible', 'opacity-0');
                }, 100);
            }

            document.getElementById('no-select').classList.remove('hidden');

        }

        function styleTomSelect(selector, dropdownParent, placeholder) {
            const options = {
                plugins: ['remove_button'],
                placeholder: placeholder,

                render: {
                    // Dropdown items
                    option: function(data, escape) {
                        return `
                            <div class="flex items-center gap-2 px-2 py-1 z-[1000]">
                                <span class="inline-block w-3 h-3 rounded-full" style="background-color:${data.couleur};"></span>
                                <span class="text-gray-800">${escape(data.formatted)}</span>
                            </div>
                        `;
                    },

                    // Selected pills
                    item: function(data, escape) {
                        return `
                            <span class="bg-red-200 text-red-900 font-semibold text-xs px-2 py-1 rounded-full flex items-center gap-1 mr-1">
                                ${escape(data.formatted)}
                            </span>
                        `;
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

            // Initialize TomSelect
            const tom = new TomSelect(selector, options);
            return tom;
        }




        /****************************************
        *   -------- Event listeners --------   *
        ****************************************/


       // Gestion d'ajout de role
        document.getElementById('add-role').addEventListener('click', () => {
            openRoleAddForm();
        });

        document.getElementById('addSave').addEventListener('click', async () => {
            const roleData = {
                nom_role: document.getElementById('addNom').value,
                description: document.getElementById('addDesc')?.value || null,
                permissions: Array.from(document.getElementById('addPermissions').selectedOptions).map(o => o.value),
                membre_ca: document.getElementById('membreCa').checked
            };

            const result = await addRole(roleData);

            if (result.success) {
                closeModal(addForm);
                fetchRoles();
                toastr.success(result.message);
            } else if (result.errors) {
                toastr.error(result.message);
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }
        });

        // Gestion de modification de role
        document.getElementById('edit-role').addEventListener('click', () => {
            const role = JSON.parse(roleDetails.dataset.role);
            toggleRoleEditForm(role);
        });

        document.getElementById('editSave').addEventListener('click', async () => {
            const roleData = {
                id: document.getElementById('editRoleId').value,
                nom_role: document.getElementById('editNom').value,
                description: document.getElementById('editDesc')?.value || null,
                permissions: Array.from(document.getElementById('editPermissions').selectedOptions).map(o => o.value),
                membre_ca: document.getElementById('editMembreCA').checked
            };

            const result = await updateRole(roleData);

            if (result.success) {
                fetchRoles();
                selectRole(result.role);
                toastr.success(result.message);
            } else if (result.errors) {
                toastr.error(result.message || "Une erreur est survenue. Vérifiez le formulaire.");
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }
        });

        // Gestion de suppression de role
        document.getElementById('editDelete').addEventListener('click', async () => {

            const roleData = {
                id: document.getElementById('editRoleId').value,
                force: false
            };

            // First confirmation
            const confirm = await Swal.fire({
                title: "Êtes-vous certain?",
                text: `"${document.getElementById('editNom').value}" sera supprimé définitivement!`,
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

            let result = await deleteRole(roleData);

            // If deletion blocked because users still have that role
            if (result.requires_force) {

                const forceConfirm = await Swal.fire({
                    title: "Utilisateurs associés",
                    text: `Des utilisateurs possèdent encore ce rôle. Voulez-vous quand même le supprimer?`,
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

                if (!forceConfirm.isConfirmed) return;

                result = await deleteRole({
                    id: roleData.id,
                    force: true
                });
            }

            if (result.success) {
                fetchRoles();
                resetSelect();
                toastr.success(result.message);
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }
        });

        styleTomSelect("#editPermissions", "body", "Sélectionnez des permissions");
        styleTomSelect("#addPermissions", null, "Sélectionnez des permissions");

        fetchRoles();
    });
}
