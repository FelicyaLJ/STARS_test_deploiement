
document.getElementById('devenir-partenaire-button').addEventListener('click', () => {
    let modal = document.getElementById("devenir-partenaire-modal");
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('opacity-100', 'scale-100');
    }, 10);
});

// Gestion du carrousel infini
document.addEventListener('DOMContentLoaded', async () => {
    //Variable form/bouton
    const bouton_create_form = document.getElementById("add_partenaire");
    const bouton_edit_form = document.getElementById("edit_partenaire");
    const bouton_delete = document.getElementById("delete");
    const partenaire_form = document.getElementById("form_partenaire");
    const form_modal = document.getElementById("modal_partenaire");

    //Please god
    let LORD = false;

    bouton_edit_form.addEventListener("click", show_edit_form);
    bouton_create_form.addEventListener("click", show_create_form);
    bouton_delete.addEventListener("click", bouton_supprimmer_partenaire);

    //Variables champs
    const champ_id = document.getElementById("id_partenaire");
    const champ_nom = document.getElementById("nom_partenaire");
    const champ_image = document.getElementById("image_partenaire");
    const champ_lien = document.getElementById("lien_partenaire");
    const champ_ordre = document.getElementById("ordre_affichage_partenaire");

    //Variables erreur
    const erreur_nom = document.getElementById("error_nom");
    const erreur_image = document.getElementById("error_image");
    const erreur_lien = document.getElementById("error_lien");

    const res = await fetch('/partenaires');
    let partenaires = await res.json().catch(() => []);
    if (!Array.isArray(partenaires) || !partenaires.length) {
        partenaires = [
            { nom: 'Demo Partner', image: null, lien: '#', ordre_affichage: 1 },
            { nom: 'Sample Partner', image: null, lien: '#', ordre_affichage: 2 },
            { nom: 'Test Partner', image: null, lien: '#', ordre_affichage: 3 },
        ];
    }


    partenaires.sort((a, b) => (a.ordre_affichage ?? 0) - (b.ordre_affichage ?? 0));
    show_correct_order_partenaire();
    while (partenaires.length < 6) partenaires = partenaires.concat(partenaires);

    const tailCount = 2;
    const leftTail = partenaires.slice(-tailCount);
    const rightTail = partenaires.slice(0, tailCount);
    const data = [...leftTail, ...partenaires, ...rightTail];

    const track = document.getElementById('carousel');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');

    const getImageUrl = (img) => {
        if (!img) return null;
        if (img.startsWith('http') || img.startsWith('/storage')) return img;
        return `/storage/partenaires/images/${img}`;
    };

    const buildSlides = arr => {
        track.innerHTML = '';
        arr.forEach(p => {
            const imageUrl = getImageUrl(p.image);
            const card = document.createElement('div');
            card.className =
                'flex-none w-56 sm:w-64 p-6 rounded-2xl bg-white/10 border border-white/20 text-white text-center cursor-pointer transition-transform duration-200 ease-out hover:scale-[1.02] partenaire';
            card.innerHTML = `
                <div class="flex flex-col items-center space-y-3">
                    <div class="w-52 sm:w-60 h-32 rounded-lg bg-white/30 overflow-hidden flex items-center justify-center">
                        ${
                        p.image
                            ? `<img src="${imageUrl}" alt="${p.nom}" class="object-cover w-full h-full">`
                            : `<span class="text-2xl font-bold">${(p.nom?.[0] || '?').toUpperCase()}</span>`
                        }
                        <div class="absolute inset-0 z-10"></div>
                        <div class="id_partenaire hidden">${p.id}</div>
                    </div>
                    <h3 class="font-semibold text-xl">${p.nom || ''}</h3>
                </div>`;
            card.addEventListener('click', () => window.open(p.lien || '#', '_blank'));
            track.appendChild(card);
        });
    };

    buildSlides(data);
    let slides = Array.from(track.children);

    let gap = parseFloat(getComputedStyle(track).gap) || 0;
    let slideWidth = slides[0].offsetWidth + gap;
    const recalc = () => {
        slides = Array.from(track.children);
        gap = parseFloat(getComputedStyle(track).gap) || 0;
        slideWidth = slides[0].offsetWidth + gap;
    };
    window.addEventListener('resize', () => { recalc(); applyTransformForIndex(centeredIndex, false); });

    let centeredIndex = tailCount;
    const computeTranslateForIndex = i => {
        const containerWidth = track.parentElement.clientWidth;
        return (containerWidth / 2) - (slideWidth / 2) - (i * slideWidth);
    };

    function highlightOnce(i) {
        slides.forEach((el, idx) => {
            el.style.transition = 'transform 0.3s ease, opacity 0.3s ease';

            const isActive = idx === i;
            const isSeamlessClone =
            (i === tailCount && idx === data.length - tailCount - 1) || // looping left
            (i === data.length - tailCount - 1 && idx === tailCount);   // looping right

            if (isActive || isSeamlessClone) {
            el.style.transform = 'scale(1.12)';
            el.style.opacity = '1';
            el.style.zIndex = '20';
            } else {
            el.style.transform = 'scale(0.92)';
            el.style.opacity = '0.6';
            el.style.zIndex = '1';
            }
        });
    }


    const applyTransformForIndex = (i, animate = true) => {
        track.style.transition = animate ? 'transform 0.7s ease-in-out' : 'none';
        track.style.transform = `translateX(${computeTranslateForIndex(i)}px)`;
    };

    recalc();
    applyTransformForIndex(centeredIndex, false);
    highlightOnce(centeredIndex);

    let isTransitioning = false;

    function goNext() {
        if (isTransitioning) return;
        isTransitioning = true;
        centeredIndex++;
        highlightOnce(centeredIndex);
        applyTransformForIndex(centeredIndex, true);
        LORD = !LORD;
    }

    function goPrev() {
        if (isTransitioning) return;
        isTransitioning = true;
        centeredIndex--;
        highlightOnce(centeredIndex);
        applyTransformForIndex(centeredIndex, true);
        LORD = !LORD;
    }

    track.addEventListener('transitionend', (ev) => {
        if (ev.target !== track || ev.propertyName !== 'transform') return;

        scaleBlocked = true;
        let wrapped = false;

        if (centeredIndex > tailCount) {
            for (let i = 0; i < tailCount; i++) {
            const first = track.firstElementChild;
            track.appendChild(first);
            }
            recalc();
            centeredIndex = centeredIndex - tailCount;
            applyTransformForIndex(centeredIndex, false);
            wrapped = true;
        }

        if (centeredIndex < tailCount) {
            for (let i = 0; i < tailCount; i++) {
            const last = track.lastElementChild;
            track.insertBefore(last, track.firstElementChild);
            }
            recalc();
            centeredIndex = centeredIndex + tailCount;
            applyTransformForIndex(centeredIndex, false);
            wrapped = true;
        }

        LORD = !LORD;

        setTimeout(() => {
            scaleBlocked = false;
            if (!wrapped) {
            highlightOnce(centeredIndex);
            } else {
            const el = slides[centeredIndex];
            if (el) {
                el.style.transition = 'none';
                el.style.transform = 'scale(1.12)';
                el.style.opacity = '1';
                el.style.zIndex = '20';
            }
            }
            isTransitioning = false;
        }, 50);
    });

    // autoplay
    let autoTimer;
    const startAuto = () => { stopAuto(); autoTimer = setInterval(goNext, 4000); };
    const stopAuto = () => { if (autoTimer) clearInterval(autoTimer); };
    startAuto();

    nextBtn.addEventListener('click', () => { stopAuto(); goNext(); startAuto(); });
    prevBtn.addEventListener('click', () => { stopAuto(); goPrev(); startAuto(); });
    track.parentElement.addEventListener('mouseenter', stopAuto);
    track.parentElement.addEventListener('mouseleave', startAuto);

    // drag
    let pointerDown = false, startX = 0;
    track.addEventListener('pointerdown', e => { pointerDown = true; startX = e.clientX; stopAuto(); });
    track.addEventListener('pointerup', e => {
        if (!pointerDown) return;
        pointerDown = false;
        const dx = e.clientX - startX;
        if (Math.abs(dx) > 40) dx < 0 ? goNext() : goPrev();
        startAuto();
    });

    function show_create_form() {

        champ_nom.value = '';
        champ_ordre.selectedIndex = '';
        champ_image.value = '';
        champ_lien.value = '';

        //Reset erreurs
        clear_error(erreur_nom);
        clear_error(erreur_image);
        clear_error(erreur_lien);

        document.getElementById("bulle_bouton_partenaire").innerHTML =`
            <button type="button" id="ajouter_partenaire" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                Ajouter le partenaire
            </button>
            `;
        document.getElementById("ajouter_partenaire").addEventListener('click', send_create_partenaire);

        //S'assurer que le comportement inverse s'applique au add form
        if(!bouton_delete.classList.contains("hidden")) bouton_delete.classList.add("hidden");

        //Changer pour target form
        form_modal.classList.remove('hidden');
                setTimeout(() => {
                    form_modal.classList.add('opacity-100');
                    form_modal.classList.add('scale-100');
                }, 10);
    }

    async function send_create_partenaire() {

        const partenaire_data = new FormData();

        partenaire_data.append('nom_partenaire', champ_nom.value);
        partenaire_data.append('lien', champ_lien.value);
        partenaire_data.append('ordre_affichage', champ_ordre.value);


        let image = champ_image.files[0];
        if (image) partenaire_data.append('image', image);

        const result = await create_partenaire(partenaire_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
                toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    async function create_partenaire(partenaire_data) {
        //Mettre bonne cible
        const url = 'partenaires/create'
        const options = {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: partenaire_data
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_error(erreur_nom);
            clear_error(erreur_image);
            clear_error(erreur_lien);

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.nom_partenaire)
                        show_error(erreur_nom, data.errors.nom_partenaire[0]);
                    if(data.errors.image)
                        show_error(erreur_image, data.errors.image[0]);
                    if(data.errors.lien)
                        show_error(erreur_lien, data.errors.lien[0]);
                }
                return;
            }

            close_modal(form_modal);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    function show_edit_form() {

        const id = document.querySelector('div[style*="z-index: 20"]').getElementsByClassName("id_partenaire")[0].innerHTML;
        const le_partenaire = partenaires.find(t => t.id == id);

        champ_nom.value = le_partenaire.nom;
        champ_ordre.selectedIndex = le_partenaire.ordre_affichage - 1;
        champ_image.value = '';
        champ_lien.value = '';
        champ_id.value = le_partenaire.id;

        //Reset erreurs
        clear_error(erreur_nom);
        clear_error(erreur_image);
        clear_error(erreur_lien);

        document.getElementById("bulle_bouton_partenaire").innerHTML =`
            <button type="button" id="bouton_edit_partenaire" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full mt-[5%]">
                Modifier le partenaire
            </button>
            `;
        document.getElementById("bouton_edit_partenaire").addEventListener("click", send_edit_partenaire);

        //S'assurer que le comportement inverse s'applique au add form
        if(bouton_delete.classList.contains("hidden")) bouton_delete.classList.remove("hidden");

        //Changer pour target form
        form_modal.classList.remove('hidden');
                setTimeout(() => {
                    form_modal.classList.add('opacity-100');
                    form_modal.classList.add('scale-100');
                }, 10);
    }

    async function send_edit_partenaire() {

        const partenaire_data = new FormData();

        partenaire_data.append('nom_partenaire', champ_nom.value);
        partenaire_data.append('lien', champ_lien.value);
        partenaire_data.append('ordre_affichage', champ_ordre.value);
        partenaire_data.append('id', champ_id.value);

        let image = champ_image.files[0];
        if (image) partenaire_data.append('image', image);

        const result = await edit_partenaire(partenaire_data);

        if(result !== undefined) {
            if (result.success)
                toastr.success(result.message);
            else if (result.errors)
                toastr.error(result.message);
            else
                toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    async function edit_partenaire(partenaire_data) {
        //Mettre bonne cible
        const url = 'partenaires/edit'
        const options = {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: partenaire_data
        }

        try {
            const response = await fetch(url, options);
            const data = await response.json();

            clear_error(erreur_nom);
            clear_error(erreur_image);
            clear_error(erreur_lien);

            if (!response.ok) {
                if (data.errors) {
                    if (data.errors.nom_partenaire)
                        show_error(erreur_nom, data.errors.nom_partenaire[0]);
                    if(data.errors.image)
                        show_error(erreur_image, data.errors.image[0]);
                    if(data.errors.lien)
                        show_error(erreur_lien, data.errors.lien[0]);
                }
                return;
            }

            //Fermer bon modal
            close_modal(form_modal);

            return data;
        } catch (err) {
            console.error(err);
            return { success: false, message: "Une erreur s'est produite." };
        }
    }

    //Gestion de suppression de catégories FAQ
    async function bouton_supprimmer_partenaire() {

        let id = champ_id.value;
        const le_partenaire = partenaires.find(t => t.id == id);

        const confirm = await Swal.fire({
            title: "Êtes-vous certain?",
            text: `"${le_partenaire.nom}" sera supprimé définitivement, et ne sera pas récupérable. Êtes-vous sur?`,
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

        const result = await delete_partenaire(id);

        if (result.success) {
            toastr.success(result.message);
        } else if (result.errors) {
            toastr.error(result.message);
        } else {
            toastr.error(result.message || "Une erreur inconnue est survenue.");
        }
    }

    //Requête de suppression de catégorie FAQ
    async function delete_partenaire(id) {

        //Mettre bonne adresse
        const url = `partenaires/delete`;
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

            close_modal(form_modal);

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

    // Montre un message d'erreur de formulaire
    function show_error(error_span, message) {
        error_span.textContent = message;
        error_span.classList.remove('hidden');
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

    //Ajuste la valeur de l'ordre dépendament de la catégorie
    function show_correct_order_partenaire() {

        champ_ordre.options.length = 0;

        let i = 1;

        for(const sujet of partenaires) {
            const option = document.createElement('option');
            option.value = i;
            option.innerHTML = i + ' (' + sujet.nom + ')';
            i += 1;
            champ_ordre.append(option);
        }

        const option = document.createElement('option');
        option.value = i;
        option.innerHTML = i + ' (En dernier)';
        champ_ordre.append(option);

        champ_ordre.value = '1';
    }
});
