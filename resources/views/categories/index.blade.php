<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Categories
            </h2>
            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium px-4 py-2 rounded-lg hover:opacity-80 transition">
                + New Category
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Category List --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                @if ($categories->isEmpty())
                    <div class="text-center py-20">
                        <p class="text-4xl mb-3">🗂️</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No categories yet</p>
                        <p class="text-xs text-gray-400 mb-5">Create one to start organizing your transactions.</p>
                        <a href="{{ route('categories.create') }}"
                           class="inline-block bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium px-5 py-2.5 rounded-lg hover:opacity-80 transition">
                            + New Category
                        </a>
                    </div>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 text-xs text-gray-400 uppercase tracking-widest">
                                <th class="text-left px-6 py-4 font-medium">Name</th>
                                <th class="text-left px-6 py-4 font-medium">Description</th>
                                <th class="text-right px-6 py-4 font-medium">Transactions</th>
                                <th class="text-right px-6 py-4 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition group">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                        {{ $category->description ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-gray-500 dark:text-gray-400">
                                        {{ $category->transactions_count }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-3 opacity-0 group-hover:opacity-100 transition">
                                            <a href="{{ route('categories.edit', $category) }}"
                                               class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xs underline underline-offset-2">
                                                Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete \'{{ addslashes($category->name) }}\'? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-400 hover:text-red-600 text-xs underline underline-offset-2">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($categories->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                            {{ $categories->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>