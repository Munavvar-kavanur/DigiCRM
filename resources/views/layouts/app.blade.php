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

            <!-- Sidebar -->
            <aside :class="[
                    sidebarCollapsed ? 'md:w-20' : 'md:w-72',
                    isLoaded ? 'transition-all duration-300 ease-in-out' : ''
                ]" 
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 h-full flex-shrink-0 fixed inset-y-3 left-3 z-50 md:relative md:inset-auto md:left-auto transform" 
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-[110%] md:translate-x-0'"
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
        @livewireScripts
    </body>
</html>
