@if($cookie_consent === null)
<div id="cook-consent"
    class="fixed bottom-0 inset-x-0 bg-black/50 backdrop-blur text-gray-50 p-4 flex justify-between items-center
           transition-transform transition-opacity duration-500 ease-out
           transform translate-y-full opacity-0 z-10">
    <span>
        {{ __('Nous utilisons des cookies pour améliorer votre expérience. En utilisant notre site, vous acceptez les termes de notre ') }}
        <button type="button" id="politique-button" class="underline">{{ __('Politique de confidentialité') }}</button>.
    </span>
    <div class="ml-4 flex flex-col gap-2 sm:flex-row">
        <button id="accept-cook"
            class="text-red-300 bg-red-800/70 hover:bg-red-600/70 hover:ring-1 hover:ring-red-300 px-3 py-1 rounded">
            {{ __('Accepter') }}
        </button>
        <button id="reject-cook"
            class="text-red-300 ring-1 ring-red-300 hover:bg-red-700/70 px-3 py-1 rounded">
            {{ __('Rejeter') }}
        </button>
    </div>
</div>

<x-modal-stars title="Politique de confidentialité" id="politique-modal">
    <div class="max-h-[40rem] overflow-y-auto">
        <p class="">{{__('Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.

Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.

Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.

Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.

Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.')}}</p>
    </div>
</x-modal-stars>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const banner = document.getElementById('cook-consent');
    if (banner) {
        setTimeout(() => {
            banner.classList.remove('translate-y-full', 'opacity-0');
            banner.classList.add('translate-y-0', 'opacity-100');
        }, 100);

        // Accepter
        document.getElementById('accept-cook')?.addEventListener('click', function () {
            fetch("/accept-cookies", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            }).then(() => {
                banner.classList.add('translate-y-full', 'opacity-0');
                setTimeout(() => banner.remove(), 500);
            });
        });

        // Rejeter
        document.getElementById('reject-cook')?.addEventListener('click', function () {
            fetch("/reject-cookies", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            }).then(() => {
                banner.classList.add('translate-y-full', 'opacity-0');
                setTimeout(() => banner.remove(), 500);
            });
        });

        document.getElementById('politique-button').addEventListener('click', () => {
            let modal = document.getElementById("politique-modal");
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.classList.add('scale-100');
            }, 10);
        });
    }
});
</script>

