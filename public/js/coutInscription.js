document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalTableau");
    const btnModif = document.getElementById("modifTableau");
    btnModif.addEventListener('click', openModal);
    const newImage = document.getElementById("image");
    if (newImage) {
        newImage.addEventListener("change", () => checkImageFormat(false));
    }
    const btnEnregistrer = document.getElementById("enregistrerModif");
    btnEnregistrer.addEventListener("click", enregistrer);

    async function enregistrer(evt){
        evt.preventDefault();
        if (checkImageFormat()){
            try {
                const image = new FormData(document.getElementById("form"));
                image.append("_method", "PUT");
                const response = await fetch(`/cout/update`, {
                    method:"POST",
                    headers: {"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content},
                    body: image
                });
                if (!response.ok) {
                    const error= await response.json();
                    let errorMessage = error.message || JSON.stringify(error);
                    toastr.error("Erreur de mise à jour: ", errorMessage);
                    newImage.value = '';
                }
                else{
                    toastr.success("L'exercice a été mis à jour avec succès!");
                    newImage.value = '';
                    closeModal();
                    let img = document.getElementById('tableauImage');
                    if (!img){
                        img = creerElement("img", {attrs: { width:"", height:"", alt: "Aucun tableau de coût." }});
                        document.getElementById("imageIci").innerHTML=``;
                        document.getElementById("imageIci").appendChild(img);
                    }
                    const data = await response.json();
                    img.src = `${data.newImage}?t=${new Date().getTime()}`;
                }
            } catch (e) {
                toastr.error('Erreur lors de la mise à jour de l\'exercice:', e);
            }
        }
    }

    function checkImageFormat() {
        const fileBox = document.getElementById("image");
        const err = document.getElementById("formErrorImage");
        if (!fileBox) return false;

        const file = fileBox.files[0];
        if (!file){
            err.textContent = `Vous ne pouvez pas laisser le tableau vide.`;
            err.classList.remove("hidden");
            fileBox.value = '';
            return false;
        }

        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            err.textContent = `Le type de fichier sélectionné n'est pas autorisé.`;
            err.classList.remove("hidden");
            fileBox.value = '';
            return false;
        }

        const maxSizeMB = 5;
        if (file.size > maxSizeMB * 1024 * 1024) {
            err.textContent = `Le fichier dépasse la taille maximale de ${maxSizeMB} Mo.`;
            err.classList.remove("hidden");
            fileBox.value = '';
            return false;
        }
        err.classList.add("hidden");
        return true;
    }

    function openModal() {
        if (!modal) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.classList.add('scale-100');
        }, 10);
    }

     function closeModal() {
        if (!modal) return;
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
        if (options.dataset) Object.entries(options.dataset).forEach(([k, v]) => {el.dataset[k] = v;});
        if (options.text) el.textContent = options.text;
        if (options.html) el.innerHTML = options.html;
        return el;
    }
});
