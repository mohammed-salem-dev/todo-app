<x-app-layout>
<x-slot name="title">{{ $project->name }} — Board</x-slot>

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('projects.show', $project) }}"
           class="text-sm text-gray-400 hover:text-violet-500 transition">
            ← {{ $project->name }}
        </a>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mt-1">Kanban Board</h1>
    </div>
    <a href="{{ route('projects.tasks.create', $project) }}"
       class="inline-flex items-center gap-1 bg-violet-600 text-white
              text-sm font-medium px-4 py-2 rounded-lg hover:bg-violet-700 transition">
        + Add Task
    </a>
</div>

@php
$columns = [
    'todo'  => ['label' => 'To Do', 'dot' => 'bg-slate-400'],
    'doing' => ['label' => 'Doing', 'dot' => 'bg-blue-400'],
    'done'  => ['label' => 'Done',  'dot' => 'bg-emerald-400'],
];

$labelColors = [
    'slate'   => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300',
    'emerald' => 'bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300',
    'blue'    => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300',
    'red'     => 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300',
    'yellow'  => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300',
    'purple'  => 'bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300',
    'pink'    => 'bg-pink-100 dark:bg-pink-900 text-pink-700 dark:text-pink-300',
    'orange'  => 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300',
];
@endphp

@include('partials._task_filters', ['labels' => $labels, 'filters' => $filters])

<div
    x-data="kanbanBoard('{{ $project->id }}', '{{ csrf_token() }}')"
    x-init="init()"
>
    {{-- Saving toast --}}
    <div
        x-show="saving"
        x-transition
        class="fixed bottom-5 right-5 z-50 bg-gray-900 dark:bg-gray-700 text-white
               text-sm px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        Saving…
    </div>

    {{-- Error toast --}}
    <div
        x-show="error"
        x-transition
        class="fixed bottom-5 right-5 z-50 bg-red-600 text-white
               text-sm px-4 py-2 rounded-lg shadow-lg">
        ⚠ Save failed — please refresh.
    </div>

    {{-- Columns --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">

        @foreach ($columns as $state => $col)
        <div class="flex flex-col gap-2">

            {{-- Column header --}}
            <div class="flex items-center gap-2 px-1">
                <span class="w-2.5 h-2.5 rounded-full {{ $col['dot'] }}"></span>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    {{ $col['label'] }}
                </span>
                <span class="ml-auto text-xs font-medium
                             text-gray-400 dark:text-gray-500
                             bg-gray-100 dark:bg-gray-800
                             px-2 py-0.5 rounded-full">
                    {{ ($tasks[$state] ?? collect())->count() }}
                </span>
            </div>

            {{-- Drop zone --}}
            <div
                class="kanban-column min-h-28 rounded-xl p-2 space-y-2
                       bg-gray-50 dark:bg-gray-800/50
                       border border-dashed
                       border-gray-200 dark:border-gray-700"
                data-state="{{ $state }}"
            >
                @foreach ($tasks[$state] ?? [] as $task)
                <div
                    class="task-card
                           bg-white dark:bg-gray-900
                           rounded-lg border
                           border-gray-200 dark:border-gray-700
                           shadow-sm p-3 cursor-grab active:cursor-grabbing
                           hover:border-violet-300 dark:hover:border-violet-600
                           transition select-none"
                    data-task-id="{{ $task->id }}"
                >
                    {{-- Title --}}
                    <p class="text-sm font-medium
                              text-gray-800 dark:text-gray-100
                              leading-snug">
                        {{ $task->title }}
                    </p>

                    {{-- Details preview --}}
                    @if ($task->details)
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 line-clamp-2">
                            {{ $task->details }}
                        </p>
                    @endif

                    {{-- Due date --}}
                    @if ($task->due_at)
                        @php
                            $overdue = $task->due_at->isPast()
                                    && $task->state !== \App\Enums\TaskState::Done;
                        @endphp
                        <p class="text-xs mt-1.5
                                  {{ $overdue
                                      ? 'text-red-500 font-medium'
                                      : 'text-gray-400 dark:text-gray-500' }}">
                            📅 {{ $task->due_at->format('M d') }}
                            @if ($overdue) · overdue @endif
                        </p>
                    @endif

                    {{-- Labels --}}
                    @if ($task->labels->isNotEmpty())
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach ($task->labels as $label)
                                @php
                                    $cls = $labelColors[$label->color]
                                        ?? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300';
                                @endphp
                                <span class="text-xs px-1.5 py-0.5 rounded-full font-medium {{ $cls }}">
                                    {{ $label->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Recurring badge --}}
                    @if ($task->isRecurring())
                        <p class="text-xs text-violet-500 dark:text-violet-400 mt-1.5">
                            🔁 {{ $task->recurrence_type->label() }}
                        </p>
                    @endif

                    {{-- Card actions --}}
                    <div class="flex items-center gap-3 mt-2 pt-2
                                border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                           class="text-xs text-gray-400 dark:text-gray-500
                                  hover:text-violet-500 dark:hover:text-violet-400 transition">
                            ✏ Edit
                        </a>
                        <form method="POST"
                              action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                              x-data
                              @submit.prevent="confirm('Delete \'{{ addslashes($task->title) }}\'?') && $el.submit()">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-gray-400 dark:text-gray-500
                                           hover:text-red-500 dark:hover:text-red-400 transition">
                                🗑 Delete
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach

            </div>
        </div>
        @endforeach

    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
function kanbanBoard(projectId, csrfToken) {
    return {
        saving: false,
        error:  false,

        init() {
            document.querySelectorAll('.kanban-column').forEach(column => {
                Sortable.create(column, {
                    group:      'tasks',
                    animation:  150,
                    ghostClass: 'opacity-40',
                    dragClass:  'rotate-1',
                    onEnd: (evt) => this.onDragEnd(evt),
                });
            });
        },

        onDragEnd(evt) {
            const taskId     = parseInt(evt.item.dataset.taskId);
            const toState    = evt.to.dataset.state;
            const orderedIds = [...evt.to.children]
                                .map(el => parseInt(el.dataset.taskId))
                                .filter(id => !isNaN(id));

            this.saving = true;
            this.error  = false;

            fetch(`/projects/${projectId}/board/move`, {
                method:  'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept':       'application/json',
                },
                body: JSON.stringify({
                    task_id:          taskId,
                    to_state:         toState,
                    ordered_task_ids: orderedIds,
                }),
            })
            .then(r => {
                if (!r.ok) throw new Error('Server error');
                return r.json();
            })
            .then(data => {
                this.saving = false;
                if (!data.success) {
                    this.error = true;
                    setTimeout(() => this.error = false, 4000);
                }
            })
            .catch(() => {
                this.saving = false;
                this.error  = true;
                setTimeout(() => this.error = false, 4000);
            });
        },
    };
}
</script>
@endpush

</x-app-layout>
