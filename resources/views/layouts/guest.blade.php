<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="h-full"
      x-data="{ dark: localStorage.getItem('theme') === 'dark' }"
      x-init="$watch('dark', val => {
          localStorage.setItem('theme', val ? 'dark' : 'light');
          document.documentElement.classList.toggle('dark', val);
      })">
<head>
    <meta charset="utf-8" />

    {{-- Prevent flash --}}
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased
             bg-gray-50 dark:bg-gray-950
             text-gray-900 dark:text-gray-100
             transition-colors duration-200">

    <div class="min-h-full flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">

        {{-- App name --}}
        <div class="text-center mb-8">
            <a href="/"
               class="text-2xl font-bold tracking-tight
                      text-violet-600 dark:text-violet-400 hover:opacity-80 transition">
                {{ config('app.name') }}
            </a>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                Manage your projects and tasks
            </p>
        </div>

        {{-- Card --}}
        <div class="w-full max-w-md mx-auto
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-800
                    rounded-2xl shadow-xl px-8 py-10">
            {{ $slot }}
        </div>

        {{-- Dark mode toggle --}}
        <div class="flex justify-center mt-6">
            <button @click="dark = !dark"
                    class="flex items-center gap-2 text-xs text-gray-400
                           dark:text-gray-500 hover:text-gray-600
                           dark:hover:text-gray-300 transition">
                <svg x-show="dark" class="w-4 h-4" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
                <svg x-show="!dark" class="w-4 h-4" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <span x-text="dark ? 'Light mode' : 'Dark mode'"></span>
            </button>
        </div>

    </div>
</body>
</html>
