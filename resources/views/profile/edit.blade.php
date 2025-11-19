<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg basis-2/3">
                    <div class="">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg basis-1/3">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-black/60 backdrop-blur shadow sm:rounded-lg">
                <div class="">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    @include('profile.partials.delete-user-confirm')
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('confirm-user-deletion');
        const openButton = document.getElementById('openUserDeletionModal');

        // Open modal on click
        openButton.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.classList.add('scale-100');
            }, 10);
            // optionally focus the password input
            const passwordInput = modal.querySelector('#password');
            if (passwordInput) passwordInput.focus();
        });

        // Show modal automatically if there are validation errors
        const hasUserDeletionErrors = @json($errors->userDeletion->isNotEmpty());
        if (hasUserDeletionErrors) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.classList.add('scale-100');
            }, 10);
            const passwordInput = modal.querySelector('#password');
            if (passwordInput) passwordInput.focus();
        }
    });
</script>
