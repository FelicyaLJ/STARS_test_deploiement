<section class="space-y-6 flex justify-between">
    <header class="max-w-xl">
        <h2 class="text-2xl font-semibold text-gray-50">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-300">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        id="openUserDeletionModal"
    >{{ __('Delete Account') }}</x-danger-button>

</section>
