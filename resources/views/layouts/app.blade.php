<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="h-full"
      x-data="{ dark: localStorage.getItem('theme') === 'dark' }"
      x-init="$watch('dark', val => {
          localStorage.setItem('theme', val ? 'dark' : 'light');
          document.documentElement.classList.toggle('dark', val);
      })">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <meta charset="utf-8" />

    {{-- ↓ ADD THIS — must be first, before CSS loads --}}
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    {{-- ↑ Runs synchronously, zero flash --}}

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name') }} — {{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased
             bg-gray-50 dark:bg-[#0f1117]
             text-gray-900 dark:text-gray-100 transition-colors duration-200">

    {{-- ── Top Nav ─────────────────────────────────────────── --}}
    <nav class="bg-white dark:bg-gray-900
                border-b border-gray-200 dark:border-gray-800
                sticky top-0 z-40 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">

                {{-- Logo --}}
                <a href="{{ route('projects.index') }}"
                   class="flex items-center gap-2 text-violet-600 dark:text-violet-400
                          font-bold text-lg tracking-tight hover:opacity-80 transition">
                    Todo App
                </a>

                {{-- Right side --}}
                <div class="flex items-center gap-1">

                    {{-- Labels link --}}
                    <a href="{{ route('labels.index') }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm
                              text-gray-600 dark:text-gray-400
                              hover:bg-gray-100 dark:hover:bg-gray-800
                              hover:text-violet-600 dark:hover:text-violet-400
                              transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Labels
                    </a>

                    {{-- Divider --}}
                    <span class="w-px h-5 bg-gray-200 dark:bg-gray-700 mx-1"></span>

                    {{-- Dark mode toggle --}}
                    <button @click="dark = !dark"
                            class="p-2 rounded-lg text-gray-500 dark:text-gray-400
                                   hover:bg-gray-100 dark:hover:bg-gray-800
                                   transition"
                            :title="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                        {{-- Sun icon (shown in dark mode) --}}
                        <svg x-show="dark" class="w-4 h-4" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                        </svg>
                        {{-- Moon icon (shown in light mode) --}}
                        <svg x-show="!dark" class="w-4 h-4" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    {{-- Divider --}}
                    <span class="w-px h-5 bg-gray-200 dark:bg-gray-700 mx-1"></span>

                    {{-- User dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm
                                       text-gray-700 dark:text-gray-300
                                       hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            {{-- Avatar initial --}}
                            <span class="w-6 h-6 rounded-full bg-violet-100 dark:bg-violet-900
                                         text-violet-700 dark:text-violet-300
                                         text-xs font-bold flex items-center justify-center uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="hidden sm:block font-medium">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                                 :class="{ 'rotate-180': open }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown menu --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-1 w-44 bg-white dark:bg-gray-900
                                    border border-gray-200 dark:border-gray-700
                                    rounded-xl shadow-lg py-1 z-50"
                             style="display:none">

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm
                                      text-gray-700 dark:text-gray-300
                                      hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profile
                            </a>

                            <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm
                                               text-red-500 hover:bg-red-50 dark:hover:bg-red-950
                                               transition text-left">
                                    <svg class="w-4 h-4" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4">
            <div class="bg-green-50 dark:bg-green-950 border border-green-200
                        dark:border-green-800 text-green-800 dark:text-green-300
                        text-sm rounded-xl px-4 py-3 flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-4">
            <div class="bg-red-50 dark:bg-red-950 border border-red-200
                        dark:border-red-800 text-red-800 dark:text-red-300
                        text-sm rounded-xl px-4 py-3 flex items-center gap-2">
                <span>⚠</span> {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Page Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    @stack('scripts')
</body>
</html>
