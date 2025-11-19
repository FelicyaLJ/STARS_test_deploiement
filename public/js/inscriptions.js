document.addEventListener("DOMContentLoaded", () => {
    const deployFiltre = document.getElementById("deployFiltre");
    const divToDeploy = document.getElementById("contentFiltre");
    const resetBtn = document.getElementById("reset");
    const rechercheActivite = document.getElementById("rechercheActivite");
    const champTri = document.getElementById("tri");
    const listeActivites = document.getElementById("listeActivites");
    const btnAjouter = document.getElementById("ajoutActivite");
    const activiteModal = document.getElementById("activiteModal");
    const evenement_a_prix_add = document.getElementById("si_evenement_prix_add");
    const input_prix_evenement_add = document.getElementById("prix_evenement_add");
    const nomActivite = document.getElementById("nomActivite");
    const descriptionEquipe = document.getElementById("descriptionEquipe");
    const dateAdd = document.getElementById("date_add");
    const heureDebut = document.getElementById("heure_debut_add");
    const heureFin = document.getElementById("heure_fin_add");
    const categorieAdd = document.getElementById("categorie_equipe_add");
    const bulle_evenement_prix_add = document.getElementById("bulle_evenement_prix_add");
    const btnSave = document.getElementById("enregistrer");
    const formErrorNom = document.getElementById("formErrorNomActivite");
    const formErrorDescription = document.getElementById("formErrorDescription");
    const formErrorDate = document.getElementById("formErrorDate");
    const formErrorDebut = document.getElementById("formErrorDebut");
    const formErrorFin = document.getElementById("formErrorFin");
    const formErrorCout = document.getElementById("formErrorCout");
    const formErrorCategorie = document.getElementById("formErrorCategorie");
    const btnSupprimer = document.getElementById("supprimer");
    const btnEmail = document.getElementById("envoiInscription");
    const rechercheNom = document.getElementById("rechercheMembre");

    if (rechercheNom) rechercheNom.addEventListener("input", search);

    champTri.addEventListener("change", () => trier(champTri.value));
    rechercheActivite.addEventListener("input", () => filtrer(rechercheActivite.value));
    resetBtn.addEventListener("click", reset);
    if (btnAjouter) btnAjouter.addEventListener("click", ()=> popupForm(activiteModal));
    if (btnSupprimer) btnSupprimer.addEventListener("click", supprimer);
    btnEmail.addEventListener("click", validateInscription);

    if (evenement_a_prix_add) {
        evenement_a_prix_add.addEventListener("change", () => {
            handle_prix_switch(evenement_a_prix_add, input_prix_evenement_add, bulle_evenement_prix_add);
        });
    }

    if (btnSave) btnSave.addEventListener("click", enregistrer);

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

    const activitesMap = new Map();

    equipes.forEach(equipe => {
        const nom  = equipe.nom_equipe;
        activitesMap.set(nom, {nom, equipe, evenement: null});
    });

    evenements.forEach(evnt => {
        const nom = evnt.nom_evenement;
        if (activitesMap.has(nom)){
            activitesMap.get(nom).evenement = evnt;
        } else{
            Swal.fire({
                title: "Erreur",
                text: `Les activités locales n'ont pas pu être chargées`,
                icon: "warning",
                color: "#f9fafb",
                background: "rgba(1, 1, 1, 0.6)",
                confirmButtonColor: "#f06f6fff",
                customClass: {
                    popup: "rounded-lg shadow-lg backdrop-blur",
                },
                didOpen: (popup) => {
                    popup.style.border = "1px solid rgba(255, 255, 255, 0.2)";
                    popup.style.backdropFilter = "blur(10px)";
                },
            });
        }
    });

    const activites = Array.from(activitesMap.values());

    trier("0");

    function creerElement(tag, options={}){
        const el = document.createElement(tag);
        if (options.classes) el.classList.add(...options.classes);
        if (options.attrs) Object.entries(options.attrs).forEach(([k,v])=> el.setAttribute(k,v));
        if (options.text) el.textContent = options.text;
        if (options.html) el.innerHTML = options.html;
        if (options.dataset) Object.entries(options.dataset).forEach(([k, v]) => {el.dataset[k] = v;});
        return el;
    }

    function reset(){
        if (rechercheActivite) rechercheActivite.value = "";
        if (champTri) champTri.value = "";
        trier("0");
        filtrer("");
        document.getElementById("noResultsMsg").classList.add("hidden");
    }

    function trier(tri){
        switch(tri){
            case "0"://antichronologique création équipes
                activites.sort((a, b) => {
                    return b.equipe.id - a.equipe.id;
                });
                break;
            case "1"://chronologique
                activites.sort((a, b) =>{
                    return a.equipe.id - b.equipe.id;
                });
                break;
            case "2"://cout croissant
                activites.sort((a, b) =>{
                    return a.evenement.prix - b.evenement.prix;
                });
                break;
            case "3": //cout décroissant
                activites.sort((a, b) =>{
                    return b.evenement.prix - a.evenement.prix;
                });
                break;
            case "4": //catégorie d'événement
                activites.sort((a, b) =>a.evenement.categorie.nom_categorie.localeCompare(b.evenement.categorie.nom_categorie));
                break;
            case "5": //catégorie d'équipe
                activites.sort((a, b) =>a.equipe.categorie.nom_categorie.localeCompare(b.equipe.categorie.nom_categorie));
                break;
            case "6": //Nom d'activité
                activites.sort((a, b) =>a.nom.localeCompare(b.nom));
                break;
            default:
                break;
        }
        populateListeActivites();
        if (rechercheActivite.value){
            filtrer(rechercheActivite.value);
        }
    }

    function filtrer(filtre){
        document.querySelectorAll("[data-equipe-id]").forEach(div => div.classList.remove("hidden"));
        if (filtre!=""){
            filtre=filtre.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            activites.forEach(activite =>{
                let titre = activite.nom;
                titre=titre.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                if (!(titre.toLowerCase().startsWith(filtre.toLowerCase()))){
                    const div = document.querySelector(`[data-equipe-id="${activite.equipe.id}"]`);
                    if (div) div.classList.add("hidden");
                }
            });
        }
        const visible = document.querySelectorAll("#listeActivites");
        let msg = document.getElementById("noResultsMsg");
        if (visible.length === 0) {
            msg.classList.remove("hidden");
        }
        else{
            msg.classList.add("hidden");
        }
    }

    function populateListeActivites(){
        listeActivites.innerHTML=``;
        if (activites.length==0){
            const div = creerElement("div", {classes: ["border", "border-white", "p-3", "rounded"]});
            const title = creerElement("p", {classes:['text-gray-300'], text:"Aucune activité locale."});
            div.appendChild(title);
            listeActivites.appendChild(div);
        }
        else{
            activites.forEach(activite=>{
                const div = creerElement("div", {classes: ["activite", "ring-2", "ring-white", "mx-2", "transition-colors", "duration-200", "text-gray-200", "mb-4", "p-4", "rounded-lg", "cursor-pointer", "hover:bg-white/20", "hover:ring-4"], dataset: { equipeId: activite.equipe.id }});

                const titleDiv = creerElement("div", {
                    classes:["flex", "flex-row", "gap-2", "items-center", "justify-between"]
                })

                const title = creerElement("p", {classes:["font-semibold", "text-lg"], text:activite.nom});
                titleDiv.appendChild(title);

                if (canManageActivites){
                    const btn = creerElement("button", {
                        classes:["modifActivite", "transform", "transition-transform", "duration-500", "ease-[cubic-bezier(0.22,1,0.36,1)]", "hover:scale-110", "transition-colors", "hover:text-red-400"],
                        html:`<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil">
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                <path d="m15 5 4 4"/>
                                </svg>`,
                        dataset:{btnId: activite.equipe.id}
                    });
                    titleDiv.appendChild(btn);
                }

                div.appendChild(titleDiv);

                listeActivites.appendChild(div);
                div.addEventListener("click", (evt)=>{
                    display(activite.equipe.id);
                    highlightSelected(div);
                });
            });
            btnsModifier = document.getElementsByClassName("modifActivite");
            if (btnsModifier){
                for (let i = 0; i < btnsModifier.length; i++) {
                    btnsModifier[i].addEventListener("click", (evt) => {
                        evt.stopPropagation();
                        const btnId = evt.currentTarget.dataset.btnId;
                        popupForm(activiteModal, btnId);
                    });
                }
            }
        }
    }

    function autoFill(id){
        btnSupprimer.classList.remove("hidden");
        const editActivite = activites.find(a=>a.equipe.id == id)
        document.getElementById("idActivite").value=id;
        nomActivite.value = editActivite.nom;
        if (editActivite.equipe.description){
            descriptionEquipe.value = editActivite.equipe.description;
        }
        dateAdd.value = new Date(editActivite.evenement.date).toISOString().slice(0,10);
        heureDebut.value = editActivite.evenement.heure_debut.slice(0, -3);
        heureFin.value = editActivite.evenement.heure_fin.slice(0, -3);
        if (editActivite.evenement.prix){
            evenement_a_prix_add.checked=true;
            handle_prix_switch(evenement_a_prix_add, input_prix_evenement_add, bulle_evenement_prix_add);
            input_prix_evenement_add.value=editActivite.evenement.prix;
        } else {
            evenement_a_prix_add.checked = false;
            input_prix_evenement_add.value = "";
        }
        categorieAdd.value = String(editActivite.equipe.categorie.id);

        const membresContainer = document.getElementById("membres");
        membresContainer.innerHTML = "";


        editActivite.equipe.joueurs.forEach(membre=>{
            const wrapper = createMembreElement(membre);
            membresContainer.appendChild(wrapper);
        });

        return;
    }

    function highlightSelected(selectedItem) {
        document.querySelectorAll(".activite").forEach(activite => {
            activite.classList.remove(
                "bg-white/20",
                "backdrop-blur-md",
                "ring-4",
                "ring-red-400",
                "scale-[1.02]",
                "shadow-lg",
                "text-gray-50"
            );
            activite.classList.add(
                "hover:bg-white/10",
                "text-gray-200"
            );
        });

        selectedItem.classList.remove("hover:bg-white/10", "text-gray-200");
        selectedItem.classList.add(
            "bg-white/20",
            "backdrop-blur-md",
            "ring-4",
            "ring-red-400",
            "scale-[1.02]",
            "shadow-lg",
            "text-gray-50",
            "transition-all",
            "duration-300",
            "ease-[cubic-bezier(0.22,1,0.36,1)]"
        );
    }


    function openAddForm(form) {
        form.classList.remove('hidden');
        setTimeout(() => {
            form.classList.add('opacity-100');
            form.classList.add('scale-100');
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
    function reload(){
        location.reload();
    }

    function display(id_equipe){

        if (id_equipe>0){
            currentActivite = activites.find(f => f.equipe.id == id_equipe);
            const rawDate = String(currentActivite.evenement.date || "");
            const datePart = rawDate.split('T')[0];
            const [y, m, d] = datePart.split('-').map(s => parseInt(s, 10));
            let dateBase = new Date(y, m - 1, d);

            const jours = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
            const moisNoms = [
                "janvier", "février", "mars", "avril", "mai", "juin",
                "juillet", "août", "septembre", "octobre", "novembre", "décembre"
            ];

            const jourAffiche = d === 1 ? "1er" : d;
            const dateDetail = `${jourAffiche} ${moisNoms[m]} ${y}`;
            const jourSemaine = jours[dateBase.getDay()];

            let heureDebut = currentActivite.evenement.heure_debut.slice(0, 5);
            if (String(heureDebut).startsWith("0")) heureDebut = heureDebut.slice(1, 5);
            let heureFin = currentActivite.evenement.heure_fin.slice(0, 5);
            if (String(heureFin).startsWith("0")) heureFin = heureFin.slice(1, 5);


            document.getElementById("include").classList.remove("hidden");
            document.getElementById("detailDescription").textContent=currentActivite.evenement.description;
            document.getElementById("detailDateDebut").textContent=dateDetail;
            document.getElementById("jourSemaine").textContent=jourSemaine;
            document.getElementById("detailDebut").textContent=heureDebut;
            document.getElementById("detailFin").textContent=heureFin;
            document.getElementById("detailPrix").textContent=currentActivite.evenement.prix + "$";
            document.getElementById("detailCategorie").textContent=currentActivite.equipe.categorie.nom_categorie;

            document.getElementById("titrePage").textContent="Activité " + currentActivite.nom;
            document.getElementById("envoiInscription").dataset.id=id_equipe;

            if (currentActivite.equipe.deja_inscrit){
                document.getElementById("envoiInscription").classList.add("hidden");
                document.getElementById("dejaInscrit").classList.add("hidden");
            }
            else if (currentActivite.equipe.deja_demande){
                document.getElementById("envoiInscription").classList.add("hidden");
                document.getElementById("dejaInscrit").classList.remove("hidden");
            }
            else{
                document.getElementById("envoiInscription").classList.remove("hidden");
                document.getElementById("dejaInscrit").classList.add("hidden");
            }
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        }
        else {
            //toggleForum(false);
            document.getElementById("include").classList.add("hidden");
            currentActivite = null;
        }
    }

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

    function clearForm(form){
        if (form == activiteModal){
            const membresContainer = document.getElementById("membres");
            membresContainer.innerHTML = "";
            document.getElementById("erreurRechercheMembre").classList.add("hidden");
            document.getElementById("rechercheMembre").value="";
            btnSupprimer.classList.add("hidden");
            document.getElementById("idActivite").value=0;
            nomActivite.value="";
            descriptionEquipe.value="";
            dateAdd.value="";
            heureDebut.value="";
            heureFin.value="";
            evenement_a_prix_add.checked=false;
            input_prix_evenement_add.value="";
            categorieAdd.value="0";
            formErrorNom.classList.add("hidden");
            formErrorDescription.classList.add("hidden");
            formErrorDate.classList.add("hidden");
            formErrorDebut.classList.add("hidden");
            formErrorFin.classList.add("hidden");
            formErrorCout.classList.add("hidden");
            formErrorCategorie.classList.add("hidden");
        }
    }

    function popupForm(form, id) {
            clearForm(form);
            if (id){
                setTimeout(() => autoFill(id), 10);
            }
            openAddForm(form);
    }

    function dansLeFutur(inputDateStr) {
        const inputDate = new Date(inputDateStr);
        const today = new Date();

        today.setHours(0, 0, 0, 0);
        inputDate.setHours(0, 0, 0, 0);

        return inputDate >= today;
    }

    function apresHeureDebut(heureFin) {
        const [h1, m1] = String(heureDebut.value).split(':').map(Number);
        const [h2, m2] = String(heureFin.value).split(':').map(Number);

        return h2 > h1 || (h2 === h1 && m2 > m1);
    }

    async function supprimer(evt){
        const id = document.getElementById("idActivite").value;
        const activite = activites.find(a => a.equipe.id == id);
        Swal.fire({
            title: "Confirmation",
            text: "Voulez-vous vraiment supprimer l'activité \"" + activite.nom + "\" et toutes les données associées (horaire, membres, etc.)?",
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
        }).then(async (result) => {
            if (result.isConfirmed) {
                await destroyEquipe(id);
                await destroyEvenement(activite.evenement.id);
                reload();
            } else {
                return;
            }
        });
    }

    async function destroyEvenement(id){
        try {
            const response = await fetch(`/evenements/delete`, {
                method:"DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body:JSON.stringify({id})
            });
            const data = await response.json();

            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur: ", error);
            }

            toastr.success("Événement supprimée avec succès!");

        } catch (e) {
            toastr.error('Erreur lors de la suppression de l\'activité:', e);
        }
    }

    async function destroyEquipe(equipeId) {
        try {
            const response = await fetch(`/equipes/suppression/${equipeId}`, {
                method:"DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                }
            });
            const data = await response.json();

            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur: ", error);
            }

            toastr.success("Équipe supprimée avec succès!");
        } catch (e) {
            toastr.error('Erreur lors de la suppression de l\'équipe:', e);
        }
    }

    function enregistrer(){
        let regexAccepted;

        try {
            new RegExp("^[\\p{L}\\s'0-9-]+$", "u");
            regexAccepted = /^[\p{L}\s'0-9-]+$/u;
        } catch {
            regexAccepted = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'0-9-]+$/;
        }

        let ok = true;

        if (nomActivite.value === "") {
            formErrorNom.textContent = "Le nom de l'activité ne peut pas être vide.";
            formErrorNom.classList.remove("hidden");
            ok = false;
        } else if (!regexAccepted.test(nomActivite.value)) {
            formErrorNom.textContent = "Le nom de l'activité contient des caractères non autorisés.";
            formErrorNom.classList.remove("hidden");
            ok = false;
        } else if (nomActivite.value.length>255) {
            formErrorNom.textContent = "Le nom de l'activité est trop long.";
            formErrorNom.classList.remove("hidden");
            ok = false;
        } else {
            formErrorNom.classList.add("hidden");
        }

        try {
            new RegExp("^[\\p{L}\\s\\-'.!0-9,. ]+$", "u");
            regexAccepted = /^[\p{L}\s\-'.!0-9,. ]+$/u;
        } catch {
            regexAccepted = /^[A-Za-zÀ-ÖØ-öø-ÿ\s\-'.!0-9,. ]+$/;
        }

        if (!descriptionEquipe.value =="" && !regexAccepted.test(descriptionEquipe.value)){
            formErrorDescription.textContent = "La description contient des caractères non autorisés.";
            formErrorDescription.classList.remove("hidden");
            ok = false;
        } else if (descriptionEquipe.value.length>1000){
            formErrorDescription.textContent = "La description est trop longue.";
            formErrorDescription.classList.remove("hidden");
            ok = false;
        } else{
            formErrorDescription.classList.add("hidden");
        }

        if (!dateAdd.value){
            formErrorDate.textContent = "La date de l'événement ne peut pas être nulle.";
            formErrorDate.classList.remove("hidden");
            ok = false;
        } else if (!dansLeFutur(String(dateAdd.value))){
            formErrorDate.textContent = "La date ne peut pas être passée.";
            formErrorDate.classList.remove("hidden");
            ok = false;
        } else{
            formErrorDate.classList.add("hidden");
        }


        regexAccepted=/^([01]\d|2[0-3]):([0-5]\d)$/;
        if (!heureDebut.value){
            formErrorDebut.textContent = "L'heure de l'événement ne peut pas être nulle.";
            formErrorDebut.classList.remove("hidden");
            ok = false;
        } else if (!regexAccepted.test(heureDebut.value)){
            formErrorDebut.textContent = "L'heure' de l'événement est invalide.";
            formErrorDebut.classList.remove("hidden");
            ok = false;
        } else{
            formErrorDebut.classList.add("hidden");
        }

        if (!heureFin.value){
            formErrorFin.textContent = "L'heure de l'événement ne peut pas être nulle.";
            formErrorFin.classList.remove("hidden");
            ok = false;
        } else if (!regexAccepted.test(heureFin.value)){
            formErrorFin.textContent = "L'heure' de l'événement est invalide.";
            formErrorFin.classList.remove("hidden");
            ok = false;
        } else if (!apresHeureDebut(heureFin)){
            formErrorFin.textContent = "La fin de l'événement ne peut pas se produire avant le début.";
            formErrorFin.classList.remove("hidden");
            ok = false;
        } else {
            formErrorFin.classList.add("hidden");
        }

        if (categorieAdd.value=="0" || !categorieAdd.value){
            formErrorCategorie.textContent = "La catégorie de l'événement ne peut pas être nulle.";
            formErrorCategorie.classList.remove("hidden");
            ok = false;
        } else {
            formErrorCategorie.classList.add("hidden");
        }

        regexAccepted=/(^\d{1,3}[.,]\d{0,2})$|^[0]$/;
        if (input_prix_evenement_add.value){
            if (!regexAccepted.test(input_prix_evenement_add.value)){
                formErrorCout.textContent = "Le format du prix entré est invalide.";
                formErrorCout.classList.remove("hidden");
                ok = false;
            } else {
                formErrorCout.classList.add("hidden");
            }
        } else{
            formErrorFin.classList.add("hidden");
        }
        if (!ok) return;
        const membres = document.getElementsByClassName("checkboxMembre");
        const arrayMembres = Array.from(membres).map(m => m.id);


        const evenement_data = {
            nom_evenement: nomActivite.value,
            date: dateAdd.value,
            heure_debut: heureDebut.value,
            heure_fin: heureFin.value,
            prix_evenement: input_prix_evenement_add.value,
            categorie_evenement: 3,
            id_etat:4,

        };
        const equipe_data = {
            nom_equipe: nomActivite.value,
            description: descriptionEquipe.value,
            nombre_joueur: 0,
            ordre_affichage: 1,
            id_categorie: Number(categorieAdd.value),
            joueurs: arrayMembres,
            id_genre : 3,
            id_etat: 3
        };
        if (document.getElementById("idActivite").value == 0 || !document.getElementById("idActivite").value){
            store(evenement_data, equipe_data);
        }
        else{
            equipe_data.id=document.getElementById("idActivite").value;
            const activite = activites.find(a => a.equipe.id == document.getElementById("idActivite").value);
            evenement_data.id = activite.evenement.id;
            evenement_data.equipes = [equipe_data.id];
            update(evenement_data, equipe_data);
        }
    }

    async function store(evenement, equipe){
        const id_equipe = await storeEquipe(equipe);
        if (id_equipe){
            evenement.equipes = id_equipe;
            await storeEvenement(evenement);
            reload();
        }
    }

    async function update(evenement, equipe){
        await updateEquipe(equipe);
        await updateEvenement(evenement);
        reload();
    }

    async function storeEquipe(equipe){
        try {
            const response = await fetch(`/enregistrementEquipe`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(equipe)
            });
            const data = await response.json();

            if (!response.ok) {
                console.log("response equipe:", data);
                // toastr.error("Erreur d'enregistrement: ", data);
                return;
            }

            toastr.success("L'équipe a bien été ajoutée!");
            return data.equipe.id;

        } catch (e) {
            console.log("catch equipe:", e);
            // toastr.error('Erreur lors de la création de l\'équipe:', e);
        }
    }

    async function storeEvenement(evenement){
        try {
            const response = await fetch(`/evenements/create`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(evenement)
            });

            const data = await response.json();

            if (!response.ok) {
                console.log("response evenement:", data);
                // toastr.error("Erreur d'enregistrement: ", data);
                return;
            }

            toastr.success("L'événement a bien été ajouté!");

        } catch (e) {
            console.log("catch evenement:", e);
            // toastr.error('Erreur lors de la création de l\'équipe:', e);
        }
    }

    async function updateEquipe(equipe){
        try {
            const response = await fetch(`/equipes/modification/${equipe.id}`, {
                method:"PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(equipe)
            });
            if (!response.ok) {
                const error= await response.json();
                console.log("response equipe: " + error);
                //toastr.error("Erreur de mise à jour: ", error);
            }
            else{
                toastr.success("Équipe mise à jour avec succès!");
            }

        } catch (e) {
            console.log("catch equipe: " + e);
            //toastr.error('Erreur lors de la mise à jour du forum:', e);
        }
    }

    async function updateEvenement(evenement){
        try {
            const response = await fetch(`/evenements/edit`, {
                method:"POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(evenement)
            });
            if (!response.ok) {
                const error= await response.json();
                console.log("response evenement: " + error);
                //toastr.error("Erreur de mise à jour: ", error);
            }
            else{
                toastr.success("Événement mise à jour avec succès!");
            }

        } catch (e) {
            console.log("catch evenement:" + e)
            //toastr.error('Erreur lors de la mise à jour de l\'événement:', e);
        }
    }

    function validateInscription(evt){
        const activite=activites.find(a=>a.equipe.id==evt.currentTarget.dataset.id);
        Swal.fire({
            title: "Inscription à l'activité " + activite.nom,
            text: "Nous vous confirmerons sous peu votre inscription afin de procéder au paiement.",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Confirmer",
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
        }).then(async (result) => {
            if (result.isConfirmed) {
                await sendEmail(activite);
            } else {
                return;
            }
        });
    }

    async function sendEmail(activite) {
        try{
            const response = await fetch(`activites/${activite.equipe.id}/inscription`,
                    {method:"POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body:JSON.stringify(activite)
                });

                if (!response.ok) {
                    const error= await response.json();
                    toastr.error("Erreur: ", error);
                }

                toastr.success("Inscription envoyée avec succès!");

        } catch (e) {
            toastr.error('Erreur lors de l\'inscription:', e);
        }
    }

    function createMembreElement(membre) {
        const userId = membre.id;
        const userName = `${membre.prenom} ${membre.nom}`;
        const isAdmin = membre.id == 1;
        const wrapper = creerElement("div", {dataset:{membreId:userId}});

        const checkbox = creerElement("input", {
            classes: [
                "checkboxMembre",
                ...(isAdmin ? ["text-gray-400", "cursor-not-allowed", "opacity-70"] : [])
            ],
            attrs: {
                type: "checkbox",
                name: userId,
                id: userId,
                value: userId,
                checked: true,
                ...(isAdmin ? { disabled: true } : {})
            }
        });

        const label = creerElement("label", {text:userName, attrs:{for: userId}});

        checkbox.addEventListener("click", removeFromList);

        wrapper.appendChild(checkbox);
        wrapper.appendChild(label);
        return wrapper;
    }

    function addToList(option){
        let membre;
        if (option.target && option.target.dataset.ajoutId){
            membre = {
                id: option.target.dataset.ajoutId,
                prenom: option.target.textContent.split(" ")[0],
                nom: option.target.textContent.split(" ").slice(1).join(" ")
            };
        }
        else if (option.id){
            membre = option;
        }
        else return;
        if (document.querySelector(`#membres div[data-membre-id="${membre.id}"]`)) {
            toastr.error(`L'utilisateur ${membre.prenom} ${membre.nom} est déjà dans les membres du forum`);
            return;
        }
        const wrapper = createMembreElement(membre);
        document.getElementById("membres").appendChild(wrapper);

        const resultats = document.getElementById("resultatRecherche");
        resultats.innerHTML = "";
        resultats.classList.add("hidden");

        document.getElementById("rechercheMembre").value = "";
    }

    async function search(evt, search = "", filtre = 1, etat = 0, role = 0) {
        const regexNotAccepted = /[^a-zA-Z\s]/;
        if (!regexNotAccepted.test(evt.target.value)){
            try {
                const response = await fetch(`/users/search?search=${encodeURIComponent(evt.target.value)}&order=${filtre}&etat=${etat}&role=${role}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                if (!response.ok) {
                    document.getElementById("erreurRechercheMembre").classList.remove("hidden");
                }
                else{
                    document.getElementById("erreurRechercheMembre").classList.add("hidden");
                    const data = await response.json();
                    updateList(data.data);
                }
            } catch (e) {
                toastr.error('Erreur lors de la récupération des usagers:', e);
            }
        }
        else{
            document.getElementById("erreurRechercheMembre").classList.remove("hidden");
        }
    }

    function updateList(users) {
        const resultats = document.getElementById("resultatRecherche");
        resultats.innerHTML = "";

        users.forEach(user => {
            const li = document.createElement("li");
            li.classList.add("p-2", "hover:bg-gray-200", "cursor-pointer", "resultat");
            li.dataset.ajoutId = user.id;
            li.textContent = `${user.prenom} ${user.nom}`;
            li.addEventListener("click", addToList);
            resultats.appendChild(li);
        });

        resultats.classList.remove("hidden");
    }

    function removeFromList(evt){
        evt.target.parentElement.remove();
    }
});
