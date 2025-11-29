<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DigiCRM') }}</title>
        @if(\App\Models\Setting::get('favicon'))
            <link rel="icon" type="image/png" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-gray-900">
        <div class="min-h-screen flex">
            <!-- Left Side: Branding -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 flex-col justify-between p-12 overflow-hidden">
                <!-- Background Pattern/Gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-blue-700 opacity-90"></div>
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                
                <!-- Content -->
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        @php
                            $logoLight = \App\Models\Setting::get('crm_logo_light');
                        @endphp
                        
                        @if($logoLight)
                            <img src="{{ asset('storage/' . $logoLight) }}" alt="{{ config('app.name') }}" class="h-10 w-auto bg-white/10 rounded-lg p-1">
                        @elseif(file_exists(public_path('images/logo/logo.png')))
                            <img src="{{ asset('images/logo/logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto bg-white/10 rounded-lg p-1">
                        @elseif(file_exists(public_path('images/logo/logo.svg')))
                            <img src="{{ asset('images/logo/logo.svg') }}" alt="{{ config('app.name') }}" class="h-10 w-auto bg-white/10 rounded-lg p-1">
                        @else
                            <div class="h-10 w-10 rounded-lg bg-white/10 flex items-center justify-center backdrop-blur-sm">
                                <span class="text-white text-lg font-bold">DC</span>
                            </div>
                        @endif
                        <span class="text-white text-xl font-bold tracking-tight">{{ config('app.name', 'DigiCRM') }}</span>
                    </div>
                </div>

                <div class="relative z-10">
                    <h2 class="text-4xl font-bold text-white mb-6 leading-tight">
                        Manage your business <br> with confidence.
                    </h2>
                    <p class="text-indigo-100 text-lg max-w-md leading-relaxed">
                        Streamline your workflow, track expenses, and manage client relationships all in one powerful platform.
                    </p>
                </div>

                <div class="relative z-10 text-indigo-200 text-sm font-medium">
                    © {{ date('Y') }} {{ config('app.name', 'DigiCRM') }}. All rights reserved.
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-gray-50 dark:bg-gray-900">
                <div class="w-full max-w-md space-y-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
