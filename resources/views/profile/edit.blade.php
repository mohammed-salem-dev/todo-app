<x-app-layout>
<x-slot name="title">Profile</x-slot>

<div class="max-w-2xl mx-auto space-y-6">

    {{-- Update Profile Info --}}
    <div class="bg-white dark:bg-gray-900
                border border-gray-200 dark:border-gray-800
                rounded-2xl shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
            Profile Information
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
            Update your account's profile information and email address.
        </p>
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Update Password --}}
    <div class="bg-white dark:bg-gray-900
                border border-gray-200 dark:border-gray-800
                rounded-2xl shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
            Update Password
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
            Ensure your account is using a long, random password to stay secure.
        </p>
        @include('profile.partials.update-password-form')
    </div>

    {{-- Delete Account --}}
    <div class="bg-white dark:bg-gray-900
                border border-red-100 dark:border-red-900
                rounded-2xl shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
            Delete Account
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
        @include('profile.partials.delete-user-form')
    </div>

</div>
</x-app-layout>
