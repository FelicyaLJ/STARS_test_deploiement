if(window.location.pathname === "/evenements") {

    //Partie a jasmin

    const div_modal = document.getElementById("modal_evenement");
    const evenements_original = evenements;

    //Variables pour checklists des champs facultatifs
    const evenement_a_prix = document.getElementById("si_evenement_prix");
    const bulle_evenement_prix = document.getElementById("bulle_evenement_prix");
    const bulle_bouton_evenement = document.getElementById("bulle_bouton_evenement");
    const boutton_ajout = document.getElementById("add_evenement");

    //Variables pour formulaire
    const input_nom_evenement = document.getElementById("nom_evenement");
    const input_description = document.getElementById("description");
    const input_date = document.getElementById("date");
    const input_heure_debut = document.getElementById("heure_debut");
    const input_heure_fin = document.getElementById("heure_fin");
    const input_categorie_evenement = document.getElementById("categorie_evenement");
    const input_terrain_evenement = document.getElementById("terrain_evenement");
    const input_equipe_evenement = document.getElementById("equipe_evenement");
    const input_prix_evenement = document.getElementById("prix_evenement");
    const input_id_evenement = document.getElementById("id_evenement");

    //Formulaire async add
    const error_nom_evenement = document.getElementById("error_nom_evenement");
    const error_description = document.getElementById("error_description");
    const error_date = document.getElementById("error_date");
    const error_heure_debut = document.getElementById("error_heure_debut");
    const error_heure_fin = document.getElementById("error_heure_fin");
    const error_prix = document.getElementById("error_prix");
    const error_terrain = document.getElementById("error_terrain");
    const error_categorie = document.getElementById("error_categorie");
    const error_date_range = document.getElementById("error_date_range");
    const error_jours = document.getElementById("error_jours");

    const simpleRadio = document.getElementById('type_simple');
    const recurrentRadio = document.getElementById('type_recurrent');
    const dateSimpleWrapper = document.getElementById('date_simple_wrapper');
    const dateRangeWrapper = document.getElementById('date_range_wrapper');


    /*************************
    *   Gestion Formulaire   *
    *************************/

    //Gérer le formattage des prix
    if (evenement_a_prix) {
        evenement_a_prix.addEventListener("change", () => {
            handle_prix_switch(evenement_a_prix, input_prix_evenement, bulle_evenement_prix);
        });
    }

    function addItemListeners() {
        const boutton_suppression = document.getElementsByClassName("bouton_suppression");
        if (boutton_suppression) {
            for(let i=0; i<boutton_suppression.length; i++){
                boutton_suppression[i].addEventListener("click", () => {
                    boutton_suppression_evenement(boutton_suppression[i]);
                });
            }
        }

        const boutton_modification = document.getElementsByClassName("bouton_modification");
        if (boutton_modification) {
            for(let i=0; i<boutton_modification.length; i++){
                boutton_modification[i].addEventListener("click", () => {
                    boutton_modification_evenement(boutton_modification[i]);
                });
            }
        }
    }

    function toggleDateFields() {
        if (!dateRangeWrapper && !dateSimpleWrapper) return;

        if (recurrentRadio && recurrentRadio.checked) {
            dateSimpleWrapper.classList.add('hidden');
            dateRangeWrapper.classList.remove('hidden');
        } else {
            dateRangeWrapper.classList.add('hidden');
            dateSimpleWrapper.classList.remove('hidden');
        }
    }

    if (simpleRadio && recurrentRadio) {
        simpleRadio.addEventListener('change', toggleDateFields);
        recurrentRadio.addEventListener('change', toggleDateFields);
    }

    toggleDateFields();

    //Montrer formulaire d'ajout d'évènements
    if (boutton_ajout) {
        boutton_ajout.addEventListener("click", () => {

            clear_all_errors();

            input_nom_evenement.value = '';
            input_description.value = '';
            input_date.value = '';
            input_heure_debut.value = '';
            input_heure_fin.value = '';
            input_categorie_evenement.selectedIndex = 0;
            input_terrain_evenement.selectedIndex = 0;

            const ts = document.getElementById('equipe_evenement').tomselect;
            ts.clear();

            if (evenement_a_prix.checked) {
                evenement_a_prix.checked = false;
                evenement_a_prix.dispatchEvent(new Event('change'));
            }

            bulle_bouton_evenement.innerHTML = `
                <button type="button" id="ajouter_evenement" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                    Ajouter l\'évenement
                </button>
            `;
            document.getElementById("modal_evenement-title").textContent = "Ajouter un évènement";
            document.getElementById("type_wrapper").classList.remove('hidden');
            document.getElementById("ajouter_evenement").addEventListener("click", send_create_evenement);
            document.getElementById("form_evenement").action = 'evenements.create.api';

            show_modal(div_modal);
        });
    }

    // Envoyer formulaire de création d'évènements
    async function send_create_evenement() {

        const evenement_data = {
            type_evenement: document.querySelector('input[name="type_evenement"]:checked')?.value || 'simple',
            nom_evenement: input_nom_evenement.value,
            description: input_description.value,
            date: input_date.value,
            date_debut: document.getElementById('date_debut')?.value,
            date_fin: document.getElementById('date_fin')?.value,
            jours: Array.from(document.querySelectorAll('input[name="jours[]"]:checked')).map(j => j.value),
            heure_debut: input_heure_debut.value,
            heure_fin: input_heure_fin.value,
            categorie_evenement: input_categorie_evenement.value,
            terrain_evenement: input_terrain_evenement.value,
            prix_evenement: input_prix_evenement.value,
            equipes: Array.from(document.getElementById('equipe_evenement').selectedOptions).map(o => o.value)
        };

        const result = await create_evenement(evenement_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Gestion de création d'évènements
    async function create_evenement(evenement_data) {

        const url = '/evenements/create'
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(evenement_data)
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();
            clear_all_errors();

            if (!response.ok) {
                if (data.errors) {

                    if (data.errors.nom_evenement)
                        show_error(error_nom_evenement, data.errors.nom_evenement[0]);
                    if (data.errors.description)
                        show_error(error_description, data.errors.description[0]);
                    if (data.errors.date)
                        show_error(error_date, data.errors.date[0]);
                    if (data.errors.heure_debut)
                        show_error(error_heure_debut, data.errors.heure_debut[0]);
                    if (data.errors.heure_fin)
                        show_error(error_heure_fin, data.errors.heure_fin[0]);
                    if (data.errors.prix_evenement)
                        show_error(error_prix, data.errors.prix_evenement[0]);
                    if (data.errors.terrain_evenement)
                        show_error(error_terrain, data.errors.terrain_evenement[0]);
                    if (data.errors.categorie_evenement)
                        show_error(error_categorie, data.errors.categorie_evenement[0]);
                    if (data.errors.date_debut)
                        show_error(error_date_range, data.errors.date_debut[0]);
                    if (data.errors.date_fin)
                        show_error(error_date_range, data.errors.date_fin[0]);
                    if (data.errors.jours)
                        show_error(error_jours, data.errors.jours[0]);
                }
                return;
            }

            fetchEvenements();

            reset_evenement_form();
            close_modal(div_modal);
            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Gestion de la suppression des évènements
    async function boutton_suppression_evenement(target) {

        const eventsArray = Array.isArray(evenements) ? evenements : evenements.data;
        const mon_evenement = eventsArray.find(t => t.id == target.value);

        //Message d'erreur
        const confirm = await Swal.fire({
            title: "Êtes-vous certain?",
            text: `"${mon_evenement.nom_evenement}" sera supprimé définitivement, et ne sera pas récupérable. Êtes-vous sur?`,
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

        const result = await remove_evenement(target.value);

        if (result.success) {
            toastr.success(result.message);
        } else if (result.errors) {
            toastr.error(result.message);
        } else {
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Suppression des évènements
    async function remove_evenement(id) {

        const url = `evenements/delete`;
        const options = {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({"id":id})
        };

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            //On enlève l'affichage de l'évènement supprimé
            let evenement_id = document.getElementById("evenement_" + id);
            if(evenement_id) evenement_id.remove();

            //On l'enlève de l'array utilisé pour les fonctions
            evenements = evenements.filter(t => t.id != id);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Gestion de la suppression des évènements
    async function boutton_modification_evenement(target) {

        const eventsArray = Array.isArray(evenements) ? evenements : evenements.data;
        const mon_evenement = eventsArray.find(t => t.id == target.value);

        input_nom_evenement.value = mon_evenement.nom_evenement;
        input_description.value = mon_evenement.description;
        input_date.value = new Date(mon_evenement.date).toISOString().slice(0, 10);
        input_heure_debut.value = mon_evenement.heure_debut.slice(0, -3);
        input_heure_fin.value = mon_evenement.heure_fin.slice(0, -3);
        input_categorie_evenement.value = mon_evenement.id_categorie;
        input_terrain_evenement.value = mon_evenement.id_terrain;
        input_id_evenement.value = mon_evenement.id;

        const ts = document.getElementById('equipe_evenement').tomselect;
        ts.clear();
        mon_evenement.equipes.forEach(equipe => {
            ts.addItem(equipe.id.toString());
        });

        if(mon_evenement.prix > 0) {
            evenement_a_prix.checked = true;
            input_prix_evenement.value = mon_evenement.prix;
            if(bulle_evenement_prix.classList.contains("hidden"))
                bulle_evenement_prix.classList.remove("hidden");
        }
        else {
            evenement_a_prix.checked = false;
            evenement_a_prix.dispatchEvent(new Event('change'));
        }

        clear_all_errors();
        bulle_bouton_evenement.innerHTML = `
                <button type="button" id="modifier_evenement" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                    Modifier l\'évenement
                </button>
                `;
        document.getElementById("type_simple").checked = true;
        document.getElementById("type_recurrent").checked = false;
        document.getElementById("date_simple_wrapper").classList.remove("hidden");
        document.getElementById("date_range_wrapper").classList.add("hidden");

        document.getElementById("modal_evenement-title").textContent = "Modifier un évènement";
        document.getElementById("type_wrapper").classList.add('hidden');
        document.getElementById("modifier_evenement").addEventListener("click", send_edit_evenement);
        document.getElementById("form_evenement").action = 'evenements.edit.api';

        show_modal(div_modal);
    }

    //Envoyer formulaire de modification d'évènements
    async function send_edit_evenement() {

        const evenement_data = {
            nom_evenement: input_nom_evenement.value,
            description: input_description.value,
            date: input_date.value,
            heure_debut: input_heure_debut.value,
            heure_fin: input_heure_fin.value,
            prix_evenement: input_prix_evenement.value,
            categorie_evenement: input_categorie_evenement.value,
            terrain_evenement: input_terrain_evenement.value,
            id: input_id_evenement.value,
            equipes: Array.from(input_equipe_evenement.selectedOptions).map(o => o.value)
        };

        const result = await edit_evenement(evenement_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Gestion de suppression des évènements
    async function edit_evenement(evenement_data) {

        const url = `evenements/edit`;
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(evenement_data)
        };

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_all_errors();

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.nom_evenement)
                        show_error(error_nom_evenement, data.errors.nom_evenement[0]);
                    if (data.errors.description)
                        show_error(error_description, data.errors.description[0]);
                    if (data.errors.date)
                        show_error(error_date, data.errors.date[0]);
                    if (data.errors.heure_debut)
                        show_error(error_heure_debut, data.errors.heure_debut[0]);
                    if (data.errors.heure_fin)
                        show_error(error_heure_fin, data.errors.heure_fin[0]);
                    if (data.errors.prix_evenement)
                        show_error(error_prix, data.errors.prix_evenement[0]);
                    if (data.errors.terrain_evenement)
                        show_error(error_terrain, data.errors.terrain_evenement[0]);
                    if (data.errors.categorie_evenement)
                        show_error(error_categorie, data.errors.categorie_evenement[0]);
                }
                return;
            }

            fetchEvenements();
            close_modal(div_modal);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Cache un champ d'erreur dans un formulaire
    function clear_error(error_span) {
        error_span.textContent = '';
        error_span.classList.add('hidden');
    }

    //Montre un champ d'erreur avec le bon texte
    function show_error(error_span, message) {
        error_span.textContent = message;
        error_span.classList.remove('hidden');
    }

    //Cache tous les champs d'erreur d'un formulaire
    function clear_all_errors() {
        clear_error(error_nom_evenement);
        clear_error(error_description);
        clear_error(error_date);
        clear_error(error_heure_debut);
        clear_error(error_heure_fin);
        clear_error(error_prix);
        clear_error(error_terrain);
        clear_error(error_categorie);
    }
/*
    //À partir de la date en DB, avoir une date d'aujourd'hui complète
    function full_french_date(date_evenement) {
        const date = new Date(date_evenement);
        date.setDate(date.getDate() + 1);

        const formatted = new Intl.DateTimeFormat('fr-FR', {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        }).format(date);

        const [weekday, day, month, year] = formatted.split(' ');

        const weekdayCap = weekday.charAt(0).toUpperCase() + weekday.slice(1);
        const monthCap = month.charAt(0).toUpperCase() + month.slice(1);

        return `${weekdayCap}, ${day} ${monthCap} ${year}`;
    }

    //À partir de la date en DB, avoir le jour du mois en chiffres
    function day_french_date(date) {

        let day = new Date(date);
        day.setDate(day.getDate() + 1);

        return day.getDate().toString().padStart(2, '0');
    }

    //À partir de la date en DB, avoir une abbréviation du mois
    function month_french_date(date) {

        const formated_date = new Date(date);

        const mois = new Intl.DateTimeFormat('fr-FR', { month: 'long' }).format(formated_date);
        const formated_mois = mois.slice(0, 3).charAt(0).toUpperCase() + mois.slice(1, 3) + '.';
        return formated_mois;
    }

    //Bien formatter une heure en DB
    function formatted_time(time) {

        const [hoursStr, minutesStr] = time.split(':');
        let hours = parseInt(hoursStr, 10);
        const minutes = parseInt(minutesStr, 10);
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;

        const formattedHours = hours.toString().padStart(2, '0');
        const formattedMinutes = minutes.toString().padStart(2, '0');

        return `${formattedHours}:${formattedMinutes} ${ampm}`;
    }
*/
    //Reset les champs inputs du form
    function reset_evenement_form() {
        document.getElementById("type_simple").checked = true;
        document.getElementById("type_recurrent").checked = false;
        document.getElementById("date_simple_wrapper").classList.remove("hidden");
        document.getElementById("date_range_wrapper").classList.add("hidden");
        document.getElementById('nom_evenement').value = '';
        document.getElementById('description').value = '';
        document.getElementById('date').value = '';
        document.getElementById('heure_debut').value = '';
        document.getElementById('heure_fin').value = '';
        document.getElementById('date_debut').value = '';
        document.getElementById('date_fin').value = '';
        document.querySelectorAll('#equipe_evenement option').forEach(o => o.selected = false);
        document.querySelectorAll('input[name="jours[]"]').forEach(cb => cb.checked = false);
    }

    //Handle l'apparance du form en fonction du checkbox
    function handle_prix_switch(target_checkbox, target_input, target_bulle) {
        if(!target_checkbox.checked){
            target_input.value = '0';
            if(!target_bulle.classList.contains("hidden"))
                target_bulle.classList.add("hidden");
        }
        else {
            target_input.value = '';
            if(target_bulle.classList.contains("hidden"))
                target_bulle.classList.remove("hidden");
        }
    }

    //Ferme le modal
    function close_modal(modal) {
        modal.classList.remove('opacity-100');
        modal.classList.remove('scale-100');
        modal.classList.add('opacity-0');
        modal.classList.add('scale-100');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function show_modal(modal) {
        modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('opacity-100');
                    modal.classList.add('scale-100');
                }, 10);
    }


    async function updateAvailableTerrains() {
        const date = document.getElementById('date').value;
        const dateDebut = document.getElementById('date_debut').value;
        const dateFin = document.getElementById('date_fin').value;
        const heureDebut = document.getElementById('heure_debut').value;
        const heureFin = document.getElementById('heure_fin').value;

        const jours = [...document.querySelectorAll('input[name="jours[]"]:checked')].map(cb => cb.value);

        if ((!date && (!dateDebut || !dateFin)) || !heureDebut || !heureFin) return;

        const params = new URLSearchParams();
        if (date) params.append('date', date);
        if (dateDebut) params.append('date_debut', dateDebut);
        if (dateFin) params.append('date_fin', dateFin);
        if (heureDebut) params.append('heure_debut', heureDebut);
        if (heureFin) params.append('heure_fin', heureFin);
        jours.forEach(j => params.append('jours[]', j));

        const response = await fetch(`/evenements/terrains/disponibles?${params.toString()}`);
        const terrains = await response.json();

        const select = document.getElementById('terrain_evenement');
        select.innerHTML = '';

        if (terrains.length === 0) {
            select.innerHTML = `<option value="0" class="text-gray-800">Aucun terrain disponible</option>`;
            return;
        }

        select.innerHTML = `<option value="0" class="text-gray-800">Choisir un terrain</option>`;
        terrains.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t.id;
            opt.textContent = t.nom_terrain;
            opt.classList.add('text-gray-800');
            select.appendChild(opt);
        });
    }


    document.querySelectorAll('#date, #date_debut, #date_fin, #heure_debut, #heure_fin, .jours').forEach(el => {
        el.addEventListener('change', updateAvailableTerrains);
    });


    /*************************
    *   Gestion Calendrier   *
    *************************/

    window.addEventListener('date-selected', async (e) => {
        const selectedDate = e.detail;
        getEvenementsByDate(selectedDate);
    });

    async function getEvenementsByDate(selectedDate) {
        if (!selectedDate) {
            if (Array.isArray(evenements_original)) {
                evenements = [...evenements_original];
            } else if (evenements_original?.data && Array.isArray(evenements_original.data)) {
                evenements = [...evenements_original.data];
            } else {
                evenements = [];
            }

            fetchEvenements();
            return;
        }

        try {
            const response = await fetch(`/evenements/by-date?date=${encodeURIComponent(selectedDate)}`);
            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    // Faire quelque chose
                }
                return;
            }

            const results = data.evenements;
            if (Array.isArray(results)) {
                evenements = results;
            } else if (results?.data && Array.isArray(results.data)) {
                evenements = results.data;
            } else {
                evenements = [];
            }

            updateEvenementsList(evenements);
            updatePagination(data.pagination);
            return;
        } catch (e) {
            console.error('Erreur en sélectionnant la date :', e);
        }
    }

    document.addEventListener('calendar-ready', (e) => {
        const calendar = e.detail.calendar;

        // Guard to prevent multiple attachments
        if (listEvenements.dataset.listenerAttached === 'true') return;
        listEvenements.dataset.listenerAttached = 'true';

        listEvenements.addEventListener('click', (ev) => {
            const btn = ev.target.closest('.button-date');
            if (!btn) return;

            const date = btn.dataset.date;
            const today = new Date();
            today.setHours(0,0,0,0);

            if (calendar?.goToDate && date >= today.toISOString().slice(0, 10)) {
                calendar.goToDate(date);
            }
        });
    });


    /*************************
    *   Gestion Catégories   *
    *************************/

    listCategories = document.getElementById('listCategories')

    btnAddCategorie = document.getElementById('addCategorieEvenement');
    btnAddSaveCategorie = document.getElementById('addSaveCategorie');
    errorNomAdd = document.getElementById('errorNomCategorieAdd');
    errorCouleurAdd = document.getElementById('errorCouleurCategorieAdd');
    modalAddCategorie = document.getElementById('categorie-evenement-add-modal');

    btnEditCategorie = document.getElementById('editCategorieEvenement');
    btnEditSaveCategorie = document.getElementById('editSaveCategorie');
    errorNomEdit = document.getElementById('errorNomCategorieEdit');
    errorCouleurEdit = document.getElementById('errorCouleurCategorieEdit');
    modalEditCategorie = document.getElementById('categorie-evenement-edit-modal');

    btnDelCategorie = document.getElementById('deleteCategorieEvenement');

    /**
     * Envoi une requête POST au controlleur de catégories d'événement
     * @param {Array} catData - Données de la catégorie à modifier
     * @returns {JSON} - Données JSON de la réponse de la requête
     */
    async function addCategorie(catData) {
        const url = '/evenements/categorie/add'
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(catData)
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_error(errorNomAdd);
            clear_error(errorCouleurAdd);
            if (!response.ok) {
                if (data.errors) {

                    if (data.errors.nom)
                        show_error(errorNomAdd, data.errors.nom[0]);
                    if (data.errors.couleur)
                        show_error(errorCouleurAdd, data.errors.couleur[0]);
                }
                return;
            }

            // Reset add form ();
            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    /**
     * Envoi une requête PATCH au controlleur de catégories d'événement
     * @param {Array} catData - Données de la catégorie à modifier
     * @returns {JSON} - Données JSON de la réponse de la requête
     */
    async function updateCategorie(catData) {
        const url = '/evenements/categorie/update'
        const options = {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(catData)
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_error(errorNomEdit);
            clear_error(errorCouleurEdit);
            if (!response.ok) {
                if (data.errors) {

                    if (data.errors.nom)
                        show_error(errorNomEdit, data.errors.nom[0]);
                    if (data.errors.couleur)
                        show_error(errorCouleurEdit, data.errors.couleur[0]);
                }
                return;
            }

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    /**
     * Envoi une requête DELETE au controlleur de catégories d'événement
     * @param {Number} catId - Id de la catégorie à supprimer
     * @returns {JSON} - Données JSON de la réponse de la requête
     */
    async function deleteCategorie(catId) {
        const url = '/evenements/categorie/delete'
        const options = {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(catId)
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
     *
     * @param {*} categories
     */
    function updateCategorieList(categories) {

        fetch('/evenements/categorie/render', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ categories: categories.map(e => e.id) })
        })
        .then(response => response.text())
        .then(html => {
            listCategories.innerHTML = html;
        });
    }

    /**
     *
     * @param {*} catData
     */
    function openCategorieEditForm(catData) {
        document.getElementById('editCategorieId').value = catData.id;
        document.getElementById('editNomCategorie').value = catData.nom_categorie;
        document.getElementById('editCouleurCategorie').value = catData.couleur;

        modalEditCategorie.classList.remove('hidden');
        setTimeout(() => {
            modalEditCategorie.classList.add('opacity-100');
            modalEditCategorie.classList.add('scale-100');
        }, 10);
    }

    // Ajouter
    if (btnAddCategorie !== null) {
        btnAddCategorie.addEventListener('click', () => {
            show_modal(modalAddCategorie);
        });
    }

    if (btnAddSaveCategorie !== null) {
        btnAddSaveCategorie.addEventListener('click', async () => {
            const catData = {
                id: document.getElementById('addCategorieId').value,
                nom: document.getElementById('addNomCategorie').value,
                couleur: document.getElementById('addCouleurCategorie').value
            };

            const result = await addCategorie(catData);

            if (result.success) {
                close_modal(modalAddCategorie);

                // Update list
                updateCategorieList(result.categories);

                // Clear form
                document.getElementById('addNomCategorie').value = '';
                document.getElementById('addCouleurCategorie').value = '';

                toastr.success(result.message);
            } else if (result.errors) {
                toastr.error(result.message);
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }
        });
    }

    // Modifier
    document.addEventListener('click', evt => {
        const button = evt.target.closest('.editCategorieEvenement');
        if (!button) return;

        const div = button.closest('div[data-cat]');
        if (!div) return;

        const catData = JSON.parse(div.dataset.cat);
        openCategorieEditForm(catData);
    });

    if (btnEditSaveCategorie !== null) {
        btnEditSaveCategorie.addEventListener('click', async () => {
            const catData = {
                id: document.getElementById('editCategorieId').value,
                nom: document.getElementById('editNomCategorie').value,
                couleur: document.getElementById('editCouleurCategorie').value
            };

            const result = await updateCategorie(catData);

            if (result.success) {
                close_modal(modalEditCategorie);

                // Update lists : evenement, cat event, calendrier
                updateCategorieList(result.categories);
                updateEvenementsList(result.evenements);

                toastr.success(result.message);
            } else if (result.errors) {
                toastr.error(result.message);
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }
        });
    }

    // Supprimer
    if (btnDelCategorie !== null) {
        btnDelCategorie.addEventListener('click', async () => {
            const catId = document.getElementById('editCategorieId').value;
            const catName = document.getElementById('editNomCategorie').value;

            const confirm = await Swal.fire({
                title: "Êtes-vous certain?",
                text: `"${catName}" sera supprimé définitivement, ainsi que tous les événements passés qui appartiennent à cette catégorie!`,
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

            const allCats = document.querySelectorAll('[data-cat]');
            let elementToDelete = null;

            allCats.forEach(el => {
                try {
                    const catData = JSON.parse(el.dataset.cat);
                    if (catData.id == catId) {
                        elementToDelete = el;
                    }
                } catch (e) {
                    console.warn('Invalid data-cat JSON', e);
                }
            });

            const result = await deleteCategorie({ id: catId });

            if (result.success) {
                if (elementToDelete) {
                    elementToDelete.classList.add('transition', 'duration-300', 'opacity-0', 'scale-90');
                    elementToDelete.addEventListener('transitionend', () => {
                        close_modal(modalEditCategorie);

                        updateCategorieList(result.categories);
                        updateEvenementsList(result.evenements);

                        if (result.warning) {
                            Swal.fire({
                                title: "Événements supprimés",
                                text: `${result.warning}`,
                                icon: "warning",
                                color: "#f9fafb",
                                background: "rgba(1, 1, 1, 0.6)",
                                confirmButtonColor: "#3085d6",
                                customClass: {
                                    popup: "rounded-lg shadow-lg backdrop-blur",
                                }
                            });
                        }

                        toastr.success(result.message);
                    }, { once: true });
                } else {
                    close_modal(modalEditCategorie);

                    updateCategorieList(result.categories);
                    updateEvenementsList(result.evenements);

                    if (result.warning) {
                        Swal.fire({
                            title: "Événements supprimés",
                            text: `${result.warning}`,
                            icon: "warning",
                            color: "#f9fafb",
                            background: "#000000d2",
                            confirmButtonColor: "#3085d6"
                        });
                    }

                    toastr.success(result.message);
                }
            } else if (result.errors) {
                toastr.error(result.message);
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }

        });
    }

}

//Partie à Cédric
const filtreForm = document.getElementById('filtreForm');
const inputSearch = document.getElementById('search');
const etatFiltre = document.getElementById('filtre-etat');
const terrainFiltre = document.getElementById('filtre-terrain');
const categorieFiltre = document.getElementById('filtre-categorie');
const errorSearch = document.getElementById('errorSearch');
const errorFiltreTerrain = document.getElementById('errorFiltreTerrain');
const errorFiltreEtat = document.getElementById('errorFiltreEtat');
const errorFiltreCategorie = document.getElementById('errorFiltreCategorie');
const listEvenements = document.getElementById('listEvenements');
const btnPast = document.getElementById('btnPast');
const btnUpcoming = document.getElementById('btnUpcoming');
let timer = null;
let currentFilter = 'upcoming';
let page = 1;
let perPage = parseInt(document.getElementById('perPage').value);
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

let currentController = null;

/**
 *
 * @returns
 */
async function fetchEvenements() {
    // Annule la requête précédente si elle est toujours en cours
    if (currentController) {
        currentController.abort();
    }

    // Nouveau contrôleur pour cette requête
    currentController = new AbortController();
    const signal = currentController.signal;

    try {
        const search = inputSearch.value;
        const terrains = Array.from(terrainFiltre.selectedOptions).map(o => o.value);
        const etats = Array.from(etatFiltre.selectedOptions).map(o => o.value);
        const categories = Array.from(categorieFiltre.selectedOptions).map(o => o.value);

        const params = new URLSearchParams({
            search,
            filter: currentFilter,
            page,
            perPage
        });
        terrains.forEach(terrain => params.append('terrains[]', terrain));
        categories.forEach(categorie => params.append('categories[]', categorie));
        etats.forEach(etat => params.append('etats[]', etat));

        const response = await fetch(`/evenements/search?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            signal
        });
        const data = await response.json();

        if (!response.ok) {
            if (data.errors) {
                if (data.errors.search)
                    showError(errorSearch, data.errors.search[0]);
                if (data.errors.terrains)
                    showError(errorFiltreTerrain, data.errors.terrains[0]);
                if (data.errors.etats)
                    showError(errorFiltreEtat, data.errors.etats[0]);
                if (data.errors.categories)
                    showError(errorFiltreCategorie, data.errors.categories[0]);
            }
            return;
        }

        evenements = data.data;
        lastFetchedData = data;
        updateEvenementsList(data.data);
        updatePagination(data);
        clearError(errorSearch);
        clearError(errorFiltreTerrain);
        clearError(errorFiltreEtat);
        clearError(errorFiltreCategorie);
    } catch (e) {
        if (e.name === 'AbortError') {
            return;
        }
        console.error('Erreur lors de la récupération des événements:', e);
    }
}

/**
 *
 * @param {*} evenements
 */
function updateEvenementsList(evenements) {

    listEvenements.style.position = "relative";
    listEvenements.parentElement.appendChild(loader);

    fetch(`/evenements/render?from=${(window.location.pathname === "/" ? 'accueil' : '')}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ evenements: evenements.map(e => e.id) })
    })
    .then(response => response.text())
    .then(html => {
        loader.remove();

        if (!html.trim()) {
            listEvenements.innerHTML = `
                <div class="text-gray-400 text-center p-6">
                    Aucun événement trouvé.
                </div>
            `;
            return;
        }

        listEvenements.innerHTML = html;

        // Re-run any inline scripts inside new content
        listEvenements.querySelectorAll("script").forEach(oldScript => {
            const newScript = document.createElement("script");
            if (oldScript.src) {
                newScript.src = oldScript.src;
            } else {
                newScript.textContent = oldScript.textContent;
            }
            document.body.appendChild(newScript);
            oldScript.remove();
        });

        listEvenements.scrollTo({
            top: 0,
            behavior: "smooth"
        });

        if (window.location.pathname === "/evenements")
            addItemListeners();
    })
    .catch(error => {
        loader.remove();
        console.error("Erreur lors du chargement des événements :", error);
        listEvenements.innerHTML = `
            <div class="text-red-300 text-center p-4">
                Erreur lors du chargement des événements.
            </div>
        `;
    });
}

/**
 *
 * @param {*} pagination
 */
function updatePagination(data) {
    const pagination = document.getElementById('pagination');
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
        fetchEvenements();
    }));

    let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let end = Math.min(totalPages, start + maxVisible - 1);
    if (end - start < maxVisible - 1) start = Math.max(1, end - maxVisible + 1);

    if (start > 1) {
        pagination.appendChild(makeButton('1', false, () => {
            page = 1;
            fetchEvenements();
        }));
        if (start > 2) pagination.appendChild(makeButton('…', true));
    }

    for (let i = start; i <= end; i++) {
        pagination.appendChild(makeButton(i, false, () => {
            page = i;
            fetchEvenements();
        }, i === currentPage));
    }

    if (end < totalPages) {
        if (end < totalPages - 1) pagination.appendChild(makeButton('…', true));
        pagination.appendChild(makeButton(totalPages, false, () => {
            page = totalPages;
            fetchEvenements();
        }));
    }

    // Next button
    pagination.appendChild(makeButton('›', currentPage === totalPages, () => {
        page++;
        fetchEvenements();
    }));
}

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

function setActiveButton() {
    if (currentFilter === 'past') {
        btnPast.classList.add('bg-gray-600', 'text-white');
        btnPast.classList.remove('bg-white/20', 'text-gray-400');

        btnUpcoming.classList.add('bg-white/20', 'text-gray-400');
        btnUpcoming.classList.remove('bg-red-400', 'text-white');
    } else {
        btnUpcoming.classList.add('bg-red-400', 'text-white');
        btnUpcoming.classList.remove('bg-white/20', 'text-gray-400');

        btnPast.classList.add('bg-white/20', 'text-gray-400');
        btnPast.classList.remove('bg-gray-600', 'text-white');
    }
}

// Filtres asynchrones
inputSearch.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(async () => {
        page = 1;
        await fetchEvenements();
    }, 300);
});

