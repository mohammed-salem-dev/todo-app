<div x-data="{ open: false }">
    <button @click="open = true"
            class="bg-red-600 hover:bg-red-700 text-white
                   text-sm font-semibold px-5 py-2 rounded-xl transition">
        Delete Account
    </button>

    {{-- Confirmation modal --}}
    <div x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center
                bg-black/50 backdrop-blur-sm px-4">
        <div class="bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-800
                    rounded-2xl shadow-xl p-6 w-full max-w-md"
             @click.outside="open = false">

            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">
                Are you sure?
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Once your account is deleted, all data will be permanently removed.
                Enter your password to confirm.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf @method('delete')

                <div>
                    <label class="block text-sm font-medium
                                  text-gray-700 dark:text-gray-300 mb-1">
                        Password
                    </label>
                    <input type="password" name="password"
                           placeholder="Your current password"
                           class="w-full border border-gray-300 dark:border-gray-700
                                  rounded-xl px-4 py-2.5 text-sm
                                  bg-white dark:bg-gray-800
                                  text-gray-900 dark:text-gray-100
                                  focus:ring-2 focus:ring-red-400 focus:outline-none transition">
                    @error('password', 'userDeletion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="open = false"
                            class="text-sm text-gray-500 dark:text-gray-400
                                   hover:text-gray-700 dark:hover:text-gray-200 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white
                                   text-sm font-semibold px-5 py-2 rounded-xl transition">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
