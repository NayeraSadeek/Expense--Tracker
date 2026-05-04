<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Dashboard
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ now()->format('F Y') }} overview
                </p>
            </div>
            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center bg-gray-900 dark:bg-white text-white dark:text-gray-900
                      text-sm font-medium px-4 py-2 rounded-lg hover:opacity-80 transition">
                + New Transaction
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── Summary Cards ── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Income --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-medium">Income</p>
                        <span class="text-emerald-500 text-lg">↑</span>
                    </div>
                    <p class="text-2xl font-mono font-semibold text-emerald-600">
                        ${{ number_format($monthlyIncome, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">This month</p>
                </div>

                {{-- Expenses --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-medium">Expenses</p>
                        <span class="text-red-400 text-lg">↓</span>
                    </div>
                    <p class="text-2xl font-mono font-semibold text-red-500">
                        ${{ number_format(abs($monthlyExpenses), 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">This month</p>
                </div>

                {{-- Net Balance --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-medium">Net</p>
                        <span class="text-gray-400 text-lg">⇄</span>
                    </div>
                    <p class="text-2xl font-mono font-semibold
                        {{ $netBalance >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $netBalance >= 0 ? '+' : '' }}${{ number_format($netBalance, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">This month</p>
                </div>

                {{-- Total Transactions --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-medium">All Time</p>
                        <span class="text-gray-400 text-lg">#</span>
                    </div>
                    <p class="text-2xl font-mono font-semibold text-gray-700 dark:text-gray-200">
                        {{ $totalTransactions }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Total transactions</p>
                </div>

            </div>

            {{-- ── Main Content Grid ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Recent Transactions (2/3 width) --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Recent Transactions</h3>
                        <a href="{{ route('transactions.index') }}"
                           class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 underline underline-offset-2">
                            View all
                        </a>
                    </div>

                    @if ($recentTransactions->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-3xl mb-2">📭</p>
                            <p class="text-sm text-gray-400">No transactions yet.</p>
                            <a href="{{ route('transactions.create') }}"
                               class="mt-3 inline-block text-xs text-gray-500 underline underline-offset-2">
                                Add your first one
                            </a>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach ($recentTransactions as $transaction)
                                <li class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <div class="flex items-center gap-3 min-w-0">
                                        {{-- Type indicator dot --}}
                                        <span class="w-2 h-2 rounded-full flex-shrink-0
                                            {{ $transaction->type === 'income' ? 'bg-emerald-500' : 'bg-red-400' }}">
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                                {{ $transaction->description ?? 'No description' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $transaction->occurred_at->format('M d') }}
                                                @if ($transaction->category)
                                                    · <span class="text-gray-500 dark:text-gray-400">{{ $transaction->category->name }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <p class="font-mono text-sm font-semibold ml-4 flex-shrink-0
                                        {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format(abs($transaction->amount), 2) }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Category Breakdown (1/3 width) --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Top Categories</h3>
                        <a href="{{ route('categories.index') }}"
                           class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 underline underline-offset-2">
                            Manage
                        </a>
                    </div>

                    @if ($categoryBreakdown->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-3xl mb-2">🗂️</p>
                            <p class="text-sm text-gray-400">No activity yet.</p>
                            <a href="{{ route('categories.create') }}"
                               class="mt-3 inline-block text-xs text-gray-500 underline underline-offset-2">
                                Create a category
                            </a>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach ($categoryBreakdown as $category)
                                <li class="flex items-center justify-between px-6 py-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 truncate">
                                        {{ $category->name }}
                                    </p>
                                    <p class="font-mono text-sm font-semibold ml-3 flex-shrink-0
                                        {{ $category->monthly_total >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $category->monthly_total >= 0 ? '+' : '' }}${{ number_format(abs($category->monthly_total), 2) }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- Budget placeholder --}}
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                        <p class="text-xs text-gray-400 text-center">
                            Budget tracking coming soon
                        </p>
                    </div>
                </div>

            </div>

            {{-- ── Quick Links ── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('transactions.create') }}"
                   class="flex flex-col items-center justify-center gap-2 bg-white dark:bg-gray-800
                          border border-gray-100 dark:border-gray-700 rounded-xl py-5
                          text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-500
                          hover:text-gray-700 dark:hover:text-gray-200 transition text-center">
                    <span class="text-2xl">➕</span>
                    <span class="text-xs font-medium">New Transaction</span>
                </a>
                <a href="{{ route('categories.create') }}"
                   class="flex flex-col items-center justify-center gap-2 bg-white dark:bg-gray-800
                          border border-gray-100 dark:border-gray-700 rounded-xl py-5
                          text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-500
                          hover:text-gray-700 dark:hover:text-gray-200 transition text-center">
                    <span class="text-2xl">🗂️</span>
                    <span class="text-xs font-medium">New Category</span>
                </a>
                <a href="{{ route('transactions.index') }}"
                   class="flex flex-col items-center justify-center gap-2 bg-white dark:bg-gray-800
                          border border-gray-100 dark:border-gray-700 rounded-xl py-5
                          text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-500
                          hover:text-gray-700 dark:hover:text-gray-200 transition text-center">
                    <span class="text-2xl">📋</span>
                    <span class="text-xs font-medium">All Transactions</span>
                </a>
                <a href="{{ route('categories.index') }}"
                   class="flex flex-col items-center justify-center gap-2 bg-white dark:bg-gray-800
                          border border-gray-100 dark:border-gray-700 rounded-xl py-5
                          text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-500
                          hover:text-gray-700 dark:hover:text-gray-200 transition text-center">
                    <span class="text-2xl">📊</span>
                    <span class="text-xs font-medium">Categories</span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>