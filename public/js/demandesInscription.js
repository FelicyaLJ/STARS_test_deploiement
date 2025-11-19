document.addEventListener("DOMContentLoaded", () => {

    const deployFiltre = document.getElementById("deployFiltre");
    const divToDeploy = document.getElementById("contentFiltre");
    const resetBtn = document.getElementById("reset");
    const champTri = document.getElementById("tri");
    const champFiltre = document.getElementById("rechercheActivite");

    champTri.addEventListener("change", () => trier(champTri.value));
    champFiltre.addEventListener("input", () => filtrer(champFiltre.value));

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

    trier("0");

    function filtrer(filtre){
        if (demandes.length!=0){
            document.querySelectorAll("[data-demande-id]").forEach(div => div.classList.remove("hidden"));
            if (filtre!=""){
                filtre=filtre.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                demandes.forEach(d =>{
                    let titre = d.equipe.nom_equipe;
                    titre=titre.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    if (!(titre.toLowerCase().startsWith(filtre.toLowerCase()))){
                        const div = document.querySelector(`[data-demande-id="${d.id}"]`);
                        if (div) div.classList.add("hidden");
                    }
                });
            }
            const visible = document.querySelectorAll("#listeDemandes [data-demande-id]:not(.hidden)");
            let msg = document.getElementById("noResultsMsg");
            if (visible.length === 0) {
                msg.classList.remove("hidden");
            }
            else{
                msg.classList.add("hidden");
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

    function trier(tri){
        switch(tri){
            case "0"://antichronologique
                demandes.sort((a, b) => {
                    const dateA = a.id;
                    const dateB = b.id;
                    return dateB - dateA;
                });
                break;
            case "1"://chronologique
                demandes.sort((a, b) =>{
                    const dateA = a.id;
                    const dateB = b.id;
                    return dateA - dateB;
                });
                break;
            case "2"://alphabétique activite
                demandes.sort((a, b) =>a.equipe.nom_equipe.localeCompare(b.equipe.nom_equipe));
                break;
            case "3"://alphabétique utilisateur
                demandes.sort((a, b) =>a.user.nom.localeCompare(b.user.nom));
                break;
            default:
                break;
        }
        populateListeDemandes();
        if (champFiltre.value){
            filtrer(champFiltre.value);
        }
    }

    function populateListeDemandes() {
        const container = document.getElementById('listeDemandes');
        if (!container) return;

        container.innerHTML = '';

        if (!Array.isArray(demandes) || demandes.length === 0){
            const empty = creerElement('p', {classes:['border', 'p-3', 'rounded', 'text-gray-300'],
                text: `Il n'y a aucune demande d'inscription en attente.`
            })
            empty.id = "noResultsMsg";
            container.appendChild(empty);
            return;
        }

        demandes.forEach(d => {
            const div = creerElement('div', {
                classes: ['border', 'rounded', 'text-white', 'p-5', 'flex', 'flex-col', 'md:flex-row', 'items-center', 'justify-between', "hover:bg-white/20", "hover:ring-4"],
                dataset: { demandeId: d.id }
            });

            const p = creerElement('p', { text: d.equipe.nom_equipe });
            div.appendChild(p);

            if (canManageDemandes) {
                const userInfo = creerElement('p', {
                    classes: ['text-sm', 'text-gray-300'],
                    text: `${d.user?.prenom} ${d.user?.nom}`
                });

                div.appendChild(userInfo);


                const addButton = creerElement('button', {
                    classes:['text-sm', 'text-red-300'],
                    text: `Appuyer pour accepter l'inscription.`
                });
                div.appendChild(addButton);
                if (canManageDemandes){
                    div.addEventListener("click", addToEquipe);
                }
            }
            const deleteBtn = creerElement('button', {
                dataset:{id:d.id},
                classes: [
                    'delete-demande',
                    'transition',
                    'hover:text-red-500',
                    'focus:outline-none'
                ],
                html: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-trash2-icon lucide-trash-2">
                            <path d="M10 11v6"/>
                            <path d="M14 11v6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                            <path d="M3 6h18"/>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>`
            });
            deleteBtn.addEventListener('click', async (e) => {
                e.stopPropagation();
                await supprimer(e.currentTarget.dataset.id);
            });

            div.appendChild(deleteBtn);
            container.appendChild(div);
        });
    }

    function addToEquipe(evt){
    const id = evt.target.dataset.demandeId;
    d = demandes.find(d=>d.id == id);
    Swal.fire({
            title: "Confirmation",
            text: `Voulez-vous ajouter ${d.user.prenom} ${d.user.nom} à l'activité ${d.equipe.nom_equipe}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ajouter",
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
                await addUser(d.user.id, d.equipe.id);
            } else {
                return;
            }
        });
    }

    async function addUser(userId, equipeId) {
        try {
            const response = await fetch(`/equipes/addUser/${encodeURIComponent(userId)}/${encodeURIComponent(equipeId)}`, {
                method:"PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            });
            if (!response.ok) {
                const error= await response.json();
                toastr.error("Erreur de mise à jour: ", error);
            }
            else{
                toastr.success("Activité mise à jour avec succès!");
                reload();
            }

        } catch (e) {
            toastr.error('Erreur lors de la mise à jour de l\'activité:', e);
        }
    }

    async function supprimer(id){
         const route = canManageDemandes
                ? '/inscriptions/demandes/suppression'
                : '/inscriptions/demandes/annulation';
        Swal.fire({
                title: "Confirmation",
                text: "Voulez-vous vraiment supprimer cette demande d'adhésion?",
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
                    try {
                        const response = await fetch(route, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({ id: id })
                        });

                        if (response.ok) {
                            toastr.success("La demande a bien été supprimée.");
                            reload();
                        } else {
                            const data = await response.json();
                            toastr.error(data.message || 'Erreur lors de la suppression.');
                        }
                    } catch (error) {
                        console.error(error);
                        toastr.error('Une erreur est survenue.');
                    }

                } else {
                    return;
                }
            });
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
});
