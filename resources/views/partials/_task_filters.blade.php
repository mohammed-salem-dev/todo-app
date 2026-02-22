@php
    $dueBuckets = [
        'overdue'       => '🔴 Overdue',
        'due_today'     => '🟡 Due Today',
        'due_this_week' => '🟢 Due This Week',
    ];

    $states    = App\Enums\TaskState::cases();
    $clearBase = url()->current();
@endphp

<form method="GET" action="{{ url()->current() }}"
      class="bg-white dark:bg-gray-900
             border border-gray-200 dark:border-gray-800
             rounded-xl shadow-sm px-4 py-3 mb-6">

    <div class="flex flex-wrap gap-3 items-end">

        {{-- Search --}}
        <div class="flex-1 min-w-44">
            <label class="block text-xs font-medium
                          text-gray-500 dark:text-gray-400 mb-1">
                Search
            </label>
            <input
                type="text"
                name="search"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="Title or details…"
                class="w-full border border-gray-300 dark:border-gray-700
                       rounded-lg px-3 py-1.5 text-sm
                       bg-white dark:bg-gray-800
                       text-gray-900 dark:text-gray-100
                       placeholder-gray-400 dark:placeholder-gray-500
                       focus:ring-2 focus:ring-violet-400 focus:outline-none transition"
            >
        </div>

        {{-- State --}}
        <div class="min-w-32">
            <label class="block text-xs font-medium
                          text-gray-500 dark:text-gray-400 mb-1">
                State
            </label>
            <select name="state"
                    class="w-full border border-gray-300 dark:border-gray-700
                           rounded-lg px-3 py-1.5 text-sm
                           bg-white dark:bg-gray-800
                           text-gray-900 dark:text-gray-100
                           appearance-none cursor-pointer
                           focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
                <option value="">All states</option>
                @foreach ($states as $state)
                    <option value="{{ $state->value }}"
                        {{ ($filters['state'] ?? '') === $state->value ? 'selected' : '' }}>
                        {{ $state->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Label --}}
        @if ($labels->isNotEmpty())
        <div class="min-w-36">
            <label class="block text-xs font-medium
                          text-gray-500 dark:text-gray-400 mb-1">
                Label
            </label>
            <select name="label_id"
                    class="w-full border border-gray-300 dark:border-gray-700
                           rounded-lg px-3 py-1.5 text-sm
                           bg-white dark:bg-gray-800
                           text-gray-900 dark:text-gray-100
                           appearance-none cursor-pointer
                           focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
                <option value="">All labels</option>
                @foreach ($labels as $label)
                    <option value="{{ $label->id }}"
                        {{ (int)($filters['label_id'] ?? 0) === $label->id ? 'selected' : '' }}>
                        {{ $label->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        {{-- Due Bucket --}}
        <div class="min-w-40">
            <label class="block text-xs font-medium
                          text-gray-500 dark:text-gray-400 mb-1">
                Due
            </label>
            <select name="due_bucket"
                    class="w-full border border-gray-300 dark:border-gray-700
                           rounded-lg px-3 py-1.5 text-sm
                           bg-white dark:bg-gray-800
                           text-gray-900 dark:text-gray-100
                           appearance-none cursor-pointer
                           focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
                <option value="">Any date</option>
                @foreach ($dueBuckets as $value => $bucketLabel)
                    <option value="{{ $value }}"
                        {{ ($filters['due_bucket'] ?? '') === $value ? 'selected' : '' }}>
                        {{ $bucketLabel }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-2 pb-0.5">
            <button type="submit"
                    class="bg-violet-600 hover:bg-violet-700 text-white
                           text-sm font-medium px-4 py-1.5 rounded-lg transition">
                Filter
            </button>
            @if (!empty($filters))
                <a href="{{ $clearBase }}"
                   class="text-sm text-gray-400 dark:text-gray-500
                          hover:text-red-500 dark:hover:text-red-400 transition">
                    ✕ Clear
                </a>
            @endif
        </div>

    </div>

    {{-- Active filter badges --}}
    @if (!empty($filters))
    <div class="flex flex-wrap gap-2 mt-3 pt-3
                border-t border-gray-100 dark:border-gray-800">
        <span class="text-xs text-gray-400 dark:text-gray-500">Active:</span>
        @foreach ($filters as $key => $value)
            @php
                $badgeLabel = match($key) {
                    'search'     => "Search: \"$value\"",
                    'state'      => 'State: ' . App\Enums\TaskState::from($value)->label(),
                    'label_id'   => 'Label: ' . ($labels->firstWhere('id', $value)?->name ?? $value),
                    'due_bucket' => $dueBuckets[$value] ?? $value,
                    default      => $value,
                };
                $remaining = array_filter($filters, fn($k) => $k !== $key, ARRAY_FILTER_USE_KEY);
                $clearUrl  = $clearBase . ($remaining ? '?' . http_build_query($remaining) : '');
            @endphp
            <a href="{{ $clearUrl }}"
               class="inline-flex items-center gap-1 text-xs
                      bg-violet-50 dark:bg-violet-950
                      text-violet-700 dark:text-violet-300
                      border border-violet-200 dark:border-violet-800
                      rounded-full px-2.5 py-0.5
                      hover:bg-red-50 dark:hover:bg-red-950
                      hover:text-red-600 dark:hover:text-red-400
                      hover:border-red-200 dark:hover:border-red-800
                      transition">
                {{ $badgeLabel }} ✕
            </a>
        @endforeach
    </div>
    @endif

</form>
