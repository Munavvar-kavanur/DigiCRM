<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Sign In</h2>
        <p class="text-sm text-gray-500 mt-1">Enter your credentials to access your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email">Email Address</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                class="premium-input"
                placeholder="you@example.com"
            >
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password">Password</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                class="premium-input"
                placeholder="Enter your password"
            >
            @error('password')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="flex items-center gap-2.5 cursor-pointer group mb-0">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="premium-checkbox" 
                    name="remember"
                >
                <span class="text-sm font-medium text-gray-700 select-none">
                    Remember me
                </span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm premium-link">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="pt-2">
            <button type="submit" class="premium-button">
                Sign In
            </button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="premium-link">
                        Create account
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>
