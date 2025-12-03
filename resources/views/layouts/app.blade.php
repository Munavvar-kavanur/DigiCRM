<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        @if(\App\Models\Setting::get('favicon'))
            <link rel="icon" type="image/png" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

        <script>
            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ 
                sidebarOpen: false, 
                sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
                darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage)),
                isLoaded: false,
                toggleSidebar() { 
                    this.sidebarCollapsed = !this.sidebarCollapsed; 
                    localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed); 
                },
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('color-theme', this.darkMode ? 'dark' : 'light');
                },
                init() {
                    // Initial check
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    
                    // Watch for changes
                    this.$watch('darkMode', value => {
                        if (value) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    });

                    // Enable transitions after page load
                    setTimeout(() => {
                        this.isLoaded = true;
                    }, 100);
                }
            }" 
            class="h-screen bg-gray-100 dark:bg-gray-900 flex p-3 gap-3 overflow-hidden relative">
            
            <!-- Global Loading Indicator -->
            <div class="fixed top-0 left-0 w-full h-1 z-[100]" style="display: none;" id="global-loader">
                <div class="h-full bg-indigo-600 dark:bg-indigo-400 animate-progress origin-left"></div>
            </div>

            <style>
                @keyframes progress {
                    0% { width: 0%; }
                    50% { width: 70%; }
                    100% { width: 100%; }
                }
                .animate-progress {
                    animation: progress 2s ease-in-out infinite;
                }
            </style>

            <script>
                document.addEventListener('livewire:navigating', () => {
                    document.getElementById('global-loader').style.display = 'block';
                });
                document.addEventListener('livewire:navigated', () => {
                    document.getElementById('global-loader').style.display = 'none';
                });
            </script>
            
            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-40 md:hidden"></div>

            <!-- Sidebar Styles -->
            <style>
                @media (max-width: 767px) {
                    #sidebar {
                        transform: translateX(-120%);
                    }
                    #sidebar.mobile-open {
                        transform: translateX(0) !important;
                    }
                }
            </style>

            <!-- Sidebar -->
            <aside :class="{
                    'md:w-20': sidebarCollapsed,
                    'md:w-72': !sidebarCollapsed,
                    'mobile-open': sidebarOpen,
                    'transition-all duration-300 ease-in-out': isLoaded
                }" 
                class="w-72 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 h-full flex-shrink-0 fixed inset-y-3 left-3 z-50 md:relative md:inset-auto md:left-auto md:translate-x-0 transform" 
                id="sidebar">
                @include('layouts.navigation')
            </aside>

            <!-- Main Content -->
            <div id="main-content" class="flex-1 flex flex-col min-w-0 gap-3 h-full overflow-hidden transition-all duration-300 ease-in-out">
                @include('layouts.topbar')

                <!-- Page Header -->
                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-[98%] mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-6 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600"
                      x-data
                      x-init="$el.classList.add('opacity-100', 'translate-y-0')"
                      class="opacity-0 translate-y-4 transition-all duration-500 ease-out">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <style>
            [x-cloak] { display: none !important; }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Toast Configuration
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50',
                    title: 'text-gray-800 dark:text-gray-100 font-semibold text-sm',
                    timerProgressBar: 'bg-indigo-500 dark:bg-indigo-400',
                    icon: 'text-xs'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Global Flash Messages
            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            @if(session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: "{{ session('warning') }}"
                });
            @endif

            @if(session('info'))
                Toast.fire({
                    icon: 'info',
                    title: "{{ session('info') }}"
                });
            @endif

            // Global Delete Confirmation
            document.addEventListener('submit', function(e) {
                if (e.target && e.target.classList.contains('delete-form')) {
                    e.preventDefault();
                    const form = e.target;
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete it!',
                        background: 'transparent',
                        color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827',
                        customClass: {
                            popup: 'bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50',
                            title: 'text-xl font-bold text-gray-900 dark:text-white',
                            htmlContainer: 'text-gray-600 dark:text-gray-300',
                            confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition-transform transform hover:scale-105',
                            cancelButton: 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2.5 px-6 rounded-xl shadow-lg transition-transform transform hover:scale-105 ml-3',
                            actions: 'gap-3'
                        },
                        buttonsStyling: false,
                        backdrop: `
                            rgba(0,0,0,0.4)
                            backdrop-filter: blur(8px)
                        `
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
            
            // Custom styles for SweetAlert2
            const style = document.createElement('style');
            style.innerHTML = `
                div:where(.swal2-container) {
                    z-index: 99999 !important; /* Increased z-index */
                }
                div:where(.swal2-icon) {
                    border-color: transparent !important;
                    margin: 0 auto 1em auto;
                }
                div:where(.swal2-popup) {
                    padding: 2em;
                }
                div:where(.swal2-toast) {
                    padding: 0.75rem 1rem !important;
                    display: flex !important;
                    align-items: center !important;
                }
                div:where(.swal2-toast) .swal2-icon {
                    margin: 0 0.5rem 0 0 !important;
                }
            `;
            document.head.appendChild(style);
        </script>
        <!-- Global Delete Confirmation Modal -->
        <div x-data="{ show: false, actionUrl: '' }" 
             @open-delete-modal.window="show = true; actionUrl = $event.detail.actionUrl" 
             x-show="show" 
             x-cloak 
             class="fixed inset-0 z-[99999] overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="show = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/50 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Delete Item
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete this item? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="actionUrl" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Delete
                            </button>
                        </form>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="show = false">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        @livewireScripts
    </body>
</html>
