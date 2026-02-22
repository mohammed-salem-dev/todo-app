<x-guest-layout>

    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
        Forgot password?
    </h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        No problem. Enter your email and we'll send you a reset link.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-violet-600 hover:bg-violet-700 text-white
                       font-semibold py-2.5 rounded-xl text-sm
                       focus:ring-2 focus:ring-violet-400 focus:outline-none
                       transition shadow-sm">
            Send reset link →
        </button>

        {{-- Back to login --}}
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 pt-1">
            Remember your password?
            <a href="{{ route('login') }}"
               class="text-violet-600 dark:text-violet-400
                      font-medium hover:underline">
                Sign in
            </a>
        </p>

    </form>

</x-guest-layout>
