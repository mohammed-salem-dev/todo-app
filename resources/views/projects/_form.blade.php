<form method="POST"
      action="{{ isset($project) ? route('projects.update', $project) : route('projects.store') }}"
      class="space-y-5">
    @csrf
    @if (isset($project)) @method('PATCH') @endif

    {{-- Name --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Project Name <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name', $project->name ?? '') }}"
               required autofocus
               class="w-full border border-gray-300 dark:border-gray-700
                      rounded-xl px-4 py-2.5 text-sm
                      bg-white dark:bg-gray-800
                      text-gray-900 dark:text-gray-100
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-violet-400 focus:outline-none transition
                      {{ $errors->has('name') ? 'border-red-400' : '' }}">
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Description <span class="text-gray-400 font-normal">(optional)</span>
        </label>
        <textarea name="description" rows="3"
                  class="w-full border border-gray-300 dark:border-gray-700
                         rounded-xl px-4 py-2.5 text-sm
                         bg-white dark:bg-gray-800
                         text-gray-900 dark:text-gray-100
                         placeholder-gray-400 dark:placeholder-gray-500
                         focus:ring-2 focus:ring-violet-400 focus:outline-none
                         transition resize-none">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ isset($project) ? route('projects.show', $project) : route('projects.index') }}"
           class="text-sm text-gray-500 dark:text-gray-400
                  hover:text-gray-700 dark:hover:text-gray-200 transition">
            Cancel
        </a>
        <button type="submit"
                class="bg-violet-600 hover:bg-violet-700 text-white
                       text-sm font-semibold px-5 py-2.5 rounded-xl
                       transition shadow-sm">
            {{ $submitLabel ?? 'Save' }}
        </button>
    </div>
</form>
