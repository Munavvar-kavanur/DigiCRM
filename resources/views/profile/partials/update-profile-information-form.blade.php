<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo with Cropper -->
        <div x-data="{ 
                photoName: null, 
                photoPreview: null,
                showCropperModal: false,
                cropper: null,
                
                selectNewPhoto() {
                    this.$refs.photo.click();
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.photoName = file.name;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            // Instead of setting preview immediately, open cropper
                            this.showCropperModal = true;
                            this.$nextTick(() => {
                                if (this.cropper) {
                                    this.cropper.destroy();
                                }
                                const image = document.getElementById('cropper-image');
                                image.src = e.target.result;
                                this.cropper = new Cropper(image, {
                                    aspectRatio: 1,
                                    viewMode: 1,
                                    autoCropArea: 1,
                                });
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                },

                cropAndSave() {
                    if (this.cropper) {
                        const canvas = this.cropper.getCroppedCanvas({
                            width: 300,
                            height: 300,
                        });
                        
                        // Compress and set preview
                        this.photoPreview = canvas.toDataURL('image/jpeg', 0.8);
                        
                        // Convert to blob and update file input (for form submission)
                        canvas.toBlob((blob) => {
                            const file = new File([blob], this.photoName, { type: 'image/jpeg' });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            this.$refs.photo.files = dataTransfer.files;
                            
                            // Trigger Livewire update if needed (using this.upload if strictly livewire, but here we use standard form submit)
                            // Since we are using standard form submit, updating the input.files is enough.
                        }, 'image/jpeg', 0.8);

                        this.showCropperModal = false;
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                },

                cancelCrop() {
                    this.showCropperModal = false;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                    this.$refs.photo.value = ''; // Clear input
                }
            }" class="col-span-6 sm:col-span-4">
            
            <!-- Hidden File Input -->
            <input type="file" class="hidden"
                        x-ref="photo"
                        name="photo"
                        accept="image/*"
                        x-on:change="handleFileSelect($event)" />

            <x-input-label for="photo" value="{{ __('Photo') }}" />

            <div class="mt-2 relative inline-block group">
                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-full h-24 w-24 object-cover border-4 border-white dark:border-gray-800 shadow-lg">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-24 h-24 bg-cover bg-no-repeat bg-center border-4 border-white dark:border-gray-800 shadow-lg"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <!-- Camera Icon Overlay -->
                <button type="button" x-on:click.prevent="selectNewPhoto" 
                    class="absolute bottom-0 right-0 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-2 shadow-lg transition-transform transform hover:scale-110 focus:outline-none ring-2 ring-white dark:ring-gray-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </button>
            </div>

            @if ($user->profile_photo_path)
                <div class="mt-2">
                    <x-secondary-button type="button" class="text-xs" x-on:click="$refs.photo.value = ''; photoPreview = null;">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                </div>
            @endif

            <x-input-error class="mt-2" :messages="$errors->get('photo')" />

            <!-- Cropper Modal -->
            <div x-show="showCropperModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showCropperModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showCropperModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                        {{ __('Crop Image') }}
                                    </h3>
                                    <div class="mt-4">
                                        <div class="img-container w-full h-96 bg-gray-100">
                                            <img id="cropper-image" src="" class="max-w-full h-full block">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" x-on:click="cropAndSave" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Crop & Save') }}
                            </button>
                            <button type="button" x-on:click="cancelCrop" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
