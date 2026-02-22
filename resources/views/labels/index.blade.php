<x-app-layout>
<x-slot name="title">Labels</x-slot>

<div class="max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Labels</h1>
        <a href="{{ route('projects.index') }}"
           class="text-sm text-gray-400 hover:text-violet-500 transition">
            ← Projects
        </a>
    </div>

    {{-- Create form --}}
    <div class="bg-white dark:bg-gray-900
                border border-gray-200 dark:border-gray-800
                rounded-2xl shadow-sm p-5 mb-6">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
            New Label
        </h2>
        <form method="POST" action="{{ route('labels.store') }}"
              class="flex items-center gap-3 flex-wrap">
            @csrf
            <input type="text" name="name"
                   value="{{ old('name') }}"
                   placeholder="Label name…"
                   required
                   class="flex-1 min-w-40 border border-gray-300 dark:border-gray-700
                          rounded-xl px-4 py-2 text-sm
                          bg-white dark:bg-gray-800
                          text-gray-900 dark:text-gray-100
                          placeholder-gray-400 dark:placeholder-gray-500
                          focus:ring-2 focus:ring-violet-400 focus:outline-none transition">

            <select name="color"
                    class="border border-gray-300 dark:border-gray-700
                           rounded-xl px-4 py-2 text-sm
                           bg-white dark:bg-gray-800
                           text-gray-900 dark:text-gray-100
                           appearance-none cursor-pointer
                           focus:ring-2 focus:ring-violet-400 focus:outline-none transition">
                @foreach (['slate','blue','emerald','red','yellow','purple','pink','orange'] as $color)
                    <option value="{{ $color }}" {{ old('color') === $color ? 'selected' : '' }}>
                        {{ ucfirst($color) }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="bg-violet-600 hover:bg-violet-700 text-white
                           text-sm font-semibold px-5 py-2 rounded-xl transition">
                Add
            </button>
        </form>
        @error('name')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Label list --}}
    @if ($labels->isEmpty())
        <p class="text-center text-sm text-gray-400 dark:text-gray-500 py-12">
            No labels yet — create your first one above.
        </p>
    @else
        <div class="space-y-2">
            @php
            $colorMap = [
                'slate'   => 'bg-slate-100   dark:bg-slate-800   text-slate-700   dark:text-slate-300',
                'blue'    => 'bg-blue-100    dark:bg-blue-900    text-blue-700    dark:text-blue-300',
                'emerald' => 'bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300',
                'red'     => 'bg-red-100     dark:bg-red-900     text-red-700     dark:text-red-300',
                'yellow'  => 'bg-yellow-100  dark:bg-yellow-900  text-yellow-700  dark:text-yellow-300',
                'purple'  => 'bg-purple-100  dark:bg-purple-900  text-purple-700  dark:text-purple-300',
                'pink'    => 'bg-pink-100    dark:bg-pink-900    text-pink-700    dark:text-pink-300',
                'orange'  => 'bg-orange-100  dark:bg-orange-900  text-orange-700  dark:text-orange-300',
            ];
            @endphp

            @foreach ($labels as $label)
            @php $cls = $colorMap[$label->color] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'; @endphp
            <div class="flex items-center justify-between px-4 py-3
                        bg-white dark:bg-gray-900
                        border border-gray-200 dark:border-gray-800
                        rounded-xl">
                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $cls }}">
                    {{ $label->name }}
                </span>

                <form method="POST" action="{{ route('labels.destroy', $label) }}"
                      x-data
                      @submit.prevent="confirm('Delete label \'{{ addslashes($label->name) }}\'?') && $el.submit()">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-xs text-gray-400 dark:text-gray-500
                                   hover:text-red-500 dark:hover:text-red-400 transition">
                        Delete
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    @endif

</div>
</x-app-layout>
