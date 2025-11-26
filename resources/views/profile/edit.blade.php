<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Account Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Left Column: Profile Information -->
                <div class="space-y-8">
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Right Column: Security & Danger Zone -->
                <div class="space-y-8">
                    <!-- Update Password -->
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account (Danger Zone) -->
                    <div class="p-4 sm:p-8 bg-red-50 dark:bg-red-900/10 shadow-sm sm:rounded-2xl border border-red-100 dark:border-red-900/20">
                        <div class="max-w-xl">
                            <h2 class="text-lg font-medium text-red-600 dark:text-red-400 mb-4">
                                {{ __('Danger Zone') }}
                            </h2>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
