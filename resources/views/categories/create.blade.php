<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.index') }}"
               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition text-sm">
                ← Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                New Category
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">

                <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name"
                               class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                            Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="e.g. Groceries, Salary, Rent…"
                               autofocus
                               class="w-full px-4 py-3 border rounded-lg text-sm bg-gray-50 dark:bg-gray-900
                                      text-gray-900 dark:text-gray-100
                                      border-gray-200 dark:border-gray-600
                                      placeholder-gray-300 dark:placeholder-gray-600
                                      focus:outline-none focus:ring-2 focus:ring-gray-400
                                      {{ $errors->has('name') ? 'border-red-400' : '' }}">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description"
                               class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                            Description <span class="text-gray-300 dark:text-gray-600 font-normal normal-case">(optional)</span>
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  placeholder="What is this category for?"
                                  class="w-full px-4 py-3 border rounded-lg text-sm bg-gray-50 dark:bg-gray-900
                                         text-gray-900 dark:text-gray-100
                                         border-gray-200 dark:border-gray-600
                                         placeholder-gray-300 dark:placeholder-gray-600
                                         focus:outline-none focus:ring-2 focus:ring-gray-400 resize-none
                                         {{ $errors->has('description') ? 'border-red-400' : '' }}">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="flex-1 bg-gray-900 dark:bg-white text-white dark:text-gray-900
                                       py-3 rounded-lg font-medium text-sm hover:opacity-80 transition">
                            Save Category
                        </button>
                        <a href="{{ route('categories.index') }}"
                           class="px-5 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                                  text-gray-500 dark:text-gray-400 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>