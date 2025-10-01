<section>
    <header>
        <h2 class="text-lg font-medium text-text-primary">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-text-secondary">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-text-secondary">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-text-secondary">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary" value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>