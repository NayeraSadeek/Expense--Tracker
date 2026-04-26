<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Transactions
            </h2>
            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium px-4 py-2 rounded-lg hover:opacity-80 transition">
                + New Transaction
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">

                @if ($transactions->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-20 text-gray-400 dark:text-gray-500">
                        <p class="text-5xl mb-4">📭</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No transactions yet</p>
                        <p class="text-xs text-gray-400 mb-5">Start tracking by adding your first one.</p>
                        <a href="{{ route('transactions.create') }}"
                           class="inline-block bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-medium px-5 py-2.5 rounded-lg hover:opacity-80 transition">
                            + Add Transaction
                        </a>
                    </div>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 text-xs text-gray-400 uppercase tracking-widest">
                                <th class="text-left px-6 py-4 font-medium">Date</th>
                                <th class="text-left px-6 py-4 font-medium">Description</th>
                                <th class="text-left px-6 py-4 font-medium">Category</th>
                                <th class="text-right px-6 py-4 font-medium">Amount</th>
                                <th class="text-right px-6 py-4 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach ($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition group">
                                    <td class="px-6 py-4 text-gray-400 tabular-nums whitespace-nowrap">
                                        {{ $transaction->occurred_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200 font-medium">
                                        {{ $transaction->description ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs px-2 py-1 rounded-md">
                                            {{ $transaction->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-semibold tabular-nums
                                        {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format(abs($transaction->amount), 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-3 opacity-0 group-hover:opacity-100 transition">
                                            <a href="{{ route('transactions.edit', $transaction) }}"
                                               class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xs underline underline-offset-2">
                                                Edit
                                            </a>
                                            <form action="{{ route('transactions.destroy', $transaction) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this transaction?')">
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

                    @if ($transactions->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>