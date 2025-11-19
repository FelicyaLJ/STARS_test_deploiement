<x-modal-stars id="confirm-user-deletion" title="{{__('Confirmation de la suppression')}}" name="confirm-user-deletion" class="" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-50">
            {{ __('Are you sure you want to delete your account?') }}
        </h2>

        <p class="mt-1 text-sm text-gray-300">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </p>

        <div class="mt-6">
            <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

            <x-text-input
                id="password"
                name="password"
                type="password"
                class="border border-gray-400 rounded w-full p-2 mb-2 text-gray-50 bg-white/10 focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                placeholder="{{ __('Password') }}"
            />

            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">

            <x-danger-button class="ms-3">
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>
    </form>
</x-modal-stars>
