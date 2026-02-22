<x-app-layout>
<x-slot name="title">Edit Task</x-slot>

<div class="max-w-2xl mx-auto">
    <a href="{{ route('projects.board', $project) }}"
       class="text-sm text-gray-400 hover:text-violet-500 transition mb-6 inline-block">
        ← Back to Board
    </a>
    <div class="bg-white dark:bg-gray-900
                border border-gray-200 dark:border-gray-800
                rounded-2xl shadow-sm p-8">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
            Edit Task
        </h1>
        @include('tasks._form', ['submitLabel' => 'Save Changes'])
    </div>
</div>
</x-app-layout>
