<x-app-layout>
<x-slot name="title">New Project</x-slot>

<div class="max-w-xl mx-auto">
    <a href="{{ route('projects.index') }}"
       class="text-sm text-gray-400 hover:text-violet-500 transition mb-6 inline-block">
        ← Back to Projects
    </a>

    <div class="bg-white dark:bg-gray-900
                border border-gray-200 dark:border-gray-800
                rounded-2xl shadow-sm p-8">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
            Create New Project
        </h1>
        @include('projects._form', ['submitLabel' => 'Create Project'])
    </div>
</div>
</x-app-layout>
