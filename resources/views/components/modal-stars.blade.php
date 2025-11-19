@props([
    'id',
    'title'
])

<section
    id="{{ $id }}"
    x-transition.opacity.duration.300ms
    class="hidden z-40 fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity duration-300 ease-in-out opacity-0"
>
    <div class="bg-black/50 backdrop-blur p-6 flex flex-col gap-8 rounded-lg shadow-lg text-gray-50 w-full max-w-lg border border-white/10 shadow-[0_0_5px_rgba(255,255,255,0.05)] rounded-lg">
        <div class="flex justify-between items-center">
            <h2 id="{{ $id }}-title" class="font-semibold text-xl">
                {{ $title ?? 'Modal Title' }}
            </h2>
            <button id="closeModal" type="button" class="text-red-300 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div>
            {{ $slot }}
        </div>
    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById(@js($id));
    if (!modal) return;

    function closeModal(evt) {
        if (evt.target === modal || evt.target.closest('#closeModal')) {
            modal.classList.remove('opacity-100');
            modal.classList.remove('scale-100');
            modal.classList.add('opacity-0');
            modal.classList.add('scale-100');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    modal.addEventListener('click', closeModal);
});
</script>
