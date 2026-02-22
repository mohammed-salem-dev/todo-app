<form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf @method('patch')

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Name
        </label>
        <input type="text" name="name"
               value="{{ old('name', $user->name) }}"
               required autofocus autocomplete="name"
               class="w-full border border-gray-300 dark:border-gray-700
                      rounded-xl px-4 py-2.5 text-sm
                      bg-white dark:bg-gray-800
                      text-gray-900 dark:text-gray-100
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Email
        </label>
        <input type="email" name="email"
               value="{{ old('email', $user->email) }}"
               required autocomplete="username"
               class="w-full border border-gray-300 dark:border-gray-700
                      rounded-xl px-4 py-2.5 text-sm
                      bg-white dark:bg-gray-800
                      text-gray-900 dark:text-gray-100
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
        @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-4 pt-1">
        <button type="submit"
                class="bg-violet-600 hover:bg-violet-700 text-white
                       text-sm font-semibold px-5 py-2 rounded-xl transition">
            Save
        </button>
        @if (session('status') === 'profile-updated')
            <span class="text-sm text-emerald-600 dark:text-emerald-400">
                ✓ Saved
            </span>
        @endif
    </div>
</form>
