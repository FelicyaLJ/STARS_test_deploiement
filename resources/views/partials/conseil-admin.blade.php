<div class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg flex flex-col">
    <div class="flex justify-between mb-6">
        <h2 class="font-semibold text-2xl leading-tight">
            {{ __('Conseil d\'administration') }}
        </h2>
        <hr>
    </div>

    <div id="membres_CA_container"
         class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-4">
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    fetch('/membres-ca')
        .then(response => response.json())
        .then(membres => {
            const container = document.getElementById('membres_CA_container');
            membres.forEach((membre, i) => {
            const div = document.createElement('div');
            const roleNames = membre.roles.map(r => r.nom_role).join(', ');
            const initials = `${membre.prenom[0]}${membre.nom[0]}`.toUpperCase();

            div.className = `
                p-6 rounded-2xl bg-white/10
                border border-white/20 shadow-md text-white
                opacity-0 translate-y-4 transition-all duration-700 hover:shadow-lg
                hover:-translate-y-1 hover:bg-white/20
            `;

            div.innerHTML = `
                <div class="flex flex-col items-center text-center space-y-3">
                    <div class="w-16 h-16 rounded-full bg-red-300/30 flex items-center justify-center text-2xl font-bold">
                        ${initials}
                    </div>
                    <h3 class="font-semibold text-xl">${membre.prenom} ${membre.nom}</h3>
                    <h4 class="font-medium text-red-300">${roleNames}</h4>
                    <p class="flex items-center gap-2 text-sm text-gray-300">
                        <svg class="w-4 h-4 text-red-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                        ${membre.email}
                    </p>
                </div>
            `;

            container.appendChild(div);

            setTimeout(() => {
                div.classList.remove('opacity-0', 'translate-y-4');
            }, i * 50);
            });
        });
});
</script>
