<x-guest-layout>

    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
        Welcome back
    </h2>
    <p class="text-sm text-gray-400 dark:text-gray-500 mb-6">
        Sign in to your account to continue
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email"
                   class="block text-sm font-medium
                          text-gray-700 dark:text-gray-300 mb-1">
                Email address
            </label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="w-full border border-gray-300 dark:border-gray-700
                          rounded-xl px-4 py-2.5 text-sm
                          bg-white dark:bg-gray-800
                          text-gray-900 dark:text-gray-100
                          placeholder-gray-400 dark:placeholder-gray-500
                          focus:ring-2 focus:ring-violet-400 focus:outline-none
                          transition
                          {{ $errors->has('email') ? 'border-red-400' : '' }}">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password"
                       class="block text-sm font-medium
                              text-gray-700 dark:text-gray-300">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-violet-500 dark:text-violet-400
                              hover:underline transition">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password"
                   class="w-full border border-gray-300 dark:border-gray-700
                          rounded-xl px-4 py-2.5 text-sm
                          bg-white dark:bg-gray-800
                          text-gray-900 dark:text-gray-100
                          focus:ring-2 focus:ring-violet-400 focus:outline-none
                          transition
                          {{ $errors->has('password') ? 'border-red-400' : '' }}">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Remember me --}}
        <label class="flex items-center gap-2 text-sm
                      text-gray-600 dark:text-gray-400 cursor-pointer">
            <input type="checkbox" name="remember"
                   class="rounded border-gray-300 dark:border-gray-600
                          text-violet-600 focus:ring-violet-400">
            Remember me
        </label>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-violet-600 hover:bg-violet-700 text-white
                       font-semibold py-2.5 rounded-xl text-sm
                       focus:ring-2 focus:ring-violet-400 focus:outline-none
                       transition shadow-sm">
            Sign in →
        </button>

        {{-- Register link --}}
        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 pt-1">
                Don't have an account?
                <a href="{{ route('register') }}"
                   class="text-violet-600 dark:text-violet-400
                          font-medium hover:underline">
                    Create one
                </a>
            </p>
        @endif

    </form>

</x-guest-layout>
