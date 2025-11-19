if(window.location.pathname === "/accueil" || window.location.pathname === "/") {
    let current_placement = 0;
    let isAnimating = false;
    let autoSlideInterval;
    const AUTO_SLIDE_DELAY = 10000;

    //Variables formulaires
    const div_modal = document.getElementById("modal_publication");
    const bulle_bouton_actualite = document.getElementById("bulle_bouton_actualite");
    const bouton_delete = document.getElementById("delete_actualite");
    const bouton_modifier = document.getElementById("mod_actualite");
    const bouton_ajouter = document.getElementById("add_actualite");

    //Variable champs formulaires
    const error_titre = document.getElementById("error_titre");
    const error_texte = document.getElementById("error_texte");
    const input_current_titre_actualite = document.getElementById("titre_actualite");
    const input_current_texte_actualite = document.getElementById("texte_actualite");
    const input_titre = document.getElementById("titre");
    const input_id_actualite = document.getElementById("id_actualite");

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

    //EventListeneners
    if (document.getElementById("actualite_droite") !== null) {
        document.getElementById("actualite_droite").addEventListener("click", () => {
            resetAutoSlide();
            defiler_actualite({target: {id: "actualite_droite"}});
        });
    }

    if (document.getElementById("actualite_gauche") !== null) {
        document.getElementById("actualite_gauche").addEventListener("click", () => {
            resetAutoSlide();
            defiler_actualite({target: {id: "actualite_gauche"}});
        });
    }

    //Montrer formulaire d'ajout d'actualité
    if (bouton_ajouter) {
        bouton_ajouter.addEventListener("click", () => {

            show_form_actualite();
            reset_actualite_form();
            clear_error(error_texte);
            clear_error(error_titre);

            document.getElementById("form_actualite").action = 'actualite_create';
            bulle_bouton_actualite.innerHTML =`
                <button type="button" id="ajouter_actualite" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full">
                    Ajouter la publication d\'actualité
                </button>
                `;
            document.getElementById("ajouter_actualite").addEventListener("click", send_create_actualite);
            document.getElementById("modal_publication-title").textContent = 'Ajouter une actualité';
        });
    }

    // Montrer formulaire de modification d'actualité
    if (bouton_modifier) {
        bouton_modifier.addEventListener("click", (evt) => {
            show_form_actualite();
            //input_texte.value = input_current_texte_actualite.textContent.trim();
            texte_quil.root.innerHTML = input_current_texte_actualite.textContent.trim();
            input_titre.value = input_current_titre_actualite.textContent.trim();
            input_id_actualite.value = evt.target.value;

            clear_error(error_texte);
            clear_error(error_titre);

            document.getElementById("modal_publication-title").textContent = 'Modifier une actualité';
            document.getElementById("form_actualite").action = 'actualite_edit';
            bulle_bouton_actualite.innerHTML =`
                <button type="button" id="modifier_actualite" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full">
                    Modifier la publication d\'actualité
                </button>
                `;
            document.getElementById("modifier_actualite").addEventListener("click", send_edit_actualite);
        });
    }

    // Initialiser suppression d'actualités
    if (bouton_delete) {
        bouton_delete.addEventListener("click", async (evt) => {

            let id = bouton_delete.value;

            const confirm = await Swal.fire({
                title: "Êtes-vous certain?",
                text: `"${actualite[current_placement].titre}" sera supprimé définitivement, et ne sera pas récupérable. Êtes-vous sur?`,
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


            const result = await delete_actualite(id);

            if (result.success) {
            //Quelque chose?
                toastr.success(result.message);
            } else if (result.errors) {
                toastr.error(result.message);
            } else {
                toastr.error(result.message || "Une erreur inconnue est survenue.");
            }

        });
    }

    // Envoyer formulaire d'ajout d'actualité
    async function send_create_actualite() {
        const actualite_data = {
            titre: input_titre.value,
            texte: texte_quil.getText().trim(),
            texte_html : texte_quil.root.innerHTML,
        };

        const result = await add_actualite(actualite_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Envoyer formulaire de modification d'actualité
    async function send_edit_actualite() {
        const actualite_data = {
            titre: input_titre.value,
            texte: texte_quil.getText().trim(),
            texte_html : texte_quil.root.innerHTML,
            id: input_id_actualite.value
        };

        const result = await edit_actualite(actualite_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    // Gérer suppression d'actualité
    async function delete_actualite(id) {
        const url = `accueil/delete`;
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

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    // Gérer création d'actualité
    async function add_actualite(actualite_data) {
        const url = 'accueil/create'
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(actualite_data)
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_error(error_titre);
            clear_error(error_texte);

            if (!response.ok) {
                if (data.errors) {

                    if (data.errors.titre)
                        show_error(error_titre, data.errors.titre[0]);
                    if (data.errors.texte)
                        show_error(error_texte, data.errors.texte[0]);
                }
                return;
            }

            reset_actualite_form();
            close_form_actualite();

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    // Gérer modification d'actualité
    async function edit_actualite(actualite_data) {

        const url = `accueil/edit`;
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(actualite_data)
        };

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_error(error_titre);
            clear_error(error_texte);

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.titre)
                        show_error(error_titre, data.errors.titre[0]);
                    if (data.errors.texte)
                        show_error(error_texte, data.errors.texte[0]);
                }
                return;
            }

            close_form_actualite();

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    function clear_error(error_span) {
        error_span.textContent = '';
        error_span.classList.add('hidden');
    }

    function show_error(error_span, message) {
        error_span.textContent = message;
        error_span.classList.remove('hidden');
    }

    function reset_actualite_form() {
        input_titre.value = '';
        texte_quil.root.innerHTML = '';
    }

    /**
     * Initialiser les poitns
     */
    function initDots() {
        let dotsContainer = document.getElementById("dots_container");
        dotsContainer.innerHTML = '';

        for(let i = 0; i < actualite.length; i++) {
            let dot = document.createElement('button');
            dot.className = 'w-2 h-2 transition-all duration-300';
            dot.dataset.index = i;
            dot.addEventListener('click', () => {
                resetAutoSlide();
                goToSlide(i);
            });
            updateDotStyle(dot, i === current_placement);
            dotsContainer.appendChild(dot);
        }
    }

    /**
     *
     * @param {HTMLButtonElement} dot
     * @param {*} isActive
     */
    function updateDotStyle(dot, isActive) {
        if(isActive) {
            dot.classList.add('bg-red-700', 'scale-125');
            dot.classList.remove('bg-gray-400');
        } else {
            dot.classList.add('bg-gray-400');
            dot.classList.remove('bg-red-700', 'scale-125');
        }
    }

    /**
     *
     */
    function updateAllDots() {
        let dots = document.querySelectorAll('#dots_container button');
        dots.forEach((dot, index) => {
            updateDotStyle(dot, index === current_placement);
        });
    }

    /**
     *
     * @param {*} index
     * @returns
     */
    function goToSlide(index) {
        if(isAnimating || index === current_placement) return;

        let isRight = index > current_placement;
        let titre = document.getElementById("titre_actualite");
        let texte = document.getElementById("texte_actualite");
        let container = document.getElementById("parent_span");

        isAnimating = true;

        // Animation de sortie
        if(isRight) {
            container.style.transform = 'translateX(-100%)';
        } else {
            container.style.transform = 'translateX(100%)';
        }
        container.style.opacity = '0';

        setTimeout(() => {
            current_placement = index;
            titre.innerHTML = actualite[current_placement].titre;
            texte.innerHTML = actualite[current_placement].texte;
            if(bouton_delete) bouton_delete.value = actualite[current_placement].id;
            if(bouton_delete) bouton_delete.value = actualite[current_placement].id;

            updateAllDots();

            container.style.transition = 'none';

            if(isRight) {
                container.style.transform = 'translateX(100%)';
            } else {
                container.style.transform = 'translateX(-100%)';
            }

            void container.offsetHeight;

            container.style.transition = '';

            requestAnimationFrame(() => {
                container.style.transform = 'translateX(0)';
                container.style.opacity = '1';

                setTimeout(() => {
                    isAnimating = false;
                }, 300);
            });

        }, 300);
    }

    function autoSlide() {
        let array_size = actualite.length - 1;
        let nextIndex = current_placement >= array_size ? 0 : current_placement + 1;
        goToSlide(nextIndex);
    }

    function startAutoSlide() {
        stopAutoSlide();
        autoSlideInterval = setInterval(autoSlide, AUTO_SLIDE_DELAY);
    }

    function stopAutoSlide() {
        if(autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
    }

    function resetAutoSlide() {
        startAutoSlide();
    }

    /**
     *
     * @param {*} evt
     * @returns
     */
    function defiler_actualite(evt) {
        if(isAnimating) return;
        isAnimating = true;

        let array_size = actualite.length - 1;
        let titre = document.getElementById("titre_actualite");
        let texte = document.getElementById("texte_actualite");
        let container = document.getElementById("parent_span");

        let isRight = evt.target.id === "actualite_droite";

        if(isRight) {
            container.style.transform = 'translateX(-100%)';
        } else {
            container.style.transform = 'translateX(100%)';
        }
        container.style.opacity = '0';

        setTimeout(() => {
            switch(evt.target.id) {
                case ("actualite_gauche"):
                    if(parseInt(current_placement) <= 0) {
                        current_placement = array_size;
                    } else {
                        current_placement -= 1;
                    }
                    break;
                case ("actualite_droite"):
                    if(parseInt(current_placement) == parseInt(array_size)) {
                        current_placement = 0;
                    } else {
                        current_placement += 1;
                    }
                    break;
            }

            titre.innerHTML = actualite[current_placement].titre;
            texte.innerHTML = actualite[current_placement].texte;
            if (bouton_delete !== null) {
                bouton_delete.value = actualite[current_placement].id;
            }
            if (bouton_modifier !== null) {
                bouton_modifier.value = actualite[current_placement].id;
            }

            updateAllDots();

            container.style.transition = 'none';

            if(isRight) {
                container.style.transform = 'translateX(100%)';
            } else {
                container.style.transform = 'translateX(-100%)';
            }

            void container.offsetHeight;

            container.style.transition = '';

            requestAnimationFrame(() => {
                container.style.transform = 'translateX(0)';
                container.style.opacity = '1';

                setTimeout(() => {
                    isAnimating = false;
                }, 300);
            });

        }, 300);
    }

    function show_form_actualite() {

        //let div_modal = document.getElementById("modal-publication");
        div_modal.classList.remove('hidden');
                setTimeout(() => {
                    div_modal.classList.add('opacity-100');
                    div_modal.classList.add('scale-100');
                }, 10);
    }

    function close_form_actualite() {

        //let div_modal = document.getElementById("modal-publication");
        div_modal.classList.add('hidden');
                setTimeout(() => {
                    div_modal.classList.add('opacity-0');
                    div_modal.classList.add('scale-0');
                }, 10);
    }

    function confirmer_remove_actualite() {

        if (confirm("Voulez-vous vraiment supprimer " + actualite[current_placement].titre + "? \n L'article est irrécupérable après la suppression") == false) {
            let text = "Aucun changement n\'a été apporté.";
            event.preventDefault();
        }
    }

    // Initialiser
    initDots();
    startAutoSlide();

    // Pause au survol
    let container = document.getElementById("parent_span");
    if(container) {
        container.addEventListener('mouseenter', stopAutoSlide);
        container.addEventListener('mouseleave', startAutoSlide);
    }

}
