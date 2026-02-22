<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
    @csrf @method('put')

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Current Password
        </label>
        <input type="password" name="current_password"
               autocomplete="current-password"
               class="w-full border border-gray-300 dark:border-gray-700
                      rounded-xl px-4 py-2.5 text-sm
                      bg-white dark:bg-gray-800
                      text-gray-900 dark:text-gray-100
                      focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
        @error('current_password', 'updatePassword')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            New Password
        </label>
        <input type="password" name="password"
               autocomplete="new-password"
               class="w-full border border-gray-300 dark:border-gray-700
                      rounded-xl px-4 py-2.5 text-sm
                      bg-white dark:bg-gray-800
                      text-gray-900 dark:text-gray-100
                      focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
        @error('password', 'updatePassword')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Confirm Password
        </label>
        <input type="password" name="password_confirmation"
               autocomplete="new-password"
               class="w-full border border-gray-300 dark:border-gray-700
                      rounded-xl px-4 py-2.5 text-sm
                      bg-white dark:bg-gray-800
                      text-gray-900 dark:text-gray-100
                      focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
        @error('password_confirmation', 'updatePassword')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-4 pt-1">
        <button type="submit"
                class="bg-violet-600 hover:bg-violet-700 text-white
                       text-sm font-semibold px-5 py-2 rounded-xl transition">
            Save
        </button>
        @if (session('status') === 'password-updated')
            <span class="text-sm text-emerald-600 dark:text-emerald-400">
                ✓ Updated
            </span>
        @endif
    </div>
</form>
