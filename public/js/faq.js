if(window.location.pathname === "/faq") {

    //Boutons
    const bouton_categorie_faq_add = document.getElementById("add-cat-faq");
    const bouton_categorie_faq_create = document.getElementById("ajouter_categorie_faq");
    const bouton_categorie_faq_delete = document.getElementById("delete_categorie_faq");
    //Bouton edit_faq est initialisé avec un Onclick()
    const bouton_faq_add = document.getElementsByClassName("add-sujet-faq");
    const bouton_faq_create = document.getElementById("ajouter_faq");
    const bouton_faq_delete = document.getElementById("delete_faq");

    //Bulles modales
    const bulle_categorie_faq = document.getElementById("modal_categorie_faq");
    const bulle_faq = document.getElementById("modal_faq");
    const bulle_ordre_faq = document.getElementById("bulle_ordre_faq");
    const bulle_ordre_categorie_faq = document.getElementById("bulle_ordre_categorie_faq")

    //Champs formulaire
    const input_nom_categorie_faq = document.getElementById("nom_categorie_faq");
    const input_ordre_categorie_faq = document.getElementById("ordre_categorie_faq");
    const input_id_categorie_faq = document.getElementById("id_categorie");
    const input_nom_faq = document.getElementById("nom_faq");
    //const input_texte_faq = document.getElementById("texte_faq");
    const input_fichier_faq = document.getElementById("fichier_faq");
    const input_categorie_faq = document.getElementById("categorie_faq");
    const input_ordre_faq = document.getElementById("ordre_faq");
    const input_lien_faq = document.getElementById("lien_faq");
    const input_id_faq = document.getElementById("id_faq");

    //Champs erreur
    const error_nom_categorie = document.getElementById("error_nom_categorie");
    const error_nom = document.getElementById("error_nom_faq");
    const error_texte = document.getElementById("error_texte");
    const error_fichier = document.getElementById("error_fichier");
    const error_categorie = document.getElementById("error_categorie");
    const error_lien = document.getElementById("error_lien");

    const quill_toolbar = [
        [{color : []}],
        ["bold" , "italic", "underline"],
        ["link"],
    ];
    const texte_quil = new Quill("#editor", {
        theme:'snow',
        modules: {
            toolbar: quill_toolbar,
        },
    });

    /*************************
    *   Gestion Formulaire   *
    *************************/

    if(input_fichier_faq !== null)
        input_fichier_faq.addEventListener('change', check_file_format);

    if(input_categorie_faq !== null)
        input_categorie_faq.addEventListener('change', show_correct_order_faq);

    // Montrer formulaire de create FAQ
    if(bouton_faq_add !== null) {
        for(let i=0; i<bouton_faq_add.length; i++)
            bouton_faq_add[i].addEventListener("click", show_form_add_faq);
    }

    if(bouton_categorie_faq_delete !== null)
        bouton_categorie_faq_delete.addEventListener("click", bouton_supprimmer_categorie_faq);

    if(bouton_faq_delete !== null)
        bouton_faq_delete.addEventListener("click", bouton_supprimmer_faq);

    // Montrer formulaire d'ajout de catégorie FAQ
    if(bouton_categorie_faq_add !== null) {
        bouton_categorie_faq_add.addEventListener("click", (evt) => {

            input_nom_categorie_faq.value = '';
            clear_error(error_nom_categorie);

            document.getElementById("form_categorie_faq").action = 'categorie_faq_create_api';
            document.getElementById("bulle_bouton_categorie_faq").innerHTML =`
                <button type="button" id="ajouter_faq" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                    Ajouter la catégorie FAQ
                </button>
                `;
            document.getElementById("ajouter_faq").addEventListener('click', send_create_categorie_faq);

            if(!bulle_ordre_categorie_faq.classList.contains("hidden")) bulle_ordre_categorie_faq.classList.add("hidden");
            if(!bouton_categorie_faq_delete.classList.contains("hidden")) bouton_categorie_faq_delete.classList.add("hidden");

            bulle_categorie_faq.classList.remove('hidden');
                    setTimeout(() => {
                        bulle_categorie_faq.classList.add('opacity-100');
                        bulle_categorie_faq.classList.add('scale-100');
                    }, 10);
        });
    }

    // Gestion d'ajout de catégorie FAQ
    if(bouton_categorie_faq_create !== null) {
        bouton_categorie_faq_create.addEventListener('click', async () => {

            const categorie_data = {
                nom_categorie: input_nom_categorie_faq.value
            };

            const result = await create_categorie_faq(categorie_data);

            if(result !== undefined) {
                if (result.success)
                    toastr.success(result.message);
                else if (result.errors)
                    toastr.error(result.message);
                else
                    toastr.error(result.message || "Une erreur inconnue est survenue.");
            }
        });
    }

    // Requête d'ajout de catégories FAQ
    async function send_create_categorie_faq() {
        const categorie_data = {
            nom_categorie: input_nom_categorie_faq.value
        };

        const result = await create_categorie_faq(categorie_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
                toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Validation de l'ajout d'une nouvelle catégorie FAQ
    async function create_categorie_faq(categorie_data) {
        const url = 'faq/categorie/create'
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(categorie_data)
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_error(error_nom_categorie);

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.nom_categorie)
                        show_error(error_nom_categorie, data.errors.nom_categorie[0]);
                }
                return;
            }

            categories.push(data.categorie_faq);
            close_modal(bulle_categorie_faq);

            let la_bulle = document.getElementById("bulle_onglet");
            la_bulle.insertAdjacentHTML('beforeend', `<button id="categorie_${data.categorie_faq.id}" @click="activeTab = '${data.categorie_faq.id}'" :class="activeTab === '${data.categorie_faq.id}'
                ? 'bg-red-800/70 text-red-300 font-bold shadow-md'
                : 'text-gray-300 hover:text-red-300 hover:bg-red-800/30'" class="flex items-center justify-center gap-2 flex-1 min-w-[10rem] px-4 py-2 text-center rounded-t-xl transition-all duration-300 relative group bg-red-800/70 text-red-300 font-bold shadow-md">

                <span class="truncate nom_categorie_faq">${data.categorie_faq.nom_categorie}</span>
                <span class="hidden" id="cat_target">${data.categorie_faq.id}</span>
                <span class="edit_categorie_faq opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:scale-110 transform cursor-pointer" onclick="show_form_edit_categorie_faq()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil">
                        <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                        <path d="m15 5 4 4"></path>
                    </svg>
                </span>
            </button>`);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    // Gestion d'ajout de FAQ
    async function send_create_faq() {

        const faq_data = new FormData();
        faq_data.append('nom_faq', input_nom_faq.value);
        faq_data.append('texte', texte_quil.getText().trim());
        faq_data.append('texte_html', texte_quil.root.innerHTML);
        faq_data.append('lien', input_lien_faq.value);
        faq_data.append('categorie', input_categorie_faq.value);
        faq_data.append('ordre', input_ordre_faq.value);

        let file = input_fichier_faq.files[0];
        if (file) faq_data.append('fichier', file);

        const result = await create_faq(faq_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Requête d'ajout FAQ
    async function create_faq(faq_data) {
        const url = 'faq/create'
        const options = {
            method: 'POST',
            headers: {
                //'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: faq_data
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_all_errors();

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.nom_faq)
                        show_error(error_nom, data.errors.nom_faq[0]);
                    if (data.errors.texte)
                        show_error(error_texte, data.errors.texte[0]);
                    if (data.errors.fichier)
                        show_error(error_fichier, data.errors.fichier[0]);
                    if (data.errors.lien)
                        show_error(error_lien, data.errors.lien[0]);
                    if (data.errors.categorie)
                        show_error(error_categorie, data.errors.categorie[0]);
                }
                return;
            }

            let html = `
                <div id="faq_${data.faq.id}" class="flex flex-col gap-4 rounded-xl bg-white/10 p-4">
                    <div class="flex flex-col">
                        <div class="flex gap-2 items-center">
                            <span class="font-semibold text-lg text-gray-100 underline text-red-400 titre_faq">${data.faq.titre}</span>
                            <button class="edit_sujet_faq duration-300 hover:scale-110 transform cursor-pointer" onclick="show_form_edit_faq(${data.faq.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil">
                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                    <path d="m15 5 4 4"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="text-gray-300 text-md texte_faq"></span>
                    </div>
                </div>`;

            if(data.faq.ordre_affichage == 1) {

                let la_bulle = document.getElementById("bulle_cat_faq_" + data.faq.id_categorie);
                la_bulle.insertAdjacentHTML('afterend', html);
            }
            else {
                for (const cat of categories) {
                    if(cat.id == data.faq.id_categorie) {
                        let i = 1;
                        cat.sujets_faq.push(data.faq);
                        for(const sujet of cat.sujets_faq) {
                            //Vérifie si le FAQ qui vient d'être créé est sensé être après le sujet choisi
                            if ((sujet.ordre_affichage + 1 ) == data.faq.ordre_affichage) {
                                let previous_faq = document.getElementById("faq_" + sujet.id);
                                previous_faq.insertAdjacentHTML('afterend', html);
                            }
                        }
                    }
                }
            }

            document.getElementById("faq_" + data.faq.id).getElementsByClassName("texte_faq")[0].innerHTML = data.faq.texte;
            close_modal(bulle_faq);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Montre formulaire ADD de FAQ
    function show_form_add_faq() {

        input_nom_faq.value = '';
        texte_quil.root.innerHTML = '';
        input_fichier_faq.value = '';
        input_categorie_faq.value = 0;
        input_lien_faq.value = '';
        input_categorie_faq.dispatchEvent(new Event('change'));
        input_ordre_faq.value = 0;
        document.getElementById("modal_faq-title").textContent = "Ajouter un FAQ";

        clear_all_errors();

        if(!bouton_categorie_faq_delete.classList.contains("hidden")) bouton_categorie_faq_delete.classList.add("hidden");
        if(!bouton_faq_delete.classList.contains("hidden")) bouton_faq_delete.classList.add("hidden");

        document.getElementById("form_faq").action = 'faq_create_api';
        document.getElementById("bulle_bouton_faq").innerHTML =`
            <button type="button" id="ajouter_faq" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                Ajouter le nouveau FAQ
            </button>
            `;
        document.getElementById("ajouter_faq").addEventListener('click', send_create_faq);

        bulle_faq.classList.remove('hidden');
                setTimeout(() => {
                    bulle_faq.classList.add('opacity-100');
                    bulle_faq.classList.add('scale-100');
                }, 10);
    }

    // Montrer formulaire de modification de catégories FAQ
    function show_form_edit_categorie_faq(id = null) {

        if(id == null){
            id = document.getElementById("cat_target").textContent;
        }

        const ma_cat = find_categorie(id);
        document.getElementById("modal_categorie_faq-title").textContent = "Modifier une catégorie FAQ";

        clear_error(error_nom_categorie);
        show_correct_order_cat(ma_cat.ordre);

        input_nom_categorie_faq.value = ma_cat.nom_categorie;
        input_id_categorie_faq.value = ma_cat.id;

        if(bulle_ordre_categorie_faq.classList.contains("hidden")) bulle_ordre_categorie_faq.classList.remove("hidden");
        if(bouton_categorie_faq_delete.classList.contains("hidden")) bouton_categorie_faq_delete.classList.remove("hidden");

        document.getElementById("form_categorie_faq").action = 'categorie_faq_edit_api';
        document.getElementById("bulle_bouton_categorie_faq").innerHTML =`
            <button type="button" id="modifier_categorie_faq" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                Modifier la catégorie FAQ
            </button>
            `;
        document.getElementById("modifier_categorie_faq").addEventListener('click', send_edit_categorie_faq);

        bulle_categorie_faq.classList.remove('hidden');
                setTimeout(() => {
                    bulle_categorie_faq.classList.add('opacity-100');
                    bulle_categorie_faq.classList.add('scale-100');
                }, 10);
    }

    //Montrer le formulaire de edit
    function show_form_edit_faq(id) {
        const mon_faq = find_faq(id);
        document.getElementById("modal_faq-title").textContent = "Modifier un FAQ";
        input_nom_faq.value = mon_faq.titre;
        texte_quil.root.innerHTML = mon_faq.texte;
        input_categorie_faq.value = mon_faq.id_categorie;
        input_categorie_faq.dispatchEvent(new Event('change'));
        input_ordre_faq.selectedIndex = mon_faq.ordre_affichage - 1;
        input_lien_faq.value = mon_faq.lien;
        input_id_faq.value = mon_faq.id;

        //A modifier si possible, voir avec Félicya
        input_fichier_faq.files[0] = '';
        input_lien_faq.value = '';

        clear_all_errors();

        if(bouton_faq_delete.classList.contains("hidden")) bouton_faq_delete.classList.remove("hidden");

        document.getElementById("modal_faq-title").textContent = "Modifier un FAQ";

        document.getElementById("form_faq").action = 'faq_edit_api';
        document.getElementById("bulle_bouton_faq").innerHTML =`
            <button type="button" id="modifier_faq" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                Modifier le FAQ
            </button>
            `;
        document.getElementById("modifier_faq").addEventListener('click', send_edit_faq);

        bulle_faq.classList.remove('hidden');
                setTimeout(() => {
                    bulle_faq.classList.add('opacity-100');
                    bulle_faq.classList.add('scale-100');
                }, 10);

    }

    // Gestion de formulaire de modification FAQ
    async function send_edit_categorie_faq() {

        const cat_data = new FormData();
        cat_data.append('nom_categorie', input_nom_categorie_faq.value);
        cat_data.append('ordre', input_ordre_categorie_faq.value);
        cat_data.append('id', input_id_categorie_faq.value);

        const result = await edit_categorie_faq(cat_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Requête d'envoi de modification FAQ
    async function send_edit_faq() {

        const faq_data = new FormData();

        faq_data.append('nom_faq', input_nom_faq.value);
        faq_data.append('texte', texte_quil.getText().trim());
        faq_data.append('texte_html', texte_quil.root.innerHTML);
        faq_data.append('lien', input_lien_faq.value)
        faq_data.append('fichier', input_fichier_faq.value);
        faq_data.append('categorie', input_categorie_faq.value);
        faq_data.append('ordre', input_ordre_faq.value);
        faq_data.append('id', input_id_faq.value);

        let file = input_fichier_faq.files[0];
        if (file) {
            faq_data.append('fichier', file);
        }
        const result = await edit_faq(faq_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Requête d'ajout FAQ
    async function edit_faq(faq_data) {
        const url = 'faq/edit'
        const options = {
            method: 'POST',
            headers: {
                //'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: faq_data
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_all_errors();

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.nom_faq)
                        show_error(error_nom, data.errors.nom_faq[0]);
                    if (data.errors.texte)
                        show_error(error_texte, data.errors.texte[0]);
                    if (data.errors.fichier)
                        show_error(error_fichier, data.errors.fichier[0]);
                    if (data.errors.lien)
                        show_error(error_lien, data.errors.lien[0]);
                    if (data.errors.categorie)
                        show_error(error_categorie, data.errors.categorie[0]);
                }
                return;
            }

            close_modal(bulle_faq);
            edit_faq_array(data.faq);

            //Lien et fichier ne sont pas ASYNC
            let faq_id = document.getElementById("faq_" + data.faq.id);
            faq_id.getElementsByClassName("texte_faq")[0].innerHTML = data.faq.texte;
            faq_id.getElementsByClassName("titre_faq")[0].textContent = data.faq.titre;

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    // Requête de modification de catégorie FAQ
    async function edit_categorie_faq(cat_data) {
        const url = 'faq/categorie/edit'
        const options = {
            method: 'POST',
            headers: {
                //'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: cat_data
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_error(error_nom_categorie);

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.nom_categorie)
                        show_error(error_nom_categorie, data.errors.nom_categorie[0]);
                }
                return;
            }

            close_modal(bulle_categorie_faq);
            edit_categorie_array(data.categorie_faq);

            //Ordre affichage pas en ASYNC
            let cat_faq_id = document.getElementById("categorie_" + data.categorie_faq.id);
            cat_faq_id.getElementsByClassName("nom_categorie_faq")[0].textContent = data.categorie_faq.nom_categorie;

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Gestion de suppression de catégories FAQ
    async function bouton_supprimmer_categorie_faq() {

        let id = input_id_categorie_faq.value;
        let categorie_faq = find_categorie(id);

        const confirm = await Swal.fire({
            title: "Êtes-vous certain?",
            text: `"${categorie_faq.nom_categorie}" sera supprimé définitivement, et ne sera pas récupérable. Êtes-vous sur?`,
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

        const result = await delete_categorie_faq(id);

        if (result.success) {
            toastr.success(result.message);
        } else if (result.errors) {
            toastr.error(result.message);
        } else {
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    //Requête de suppression de catégorie FAQ
    async function delete_categorie_faq(id) {

        const url = `faq/categorie/delete`;
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
            let categorie_id = document.getElementById("categorie_" + id);

            if(categorie_id) categorie_id.remove();
            close_modal(bulle_categorie_faq);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Gestion de suppression de catégories FAQ
    async function bouton_supprimmer_faq() {

        let id = input_id_faq.value;
        let faq = find_faq(id);

        const confirm = await Swal.fire({
            title: "Êtes-vous certain?",
            text: `"${faq.titre}" sera supprimé définitivement, et ne sera pas récupérable. Êtes-vous sur?`,
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

        const result = await delete_faq(id);

        if (result.success) {
            toastr.success(result.message);
        } else if (result.errors) {
            toastr.error(result.message);
        } else {
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Requête de suppression de FAQ
    async function delete_faq(id) {

        const url = `faq/delete`;
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
            let faq_id = document.getElementById("faq_" + id);

            if(faq_id) faq_id.remove();
            close_modal(bulle_faq);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Trouve le bon faq dans categories
    function find_faq(id) {
        for (const cat of categories) {
            for(const sujet of cat.sujets_faq) {
                if (sujet.id == id) {
                    return sujet;
                }
            }
        }
    }

    //Modififie le array de FAQ js suite à la modification d'un FAQ
    function edit_faq_array(faq){
        for (const cat of categories) {
            for(const sujet of cat.sujets_faq) {
                if (sujet.id == faq.id) {
                    sujet.titre = faq.titre;
                    sujet.texte = faq.texte;
                    sujet.lien = faq.lien;
                    sujet.ordre_affichage = faq.ordre_affichage;
                    sujet.id_categorie = faq.id_categorie;
                }
            }
        }
    }

    //Modififie le array de catégorie FAQ js suite à la modification d'une catégorie FAQ
    function edit_categorie_array(my_cat) {
        for (const cat of categories) {
            if(cat.id == my_cat.id){
                cat.nom_categorie = my_cat.nom_categorie;
                cat.ordre = my_cat.ordre;
            }
        }
    }

    // Retourne un object catégorie FAQ en fonction de l'id
    function find_categorie(id) {
        for (const cat of categories) {
            if(cat.id == id){
                return cat;
            }
        }
    }

    // Cache un message d'erreur de formulaire
    function clear_error(error_span) {
        error_span.textContent = '';
        error_span.classList.add('hidden');
    }

    // Montre un message d'erreur de formulaire
    function show_error(error_span, message) {
        error_span.textContent = message;
        error_span.classList.remove('hidden');
    }

    //Cache tous les messages d'erreur de formulaire
    function clear_all_errors(){
        clear_error(error_nom);
        clear_error(error_texte);
        clear_error(error_fichier);
        clear_error(error_categorie);
        clear_error(error_lien);
    }

    //Gestion de fermeture des modals
    function close_modal(target) {

        target.classList.remove('opacity-100');
        target.classList.remove('scale-100');
        target.classList.add('opacity-0');
        target.classList.add('scale-100');

        setTimeout(() => {
            target.classList.add('hidden');
        }, 300);
    }

    //Vérifie le format du fichier
    function check_file_format(){
        const file = input_fichier_faq.files[0];
        if (!file) return false;

        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'mp4', 'mkv', 'avi', 'mov', 'webm', 'flv', 'pdf'];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            error_fichier.textContent=`Le type de fichier sélectionné n'est pas autorisé.`;

            error_fichier.value = '';
            return false;
        }

        const maxSizeMB = 300;
        if (file.size > maxSizeMB * 1024 * 1024) {
            error_fichier.textContent=`Le fichier dépasse la taille maximale de ${maxSizeMB} Mo.`;
            error_fichier.value = '';
            return false;
        }
        return true;
    }

    //Ajuste la valeur de l'ordre dépendament de la catégorie
    function show_correct_order_faq() {

        if(input_categorie_faq.value == 0) {
            if(!bulle_ordre_faq.classList.contains("hidden")) bulle_ordre_faq.classList.add("hidden");
            input_ordre_faq.options.length = 0;
            const option = document.createElement("option");
            option.value = 0;
            option.textContent = `0`;
            input_ordre_faq.appendChild(option);

        }
        else{
            if(bulle_ordre_faq.classList.contains("hidden")) bulle_ordre_faq.classList.remove("hidden");

            input_ordre_faq.options.length = 0;

            for(const cat of categories) {
                if(cat.id == input_categorie_faq.value) {
                    let i = 1;

                    const sujets_ordre = [...cat.sujets_faq].sort((a, b) => a.ordre_affichage - b.ordre_affichage);

                    for(const sujet of sujets_ordre) {
                        const option = document.createElement('option');
                        option.value = i;
                        option.innerHTML = i + ' (' + sujet.titre + ')';
                        i += 1;
                        input_ordre_faq.append(option);
                    }

                    const option = document.createElement('option');
                    option.value = i;
                    option.innerHTML = i + ' (En dernier)';
                    input_ordre_faq.append(option);
                }
            }
            input_ordre_faq.value = '1';
        }
    }

    //Mes les catégories FAQ dans le bon ordre
    function show_correct_order_cat(ordre) {

        const cat_ordre = [...categories].sort((a, b) => a.ordre - b.ordre);

        input_ordre_categorie_faq.options.length = 0

        let i = 1;

        for(const cat of cat_ordre) {
            const option = document.createElement('option');
            option.value = i;
            option.innerHTML = i + ' (' + cat.nom_categorie + ')';
            i += 1;
            input_ordre_categorie_faq.append(option);
        }

        const option = document.createElement('option');
        option.value = i;
        option.innerHTML = i + ' (En dernier)';
        input_ordre_categorie_faq.append(option);

        input_ordre_categorie_faq.selectedIndex = ordre - 1;
    }
}
