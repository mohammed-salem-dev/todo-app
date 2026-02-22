<form method="POST"
      action="{{ isset($task)
          ? route('projects.tasks.update', [$project, $task])
          : route('projects.tasks.store', $project) }}"
      class="space-y-5">
    @csrf
    @if (isset($task)) @method('PATCH') @endif

    {{-- Title --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Title <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title"
               value="{{ old('title', $task->title ?? '') }}"
               required autofocus
               class="w-full border border-gray-300 dark:border-gray-700
                      rounded-xl px-4 py-2.5 text-sm
                      bg-white dark:bg-gray-800
                      text-gray-900 dark:text-gray-100
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
        @error('title')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Details --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Details <span class="text-gray-400 font-normal">(optional)</span>
        </label>
        <textarea name="details" rows="3"
                  class="w-full border border-gray-300 dark:border-gray-700
                         rounded-xl px-4 py-2.5 text-sm
                         bg-white dark:bg-gray-800
                         text-gray-900 dark:text-gray-100
                         placeholder-gray-400 dark:placeholder-gray-500
                         focus:ring-2 focus:ring-violet-400 focus:outline-none
                         transition resize-none">{{ old('details', $task->details ?? '') }}</textarea>
    </div>

    {{-- State + Due date --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                State
            </label>
            <select name="state"
                    class="w-full border border-gray-300 dark:border-gray-700
                           rounded-xl px-4 py-2.5 text-sm
                           bg-white dark:bg-gray-800
                           text-gray-900 dark:text-gray-100
                           focus:ring-2 focus:ring-violet-400 focus:outline-none
                           appearance-none transition">
                @foreach (App\Enums\TaskState::cases() as $state)
                    <option value="{{ $state->value }}"
                        {{ old('state', $task->state->value ?? 'todo') === $state->value ? 'selected' : '' }}>
                        {{ $state->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Due Date <span class="text-gray-400 font-normal">(optional)</span>
            </label>
            <input type="date" name="due_at"
                   value="{{ old('due_at', isset($task->due_at) ? $task->due_at->format('Y-m-d') : '') }}"
                   class="w-full border border-gray-300 dark:border-gray-700
                          rounded-xl px-4 py-2.5 text-sm
                          bg-white dark:bg-gray-800
                          text-gray-900 dark:text-gray-100
                          focus:ring-2 focus:ring-violet-400 focus:outline-none transition
                          [color-scheme:light] dark:[color-scheme:dark]">
        </div>
    </div>

    {{-- Labels --}}
    @if ($labels->isNotEmpty())
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Labels
        </label>
        <div class="flex flex-wrap gap-2">
            @foreach ($labels as $label)
                @php
                    $checked = in_array($label->id, old('labels', $selectedIds ?? []));
                    $colors  = [
                        'slate'   => 'bg-slate-100   dark:bg-slate-800   text-slate-700   dark:text-slate-300   border-slate-300   dark:border-slate-600',
                        'blue'    => 'bg-blue-100    dark:bg-blue-900    text-blue-700    dark:text-blue-300    border-blue-300    dark:border-blue-600',
                        'emerald' => 'bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-600',
                        'red'     => 'bg-red-100     dark:bg-red-900     text-red-700     dark:text-red-300     border-red-300     dark:border-red-600',
                        'yellow'  => 'bg-yellow-100  dark:bg-yellow-900  text-yellow-700  dark:text-yellow-300  border-yellow-300  dark:border-yellow-600',
                        'purple'  => 'bg-purple-100  dark:bg-purple-900  text-purple-700  dark:text-purple-300  border-purple-300  dark:border-purple-600',
                        'pink'    => 'bg-pink-100    dark:bg-pink-900    text-pink-700    dark:text-pink-300    border-pink-300    dark:border-pink-600',
                        'orange'  => 'bg-orange-100  dark:bg-orange-900  text-orange-700  dark:text-orange-300  border-orange-300  dark:border-orange-600',
                    ];
                    $cls = $colors[$label->color] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600';
                @endphp
                <label class="flex items-center gap-1.5 cursor-pointer px-3 py-1 rounded-full
                              border text-xs font-medium transition
                              {{ $cls }} {{ $checked ? 'ring-2 ring-violet-400' : '' }}">
                    <input type="checkbox" name="labels[]" value="{{ $label->id }}"
                           class="hidden" {{ $checked ? 'checked' : '' }}>
                    {{ $label->name }}
                </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recurrence --}}
    <div x-data="{ hasRecurrence: {{ old('recurrence_type', $task->recurrence_type ?? null) ? 'true' : 'false' }} }">
        <label class="flex items-center gap-2 text-sm font-medium
                      text-gray-700 dark:text-gray-300 cursor-pointer mb-2">
            <input type="checkbox" x-model="hasRecurrence"
                   class="rounded border-gray-300 dark:border-gray-600
                          text-violet-600 focus:ring-violet-400">
            Recurring task
        </label>

        <div x-show="hasRecurrence" x-cloak class="grid grid-cols-2 gap-4 mt-2">
            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">
                    Repeat
                </label>
                <select name="recurrence_type"
                        class="w-full border border-gray-300 dark:border-gray-700
                               rounded-xl px-4 py-2.5 text-sm
                               bg-white dark:bg-gray-800
                               text-gray-900 dark:text-gray-100
                               focus:ring-2 focus:ring-violet-400 focus:outline-none
                               appearance-none transition">
                    <option value="">None</option>
                    @foreach (App\Enums\RecurrenceType::cases() as $type)
                        <option value="{{ $type->value }}"
                            {{ old('recurrence_type', $task->recurrence_type?->value ?? '') === $type->value ? 'selected' : '' }}>
                            {{ ucfirst($type->value) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">
                    Every N intervals
                </label>
                <input type="number" name="recurrence_interval" min="1" max="99"
                       value="{{ old('recurrence_interval', $task->recurrence_interval ?? 1) }}"
                       class="w-full border border-gray-300 dark:border-gray-700
                              rounded-xl px-4 py-2.5 text-sm
                              bg-white dark:bg-gray-800
                              text-gray-900 dark:text-gray-100
                              focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2 border-t
                border-gray-100 dark:border-gray-800">
        <a href="{{ route('projects.board', $project) }}"
           class="text-sm text-gray-500 dark:text-gray-400
                  hover:text-gray-700 dark:hover:text-gray-200 transition">
            Cancel
        </a>
        <button type="submit"
                class="bg-violet-600 hover:bg-violet-700 text-white
                       text-sm font-semibold px-5 py-2.5 rounded-xl
                       transition shadow-sm">
            {{ $submitLabel ?? 'Save Task' }}
        </button>
    </div>
</form>
