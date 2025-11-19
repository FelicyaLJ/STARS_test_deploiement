if(window.location.pathname === "/terrain") {

    //Variables pour edit const terrain_est_enfant_edit = document.getElementById("edit_terrain_est_enfant");
    const terrains_values = null;
    const terrains_original = terrains;

    const div_modal = document.getElementById("modal_terrain");
    const input_nom_terrain = document.getElementById('nom_terrain');
    const input_description = document.getElementById('description');
    const input_latitude = document.getElementById("terrain_latitude");
    const input_longitude = document.getElementById("terrain_longitude");
    const input_terrain_couleur = document.getElementById("terrain_couleur");
    const input_terrain_visible = document.getElementById("terrain_visible");
    const input_terrain_parent = document.getElementById("etat_terrain_parent");
    const input_id_terrain = document.getElementById("id_terrain");
    const div_modal_add = document.getElementById("modal_terrain");
    const bulle_parent = document.getElementById("bulle_terrain_parent");
    const bulle_longitude_latitude = document.getElementById("bulle_longi_lati");
    const adresse_longi_lati = document.getElementById("adresse_longi_lati");
    const terrain_est_enfant = document.getElementById("terrain_est_enfant");
    const buttons_suppression = document.getElementsByClassName("delete_terrain");
    const buttons_edit = document.getElementsByClassName("mod_terrain");

    //Variables pour set adresse terrain parent
    const input_adresse_rue = document.getElementById("adresse_rue");
    const input_adresse_ville = document.getElementById("adresse_ville");
    const input_adresse_postal = document.getElementById("adresse_postal");

    //Validation formulaire async add
    const error_nom_terrain = document.getElementById("error_nom_terrain");
    const error_description = document.getElementById("error_description");
    const error_latitude = document.getElementById("error_latitude");
    const error_longitude = document.getElementById("error_longitude");
    const error_adresse_rue = document.getElementById("error_adresse_rue");
    const error_adresse_ville = document.getElementById("error_adresse_ville");
    const error_adresse_postal = document.getElementById("error_adresse_postal");

    // Filters
    const div_terrain = document.getElementById('bulle_terrain')
    const input_search = document.getElementById('search');
    const etat_filtre = document.getElementById('filtre_etat');
    const radio_filtre = document.getElementsByName("parent_enfant");
    const bulle_checkbox_parent = document.getElementById("bulle_checkbox_parent");

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

    //Fonctions a faire avec chargement
    terrain_resultat_filtre(terrains, false);

    /*************************
    *   Gestion Formulaire   *
    *************************/

    //Simple event listeners
    if (canManageTerrains) {
        input_latitude.addEventListener("input", coordinate_inputs);
        input_longitude.addEventListener("input", coordinate_inputs);
        input_terrain_parent.addEventListener("change", adjust_for_parent);
        input_adresse_postal.addEventListener("input", splice_input);
        terrain_est_enfant.addEventListener("change", handle_parent_switch);
        adresse_longi_lati.addEventListener("change", handle_coordinate_switch);

    }

    etat_filtre.addEventListener('change', filtrer_terrains);
    input_search.addEventListener('input', filtrer_terrains);

    for(let i=0; i<buttons_suppression.length; i++){
        buttons_suppression[i].addEventListener("click", bouttons_supprimmer);
    }

    //Boucle pour listeners sur les boutons edit
    for(let i=0; i<buttons_edit.length; i++){
        buttons_edit[i].addEventListener("click", () => {
            show_modal_edit(buttons_edit[i]);
        });
    }

    for(let i=0; i<radio_filtre.length; i++){
        radio_filtre[i].addEventListener("change", filtrer_terrains);
    }

    // Montrer formulaire de terrain
    if (canManageTerrains) {
        document.getElementById("add_terrain").addEventListener("click", () => {

            //On reset toutes les valeurs sans avoir besoin de les vérifier
            clear_all_errors();
            document.getElementById("modal_terrain-title").textContent = 'Ajouter un terrain';
            input_nom_terrain.value = '';
            input_nom_terrain.placeholder = 'Nom du terrain';
            input_description.value = '';
            input_latitude.value = '0';
            input_latitude.placeholder = '46.050858';
            input_longitude.value = '0';
            input_longitude.placeholder = '73.726255';
            input_terrain_couleur.value = '#242323ff';
            input_terrain_parent.value = '0';
            input_terrain_parent.dispatchEvent(new Event('change'));

            input_adresse_rue.placeholder = 'Adresse de rue (e.g. 4210 Boul Bourque)';
            input_adresse_ville.placeholder = 'Ville (e.g. Sherbrooke)';
            input_adresse_postal.placeholder = 'Code postal (e.g. J1L-1W6)';

            terrain_est_enfant.disabled = false;
            if(terrain_est_enfant.classList.contains("bg-gray-300")) terrain_est_enfant.classList.remove("bg-gray-300");
            if(bulle_checkbox_parent.classList.contains("text-gray-300")) bulle_checkbox_parent.classList.remove("text-gray-300");

            //Si la bulle parent est pas caché, on la cache
            if(!bulle_parent.classList.contains("hidden"))
                bulle_parent.classList.toggle("hidden");

            //Si la checkbost du terrain enfant est checked, on le uncheck
            if(terrain_est_enfant.checked)
                terrain_est_enfant.checked = false;

            if(!input_terrain_visible.checked)
                input_terrain_visible.checked = true;

            //Si la bulle latitude/longitude est pas caché, on la cache
            if(!bulle_longitude_latitude.classList.contains("hidden"))
                bulle_longitude_latitude.classList.toggle("hidden");

            //Si la bulle longitude/lagitude est pas caché, on la cache
            if(adresse_longi_lati.checked)
                adresse_longi_lati.checked = false;

            document.getElementById("form").action = 'terrain_create_api';
            document.getElementById("bulle_buton").innerHTML =`
                <button type="button" id="ajouter_terrain" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                    Ajouter le terrain
                </button>
                `;
            document.getElementById("ajouter_terrain").addEventListener("click", send_create_terrain);

            div_modal.classList.remove('hidden');
                    setTimeout(() => {
                        div_modal.classList.add('opacity-100');
                        div_modal.classList.add('scale-100');
                    }, 10);
        });
    }

    //Gestion du formulaire de création de terrain
    async function send_create_terrain() {
        const terrain_data = {
            nom_terrain: input_nom_terrain.value,
            description: input_description.value,
            latitude: input_latitude.value,
            longitude: input_longitude.value,
            adresse_rue: input_adresse_rue.value,
            adresse_ville: input_adresse_ville.value,
            adresse_postal: input_adresse_postal.value,
            terrain_couleur: input_terrain_couleur.value,
            terrain_visible: input_terrain_visible.checked,
            etat_terrain_parent: input_terrain_parent.value
        };

        const result = await create_terrain(terrain_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    //Gestion de suppression de terrains
    async function bouttons_supprimmer(evt) {

        let id = evt.currentTarget.value;
        let mon_terrain = terrains.find(t => t.id == id);

        const confirm = await Swal.fire({
            title: "Êtes-vous certain?",
            text: `"${mon_terrain.nom_terrain}" sera supprimé définitivement, et ne sera pas récupérable. Êtes-vous sur?`,
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

        const result = await delete_terrain(id);

        if (result.success) {
            toastr.success(result.message);
        } else if (result.errors) {
            toastr.error(result.message);
        } else {
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Gestion de modification de terrains
    async function send_edit_terrain() {
        const terrain_data = {
            nom_terrain: input_nom_terrain.value,
            description: input_description.value,
            latitude: input_latitude.value,
            longitude: input_longitude.value,
            adresse_rue: input_adresse_rue.value,
            adresse_ville: input_adresse_ville.value,
            adresse_postal: input_adresse_postal.value,
            terrain_couleur: input_terrain_couleur.value,
            terrain_visible: input_terrain_visible.checked,
            etat_terrain_parent: input_terrain_parent.value,
            id: input_id_terrain.value
        };

        const result = await edit_terrain(terrain_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Gestion de l'apparance du formulaire de modification en fonction du terrain choisi
    function show_modal_edit(target) {

        let mon_terrain = terrains.find(t => t.id == target.value);
        let terrain_enfant = terrains.find(t => t.id_parent == target.value);

        clear_all_errors();
        document.getElementById("modal_terrain-title").textContent = 'Modifier un terrain';
        input_nom_terrain.value = mon_terrain.nom_terrain;
        input_nom_terrain.placeholder = mon_terrain.nom_terrain;
        input_description.value = mon_terrain.description;
        input_id_terrain.value = target.value;
        let mon_adresse = mon_terrain.adresse.split(',').map(s => s.trim());
        input_adresse_rue.placeholder = mon_adresse[0];
        input_adresse_ville.placeholder = mon_adresse[1];
        input_adresse_postal.placeholder = mon_adresse[3];
        input_terrain_couleur.value = mon_terrain.couleur;
        input_terrain_visible.checked = mon_terrain.visible;

        if (mon_terrain.latitude !== null && mon_terrain.longitude !== null) {
            input_latitude.value = mon_terrain.latitude;
            input_latitude.placeholder = mon_terrain.latitude;
            input_longitude.value = mon_terrain.longitude.substring(1);
            input_longitude.placeholder = mon_terrain.longitude.substring(1);
            if(bulle_longitude_latitude.classList.contains("hidden"))
                bulle_longitude_latitude.classList.remove("hidden");
            adresse_longi_lati.checked = true;
        }
        else {
            input_latitude.value = '0';
            input_longitude.value = '0';
            if(!bulle_longitude_latitude.classList.contains("hidden"))
                bulle_longitude_latitude.classList.add("hidden");
            adresse_longi_lati.checked = false;
        }
        if(mon_terrain.id_parent == null) {
            terrain_est_enfant.checked = false;
            if(!bulle_parent.classList.contains("hidden"))
                bulle_parent.classList.add("hidden");
            input_terrain_parent.value = '0';
            input_adresse_rue.value = mon_adresse[0];
            input_adresse_ville.value = mon_adresse[1];
            input_adresse_postal.value = mon_adresse[3];
            handle_parent_input(input_adresse_rue, false);
            handle_parent_input(input_adresse_ville, false);
            handle_parent_input(input_adresse_postal, false);

        }
        else {
            terrain_est_enfant.checked = true;
            if(bulle_parent.classList.contains("hidden"))
                bulle_parent.classList.remove("hidden");
            input_terrain_parent.value = mon_terrain.id_parent;
            input_terrain_parent.dispatchEvent(new Event('change'));
        }

        if(terrain_enfant) {
            terrain_est_enfant.disabled = true;
            if(!terrain_est_enfant.classList.contains("bg-gray-300")) terrain_est_enfant.classList.add("bg-gray-300");
            if(!bulle_checkbox_parent.classList.contains("text-gray-300")) bulle_checkbox_parent.classList.add("text-gray-300");
        }
        else {
            terrain_est_enfant.disabled = false;
            if(terrain_est_enfant.classList.contains("bg-gray-300")) terrain_est_enfant.classList.remove("bg-gray-300");
            if(bulle_checkbox_parent.classList.contains("text-gray-300")) bulle_checkbox_parent.classList.remove("text-gray-300");
        }

        div_modal.classList.remove('hidden');
                setTimeout(() => {
                    div_modal.classList.add('opacity-100');
                    div_modal.classList.add('scale-100');
                }, 10);

        document.getElementById("form").action = 'terrain_edit_api';
        document.getElementById("bulle_buton").innerHTML =`
            <button type="button" id="modifier_terrain" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                Modifier le terrain
            </button>
            `;
        document.getElementById("modifier_terrain").addEventListener("click", send_edit_terrain);
    }

    // Gestion de la mise en forme du code postal
    function splice_input(evt) {

        let value = evt.target.value;

        if(value.length === 4 && value[3] !== '-' && value[3] !== undefined) {
            evt.target.value = value.slice(0, 3) + "-";
        }
        if (value.length > 4 && value[3] !== '-') {
            value = value.replace('-', '');
            evt.target.value = value.slice(0, 3) + '-' + value.slice(3);
        }
    }

    // Gestion du bouton checkbox pour les coordonées
    function handle_coordinate_switch() {

        if(!adresse_longi_lati.checked){
            input_latitude.value = '0';
            input_longitude.value = '0';
        }
        else {
            input_latitude.value = '';
            input_longitude.value = '';
        }
        bulle_longitude_latitude.classList.toggle("hidden");
    }

    // Gestion du bouton checkbox pour les terrains parents
    function handle_parent_switch() {

        if(!terrain_est_enfant.checked){
            input_terrain_parent.value = '0';
            input_terrain_parent.dispatchEvent(new Event('change'));
        }
        bulle_parent.classList.toggle("hidden");
    }

    //Ajuste l'adresse en fonction de l'ajout d'un terrain parent ou de son retrait
    function adjust_for_parent() {

        if(input_terrain_parent.value !== '0'){
            const array_adresse = terrains.find(t => t.id ==input_terrain_parent.value)?.adresse.split(',').map(s => s.trim());

            input_adresse_rue.value = array_adresse[0];
            handle_parent_input(input_adresse_rue, true);

            input_adresse_ville.value = array_adresse[1];
            handle_parent_input(input_adresse_ville, true);

            input_adresse_postal.value = array_adresse[3];
            handle_parent_input(input_adresse_postal, true);
        }
        else {
            input_adresse_rue.value = '';
            handle_parent_input(input_adresse_rue, false);

            input_adresse_ville.value = '';
            handle_parent_input(input_adresse_ville, false);

            input_adresse_postal.value = '';
            handle_parent_input(input_adresse_postal, false);
        }
    }

    //Bloque les champs d'adresse si le terrain a un terrain parent
    function handle_parent_input(target, bool) {

        if(bool) {
            target.disabled = true;
            if(!target.classList.contains("brightness-50"))  target.classList.add("brightness-50");
        }
        else {
            target.disabled = false;
            if(target.classList.contains("brightness-50")) target.classList.remove("brightness-50");
        }
    }
    //Gestion de fermeture des modals
    function close_modal() {

        div_modal.classList.remove('opacity-100');
        div_modal.classList.remove('scale-100');
        div_modal.classList.add('opacity-0');
        div_modal.classList.add('scale-100');

        setTimeout(() => {
            div_modal.classList.add('hidden');
        }, 300);
    }

    //Gestion de la mise en forme des coordonées
    function coordinate_inputs(evt){
        let value = evt.target.value;

        if(value.length === 3 && value[2] !== '.' && value[2] !== undefined)
            evt.target.value = value.slice(0, 2) + ".";

        if (value.length > 3 && value[2] !== '.') {
            value = value.replace('.', '');
            evt.target.value = value.slice(0, 2) + '.' + value.slice(2);
        }
    }

    // Validation de l'ajout d'un nouveau terrain
    async function create_terrain(terrain_data) {
        const url = 'terrains/create'
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(terrain_data)
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_all_errors("add");

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.nom_terrain)
                        show_error(error_nom_terrain, data.errors.nom_terrain[0]);
                    if (data.errors.description)
                        show_error(error_description, data.errors.description[0]);
                    if (data.errors.latitude)
                        show_error(error_latitude, data.errors.latitude[0]);
                    if (data.errors.longitude)
                        show_error(error_longitude, data.errors.longitude[0]);
                    if (data.errors.adresse_rue)
                        show_error(error_adresse_rue, data.errors.adresse_rue[0]);
                    if (data.errors.adresse_ville)
                        show_error(error_adresse_ville, data.errors.adresse_ville[0]);
                    if (data.errors.adresse_postal)
                        show_error(error_adresse_postal, data.errors.adresse_postal[0]);
                }
                return;
            }
            reset_create_terrain_form();
            close_modal();
            terrains.push({
                id: data.nouveau_terrain.id,
                nom_terrain: data.nouveau_terrain.nom_terrain,
                description: data.nouveau_terrain.description,
                latitude: data.nouveau_terrain.latitude,
                longitude: data.nouveau_terrain.longitude,
                adresse: data.nouveau_terrain.adresse,
                id_parent: data.nouveau_terrain.id_parent,
                couleur: data.nouveau_terrain.couleur,
                visible: data.nouveau_terrain.visible
            });
            creer_bulle_terrain(terrains[terrains.length - 1]);
            filtrer_terrains();
            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Requête de suppression de terrain
    async function delete_terrain(id) {

        const url = `terrains/delete`;
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

            //Enlève pas les terrains si
            if (!response.ok) return data;

            //Enlever le terrain de l'affichage
            let terrain_id = document.getElementById("terrain_" + id);
            let etat_terrain_parent =  document.getElementById('etat_terrain_parent').querySelector(`option[value="${id}"]`);

            if(terrain_id) terrain_id.remove();
            if(etat_terrain_parent) etat_terrain_parent.remove();

            //Enlever le terrain de l'array utilisé pour le filtre
            const i = terrains.findIndex(t => t.id === id);
            if (i === -1) terrains.splice(i, 1);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Fonction de modification d'actualité
    async function edit_terrain(terrain_data) {

        const url = `terrains/edit`;
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(terrain_data)
        };

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_all_errors("edit");

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.nom_terrain)
                        show_error(error_nom_terrain, data.errors.nom_terrain[0]);
                    if (data.errors.description)
                        show_error(error_description, data.errors.description[0]);
                    if (data.errors.latitude)
                        show_error(error_latitude, data.errorlatitude[0]);
                    if (data.errors.longitude)
                        show_error(error_longitude, data.errors.longitude[0]);
                    if (data.errors.adresse_rue)
                        show_error(error_adresse_rue, data.errors.adresse_rue[0]);
                    if (data.errors.adresse_ville)
                        show_error(error_adresse_ville, data.errors.adresse_ville[0]);
                    if (data.errors.adresse_postal)
                        show_error(error_adresse_postal, data.errors.adresse_postal[0]);
                }
                return;
            }

            let old_parent_id = terrains.find(t => t.id == data.terrain.id).id_parent;
            //Modifier terrains pour que le terrain réponde tout de suite aux filtres
            let mon_terrain = terrains.find(t => t.id == data.terrain.id);

            mon_terrain.nom_terrain = data.terrain.nom_terrain;
            mon_terrain.description = data.terrain.description;
            mon_terrain.latitude = data.terrain.latitude;
            mon_terrain.longitude = data.terrain.longitude;
            mon_terrain.adresse = data.terrain.adresse;
            mon_terrain.id_parent = data.terrain.id_parent;

            //Affichage des nouvelle valeurs
            let selected_div = document.getElementById("terrain_" + data.terrain.id);
            selected_div.getElementsByClassName("nom_terrain")[0].innerHTML = data.terrain.nom_terrain;
            selected_div.getElementsByClassName("description")[0].innerHTML = data.terrain.description;

            if(data.terrain.latitude !== null && data.terrain.longitude !== null) {
                selected_div.getElementsByClassName("adresse")[0].innerHTML = `
                    <a href="https://www.google.com/maps/search/?api=1&query=${data.terrain.latitude},${data.terrain.longitude}" target="_blank">${data.terrain.adresse}</a>`
            }
            else {
                selected_div.getElementsByClassName("adresse")[0].innerHTML = `
                    <a href="https://www.google.com/maps/search/?api=1&query=${data.terrain.adresse}" target="_blank">${data.terrain.adresse}</a>`
            }

            if(data.terrain.id_parent !== null) {

                selected_div.getElementsByClassName("terrain_parent")[0].innerHTML = `
                    <p class="mr-[1%] ">Terrain parent :</p>
                    <p class="cible_enfant">${terrains.find(t => t.id == data.terrain.id_parent).nom_terrain}</p>`;

                if(document.getElementById('etat_terrain_parent').querySelector(`option[value="${data.terrain.id}"]`))
                    document.getElementById('etat_terrain_parent').querySelector(`option[value="${data.terrain.id}"]`).remove();
            }
            //Si le nouvel id_parent est null mais que l'ancien ne l'est pas, c'est que ce terrain est éligible d'être un terrain parent
            else if (old_parent_id !== null) {
                selected_div.getElementsByClassName("terrain_parent")[0].innerHTML = '';
                var option = document.createElement('option');
                option.value = data.terrain.id;
                option.innerHTML = data.terrain.nom_terrain;
                document.getElementById('etat_terrain_parent').appendChild(option);
            }
            close_modal(div_modal_add);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Filtrer les terrains affichés selon la relation parent/enfant
    function filtrer_terrains(evt) {
        let object_array = [];

        for (const terrain of terrains) {

            //Switch dépendament de la valeur radio
            switch(document.querySelector('input[name="parent_enfant"]:checked').value) {

                case "est_parent":
                    let terrain_parent = terrains.find(t => t.id == terrain.id_parent);
                    //Si il n'est pas déja dans l'object_array
                    if(terrain_parent && !object_array.find(t => t.id == terrain_parent.id)) {
                        if(filtrer_champ(terrain_parent)) object_array.push(terrain_parent);
                    }
                    break;

                case "est_enfant":
                    if (terrain.id_parent) {
                        if(filtrer_champ(terrain)) object_array.push(terrain);
                    }
                    break;

                case "tous":
                default:
                    if(filtrer_champ(terrain)) object_array.push(terrain);
                    break;
            }
        }

        terrain_resultat_filtre(object_array);
    }

    //Filtrer les terrains affichés selon les autres champs de filtre
    function filtrer_champ(terrain) {
        //Filtre input qui cherche dans nom/description/adresse
        if(input_search.length <= 0 || (terrain.nom_terrain.toUpperCase().includes(input_search.value.toUpperCase()) || terrain.description.toUpperCase().includes(input_search.value.toUpperCase()) || terrain.adresse.toUpperCase().includes(input_search.value.toUpperCase()))) {

            //état est sur tous ou la valeur de l'état est égal à l'id de l'état du terrain
            console.log(etat_filtre.options[etat_filtre.selectedIndex].innerHTML + ' = ' + document.getElementById("terrain_" + terrain.id).getElementsByClassName("span_etat")[0].textContent);
            if ((etat_filtre.value == 0 || etat_filtre.options[etat_filtre.selectedIndex].innerHTML == document.getElementById("terrain_" + terrain.id).getElementsByClassName("span_etat")[0].textContent))
                return true;
        }
        else return false;
    }

    //Cache ou montre les résultats des filtres
    function terrain_resultat_filtre(object_array, veut_filtre = true) {
        if(veut_filtre){
            for (const terrain of terrains) {

                let terrain_div = document.getElementById("terrain_" + terrain.id);

                if(terrain_div !== null) {
                    if(object_array.find(t => t.id == terrain.id)){
                        if(terrain_div.classList.contains("hidden")) terrain_div.classList.remove("hidden");
                    }
                    else {
                        if(!terrain_div.classList.contains("hidden")) terrain_div.classList.add("hidden");
                    }
                }
            }
        }
    }

    //Crée l'affichage d'un terrain selon un object terrain
    function creer_bulle_terrain(terrain){
        let etat_terrain = "";
        let etat_parent = "";
        let lien_terrain = "";

        if (!terrain.visible && !canManageTerrains) return;

        switch(terrain.id_parent) {
            case null:
                //Rien faire si null
                break;
            default:
                etat_parent =
                    `<p class="mr-[1%] ">Terrain parent :</p>
                    <p>`+ terrains.find(t => t.id == terrain.id_parent)?.nom_terrain +`</p>`;
                break;
        }

        switch(terrain.etat) {
            case "Disponible":
                etat_terrain = `<span class="span_etat m-auto text-green-600 font-bold uppercase text-sm">Disponible</span>`;
                break;
            case "Réservé":
                etat_terrain = `<span class="span_etat m-auto text-orange-600 font-bold uppercase text-sm">Réservé</span>`;
                break;
            default:
                etat_terrain = `<span class="span_etat m-auto text-green-600 font-bold uppercase text-sm">Disponible</span>`;
                break;
        }

        if (!terrain.visible) {
            etat_terrain = `<span class="span_etat m-auto text-yellow-600 font-bold uppercase text-sm">Non visible</span>`;
        }

        if(terrain.latitude !== 0 && terrain.longitude !== 0)
            lien_terrain = terrain.latitude + ',' + terrain.longitude;
        else
            lien_terrain = terrain.adresse;

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = `
            <div id="terrain_${terrain.id}" class="mb-[5%] relative flex gap-2 rounded-lg p-4 mr-2 flex flex-col sm:flex-row"
                style="
                    background-color: ${terrain.couleur}33;
                    border: 1px solid ${terrain.couleur}66;
            ">
                <div class="w-full rounded-lg p-3 bg-black/60 basis-5/6">
                    <h3 class="font-semibold text-xl text-gray-50 leading-tight w-full nom_terrain">
                        ${terrain.nom_terrain}
                    </h3>
                    <p class="underline text-blue-600 adresse">
                        <a href="https://www.google.com/maps/search/?api=1&query=${lien_terrain}" target="_blank">${terrain.adresse}</a>
                    </p>
                    <p class="text-gray-300 description">${terrain.description}</p>
                    <div class="flex text-gray-300 justify-end terrain_parent">
                        ${etat_parent}
                    </div>
                </div>

                <div class="flex flex-col bulle_ext basis-1/6">
                    <div class="bg-black/60 rounded-lg flex justify-center py-2 px-3">
                        ${etat_terrain}
                    </div>

                    ${
                    canManageTerrains
                        ? `<div class="flex flex-row sm:flex-col justify-evenly mt-3">
                                <button class="m-auto group w-full text-white mod_terrain transition-all duration-300 ease-out rounded-lg py-2 hover:bg-white/10 basis-1/2" value="${terrain.id}" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-amber-600 m-auto">
                                        <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                        <path d="m15 5 4 4"/>
                                    </svg>
                                </button>

                                <button class="m-auto group w-full text-white delete_terrain transition-all duration-300 ease-out rounded-lg py-2 hover:bg-white/10 basis-1/2" type="button" value="${terrain.id}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-600 m-auto">
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                        <path d="M3 6h18"/>
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </button>
                            </div>`
                        : ``
                    }

                </div>
            </div>`;

        const nouveau_div = tempDiv.firstElementChild;

        div_terrain.appendChild(nouveau_div);

        if (canManageTerrains) {
            const btnModif = nouveau_div.getElementsByClassName("mod_terrain")[0];
            const btnSupp = nouveau_div.getElementsByClassName("delete_terrain")[0];

            btnModif.addEventListener("click", () => {
                show_modal_edit(btnModif);
            });
            btnSupp.addEventListener("click", bouttons_supprimmer);
        }
    }

    //Cache un champ d'erreur de formulaire
    function clear_error(error_span) {

        error_span.textContent = '';
        error_span.classList.add('hidden');
    }

    //Cache tous les champs d'erreur de formulaire
    function clear_all_errors() {
        clear_error(error_nom_terrain);
        clear_error(error_description);
        clear_error(error_latitude);
        clear_error(error_longitude);
        clear_error(error_adresse_rue);
        clear_error(error_adresse_ville);
        clear_error(error_adresse_postal);
    }

    //Montre un champ d'erreur de formulaire avec le bon message d'erreur
    function show_error(error_span, message) {
        error_span.textContent = message;
        error_span.classList.remove('hidden');
    }

    //Reset tous les champs du formulaire
    function reset_create_terrain_form() {

        document.getElementById('nom_terrain').value = '';
        document.getElementById('description').value = '';
        document.getElementById('adresse_rue').value = '';
        document.getElementById('adresse_ville').value = '';
        document.getElementById('adresse_postal').value = '';
    }

    /*************************
    *   Gestion Calendrier   *
    *************************/
    // Gestion Calendrier
    window.addEventListener('date-selected', async (e) => {
        const selectedDate = e.detail;
        getTerrainsByDate(selectedDate);
    });

    async function getTerrainsByDate(selectedDate) {
        if (selectedDate === null) {
            terrains = terrains_original;
            div_terrain.innerHTML = "";
            terrains.forEach(t => {
                creer_bulle_terrain(t);
            });
            return;
        }

        div_terrain.style.position = "relative";
        div_terrain.parentElement.appendChild(loader);

        try {
            const response = await fetch(`/terrains/by-date?date=${encodeURIComponent(selectedDate)}`);
            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    // Faire quelque chose
                }
                return;
            }

            terrains = data.terrains;


            div_terrain.innerHTML = "";
            terrains.forEach(t => {
                creer_bulle_terrain(t);
            });


            return;
        } catch (e) {
            console.error('Erreur en sélectionnant la date :', e);
        } finally {
            loader.remove();
        }
    }
}
