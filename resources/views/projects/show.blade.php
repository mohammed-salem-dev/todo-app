<x-app-layout>
<x-slot name="title">{{ $project->name }}</x-slot>

{{-- Header --}}
<div class="flex items-start justify-between mb-6">
    <div>
        <a href="{{ route('projects.index') }}"
           class="text-sm text-gray-400 hover:text-violet-500 transition">
            ← Projects
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
            {{ $project->name }}
        </h1>
        @if ($project->description)
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $project->description }}
            </p>
        @endif
    </div>
    <div class="flex items-center gap-2 mt-1">
        <a href="{{ route('projects.edit', $project) }}"
           class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-700
                  text-gray-600 dark:text-gray-400 rounded-lg
                  hover:border-violet-400 hover:text-violet-600
                  dark:hover:text-violet-400 transition">
            Edit
        </a>
        <form method="POST" action="{{ route('projects.destroy', $project) }}"
              x-data
              @submit.prevent="confirm('Delete this project and all its tasks?') && $el.submit()">
            @csrf @method('DELETE')
            <button type="submit"
                    class="px-3 py-1.5 text-sm border border-red-300 dark:border-red-800
                           text-red-500 rounded-lg hover:bg-red-50
                           dark:hover:bg-red-950 transition">
                Delete
            </button>
        </form>
    </div>
</div>

{{-- Stat tiles --}}
@php
$tiles = [
    ['label' => 'To Do',  'state' => 'todo',  'color' => 'text-gray-500 dark:text-gray-400',   'badge' => 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300'],
    ['label' => 'Doing',  'state' => 'doing', 'color' => 'text-blue-500',                       'badge' => 'bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-300'],
    ['label' => 'Done',   'state' => 'done',  'color' => 'text-emerald-500',                    'badge' => 'bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300'],
];
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach ($tiles as $tile)
    <div class="bg-white dark:bg-gray-900
                border border-gray-200 dark:border-gray-800
                rounded-2xl p-5 text-center shadow-sm">
        <div class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ $taskCounts[$tile['state']] ?? 0 }}
        </div>
        <span class="inline-block mt-1 text-xs font-medium px-2 py-0.5
                     rounded-full {{ $tile['badge'] }}">
            {{ $tile['label'] }}
        </span>
    </div>
    @endforeach

    {{-- Open Board tile --}}
    <a href="{{ route('projects.board', $project) }}"
       class="bg-violet-50 dark:bg-violet-950
              border border-violet-200 dark:border-violet-800
              rounded-2xl p-5 text-center shadow-sm
              hover:bg-violet-100 dark:hover:bg-violet-900
              transition flex flex-col items-center justify-center gap-1">
        <span class="text-3xl">📋</span>
        <span class="text-sm font-semibold text-violet-600 dark:text-violet-400">
            Open Board →
        </span>
    </a>
</div>

{{-- Activity Feed --}}
<div class="bg-white dark:bg-gray-900
            border border-gray-200 dark:border-gray-800
            rounded-2xl shadow-sm p-6">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            Recent Activity
        </h2>
        <span class="text-xs text-gray-400">Latest 20 events</span>
    </div>

    @if ($activity->isEmpty())
        <div class="text-center py-10">
            <p class="text-3xl mb-2">🕓</p>
            <p class="text-sm text-gray-400">No activity yet.</p>
        </div>
    @else
        <ol class="relative border-l border-gray-100 dark:border-gray-800 space-y-0 ml-3">
            @foreach ($activity as $event)
            <li class="mb-5 ml-4">
                <span class="absolute -left-1.5 mt-1.5 w-3 h-3 rounded-full border-2
                             border-white dark:border-gray-900
                             {{ str_contains($event->action, 'deleted')   ? 'bg-red-400'     :
                               (str_contains($event->action, 'created')   ? 'bg-emerald-400' :
                               (str_contains($event->action, 'completed') ? 'bg-emerald-400' :
                               (str_contains($event->action, 'state')     ? 'bg-blue-400'    :
                                                                             'bg-violet-300'))) }}">
                </span>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-snug">
                    {{ \App\Services\ActivityLogger::describe($event) }}
                </p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs text-gray-400">
                        {{ $event->created_at->diffForHumans() }}
                    </span>
                    <span class="text-gray-300 dark:text-gray-600">·</span>
                    <span class="text-xs text-gray-400 font-mono">
                        {{ $event->action }}
                    </span>
                </div>
            </li>
            @endforeach
        </ol>
    @endif
</div>

</x-app-layout>
