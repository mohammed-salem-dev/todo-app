<x-app-layout>
<x-slot name="title">My Projects</x-slot>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            My Projects
        </h1>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">
            {{ $projects->count() }} {{ Str::plural('project', $projects->count()) }}
        </p>
    </div>
    <a href="{{ route('projects.create') }}"
       class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700
              text-white text-sm font-semibold px-4 py-2.5 rounded-xl
              shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Project
    </a>
</div>

@if ($projects->isEmpty())
    <div class="text-center py-28">
        <div class="text-6xl mb-4">📋</div>
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">
            No projects yet
        </h3>
        <p class="text-sm text-gray-400 dark:text-gray-500 mb-6">
            Create your first project to get started
        </p>
        <a href="{{ route('projects.create') }}"
           class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700
                  text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
            + Create Project
        </a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($projects as $project)
        <div class="group flex flex-col gap-3 p-5 rounded-2xl
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-800
                    shadow-sm hover:shadow-md
                    hover:border-violet-300 dark:hover:border-violet-700
                    transition-all duration-200">

            {{-- Name + task count badge --}}
            <div class="flex items-start justify-between gap-2">
                <a href="{{ route('projects.show', $project) }}"
                   class="font-semibold text-base leading-snug truncate
                          text-gray-900 dark:text-white
                          hover:text-violet-600 dark:hover:text-violet-400 transition">
                    {{ $project->name }}
                </a>
                <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full
                             bg-violet-50 dark:bg-violet-950
                             text-violet-700 dark:text-violet-300
                             border border-violet-100 dark:border-violet-800">
                    {{ $project->tasks_count }}
                    {{ Str::plural('task', $project->tasks_count) }}
                </span>
            </div>

            {{-- Description --}}
            @if ($project->description)
                <p class="text-sm leading-relaxed line-clamp-2
                           text-gray-500 dark:text-gray-400">
                    {{ $project->description }}
                </p>
            @endif

  {{-- Footer --}}
<div class="mt-auto pt-3
            border-t border-gray-100 dark:border-gray-800
            flex items-center justify-between gap-4">

    {{-- Created date --}}
    <div class="flex flex-col gap-0.5 min-w-0">
        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
            {{ $project->created_at->format('M d, Y') }}
        </span>
        <span class="text-xs text-gray-300 dark:text-gray-600">
            {{ $project->created_at->diffForHumans() }}
        </span>
    </div>

    {{-- Actions — shrink-0 keeps them from wrapping --}}
    <div class="flex items-center gap-1 text-xs shrink-0">
        <a href="{{ route('projects.board', $project) }}"
           class="px-2.5 py-1 rounded-lg font-medium
                  text-violet-600 dark:text-violet-400
                  hover:bg-violet-50 dark:hover:bg-violet-950 transition">
            Board
        </a>
        <a href="{{ route('projects.edit', $project) }}"
           class="px-2.5 py-1 rounded-lg
                  text-gray-500 dark:text-gray-400
                  hover:bg-gray-100 dark:hover:bg-gray-800
                  hover:text-gray-700 dark:hover:text-gray-200 transition">
            Edit
        </a>
        <form method="POST"
              action="{{ route('projects.destroy', $project) }}"
              x-data
              @submit.prevent="
                  confirm('Delete \'{{ addslashes($project->name) }}\' and all its tasks?')
                  && $el.submit()">
            @csrf
            @method('DELETE')
            <button type="submit"
                    title="Delete project"
                    class="p-1.5 rounded-lg
                           text-gray-400 dark:text-gray-500
                           hover:bg-red-50 dark:hover:bg-red-950
                           hover:text-red-500 dark:hover:text-red-400 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
    </div>

</div>



        </div>
        @endforeach
    </div>
@endif

</x-app-layout>
