<x-guest-layout>
    <!-- Mobile Logo (Visible only on small screens) -->
    <div class="lg:hidden text-center mb-8">
        <div class="flex justify-center mb-4">
            @php
                $logoLight = \App\Models\Setting::get('crm_logo_light');
                $logoDark = \App\Models\Setting::get('crm_logo_dark');
            @endphp

            @if($logoDark)
                <img src="{{ asset('storage/' . $logoDark) }}" alt="{{ config('app.name') }}" class="h-12 w-auto dark:hidden">
            @endif
            @if($logoLight)
                <img src="{{ asset('storage/' . $logoLight) }}" alt="{{ config('app.name') }}" class="h-12 w-auto hidden dark:block">
            @endif

            @if(!$logoDark && !$logoLight)
                @if(file_exists(public_path('images/logo/logo.png')))
                    <img src="{{ asset('images/logo/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 w-auto">
                @elseif(file_exists(public_path('images/logo/logo.svg')))
                    <img src="{{ asset('images/logo/logo.svg') }}" alt="{{ config('app.name') }}" class="h-12 w-auto">
                @else
                    <div class="h-12 w-12 rounded-lg bg-indigo-600 flex items-center justify-center shadow-lg">
                        <span class="text-white text-xl font-bold">DC</span>
                    </div>
                @endif
            @elseif(!$logoDark && $logoLight)
                 <img src="{{ asset('storage/' . $logoLight) }}" alt="{{ config('app.name') }}" class="h-12 w-auto dark:hidden">
            @elseif($logoDark && !$logoLight)
                 <img src="{{ asset('storage/' . $logoDark) }}" alt="{{ config('app.name') }}" class="h-12 w-auto hidden dark:block">
            @endif
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ config('app.name', 'DigiCRM') }}</h2>
    </div>

    <!-- Header -->
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Welcome back</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Please enter your details to sign in.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
            <div class="relative">
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    class="block w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200"
                    placeholder="you@company.com"
                >
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
            <div class="relative">
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    class="block w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200"
                    placeholder="••••••••"
                >
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center cursor-pointer">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition duration-150 ease-in-out" 
                    name="remember"
                >
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-[1.02]">
            Sign in
        </button>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                        Sign up for free
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>
