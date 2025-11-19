document.addEventListener("DOMContentLoaded", () => {
    let exercice = null;

    const rechercheCategorie = document.getElementById("rechercheCategorie");
    const champTri = document.getElementById("tri");
    const listeCategories = document.getElementById("listeCategories");
    const addForm = document.getElementById("formModal");
    const editForm = document.getElementById("formModalModif");
    const modalTitle = document.getElementById("formModal-title");
    const btnAjoutCategorie = document.getElementById("ajoutCategorie");
    const rechercheExercice=document.getElementById("rechercheMotCle");
    const resetBtn = document.getElementById("reset");

    const btnModifier = document.getElementById("modifierExercice");
    const btnEnregistrer = document.getElementById("enregistrerModif");
    const btnSupprimer = modalQuery(editForm, "supprimerExercice");
    const deployFiltre = document.getElementById("deployFiltre");
    const divToDeploy = document.getElementById("contentFiltre");

    deployFiltre.style.position = "relative";

    Object.assign(divToDeploy.style, {
        position: "absolute",
        top: "100%",
        right: "0",
        width: "230px",
        backgroundColor: "#00000090",
        color: "#f9fafb",
        borderRadius: "0.5rem",       // rounded corners
        boxShadow: "0 5px 15px rgba(0,0,0,0.3)", // shadow
        zIndex: "1000",               // float above content
        overflow: "hidden",           // hide overflow
        maxHeight: "0",               // start collapsed
        transition: "max-height 0.3s ease", // smooth animation
        display: "block"
    });
    divToDeploy.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    deployFiltre.addEventListener("click", ()=>{
        const isExpanded = parseInt(getComputedStyle(divToDeploy).maxHeight) > 0;

        if (isExpanded) {
            divToDeploy.style.maxHeight = "0";
        } else {
            const fullHeight = divToDeploy.scrollHeight + "px";
            divToDeploy.style.maxHeight = fullHeight;
        }
    });

    if (modalQuery(editForm, "image")) modalQuery(editForm, "image").addEventListener('change', () => checkImageFormat(false));
    if (modalQuery(editForm, "fichier")) modalQuery(editForm, "fichier").addEventListener('change', checkFileFormat);

    if (btnModifier) {
        btnModifier.addEventListener("click", (e) => {
            setEditFormValues(exercice);
            openExerciceForm(editForm);
        });
    }

    if (btnEnregistrer) {
        btnEnregistrer.addEventListener("click", function (evt) {
            evt.preventDefault();
            if (!validateData(editForm, false)) return;
            const formEl = getModalForm(editForm);
            if (!formEl) return;
            const exerciceData = new FormData(formEl);
            exerciceData.append("_method", "PUT");
            updateExercice(exerciceData);
        });
    }

    if (btnSupprimer) {
        btnSupprimer.addEventListener("click", function (evt) {
            evt.preventDefault();
            Swal.fire({
                title: "Confirmation",
                text: "Voulez-vous vraiment supprimer cet exercice?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Supprimer",
                cancelButtonText: "Annuler",
                color: "#f9fafb",
                background: "rgba(1, 1, 1, 0.6)",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "rgba(93, 87, 87, 1)",
                customClass: {
                    popup: "rounded-lg shadow-lg backdrop-blur",
                },
                didOpen: (popup) => {
                    popup.style.border = "1px solid rgba(255, 255, 255, 0.2)";
                    popup.style.backdropFilter = "blur(10px)";
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const id = evt.target.dataset?.id || (exercice ? exercice.id : null);
                    if (id) deleteExercice(id);
                } else {
                    return;
                }
            });
        });
    }

    rechercheCategorie.addEventListener( "input", () => rechercherCategorie(rechercheCategorie.value));
    champTri.addEventListener("change", () => trier(champTri.value));
    if (btnAjoutCategorie) btnAjoutCategorie.addEventListener("click", popupForm);
    resetBtn.addEventListener("click", reset);

    rechercheExercice.addEventListener("input", filtrerTout);
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', filtrerTout);
    });

    if (categories && categories.length) trier("0");
    else populateListeCategories();

    function openExerciceForm(modal) {
        if (!modal) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.classList.add('scale-100');
        }, 10);
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.classList.remove('opacity-100');
        modal.classList.remove('scale-100');
        modal.classList.add('opacity-0');
        modal.classList.add('scale-100');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function reload(){
        location.reload();
    }

    function checkFileFormat() {
        const fileBox = document.getElementById("fichier");
        if (!fileBox || !fileBox.files[0]) return true; // optional file field → true if none

        const file = fileBox.files[0];
        const allowedExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg',
            'mp4', 'mkv', 'avi', 'mov', 'webm', 'flv', 'pdf'
        ];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            const err = document.getElementById("formErrorFichier");
            if (err) {
                err.textContent = `Le type de fichier sélectionné n'est pas autorisé.`;
                err.classList.remove("hidden");
            }
            fileBox.value = '';
            return false;
        }

        const maxSizeMB = 300;
        if (file.size > maxSizeMB * 1024 * 1024) {
            const err = document.getElementById("formErrorFichier");
            if (err) {
                err.textContent = `Le fichier dépasse la taille maximale de ${maxSizeMB} Mo.`;
                err.classList.remove("hidden");
            }
            fileBox.value = '';
            return false;
        }

        const err = document.getElementById("formErrorFichier");
        if (err) err.classList.add("hidden");
        return true;
    }

    function checkImageFormat(required = true) {
        const fileBox = document.getElementById("image");
        if (!fileBox) return true;

        const file = fileBox.files[0];
        if (!file) {
            if (required) {
                const err = document.getElementById("formErrorImage");
                if (err) {
                    err.textContent = `L'image de couverture ne peut pas être vide.`;
                    err.classList.remove("hidden");
                }
                return false;
            } else return true;
        }

        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            const err = document.getElementById("formErrorImage");
            if (err) {
                err.textContent = `Le type de fichier sélectionné n'est pas autorisé.`;
                err.classList.remove("hidden");
            }
            fileBox.value = '';
            return false;
        }

        const maxSizeMB = 5;
        if (file.size > maxSizeMB * 1024 * 1024) {
            const err = document.getElementById("formErrorImage");
            if (err) {
                err.textContent = `Le fichier dépasse la taille maximale de ${maxSizeMB} Mo.`;
                err.classList.remove("hidden");
            }
            fileBox.value = '';
            return false;
        }

        const err = document.getElementById("formErrorImage");
        if (err) err.classList.add("hidden");
        return true;
    }

    function getFileType(nom){
        if (!nom) return 0;
        const lower = nom.toLowerCase();

        if ([".jpg", ".jpeg", ".png", ".gif", ".bmp", ".webp", ".svg"].some(ext => lower.endsWith(ext))) return 1;
        if ([".mp4", ".mkv", ".avi", ".mov", ".webm", ".flv"].some(ext => lower.endsWith(ext))) return 2;
        if ([".pdf"].some(ext => lower.endsWith(ext))) return 3;

        return 0;
    }

    function getMimeType(filename) {
        if (!filename) return "";
        const ext = filename.split(".").pop().toLowerCase();
        switch (ext) {
            case "mp4": return "video/mp4";
            case "webm": return "video/webm";
            case "ogg": return "video/ogg";
            case "avi": return "video/x-msvideo";
            case "mov": return "video/quicktime";
            case "mkv": return "video/x-matroska";
            case "flv": return "video/x-flv";
            default: return "";
        }
    }

    function treatFile(fichier){
        let type=getFileType(fichier);
        let elem=null;
        switch (type){
            case 0:
                elem = creerElement("p", { text: "Le fichier de cet entrainement ne peut pas être lu." });
                break;
            case 1:
                elem = creerElement("img", {attrs: {src: urlFichierBase + fichier, alt: "image", style: "width: 100%", style: "height:100%; max-height: 600px; min-height:200px; min-width:200px"}});
                break;

            case 2:
                elem = creerElement("video", {attrs: {controls: true, width: "640", autoplay: false}});
                const source = creerElement("source", {attrs: {src: urlFichierBase + fichier, type: getMimeType(fichier), style: "width: 100%", style: "height:100%"}});
                elem.appendChild(source);
                break;

            case 3:
                elem = creerElement("embed", {attrs: {src: urlFichierBase + fichier, type: "application/pdf", width: "100%", height: "400px", style: "height:400px"}});
                break;

            default:
                elem = creerElement("p", {text: "Une erreur est survenue."});
                break;
        }
        return elem;
    }

    function getModalForm(modal){
        if (!modal) return null;
        return modal.querySelector('form');
    }

    function getModalFormContent(modal) {
        if (!modal) return null;
        return modal.querySelector('#formContent');
    }

    function modalQuery(modal, id) {
        if (modal) {
            const el = modal.querySelector(`#${id}`);
            if (el) return el;
        }
        return document.getElementById(id);
    }

    function creerElement(tag, options={}){
        const el = document.createElement(tag);
        if (options.classes) el.classList.add(...options.classes);
        if (options.attrs) Object.entries(options.attrs).forEach(([k,v])=> el.setAttribute(k,v));
        if (options.dataset) Object.entries(options.dataset).forEach(([k, v]) => {el.dataset[k] = v;});
        if (options.text) el.textContent = options.text;
        if (options.html) el.innerHTML = options.html;
        return el;
    }

    function creerExerciceDiv(exercice) {
        const divEx = creerElement("div", {classes: ["grid-1", "exerciceDiv", "border", "rounded-lg", "min-w-[10rem]", "min-h-[15rem]", "text-gray-50", "mx-2", "mb-5", "overflow-hidden", "flex", "flex-col", "justify-between", "items-center", "text-center", "cursor-pointer", "hover:bg-white/10", "hover:text-gray-200", "transition-colors", "duration-300"], dataset: { exercice: exercice.id }});
        const img = creerElement("img", {attrs: { src: urlImageBase + exercice.image, alt: "image" }, classes: ["w-full", "max-h-48", "object-cover", "object-top", "rounded-t", "cursor-pointer"]});
        const p = creerElement("p", {classes: ["border-t", "border-gray-300", "my-4", "px-2"], text: exercice.nom_exercice});

        divEx.append(img, p);
        divEx.addEventListener("click", () => {
            display(exercice.id);
        });

        return divEx;
    }

    function creerCategorieDiv(categorie) {
        const div = creerElement("div", {classes: ["deployZone", "mx-2", "mb-4", "p-4", "rounded-lg", "cursor-pointer", "bg-white/10", "flex", "justify-between", "text-gray-50", "hover:bg-white/30", "transition", "duration-300"], dataset: { categorie: categorie.id }});
        const sousDiv = creerElement("div", { classes: ["flex", "space-x-4"] });

        if (canManageEntrainements) {
            const btnMod = creerElement("button", {classes: ["modifierCategorie"], attrs: { type: "button", id: categorie.id }, html: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"> <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>`});
            btnMod.addEventListener("click", (e) => {
                e.stopPropagation();
                popupForm(e);
            });
            sousDiv.appendChild(btnMod);
        }

        const title = creerElement("p", { classes: ["font-semibold", "text-lg"], text: categorie.nom_categorie});
        sousDiv.appendChild(title);
        div.appendChild(sousDiv);

        const btnDeploy = creerElement("button", {classes: ["deployExercices"], dataset: { categorie: categorie.id }, html: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-down-dash-icon lucide-arrow-big-down-dash"><path d="M15 11a1 1 0 0 0 1 1h2.939a1 1 0 0 1 .75 1.811l-6.835 6.836a1.207 1.207 0 0 1-1.707 0L4.31 13.81a1 1 0 0 1 .75-1.811H8a1 1 0 0 0 1-1V9a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1z"/><path d="M9 4h6"/></svg>`});
        //btnDeploy.addEventListener("click", deploy);
        div.appendChild(btnDeploy);

        div.addEventListener("click", deploy);

        return div;
    }

    function creerExerciceListeDiv(categorie){
        const div2 = creerElement("div", {classes: ["exerciceList", "grid", "auto-rows-fr", "overflow-hidden", "max-h-0", "transition-[max-height,opacity,transform]", "duration-500"], dataset: { deploy: categorie.id }});
        applyGridColumns(div2);
        const p = creerElement("p", {attrs: { id: "exerciceP-" + categorie.id }, text: "Aucune correspondance.", classes: ["hidden", "text-gray-50"]});
        div2.appendChild(p);

        if (categorie.exercices.length > 0) {
            categorie.exercices.forEach(ex => div2.appendChild(creerExerciceDiv(ex)));
        } else {
            const divEx = creerElement("div", { classes: ["grid-1", "mx-2", "mb-5", "overflow-hidden", "border", "text-center", "p-3", "rounded"]});
            divEx.appendChild(creerElement("p", { text: "Aucun exercice dans cette catégorie", classes: [ "text-gray-50"] }));
            div2.appendChild(divEx);
        }

        if (canManageEntrainements) {
            const divPlus = creerElement("div", {classes: ["addExercice", "text-gray-50", "grid", "min-w-[10rem]", "min-h-[15rem]", "mx-2", "mb-5", "overflow-hidden", "border", "rounded-lg", "place-items-center", "cursor-pointer", "hover:bg-white/10", "hover:text-gray-200", "transition-colors", "duration-300"], dataset: {categorie: categorie.id }});
            divPlus.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-plus-icon lucide-square-plus"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>`;
            divPlus.addEventListener("click", popupForm);
            div2.appendChild(divPlus);
        }

        return div2;
    }

    function populateListeCategories(){
        if (!listeCategories) return;
        listeCategories.innerHTML = "";
        categories.forEach(categorie => {
            listeCategories.appendChild(creerCategorieDiv(categorie));
            listeCategories.appendChild(creerExerciceListeDiv(categorie));
        });
    }

    function applyFiltre(exercice, recherche, typeFichier){
        const rechercheClean = recherche ? recherche.normalize("NFD").replace(/\p{Diacritic}/gu, "").toLowerCase() : "";
        const nomClean = exercice.nom_exercice.normalize("NFD").replace(/\p{Diacritic}/gu, "").toLowerCase();
        const matchNom = !rechercheClean || nomClean.startsWith(rechercheClean);

        let matchType = true;
        typeFichier = parseInt(typeFichier);
        if (typeFichier === 0) matchType = exercice.fichier === "";
        else if (typeFichier === 4) matchType = true;
        else matchType = getFileType(exercice.fichier) === typeFichier;

        return matchNom && matchType;
    }

    function filtrerTout(){
        const recherche = (rechercheExercice && rechercheExercice.value) ? rechercheExercice.value : "";
        const typeFichier = document.querySelector('input[name="type"]:checked')?.value || "4";

        categories.forEach(categorie=>{
            const p = document.getElementById("exerciceP-"+ categorie.id);
            const divToDeploy = document.querySelector(`[data-deploy="${categorie.id}"]`);

            let j = categorie.exercices.length;

            const categorieDiv = document.querySelector(`[data-categorie="${categorie.id}"]`);
            const categorieVisible = categorieDiv && !categorieDiv.classList.contains("hidden");

            categorie.exercices.forEach(exercice=>{
                const match = applyFiltre(exercice, recherche, typeFichier);
                    const div = document.querySelector(`[data-exercice="${exercice.id}"]`);
                    if (!match || !categorieVisible){
                        div.classList.add("hidden")
                        j--;
                    }
                    else{
                        div.classList.remove("hidden");
                    }
            });
            if (j === 0) {
                p.classList.remove("hidden");
            }
            else{
                p.classList.add("hidden");
                divToDeploy.classList.remove("hidden");
                divToDeploy.style.maxHeight = "none";
                const fullHeight = divToDeploy.scrollHeight + "px";
                setTimeout(() => {
                    divToDeploy.style.maxHeight = fullHeight;
                }, 10);
            }
        });
    }

    function reset(){
         if (champTri) champTri.value = "0";
        trier("0");
        if (rechercheCategorie) rechercheCategorie.value = "";
        if (rechercheExercice) rechercheExercice.value = "";
        const typeTous = document.getElementById("typeTous");
        if (typeTous) typeTous.checked = true;
    }

    function deploy(evt){
        const id = evt.currentTarget.dataset?.categorie;
        if (!id) return;
        const divToDeploy = document.querySelector(`[data-deploy="${id}"]`);
        if (!divToDeploy) return;

        const isExpanded = parseInt(getComputedStyle(divToDeploy).maxHeight) > 0;
        if (isExpanded) {
            divToDeploy.style.maxHeight = "0";
        } else {
            divToDeploy.classList.remove("hidden");
            divToDeploy.style.maxHeight = "none";
            const fullHeight = divToDeploy.scrollHeight + "px";
            divToDeploy.style.maxHeight = "0"; // reset
            setTimeout(() => {
                divToDeploy.style.maxHeight = fullHeight;
            }, 10);
        }
    }

    function rechercherCategorie(filtre){
        if (filtre.trim()!==""){
            const filtreClean=filtre.normalize("NFD").replace(/\p{Diacritic}/gu, "").toLowerCase();

            categories.forEach(categorie=>{
                document.querySelector(`[data-categorie="${categorie.id}"]`).classList.remove("hidden");

                let titre = categorie.nom_categorie.normalize("NFD").replace(/\p{Diacritic}/gu, "").toLowerCase();

                if (!titre.startsWith(filtreClean)){
                    const categorieDiv = document.querySelector(`[data-categorie="${categorie.id}"]`);
                    categorieDiv.classList.add("hidden");
                    const divToDeploy = document.querySelector(`[data-deploy="${categorie.id}"]`);
                    divToDeploy.style.maxHeight = "0";
                    divToDeploy.classList.add("hidden");
                }
            });
        }
        else{
            categories.forEach(categorie=>{
                document.querySelector(`[data-categorie="${categorie.id}"]`).classList.remove("hidden");
                const divToDeploy = document.querySelector(`[data-deploy="${categorie.id}"]`);
                    divToDeploy.style.maxHeight = "0";
                    divToDeploy.classList.add("hidden");
            });
        }
    }

    function trier(tri){
        switch(tri){
            case "0"://par ordre d'affichage
                categories.sort((a, b) => {
                    const nbA = a.ordre_affichage;
                    const nbB = b.ordre_affichage;
                    return nbA - nbB;
                });
                categories.forEach(categorie => {
                    categorie.exercices.sort((a, b)=>{
                    const nbA=a.ordre_affichage;
                    const nbB=b.ordre_affichage;
                    return nbA-nbB;
                    });
                });
                break;

            case "1"://ajout récent
                categories.sort((a, b) => {
                const latestA = a.exercices.length
                    ? Math.max(...a.exercices.map(e => new Date(e.forum.created_at)))
                    : 0;

                const latestB = b.exercices.length
                    ? Math.max(...b.exercices.map(e => new Date(e.forum.created_at)))
                    : 0;
                    return latestB - latestA;
                });
                categories.forEach(categorie => {
                    categorie.exercices.sort((a, b)=>{
                    const nbA=new Date(a.forum.created_at);
                    const nbB= new Date(b.forum.created_at);
                    return nbB-nbA;
                    });
                });
                break;

            case "2"://nombre d'exercices (croissant)
                categories.sort((a, b) => {
                    const nbA = a.exercices.length;
                    const nbB = b.exercices.length;
                    return nbA - nbB;
                });
                categories.forEach(categorie => {
                    categorie.exercices.sort((a, b)=>{
                    const nbA=a.ordre_affichage;
                    const nbB=b.ordre_affichage;
                    return nbA-nbB;
                    });
                });
                break;

            case "3"://nombre d'exercices (décroissant)
                categories.sort((a, b) => {
                    const nbA = a.exercices.length;
                    const nbB = b.exercices.length;
                    return nbB - nbA;
                });
                categories.forEach(categorie => {
                    categorie.exercices.sort((a, b)=>{
                    const nbA=a.ordre_affichage;
                    const nbB=b.ordre_affichage;
                    return nbA-nbB;
                    });
                });
                break;

            case "4": //alphabétique
                categories.sort((a, b) =>a.nom_categorie.localeCompare(b.nom_categorie));
                categories.forEach(categorie => {
                    categorie.exercices.sort((a, b)=>a.nom_exercice.localeCompare(b.nom_exercice));
                });
                break;

            default:
                break;
        }
        populateListeCategories();
    }

    function triOrdreAffichage(id){
        categories.find(c => c.id == id).exercices.sort((a, b) => {
            const nbA = a.ordre_affichage;
            const nbB = b.ordre_affichage;
            return nbA - nbB;
        });
        return categories.find(c => c.id == id).exercices;
    }

    function popupForm(evt) {
        evt.stopPropagation();
        const source = evt.currentTarget;
        const categorieId = source.dataset?.categorie || source.dataset?.id || source.id;

        if (source.classList && source.classList.contains("addCategorie")) {
            if (!addForm) return;
            const modalTitleEl = modalQuery(addForm, "formModal-title");
            if (modalTitleEl) modalTitleEl.textContent = "Ajouter une catégorie";

            setForm(addForm, 1, 0);
            const fc = getModalFormContent(addForm);
            if (fc) {
                const saveBtn = creerElement("button", {
                    attrs: {
                        type: "button",
                        id: "saveCategorie"
                    },
                    classes: ["bg-blue-500", "text-white", "px-4", "py-2", "rounded", "mt-2", "mt-[5%]"],
                    text: "Enregistrer"
                });
                fc.appendChild(saveBtn);
                saveBtn.addEventListener("click", saveCategorie);
            }
            openExerciceForm(addForm);
            return;
        }

        if (source.classList && source.classList.contains("addExercice")) {
            if (!addForm) return;
            const modalTitleEl = modalQuery(addForm, "formModal-title");
            if (modalTitleEl) modalTitleEl.textContent = "Ajouter un exercice";

            setForm(addForm, 2, categorieId);
            const fc = getModalFormContent(addForm);
            if (fc) {
                const saveBtn = creerElement("button", {
                    attrs: {
                        type: "button",
                        id: "saveExercice"
                    },
                    classes: ["bg-blue-500", "text-white", "px-4", "py-2", "rounded", "mt-2", "mt-[5%]"],
                    text: "Enregistrer"
                });
                fc.appendChild(saveBtn);
                saveBtn.addEventListener("click", saveExercice);

                const fichierEl = modalQuery(addForm, "fichier");
                const imageEl = modalQuery(addForm, "image");
                if (fichierEl) fichierEl.addEventListener("change", () => checkFileFormat());
                if (imageEl) imageEl.addEventListener("change", () => checkImageFormat(true));
            }
            openExerciceForm(addForm);
            return;
        }

        if (source.classList && source.classList.contains("modifierCategorie")) {
            if (!addForm) return;
            const modalTitleEl = modalQuery(addForm, "formModal-title");
            if (modalTitleEl) modalTitleEl.textContent = "Modifier une catégorie";

            setForm(addForm, 1, categorieId);
            const fc = getModalFormContent(addForm);
            if (fc) {
                const divBtn = creerElement("div", {classes:["flex", "justify-between"]});

                const updateBtn = creerElement("button", {
                    attrs: {
                        type: "button",
                        id: "updateCategorie",
                        "data-id": categorieId
                    },
                    classes: ["bg-blue-500", "text-white", "px-4", "py-2", "rounded", "mt-2", "mt-[5%]", "w-4/5"],
                    text: "Enregistrer"
                });

                divBtn.appendChild(updateBtn);

                const supBtn = creerElement("button", {
                    attrs: {
                        type: "button",
                        id: "supprimerCategorie",
                        "data-id": categorieId
                    },
                    classes: ["bg-red-500", "text-white", "px-4", "py-2", "rounded", "mt-2", "mt-[5%]", "w-fit"]
                });

                supBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-700 m-auto"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                        <path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>`;

                divBtn.appendChild(supBtn);
                fc.appendChild(divBtn);


                supBtn.addEventListener("click", supprimer);
                updateBtn.addEventListener("click", saveCategorie);

                autoFillCategorieInModal(addForm, categorieId);
            }
            openExerciceForm(addForm);
            return;
        }

        if (source.id === "modifierExercice" || source.classList && source.classList.contains("modifierExerciceBtn")) {
            if (!editForm) return;
            setEditFormValues(exercice);
            openExerciceForm(editForm);
            return;
        }
        toastr.error("Une erreur est survenue.");
    }

    function autoFillCategorieInModal(modal, id) {
        const categorie = categories.find(c => c.id == id);
        if (!categorie || !modal) return;
        const nomInput = modalQuery(modal, "nom_categorie");
        const ordreSel = modalQuery(modal, "ordre_categorie");
        if (nomInput) nomInput.value = categorie.nom_categorie;
        if (ordreSel) ordreSel.value = categorie.ordre_affichage;
    }

    function setEditFormValues(ex) {
        if (!editForm || !ex) return;
        modalQuery(editForm, "exerciceId")?.setAttribute("value", ex.id);
        const nom = modalQuery(editForm, "nom_exercice"); if (nom) nom.value = ex.nom_exercice || "";
        const texte = modalQuery(editForm, "texte"); if (texte) texte.value = ex.texte || "";
        const lien = modalQuery(editForm, "lien"); if (lien) lien.value = ex.lien || "";
        const select = document.getElementById("ordre_affichage");
        if (!select) return;

        select.innerHTML = "";

        const exercicesMemeCategorie = [];
        categories.forEach(c => {
            if (c.id == exercice.id_categorie && c.exercices) {
                c.exercices.forEach(e => exercicesMemeCategorie.push(e));
            }
        });

        exercicesMemeCategorie.sort((a, b) => a.ordre_affichage - b.ordre_affichage);

        let index = 1;
        const currentOrdre = ex.ordre_affichage;
        exercicesMemeCategorie.forEach(ex => {
            const opt = creerElement("option", {attrs: { value: ex.ordre_affichage }, text: index.toString()});
            if (currentOrdre && ex.ordre_affichage == currentOrdre) opt.selected = true;
            select.appendChild(opt);
            index++;
        });

        const optNew = creerElement("option", {attrs: { value: index }, text: index.toString()});
        if (!currentOrdre) optNew.selected = true;
        select.appendChild(optNew);

        const ordre = modalQuery(editForm, "ordre_affichage");
        if (ordre) ordre.value = ex.ordre_affichage || "";

        const btnEnregistrer = document.getElementById("enregistrerModif");
        if (btnEnregistrer) btnEnregistrer.dataset.id = ex.id;

        const btnSupprimer = document.getElementById("supprimerExercice");
        if (btnSupprimer) btnSupprimer.dataset.id = ex.id;
    }

    function setForm(modal, task, id){
        const formContent = getModalFormContent(modal);
        if (!formContent) return;
        formContent.innerHTML = "";
        switch(task){
            case 1: //catégorie
                formContent.innerHTML = `<label for="nom_categorie">Nom de l'exercice</label>
                <input class="text-black" type="text" placeholder="Nom" name="nom_categorie" id="nom_categorie">
                <p class="text-red-500 hidden" id="formErrorNomCat">Charactères spéciaux non-acceptés.</p>
                <label for="ordre_affichage_cat">Ordre d'affichage:</label>`;

                const selectCategorie = creerElement("select", {classes: ["text-black", "border", "rounded", "p-1", "w-full"], attrs: { id: "ordre_categorie", name: "ordre_categorie" }});
                formContent.appendChild(selectCategorie);

                index=1;
                categories.forEach((categorie) => {
                    const opt = creerElement("option", {attrs: { value: categorie.ordre_affichage }, text: index.toString()});
                    selectCategorie.appendChild(opt);
                    index++;
                });

                if (id==0){
                    const newOpt = creerElement("option", {attrs: { value: maxOrdreCategorie + 1 }, text: index.toString()});
                    newOpt.selected = true;
                    selectCategorie.appendChild(newOpt);
                }
                else{
                    const categorie = categories.find(c => c.id == id);
                    if (categorie) selectCategorie.value=categorie.ordre_affichage;
                }
                formContent.append(selectCategorie);
                break;

            case 2:
                const exercices_plus_recents=triOrdreAffichage(id);
                formContent.innerHTML=`<input type="hidden" name="id_categorie" id="id_categorie" value=${id}>
                <label for="nom_exercice">Nom de l'exercice</label>
                <input class="text-black" type="text" placeholder="Nom" name="nom_exercice" id="nom_exercice">
                <p class="text-red-500 hidden" id="formErrorNom">Charactères spéciaux non-acceptés.</p>

                <label for="texte">Description (facultatif)</label>
                <input class="text-black" type="text" placeholder="Description" name="texte" id="texte">
                <p class="text-red-500 hidden" id="formErrorDesc">Charactères spéciaux non-acceptés.</p>

                <label for="image">Sélectionner une image de couverture:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <p class="text-red-500 hidden" id="formErrorImage">Le fichier doit être une image.</p>

                <label for="fichier">Sélectionner un fichier à inclure (facultatif)</label>
                <input type="file" id="fichier" name="fichier" accept="image/*,video/*,.pdf">
                <p class="text-red-500 hidden" id="formErrorFichier">Le fichier doit être une image, vidéo ou PDF.</p>

                <label for="lien">Lien vers une page externe(facultatif)</label>
                <input class="text-black" type="text" placeholder="exemple.com" name="lien" id="lien">
                <p class="text-red-500 hidden" id="formErrorLien">Le lien doit respecter un format classique.</p>

                <label for="ordre_affichage">Ordre d'affichage:</label>`;

                const select = creerElement("select", {classes: ["text-black", "border", "rounded", "p-1", "w-full"], attrs: { id: "ordre_affichage", name: "ordre_affichage" }});

                index=1;
                exercices_plus_recents.forEach(ex => {
                    select.append(creerElement("option", {attrs: { value: ex.ordre_affichage }, text: index.toString() }));
                    index++;
                });

                const newOpt = creerElement("option", {attrs: { value: maxOrdreExercice + 1 }, text: index.toString()});
                select.appendChild(newOpt);
                select.value = newOpt.value;

                formContent.appendChild(select);
                break;
            default:
                break;
        }
    }

    function validateData(modal = addForm, requireImage=true){
        const fichierEl = modalQuery(modal, "fichier");
        const imageEl = modalQuery(modal, "image");
          let ok = true;

        if (fichierEl && fichierEl.files[0] && !checkFileFormat()) ok = false;
        if (imageEl && !checkImageFormat(requireImage)) ok = false;

        const regexNotAccepted = /['`_=@<>"\/\\^\*\[\]\{\}\|#]/;
        const nom = modalQuery(modal, "nom_exercice") ? modalQuery(modal, "nom_exercice").value : "";
        const texte = modalQuery(modal, "texte") ? modalQuery(modal, "texte").value : "";
        const lien = modalQuery(modal, "lien") ? modalQuery(modal, "lien").value : "";
        const formErrorNom = modalQuery(modal, "formErrorNom");
        const formErrorDesc = modalQuery(modal, "formErrorDesc");
        const formErrorLien = modalQuery(modal, "formErrorLien");


        if (nom === "") {
            if (formErrorNom) {
                formErrorNom.textContent = "Le nom ne peut pas être vide.";
                formErrorNom.classList.remove("hidden");
            }
            ok = false;
        } else if (regexNotAccepted.test(nom)) {
            if (formErrorNom) {
                formErrorNom.textContent = "Le nom ne peut pas contenir de caractères spéciaux.";
                formErrorNom.classList.remove("hidden");
            }
            ok = false;
        }

        if (regexNotAccepted.test(texte)) {
            if (formErrorDesc) {
                formErrorDesc.textContent = "La description ne peut pas contenir de caractères spéciaux.";
                formErrorDesc.classList.remove("hidden");
            }
            ok = false;
        }

        const regexLien = /^(https?:\/\/)?([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})(:[0-9]+)?(\/[^\s]*)?$/;
        if (lien) {
            if (!regexLien.test(lien)) {
                if (formErrorLien) {
                    formErrorLien.textContent = "Le lien est invalide.";
                    formErrorLien.classList.remove("hidden");
                }
                ok = false;
            }
        }
        return ok;
    }

    function saveExercice(evt){
        evt?.preventDefault?.();
        const modal = addForm;
        if(!validateData(modal, true)) return;
        storeExercice(modal);
    }

    async function storeExercice(modal){
        const nomEx = modalQuery(modal, "nom_exercice") ? modalQuery(modal, "nom_exercice").value : "";
        const forumData = {
            nom_forum: nomEx,
            membres: []
        };
        let forumId;
        try {
            const response = await fetch(`/exercice/forums/store`, {
                method:"POST",
                headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content},
                body: JSON.stringify(forumData)
            });
            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur d'enregistrement: ", error);
                return;
            }
            const data = await response.json();
            forumId=data.id;
            toastr.success("Liaison de l'exercice à un forum réussie.");
        } catch (e) {
            toastr.error('Erreur lors de la création du forum:', e);
            return;
        }

        const formEl = getModalForm(modal);
        if (!formEl) {
            toastr.error("Une erreur est survenue dans la récupération du formulaire.");
            return;
        }
        const formData=new FormData(formEl);
        formData.append("id_forum", forumId);
        try {
            const response = await fetch("/exercice/store", {
                method: "POST",
                headers: {"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content},
                body: formData
            });
            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur d'enregistrement: ", error);
                return;
            }
            toastr.success("L'exercice a bien été enregistré!")
            closeModal(modal);
            reload();
        } catch (e) {
            toastr.error('Erreur lors de la création de l\'exercice:', e);
            return;
        }
    }

    function saveCategorie(evt){
        evt?.preventDefault?.();
        const modal = addForm;
        if (!modal) return;
        const nomInput = modalQuery(modal, "nom_categorie");
        const formErrorNom = modalQuery(modal, "formErrorNomCat");
        const regexNotAccepted = /['`_=@<>"\/\\^\*\[\]\{\}\|#]/;
        const nom = nomInput ? nomInput.value : "";

        if (nom === "") {
            if (formErrorNom){
                formErrorNom.textContent = "Le nom ne peut pas être vide.";
                formErrorNom.classList.remove("hidden");
            }
            return false;
        }
        else if (regexNotAccepted.test(nom)){
            if (formErrorNom){
                formErrorNom.textContent = "Le nom ne peut pas contenir de caractères spéciaux.";
                formErrorNom.classList.remove("hidden");
            }
            return false;
        }
        const targetId = evt?.target?.id;
        if (targetId === "saveCategorie") {
            storeCategorie(modal);
        } else if (targetId === "updateCategorie") {
            const catId = evt.target.dataset?.id;
            updateCategorie(modal, catId);
        }
    }

    async function storeCategorie(modal) {
        const nom = modalQuery(modal, "nom_categorie") ? modalQuery(modal, "nom_categorie").value : "";
        const ordre = modalQuery(modal, "ordre_categorie") ? modalQuery(modal, "ordre_categorie").value : "";
        const formData = { nom_categorie: nom, ordre_affichage: ordre };
        try {
            const response = await fetch("/exercices/categorie/store", {
                method: "POST",
                headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content},
                body: JSON.stringify(formData)
            });
            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur d'enregistrement: ", error);
                return;
            }
            toastr.success("La catégorie d'entrainement a bien été enregistrée!");
            closeModal(addForm);
            reload();
        } catch (e) {
            WebTransportBidirectionalStream.error('Erreur lors de la création de la catégorie:', e);
            return;
        }
    }

    async function updateCategorie(modal, categorie_id) {
        const nom = modalQuery(modal, "nom_categorie") ? modalQuery(modal, "nom_categorie").value : "";
        const ordre = modalQuery(modal, "ordre_categorie") ? modalQuery(modal, "ordre_categorie").value : "";
        const formData = {
            nom_categorie: nom,
            ordre_affichage: ordre,
            id: categorie_id
        };
        try {
            const response = await fetch(`/exercices/categorie/update/${encodeURIComponent(categorie_id)}`, {
                method:"PUT",
                headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content},
                body: JSON.stringify(formData)
            });
            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur de mise à jour: ", error);
            }
            toastr.success("La catégorie mise à jour avec succès!")
            closeModal(addForm);
            reload();
        } catch (e) {
            toastr.error('Erreur lors de la mise à jour de la catégorie:', e);
        }
    }

    function supprimer(evt){
        const btn = evt.currentTarget;
        if (!btn) return;
        const id = btn.dataset?.id;
        if (!id) return;
        const categorie = categories.find(c => c.id == id);
        let message = "";
        if (categorie && categorie.exercices.length != 0) {
            message = "Cette catégorie contient des exercices. Souhaitez-vous vraiment supprimer cette catégorie et tous les exercices qui s'y trouvent?";
        } else {
            message = "Souhaitez-vous supprimer cette catégorie? Elle ne contient pas d'exercices.";
        }
            Swal.fire({
                title: "Confirmation",
                text: message,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Supprimer",
                cancelButtonText: "Annuler",
                color: "#f9fafb",
                background: "rgba(1, 1, 1, 0.6)",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "rgba(93, 87, 87, 1)",
                customClass: {
                    popup: "rounded-lg shadow-lg backdrop-blur",
                },
                didOpen: (popup) => {
                    popup.style.border = "1px solid rgba(255, 255, 255, 0.2)";
                    popup.style.backdropFilter = "blur(10px)";
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteCategorie(id);
                } else {
                    return;
                }
            });

    }

    async function deleteCategorie(id) {
        try {
            const response = await fetch(`/exercices/categorie/destroy`, {
                method:"DELETE",
                headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,},
                body:JSON.stringify({id})
            });
            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur: ", error);
            }
            toastr.success("La catégorie a bien été supprimée!");
            reload();
        } catch (e) {
            toastr.error('Erreur lors de la suppression de la catégorie:', e);
        }
    }

    function display(exerciceId){
        exercice=null;
        for (const cat of categories) {
            const found = cat.exercices.find(e => e.id == exerciceId);
            if (found) {
                exercice = found;
                break;
            }
        }
        displayExercice();

        if (window.refreshMessages) {
            window.refreshMessages(exercice.forum.id);
            return;
        }

        document.addEventListener('forumsReady', () => {
            window.refreshMessages(exercice.forum.id);
        });
    }

    function displayExercice(){
        const div = document.getElementById("exercice");
        if (!div) return;
        div.innerHTML="";
        if (!exercice){
            toggleExercice(false);
            return;
        }
        toggleExercice();

        const divChild = creerElement("div", {classes: ["flex", "flex-col", "items-center"]});
        const h = creerElement("h2", {classes: ["font-semibold", "text-3xl", "text-gray-50", "leading-tight", "pb-10"], text: exercice.nom_exercice});

        divChild.appendChild(h);

        if (exercice.fichier) divChild.appendChild(treatFile(exercice.fichier));

        div.appendChild(divChild);

        const divExercice = creerElement("div", {classes: ["flex", "flex-col", "items-center", "justify-center"]});

        const texte = creerElement("p", {classes: ["border", "p-3", "rounded", "mt-5"], text: exercice.texte});
        divExercice.appendChild(texte);

        if (exercice.lien){
            const lien = creerElement("a", {classes: ["text-blue-500", "underline",], text: exercice.lien.trim(), attrs: {href: /^https?:\/\//i.test(exercice.lien)? exercice.lien.trim(): "https://" + exercice.lien.trim(), target: "_blank", rel: "noopener noreferrer"}});
            divExercice.appendChild(lien);
        }

        div.appendChild(divExercice);

        if (div) {
            div.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }
    }

    function toggleExercice(open = true) {
        const liste = document.getElementById("categoriesPanel");
        const include = document.getElementById("include");
        const alreadyOpen = !include.classList.contains("opacity-0");

        if (open) {
            include.classList.remove("opacity-0", "scale-95", "flex-[0_1_0%]");
            include.classList.add("opacity-100", "scale-100", "flex-[2_1_66%]");

            liste.classList.remove("flex-[1_1_100%]");
            liste.classList.add("flex-[1_1_33%]");

            if (!alreadyOpen) {
                setTimeout(adjustAllExerciceLists, 50);
                setTimeout(() => {
                    const openList = document.querySelector(".exerciceList[style*='max-height']");
                    adjustExerciceListHeight(openList);
                }, 50);
            }
        } else {
            include.classList.remove("opacity-100", "scale-100", "flex-[2_1_66%]");
            include.classList.add("opacity-0", "scale-95", "flex-[0_1_0%]");
            setTimeout(adjustAllExerciceLists, 100);
            setTimeout(() => {
                const openList = document.querySelector(".exerciceList[style*='max-height']");
                if (openList) applyGridColumns(openList);
                liste.classList.remove("flex-[1_1_33%]");
                liste.classList.add("flex-[1_1_100%]");
            }, 50);
        }

    }

    function isCompact() {
        const liste = document.getElementById("categoriesPanel");
        return liste && liste.classList.contains("flex-[1_1_33%]");
    }

    function applyGridColumns(divList) {
        if (!divList) return;

        divList.classList.add("shrinking");

        setTimeout(() => {
            divList.classList.remove(
                "grid-cols-1",
                "sm:grid-cols-2",
                "lg:grid-cols-3",
                "xl:grid-cols-4"
            );

            if (isCompact() && window.innerWidth >= 1024) {
                divList.classList.add("grid-cols-1");
            } else {
                divList.classList.add("sm:grid-cols-2", "lg:grid-cols-3", "xl:grid-cols-4");
            }

            requestAnimationFrame(() => {
                adjustExerciceListHeight(divList);
                setTimeout(() => divList.classList.remove("shrinking"), 400);
            });
        }, 400);
    }

    function adjustAllExerciceLists() {
        document.querySelectorAll(".exerciceList").forEach(div => applyGridColumns(div));
    }

    function adjustExerciceListHeight(divList) {
        const isExpanded = parseInt(getComputedStyle(divList).maxHeight) > 0;
        if (!isExpanded) return;

        divList.style.maxHeight = "none";
        const fullHeight = divList.scrollHeight + "px";
        divList.style.maxHeight = fullHeight;
    }

    document.querySelector("#include .closeExercice")?.addEventListener("click", () => {
        toggleExercice(false);
    });

    window.addEventListener("resize", () => {
        const openList = document.querySelector(".exerciceList[style*='max-height']");
        if (openList && parseInt(openList.style.maxHeight) > 0) {
            adjustExerciceListHeight(openList);
        }
    });

    async function updateExercice(exerciceData) {
        try {
            const response = await fetch(`/exercice/update`, {
                method:"POST",
                headers: {"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content},
                body: exerciceData
            });
            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur de mise à jour: ", error);
            }
            else{
                toastr.success("L'exercice a été mis à jour avec succès!");
                closeModal(editForm);
                reload();
            }
        } catch (e) {
            toastr.error('Erreur lors de la mise à jour de l\'exercice:', e);
        }
    }

    async function deleteExercice(id) {
        try {
            const response = await fetch(`/exercice/destroy`, {
                method:"DELETE",
                headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,},
                body:JSON.stringify({id})
            });
            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur: ", error);
            }
            toastr.success("L'exercice a bien été supprimé!");
            exercice=null;
            reload();
        } catch (e) {
            toastr.error('Erreur lors de la suppression de l\'exercice:', e);
        }
    }

});
