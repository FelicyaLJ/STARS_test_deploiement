document.addEventListener("DOMContentLoaded", () => {
    let forum = null;
    let currentForums = null;
    const regexNotAccepted = /[`_=<>"\/\\^\*\[\]\{\}\|#]/;
    const addForm = document.getElementById("forumFormModal");
    const joinForm = document.getElementById("modalDemandeForum");
    const signalForm = document.getElementById("modalSignalement");
    const listeForums = document.getElementById("listeForums");
    const btnEnvoiSignal = document.getElementById("btnSendSignalement");
    btnEnvoiSignal.addEventListener("click", signalerMessage);

    if (listeForums){
        currentForums = forums;
        const champTri = document.getElementById("tri");
        const champFiltre = document.getElementById("rechercheForum");
        const btnAjouter = document.getElementById("ajoutForum");
        const btnSave = document.getElementById("enregistrer");
        const equipes = document.getElementById("equipes");
        const resultatRecherche = document.getElementById("resultatRecherche");
        const modalTitle = document.getElementById("forumFormModal-title");
        const rechercheNom = document.getElementById("rechercheMembre");
        const deployFiltre = document.getElementById("deployFiltre");
        const divToDeploy = document.getElementById("contentFiltre");
        const resetBtn = document.getElementById("reset");
        const switchForums = document.getElementById("switch");
        const sendJoinBtn = document.getElementById("btnSendJoinRequest");
        const titreListe = document.getElementById("titreListe");
        let prevTitre = titreListe.textContent;

        let id = 0;
        trier("0");

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
        resetBtn.addEventListener("click", reset);

        switchForums.addEventListener("change", swapForums);

        if (equipes) equipes.addEventListener("change", dealWithEquipes);
        champTri.addEventListener("change", () => trier(champTri.value));
        champFiltre.addEventListener("input", () => filtrer(champFiltre.value));
        if (btnAjouter) btnAjouter.addEventListener("click", popupForm);
        if (btnSave) btnSave.addEventListener("click", enregistrer);
        if (rechercheNom) rechercheNom.addEventListener("input", search);
        if (resultatRecherche) resultatRecherche.addEventListener("change", (evt) => {
                    const option = evt.target.selectedOptions[0];
                    if (!option) return;
                    addToList(option);
                });


        function swapForums(evt){
            const val = evt.target.checked;
            toggleForum(false);
            if (val==false){
                currentForums=forums;
                titreListe.textContent = prevTitre;
            }
            else if (val == true){
                currentForums=autresForums;
                titreListe.textContent = "Rejoindre un forum";
            }
            trier(0);
        }
        function trier(tri){
            switch(tri){
                case "0"://antichronologique messages
                    currentForums.sort((a, b) => {
                        const dateA = a.last_message ? new Date(a.last_message.created_at) : new Date(0);
                        const dateB = b.last_message ? new Date(b.last_message.created_at) : new Date(0);
                        return dateB - dateA;
                    });
                    break;
                case "1"://chronologique
                    currentForums.sort((a, b) =>{
                        const dateA = new Date(a.created_at);
                        const dateB = new Date(b.created_at);
                        return dateB - dateA;
                    });
                    break;
                case "2"://antichronologique
                    currentForums.sort((a, b) =>{
                        const dateA = new Date(a.created_at);
                        const dateB = new Date(b.created_at);
                        return dateA - dateB;
                    });
                    break;
                case "3": //alphabétique
                    currentForums.sort((a, b) =>a.nom_forum.localeCompare(b.nom_forum));
                    break;
                default:
                    break;
            }
            populateListeForums();
            if (champFiltre.value){
                filtrer(champFiltre.value);
            }
        }

        function filtrer(filtre){
            if (currentForums.length!=0){
                document.querySelectorAll("[data-forum-id]").forEach(div => div.classList.remove("hidden"));
                if (filtre!=""){
                    filtre=filtre.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    for (let i = 0; i<currentForums.length; i++){
                        let titre = currentForums[i].nom_forum;
                        titre=titre.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                        if (!(titre.toLowerCase().startsWith(filtre.toLowerCase()))){
                            const div = document.querySelector(`[data-forum-id="${currentForums[i].id}"]`);
                            if (div) div.classList.add("hidden");
                        }
                    }
                }
                const visible = document.querySelectorAll("#listeForums .forum:not(.hidden)");
                let msg = document.getElementById("noResultsMsg");
                if (visible.length === 0) {
                    msg.classList.remove("hidden");
                }
                else{
                    msg.classList.add("hidden");
                }
            }
        }

        function populateListeForums(){
            listeForums.innerHTML=``;
            if (currentForums.length==0){
                const div = creerElement("div", {classes: ["bg-gray-200", "border", "border-black", "p-3", "rounded"]});
                let title;
                if (currentForums==autresForums){
                    title = creerElement("p", {classes:["font-semibold", "text-lg"], text:"Aucun forum n'est disponible pour demande d'adhésion."});
                }
                else{
                    title = creerElement("p", {classes:["font-semibold", "text-lg"], text:"Vous ne faites partie d'aucun forum."});
                }

                div.appendChild(title);
                listeForums.appendChild(div);
            }
            else {
                currentForums.forEach(forum=>{
                    const div = creerElement("div", {classes: ["forum", "ring-2", "ring-white", "mx-2", "transition", "duration-200", "text-gray-200", "mb-4", "p-4", "rounded-lg", "cursor-pointer", "hover:bg-white/20", "hover:ring-4"], dataset: { forumId: forum.id }});

                    const titleDiv = creerElement("div", {
                        classes:["flex", "flex-row", "gap-2", "items-center", "justify-between"]
                    })

                    const title = creerElement("p", {classes:["font-semibold", "text-lg"], text:forum.nom_forum});
                    titleDiv.appendChild(title);

                    if (canManageForums){
                        const btn = creerElement("button", {
                            classes:["modifForum", "transform", "transition-transform", "duration-500", "ease-[cubic-bezier(0.22,1,0.36,1)]", "hover:scale-110", "transition", "hover:text-red-400"],
                            html:`<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil">
                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                                    <path d="m15 5 4 4"/>
                                  </svg>`,
                            dataset:{btnId: forum.id}
                        });
                        titleDiv.appendChild(btn);
                    }

                    div.appendChild(titleDiv);

                    if (forum.last_message && currentForums !== autresForums){

                        const dateHeure = new Date(forum.last_message.created_at);
                        const year = dateHeure.getFullYear();
                        const month = String(dateHeure.getMonth()+1).padStart(2, '0');
                        const day = String(dateHeure.getDate()).padStart(2, "0");
                        const dateFormattee = `${year}-${month}-${day}`;

                        const hours = String(dateHeure.getHours()).padStart(2, '0');
                        const minutes = String(dateHeure.getMinutes()).padStart(2, '0');
                        const heureFormattee = `${hours}:${minutes}`;

                        const last_message_div = creerElement("div", {
                            classes:["mx-4"]
                        })

                        const last_message_user = creerElement("p", {
                            text: `${forum.last_message.user.prenom} ${forum.last_message.user.nom}`,
                            classes:["font-semibold", "underline"]
                        });
                        last_message_div.appendChild(last_message_user);

                        const last_message = creerElement("p", {
                            text: `${forum.last_message.texte}`,
                            classes:["text-gray-100", "truncate"]
                        });
                        last_message_div.appendChild(last_message);

                        const heure_last_message = creerElement("p", {
                            classes:["text-xs", "text-gray-300"],
                            text: `${dateFormattee}, ${heureFormattee}`
                        })
                        last_message_div.appendChild(heure_last_message);
                        div.appendChild(last_message_div);

                    }
                    else if (currentForums == autresForums){
                        const messageAdhesion = creerElement("p", {text:"Cliquez pour envoyer une demande d'adhésion."});
                        div.appendChild(messageAdhesion);
                    }

                    listeForums.appendChild(div);
                    div.addEventListener("click", (evt)=>{
                        display(forum.id);
                        highlightSelected(div);

                        const forumId = div.getAttribute('data-forum-id');
                        sendJoinBtn.dataset.forumId = forumId;

                        if (currentForums === autresForums) {
                            openForumJoinRequestForm();
                        }
                    });
                });
                btnsModifier = document.getElementsByClassName("modifForum");
                if (btnsModifier){
                    for (let i = 0; i < btnsModifier.length; i++) {
                        btnsModifier[i].addEventListener("click", (evt) => {
                            evt.stopPropagation();
                            popupForm(evt);
                        });
                    }
                }
            }
        }

        function reset(){
            if (champTri) champTri.value = "0";
            trier("0");
            if (champFiltre) champFiltre.value = "";
            filtrer("");
            document.getElementById("noResultsMsg").classList.add("hidden");
        }

        function highlightSelected(selectedItem) {
            document.querySelectorAll(".forum").forEach(forum => {
                forum.classList.remove(
                    "bg-white/20",
                    "backdrop-blur-md",
                    "ring-4",
                    "ring-red-400",
                    "scale-[1.02]",
                    "shadow-lg",
                    "text-gray-50"
                );
                forum.classList.add(
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

        function popupForm(evt) {
            clearForm();
            openForumAddForm();

            if(evt.currentTarget.classList.contains("modifForum")){
                const supBtn = creerElement("button", {
                    attrs: {
                        type: "button",
                        id: "supprimer"
                    },
                    classes: ["bg-red-500", "text-white", "px-4", "py-2", "rounded", "mt-2", "mt-[5%]", "w-fit"]
                });

                supBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="flex group lucide lucide-plus-icon lucide-plus group-hover:stroke-red-700 m-auto"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                        <path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>`;

                document.getElementById("divBtn").appendChild(supBtn);
                const btnSupprimer = document.getElementById("supprimer");
                btnSupprimer.addEventListener("click", supprimer);
                id = evt.currentTarget.dataset.btnId;
                autoFill(id);
                modalTitle.textContent = "Modifier un forum";
            }
            else{
                modalTitle.textContent = "Ajouter un forum";
                id=0;
            }
        }

        function dealWithEquipes(evt){
            Swal.fire({
                title: "Équipes",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Remplacer les membres actuels",
                cancelButtonText: "Ajouter aux membres actuels",
                color: "#f9fafb",
                background: "rgba(1, 1, 1, 0.6)",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "rgba(132, 205, 128, 1)",
                customClass: {
                    popup: "rounded-lg shadow-lg backdrop-blur",
                },
                didOpen: (popup) => {
                    popup.style.border = "1px solid rgba(255, 255, 255, 0.2)";
                    popup.style.backdropFilter = "blur(10px)";
                },
            }).then((result) => {
                if (result.isConfirmed) viderListeMembres();
                if (evt.target.value != 0){
                    getJoueurs(evt.target.value)
                .then(joueurs => {
                    if (Array.isArray(joueurs) && joueurs.length > 0) {
                        joueurs
                        .filter(joueur => joueur.id != 1)
                        .forEach(joueur => addToList(joueur));
                    } else {
                        toastr.warning("Aucun joueur trouvé dans cette équipe.");
                    }
                })
                .catch(err => {
                    toastr.error("Erreur lors du chargement des joueurs.");
                });
                }
            });

        }

        function viderListeMembres() {
            const membresContainer = document.getElementById("membres");
            const membres = membresContainer.querySelectorAll("div[data-membre-id]");

            membres.forEach(membreDiv => {
                if (membreDiv.dataset.membreId !== "1") {
                    membreDiv.remove();
                }
            });
        }

        function autoFill(id){
            const forum = currentForums.find(f => f.id == id);
            if (forum){
                document.getElementById("nomForum").value=forum.nom_forum;
                const membresContainer = document.getElementById("membres");
                membresContainer.innerHTML = "";


                forum.membres.forEach(membre=>{
                    const wrapper = createMembreElement(membre);
                    membresContainer.appendChild(wrapper);
                });
            }
        }

        function removeFromList(evt){
            evt.target.parentElement.remove();
        }

        function enregistrer(){
            const nomForum = document.getElementById("nomForum");
            const nomValue = nomForum.value.trim();
            const formErrorNom = document.getElementById("formErrorNomForum");
            const formErrorMembres = document.getElementById("formErrorMembres");
            const membres = document.getElementsByClassName("checkboxMembre");
            const regexNotAccepted = /['`_=@<>"\/\\^\*\[\]\{\}\|#]/;

            let ok = true;

            const arrayMembres = Array.from(membres).map(m => m.id);
            if (arrayMembres.length < 2) {
                formErrorMembres.textContent = "Un forum doit être composé d'au moins deux membres.";
                formErrorMembres.classList.remove("hidden");
                ok = false;
            }
            const modIds = mods.map(m => String(m.id));
            const allModsPresent = modIds.every(id => arrayMembres.includes(id));
            if (!allModsPresent) {
                formErrorMembres.textContent = "Tous les modérateurs doivent être inclus dans le forum.";
                ok = false;
            } else if (ok) {
                formErrorMembres.classList.add("hidden");
            }

            if (nomValue === "") {
                formErrorNom.textContent = "Le nom du forum ne peut pas être vide.";
                formErrorNom.classList.remove("hidden");
                ok = false;
            } else if (regexNotAccepted.test(nomValue)) {
                formErrorNom.textContent = "Le nom du forum contient des caractères non autorisés.";
                formErrorNom.classList.remove("hidden");
                ok = false;
            } else {
                formErrorNom.classList.add("hidden");
            }

            if (!ok) return;

            const forumData={
                nom_forum: document.getElementById("nomForum").value,
                membres: arrayMembres,
            }
            if (id == 0){
                storeForum(forumData);
            }
            else{
                forumData.id=id;
                update(forumData);
            }
        }

        function clearForm(){
            const membresContainer = document.getElementById("membres");
            membresContainer.innerHTML = "";
            mods.forEach(m=>{
                addToList({id: m.id, prenom: m.prenom, nom:m.nom});
            })
            document.getElementById("nomForum").value=null;
            document.getElementById("erreurRechercheMembre").classList.add("hidden");
            document.getElementById("rechercheMembre").value="";
            const button = document.getElementById("supprimer");
            if (button) button.remove();
            id=0;
        }

        async function storeForum(forumData){
            try {
                const response = await fetch(`/forums/store`, {
                    method:"POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(forumData)
                });

                if (!response.ok) {
                    const error= await response.json();
                    toastr.error("Erreur d'enregistrement: ", error);
                }
                else{
                    toastr.success("Le forum a bien été ajouté!");
                    closeModal(addForm);
                    reload();
                }

            } catch (e) {
                toastr.error('Erreur lors de la création du forum:', e);
            }
        }

        async function update(forumData){
            try {
                const response = await fetch(`/forums/update/${encodeURIComponent(forumData.id)}`, {
                    method:"PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(forumData)
                });
                if (!response.ok) {
                    const error= await response.json();
                    toastr.error("Erreur de mise à jour: ", error);
                }
                else{
                    toastr.success("Forum mis à jour avec succès!");
                    closeModal(addForm);
                    reload();
                }

            } catch (e) {
                toastr.error('Erreur lors de la mise à jour du forum:', e);
            }
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

        async function getJoueurs(equipeId){
            const response = await fetch(`/equipes/${equipeId}/joueurs`);
            if (!response.ok) {
                toastr.error("Erreur lors de la récupération des joueurs");
            }
            const data = await response.json();
            return data;
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

        function createMembreElement(membre) {
            const userId = membre.id;
            const userName = `${membre.prenom} ${membre.nom}`;
            const isAdmin = mods.some(mod => mod.id == membre.id);
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
                toastr.error(`L'utilisateur ${membre.prenom} ${membre.nom} est déjà membre du forum`);
                return;
            }
            const wrapper = createMembreElement(membre);
            document.getElementById("membres").appendChild(wrapper);

            const resultats = document.getElementById("resultatRecherche");
            resultats.innerHTML = "";
            resultats.classList.add("hidden");

            document.getElementById("rechercheMembre").value = "";
        }

        function supprimer(){
            Swal.fire({
                title: "Confirmation",
                text: "Voulez-vous vraiment supprimer ce forum et tous les messages qu'il contient?",
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
                    destroy(id);
                } else {
                    return;
                }
            });
        }

        async function destroy(id){
            try {
                const response = await fetch(`/forums/destroy`, {
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

                toastr.success("Forum supprimé avec succès!");
                reload();
            } catch (e) {
                toastr.error('Erreur lors de la suppression du forum:', e);
            }
        }

    }

    const boiteMessages = document.getElementById("boiteMessages");
      const listeMessages = document.getElementById("listeMessages");
    if (boiteMessages){
        const nouveauMessage = document.getElementById("nouveauMessage");
        const btnEnvoyer = document.getElementById("envoyerMessage");
        const form = document.getElementById("formNouveauMessage");
        const rechercheUser = document.getElementById("rechercheUser");
        const rechercheMotCle = document.getElementById("rechercheMotCle");


        btnEnvoyer.addEventListener("click", sendMessage);
        form.addEventListener("submit", (event)=>{
            event.preventDefault();
            sendMessage();
        });
        rechercheUser.addEventListener("input", rechercherUser);
        rechercheMotCle.addEventListener("input", rechercherMotCle);

        const messageInput = document.getElementById("nouveauMessage");
        const maxHeight = 80;

        messageInput.addEventListener("input", () => {
            messageInput.style.height = "auto";
            messageInput.style.height = Math.min(messageInput.scrollHeight, maxHeight) + "px";
        });

        function sendMessage(){

            const formError = document.getElementById("formError");
            const nouveauMessageValue = nouveauMessage.value.trim();

            ok = true;

            if (nouveauMessageValue === "") {
                formError.textContent = "Le message ne peut pas être vide.";
                formError.classList.remove("hidden");
                boiteMessages.scrollTop = boiteMessages.scrollHeight;
                ok = false;
            }
            if (regexNotAccepted.test(nouveauMessage.value)){
                formError.textContent = "Le message ne peut pas contenir de caractères spéciaux.";
                formError.classList.remove("hidden");
                boiteMessages.scrollTop = boiteMessages.scrollHeight;
                ok = false;
            } else if (nouveauMessage.value.length>1000){
                formErrorDescription.textContent = "La description est trop longue.";
                formErrorDescription.classList.remove("hidden");
                ok = false;
            }
            if (ok == true){
                formError.classList.add("hidden");
                messageInput.style.height = "auto";

                const message={
                    texte: nouveauMessage.value,
                    id_forum: Number(document.getElementById("forumId").value),
                }

                const reponseId = document.getElementById("reponseId");
                if(reponseId.value){
                    message.id_reponse = Number(reponseId.value);
                }

                storeMessage(message);
            }
        }

        async function storeMessage(message) {
            try {
                const response = await fetch(`/message/store`, {
                    method:"POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(message)
                });
                if (!response.ok) {
                    const error= await response.json();
                    toastr.error("Erreur d'enregistrement: ", error);
                }
                toastr.success("Le message a bien été envoyé!")
                await refreshMessages(forum.id);

            } catch (e) {
                toastr.error('Erreur lors de l\'ajout du message');
            }
        }



        function addResponse(id){
            document.getElementById("responseArea").classList.remove("hidden");
            const message = forum.messages.find(m => m.id == id);
            document.getElementById("reponseNom").innerHTML="@" + message.user.prenom + " " + message.user.nom;
            document.getElementById("reponseId").value=id;

            document.getElementById("checkboxReponse").checked = true;
            document.getElementById("checkboxReponse").onclick = removeReponse;
        }

        function removeReponse(){
            document.getElementById("responseArea").classList.add("hidden");
            document.getElementById("reponseNom").innerHTML = ``;
            document.getElementById("reponseId").value = "";
            document.getElementById("checkboxReponse").checked = false;
        }

        function supprimerMessage(evt){
            const messageId = evt.currentTarget.dataset.deleteMessageId;
            const message = forum.messages.find(m => m.id == messageId);
            Swal.fire({
                title: "Confirmation",
                text: "Voulez-vous vraiment supprimer ce message?",
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
                    destroyMessage(message.id);
                } else {
                    return;
                }
            });
        }

        async function destroyMessage(id){
            try {
                const response = await fetch(`/message/destroy`, {
                    method:"DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body:JSON.stringify({id})
                });

                if (!response.ok) {
                    const error= await response.json();
                    toastr.error("Erreur: ", error);
                }
                toastr.success("Le message a bien été supprimé.");
                await refreshMessages(forum.id);
            } catch (e) {
                toastr.error('Erreur lors de la suppression du message:', e);
            }
        }

        function rechercherUser(evt){
            const messageUnits = document.getElementsByClassName("messageUnit");
            for (let i = 0; i<forum.messages.length; i++){
                    messageUnits[i].classList.remove("hidden");
                }
            filtre=evt.target.value;
            if (filtre!=""){
                for (let i = 0; i<forum.messages.length; i++){
                    let prenom = forum.messages[i].user.prenom;
                    let nom = forum.messages[i].user.nom;
                    if (!(prenom.toLowerCase().startsWith(filtre.toLowerCase()) || nom.toLowerCase().startsWith(filtre.toLowerCase()))){
                        messageUnits[i].classList.add("hidden");
                    }
                }
            }
        }

        function rechercherMotCle(evt){
            const messageUnits = document.getElementsByClassName("messageUnit");
            for (let i = 0; i<forum.messages.length; i++){
                    messageUnits[i].classList.remove("hidden");
                }
            filtre=evt.target.value;
            if (filtre!=""){
                filtre=filtre.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                for (let i = 0; i<forum.messages.length; i++){
                    let texte = forum.messages[i].texte;
                    texte=texte.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    if (!(texte.toLowerCase().includes(filtre.toLowerCase()))){
                        messageUnits[i].classList.add("hidden");
                    }
                }
            }
        }
    }

    function signalerMessage(e){
        e.preventDefault();

        const form = document.getElementById('formSignalement');
        const raisonInput = document.getElementById('raisonSignalement');
        const errorSpan = document.getElementById('formErrorRaisonSignalement');
        const messageId = e.currentTarget.dataset.signalMessageId;

        const raison = raisonInput.value.trim();
        const validPattern = /^(?=.*\S).{1,}$/;

        if (!validPattern.test(raison)) {
            errorSpan.classList.remove('hidden');
            return;
        }

        errorSpan.classList.add('hidden');

        const idPattern = /^\d+$/;
        if (!idPattern.test(messageId)) {
            toastr.error('Message ID invalide.');
            return;
        }

        form.action = `/messages/${messageId}/signalement`;
        form.submit();
    }

    function populateListeMessages(){
            listeMessages.innerHTML=``;
            document.getElementById("nouveauMessage").value = "";
            if (forum.messages.length < 1) {
                const pl = creerElement("p", {text:"Commencez la conversation avec un petit message!", classes:["text-gray-300", "text-sm", "w-full", "h-full", "m-auto", "text-center"]});
                listeMessages.appendChild(pl);
                return;
            }
            forum.messages.forEach(message=>{
                const div = creerElement("div", {classes:["messageUnit", "w-full", "flex", "flex-col"]});

                if (message.id_user == userId){
                    div.classList.add("items-end");
                }
                else{
                    div.classList.add("items-start");
                }

                const auteur = creerElement("p", {text:message.user.prenom + " " + message.user.nom, classes:["text-gray-300", "text-sm"]});
                div.appendChild(auteur);

                const wrapper = creerElement("div", {classes:["relative", "max-w-xs", "sm:max-w-md", "p-2", "rounded-2xl", "shadow-xl"]});
                if (message.id_user == userId){
                    wrapper.classList.add("bg-blue-500", "text-white", "rounded-br-none");
                }
                else{
                    wrapper.classList.add("bg-gray-300", "text-gray-800", "rounded-bl-none");
                }

                const childDiv = creerElement("div", {classes:["p-2"], dataset:{messageId: message.id}});

                if(message.id_reponse){
                    const reponse = creerElement("button", {
                        dataset:{reponseId:message.id_reponse},
                        classes:["w-full", "reponsePreview", "text-slate-700", "text-sm", "text-left", "px-3", "py-1", "rounded-full", "bg-slate-500/40", "truncate"],
                        text:message.reponse.user.prenom + " " + message.reponse.user.nom + " : " + message.reponse.texte
                    });

                    if (message.id_user == userId) {
                        reponse.classList.remove("text-slate-700", "bg-slate-500/40");
                        reponse.classList.add("text-slate-300", "bg-indigo-700/50");
                    }

                    if (message.reponse.id_user == userId) {
                        reponse.classList.remove("bg-slate-500/40", "text-slate-700");
                        reponse.classList.add("rounded-br-none", "bg-slate-900/60", "text-slate-200");
                    } else {
                        reponse.classList.add("rounded-bl-none");
                    }

                    reponse.addEventListener("click", e => {
                        e.stopPropagation();
                        const id = e.currentTarget.dataset.reponseId;
                        const targetMessage = document.querySelector(`[data-message-id="${id}"]`);
                        if (!targetMessage) return;

                        targetMessage.style.position = "relative";

                        targetMessage.scrollIntoView({ behavior: "smooth", block: "center" });

                        let lastScroll = Date.now();
                        const checkScrollEnd = () => {
                            if (Date.now() - lastScroll > 150) {
                                targetMessage.parentElement.classList.add("flash-ring");
                                setTimeout(() => targetMessage.parentElement.classList.remove("flash-ring"), 1000);
                            } else {
                                requestAnimationFrame(checkScrollEnd);
                            }
                        };

                        const onScroll = () => {
                            lastScroll = Date.now();
                        };

                        document.addEventListener("scroll", onScroll, { passive: true });
                        requestAnimationFrame(checkScrollEnd);
                        setTimeout(() => document.removeEventListener("scroll", onScroll), 800);
                    });

                    childDiv.append(reponse);
                }

                const texte = creerElement("p", {text:message.texte, classes:["break-all"]});
                childDiv.appendChild(texte);

                const dateHeure = new Date(message.created_at);
                const year = dateHeure.getFullYear();
                const month = String(dateHeure.getMonth()+1).padStart(2, '0');
                const day = String(dateHeure.getDate()).padStart(2, "0");
                const dateFormattee = `${year}-${month}-${day}`;

                const hours = String(dateHeure.getHours()).padStart(2, '0');
                const minutes = String(dateHeure.getMinutes()).padStart(2, '0');
                const heureFormattee = `${hours}:${minutes}`;

                const createdAt = creerElement("p", {text:`${dateFormattee}, ${heureFormattee}`, classes:["text-sm"]});
                if (message.id_user == userId){
                    createdAt.classList.add("text-gray-300");
                }
                else{
                    createdAt.classList.add("text-gray-500");
                }

                childDiv.appendChild(createdAt);
                wrapper.appendChild(childDiv);

                div.appendChild(wrapper);

                const divActions = creerElement("div", { classes: ["relative", "flex", "flex-row", "items-center", "gap-2", "mt-1"] });
                if (message.id_user == userId) divActions.classList.add("flex-row-reverse");

                const btnToggle = creerElement("button", {
                    classes: ["text-gray-400", "hover:text-gray-200", "transition-colors", "duration-300"],
                    html: `
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-more-horizontal">
                            <circle cx="12" cy="12" r="1"/>
                            <circle cx="19" cy="12" r="1"/>
                            <circle cx="5" cy="12" r="1"/>
                        </svg>`,
                });

                divActions.appendChild(btnToggle);

                const divBtns = creerElement("div", {classes:["action-menu", "absolute", "left-8", "bottom-0",
                    "flex", "flex-row", "gap-2", "rounded-lg", "p-1.5",
                    "opacity-0", "scale-95", "pointer-events-none",
                    "transition-all", "duration-300", "ease-in-out", "bg-white/10", "border", "border-white/20", "backdrop-blur"]});
                if (message.id_user == userId) {
                    divBtns.classList.remove("left-8");
                    divBtns.classList.remove("right-10");
                }

                const btnReponse = creerElement("button", {
                    classes:["btnReponse", "text-gray-50", "hover:text-yellow-500", "transition-colors", "duration-300"],
                    html:`
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-reply-icon lucide-reply"><path d="M20 18v-2a4 4 0 0 0-4-4H4"/>
                            <path d="m9 17-5-5 5-5"/>
                        </svg>`,
                    dataset:{reponseMessageId:message.id}
                });
                divBtns.appendChild(btnReponse);

                if (canManageMessages){
                    const btn = creerElement("button", { // fix
                        classes:["deleteMessage", "text-gray-50", "hover:text-red-600", "transition-colors", "duration-300"],
                        html:`<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-message-circle-x-icon lucide-message-circle-x">
                                <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"/>
                                <path d="m15 9-6 6"/>
                                <path d="m9 9 6 6"/>
                              </svg>`,
                        dataset:{deleteMessageId: message.id}
                    });
                    divBtns.appendChild(btn);
                }
                const btn = creerElement("button", {
                    classes:["signalMessage", "text-gray-50", "hover:text-red-600", "transition-colors", "duration-300"],
                    html:`<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>`,
                    dataset:{signalMessageId: message.id}
                });
                divBtns.appendChild(btn);
                divActions.appendChild(divBtns);
                div.appendChild(divActions);

                listeMessages.appendChild(div);

                btnToggle.addEventListener("click", (e) => {
                    e.stopPropagation();
                    const expanded = divBtns.classList.contains("opacity-100");

                    document.querySelectorAll(".action-menu").forEach(menu => {
                        menu.classList.remove("opacity-100", "scale-100");
                        menu.classList.add("opacity-0", "scale-95", "pointer-events-none");
                    });

                    if (!expanded) {
                        divBtns.classList.remove("opacity-0", "scale-95", "pointer-events-none");
                        divBtns.classList.add("opacity-100", "scale-100");
                    }
                });

            });


            btnsSupprimer = document.getElementsByClassName("deleteMessage");
            for (let i = 0; i < btnsSupprimer.length; i++) {
                btnsSupprimer[i].addEventListener("click", (evt) => {
                    evt.stopPropagation();
                    supprimerMessage(evt);
                });
            }

            btnsSignaler = document.getElementsByClassName("signalMessage");
            for (let i = 0; i < btnsSignaler.length; i++) {
                btnsSignaler[i].addEventListener("click", (evt) => {
                    evt.stopPropagation();
                    openFormSignalement(evt);
                });
            }

            btnsReponse = document.getElementsByClassName("btnReponse");
            for (let i = 0; i < btnsReponse.length; i++) {
                btnsReponse[i].addEventListener("click", (evt) => {
                    evt.stopPropagation();
                    addResponse(evt.currentTarget.dataset.reponseMessageId);
                });
            }

            document.addEventListener("click", () => {
                document.querySelectorAll(".action-menu").forEach(menu => {
                    menu.classList.add("opacity-0", "scale-95", "pointer-events-none");
                    menu.classList.remove("opacity-100", "scale-100");
                });
            });
        }

    function openForumAddForm() {
        addForm.classList.remove('hidden');
        setTimeout(() => {
            addForm.classList.add('opacity-100');
            addForm.classList.add('scale-100');
        }, 10);
    }

    function openForumJoinRequestForm() {
        joinForm.classList.remove('hidden');
        setTimeout(() => {
            joinForm.classList.add('opacity-100');
            joinForm.classList.add('scale-100');
        }, 10);
    }

    function openFormSignalement(evt) {
        btnEnvoiSignal.dataset.signalMessageId = evt.currentTarget.dataset.signalMessageId;
        signalForm.classList.remove('hidden');
        setTimeout(() => {
            signalForm.classList.add('opacity-100');
            signalForm.classList.add('scale-100');
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

    function creerElement(tag, options={}){
        const el = document.createElement(tag);
        if (options.classes) el.classList.add(...options.classes);
        if (options.attrs) Object.entries(options.attrs).forEach(([k,v])=> el.setAttribute(k,v));
        if (options.text) el.textContent = options.text;
        if (options.html) el.innerHTML = options.html;
        if (options.dataset) Object.entries(options.dataset).forEach(([k, v]) => {el.dataset[k] = v;});
        return el;
    }
    function reload(){
        location.reload();
    }

    function display(id_forum){
        if (currentForums === autresForums)
            return;

        if (id_forum>0){
            forum = currentForums.find(f => f.id == id_forum);
            toggleForum()

            document.getElementById("forumId").value = forum.id;
            document.getElementById("titrePage").textContent="Forum " + forum.nom_forum;
            populateListeMessages();
            listeMessages.scrollTop = listeMessages.scrollHeight;
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        }
        else {
            toggleForum(false);
            forum = null;
        }
    }

    function toggleForum(open = true) {
        const liste = document.getElementById("liste-container");
        const include = document.getElementById("include");

        if (open) {
            include.classList.remove("opacity-0", "scale-95", "flex-[0_1_0%]");
            include.classList.add("opacity-100", "scale-100", "flex-[2_1_66%]");

            liste.classList.remove("flex-[1_1_100%]");
            liste.classList.add("flex-[1_1_33%]");
        } else {
            include.classList.remove("opacity-100", "scale-100", "flex-[2_1_66%]");
            include.classList.add("opacity-0", "scale-95", "flex-[0_1_0%]");

            setTimeout(() => {
                liste.classList.remove("flex-[1_1_33%]");
                liste.classList.add("flex-[1_1_100%]");
            }, 500);
        }
    }

    if (document.location.pathname == "/forums") {
        document.querySelector("#include .closeForum")?.addEventListener("click", () => {
            toggleForum(false);

            document.querySelectorAll(".forum").forEach(forum => {
                forum.classList.remove(
                    "bg-white/20",
                    "backdrop-blur-md",
                    "ring-4",
                    "ring-red-400",
                    "scale-[1.02]",
                    "shadow-lg",
                    "text-gray-50"
                );
                forum.classList.add("hover:bg-white/10", "text-gray-200");
            });
        });
    }

    const form = document.getElementById('forumDemandeForm');

    document.querySelectorAll('.adhesion-btn').forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();

            const form = document.getElementById('forumDemandeForm');
            const raisonInput = document.getElementById('raison');
            const errorSpan = document.getElementById('formErrorRaison');
            const forumId = button.dataset.forumId;

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

            form.action = `/forums/${forumId}/adhesion`;
            form.submit();
        });
    });

    async function refreshMessages(forumId) {
        const response = await fetch(`/forums/${forumId}/messages`);
        const data = await response.json();
        forum = data;
        document.getElementById("include").classList.remove("hidden");
        document.getElementById("forumId").value = forum.id;
        document.getElementById("titrePage").textContent="Forum " + forum.nom_forum;
        populateListeMessages();
        boiteMessages.scrollTop = boiteMessages.scrollHeight;
    }

    window.refreshMessages = refreshMessages;
    document.dispatchEvent(new CustomEvent('forumsReady'));
});
