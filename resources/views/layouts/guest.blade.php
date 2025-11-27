<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DigiCRM') }} - Login</title>
        @if(\App\Models\Setting::get('favicon'))
            <link rel="icon" type="image/png" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            * {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            }
            
            body {
                background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #8b5cf6 100%);
                min-height: 100vh;
                position: relative;
            }
            
            /* Subtle animated mesh gradient background */
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: 
                    radial-gradient(ellipse at 20% 30%, rgba(96, 165, 250, 0.15) 0%, transparent 50%),
                    radial-gradient(ellipse at 80% 70%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                    radial-gradient(ellipse at 50% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
                animation: meshMove 20s ease-in-out infinite;
                pointer-events: none;
            }
            
            @keyframes meshMove {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.8; transform: scale(1.1); }
            }
            
            /* Premium card with subtle shadow */
            .premium-card {
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 
                    0 20px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -5px rgba(0, 0, 0, 0.04),
                    0 0 0 1px rgba(0, 0, 0, 0.05);
            }
            
            /* Input field styling */
            .premium-input {
                width: 100%;
                padding: 14px 16px;
                border: 1.5px solid #e5e7eb;
                border-radius: 10px;
                font-size: 15px;
                color: #1f2937;
                background: #ffffff;
                transition: all 0.2s ease;
                outline: none;
            }
            
            .premium-input::placeholder {
                color: #9ca3af;
            }
            
            .premium-input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            .premium-input:hover:not(:focus) {
                border-color: #d1d5db;
            }
            
            /* Button styling */
            .premium-button {
                width: 100%;
                padding: 14px 24px;
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                color: #ffffff;
                border: none;
                border-radius: 10px;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
            }
            
            .premium-button:hover {
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
                transform: translateY(-1px);
            }
            
            .premium-button:active {
                transform: translateY(0);
            }
            
            /* Checkbox styling */
            .premium-checkbox {
                width: 18px;
                height: 18px;
                border: 1.5px solid #d1d5db;
                border-radius: 5px;
                cursor: pointer;
                appearance: none;
                position: relative;
                transition: all 0.2s;
                flex-shrink: 0;
            }
            
            .premium-checkbox:checked {
                background: #3b82f6;
                border-color: #3b82f6;
            }
            
            .premium-checkbox:checked::after {
                content: '';
                position: absolute;
                top: 2px;
                left: 5px;
                width: 4px;
                height: 8px;
                border: solid white;
                border-width: 0 2px 2px 0;
                transform: rotate(45deg);
            }
            
            /* Link styling */
            .premium-link {
                color: #3b82f6;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s;
            }
            
            .premium-link:hover {
                color: #2563eb;
                text-decoration: underline;
            }
            
            /* Logo container */
            .logo-container {
                animation: fadeIn 0.6s ease-out;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            /* Card animation */
            .card-container {
                animation: slideUp 0.6s ease-out 0.1s both;
            }
            
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            /* Label styling */
            label {
                display: block;
                font-size: 14px;
                font-weight: 600;
                color: #374151;
                margin-bottom: 8px;
            }
            
            /* Error message styling */
            .error-message {
                color: #dc2626;
                font-size: 13px;
                margin-top: 6px;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 relative z-10">
            <!-- Logo Container -->
            <div class="logo-container mb-8 text-center">
                <div class="flex justify-center mb-4">
                    @if(file_exists(public_path('images/logo/logo.png')))
                        <img src="{{ asset('images/logo/logo.png') }}" alt="{{ config('app.name') }}" class="h-16 w-auto">
                    @elseif(file_exists(public_path('images/logo/logo.svg')))
                        <img src="{{ asset('images/logo/logo.svg') }}" alt="{{ config('app.name') }}" class="h-16 w-auto">
                    @else
                        <div class="h-16 w-16 rounded-xl bg-white flex items-center justify-center shadow-lg">
                            <span class="text-blue-600 text-2xl font-bold">DC</span>
                        </div>
                    @endif
                </div>
                <h1 class="text-white text-2xl font-bold mb-2">
                    {{ config('app.name', 'DigiCRM') }}
                </h1>
                <p class="text-white text-sm font-medium">
                    Welcome back! Please sign in to continue
                </p>
            </div>

            <!-- Login Card -->
            <div class="w-full sm:max-w-[440px] card-container">
                <div class="premium-card p-8">
                    {{ $slot }}
                </div>
                
                <!-- Footer -->
                <p class="text-center text-white text-sm mt-6 font-medium">
                    © {{ date('Y') }} {{ config('app.name', 'DigiCRM') }}. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
