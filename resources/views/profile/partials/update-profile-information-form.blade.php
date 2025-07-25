@push('styles')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"
    @endpush <section>
<header>
    <h2 class="text-lg font-medium text-gray-900">
        {{ __('Profile Information') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600">
        {{ __("Update your account's profile information and email address.") }}
    </p>
</header>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="block w-full mt-1" :value="old('name', $user->name)" required
            autofocus autocomplete="name" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="username" :value="__('Username')" />
        <x-text-input id="username" name="username" type="text" class="block w-full mt-1" :value="old('username', $user->username)" required
            autofocus autocomplete="username" />
        <x-input-error class="mt-2" :messages="$errors->get('username')" />
    </div>

    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)" required
            autocomplete="username" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div>
                <p class="mt-2 text-sm text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification"
                        class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-medium text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-white" for="avatar">Avatar</label>
        <input
            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
            aria-describedby="file_input_help" type="file" accept="image/*" id="avatar" name="avatar">
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Image type file .png .jpg
            .jpeg & etc.</p>
        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        <div class="mt-4">
            <img class="w-20 my-5 h-20 rounded-full" id="avatar-preview"
                src="{{ asset($user->avatar ? '/storage/' . $user->avatar : 'avatar.png') }}" alt="{{ $user->name }}">
        </div>
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>

        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">{{ __('Saved.') }}</p>
        @endif
    </div>
</form>

</section>

@push('scripts')
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>

    <script>
        // Register the plugin
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        FilePond.registerPlugin(FilePondPluginImageResize);
        FilePond.registerPlugin(FilePondPluginImageTransform);
        FilePond.create(document.querySelector('#avatar'), {
            acceptedFileTypes: ['image'],
            maxFileSize: '1MB',
            allowImageResize: true,
            imageResizeTargetWidth: 100, // pixel
            imageResizeMode: 'contain', // opsi: 'cover', 'contain', 'force'
            imageResizeUpscale: false,
        });

        FilePond.setOptions({
            server: {
                url: "{{ route('profile.avatar') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });
    </script>
@endpush