etatFiltre.addEventListener('change', async () => {
    page = 1;
    await fetchEvenements();
});

terrainFiltre.addEventListener('change', async () => {
    page = 1;
    await fetchEvenements();
});

categorieFiltre.addEventListener('change', async () => {
    page = 1;
    await fetchEvenements();
});

document.getElementById('perPage').addEventListener('change', async () => {
    perPage = parseInt(document.getElementById('perPage').value);
    page = 1;
    await fetchEvenements();
});

if (btnPast && btnUpcoming) {
    btnPast.addEventListener('click', async () => {
        currentFilter = 'past';
        setActiveButton();
        page = 1;
        await fetchEvenements();
    });

    btnUpcoming.addEventListener('click', async () => {
        currentFilter = 'upcoming';
        setActiveButton();
        page = 1;
        await fetchEvenements();
    });
    setActiveButton();
}

fetchEvenements();

if (window.location.pathname === "/evenements")
    addItemListeners();


/****************
*    Filtres    *
****************/

document.getElementById('filtre-toggle').addEventListener('click', () => {
    const filtreForm = document.getElementById('filtre-evenements');

    const isExpanded = parseInt(getComputedStyle(filtreForm).maxHeight) > 0;
    if (isExpanded) {
        filtreForm.style.maxHeight = "0";
    } else {
        filtreForm.style.maxHeight = "none";
        const fullHeight = filtreForm.scrollHeight + "px";
        filtreForm.style.maxHeight = "0"; // reset
        setTimeout(() => {
            filtreForm.style.maxHeight = fullHeight;
        }, 10);
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

    // —————— FORCE-PLACEMENT HANDLER ——————
    // If TomSelect appends dropdown to body or other parent and it still appears wrong,
    // we compute exact coordinates from the control input and position absolutely.
    // Works reliably across modals, overflow containers, transforms, etc.
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

window.addEventListener('resize', () => {
    if (typeof lastFetchedData !== 'undefined') {
        updatePagination(lastFetchedData);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    clearNativeSelects();

    styleTomSelect("#filtre-etat", "body", "Sélectionnez des états");
    styleTomSelect("#filtre-terrain", "body", "Sélectionnez des terrains");
    styleTomSelect("#filtre-categorie", "body", "Sélectionnez des catégories");
    if (document.getElementById('equipe_evenement'))
        styleTomSelect("#equipe_evenement", null, "Sélectionnez des équipes");

    if (typeof TomSelect !== 'undefined' && TomSelect.instances) {
        Object.values(TomSelect.instances).forEach(inst => inst.clear());
    }
});





