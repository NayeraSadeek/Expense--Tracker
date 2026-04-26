<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index') }}"
               class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                New Transaction
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">

                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf

                    {{-- Type Toggle --}}
                    <div class="grid grid-cols-2 border-b border-gray-200 dark:border-gray-700" id="typeToggle">
                        <button type="button" data-type="expense"
                                class="toggle-btn py-4 text-sm font-medium border-b-2 border-red-500 text-red-500">
                            Expense
                        </button>
                        <button type="button" data-type="income"
                                class="toggle-btn py-4 text-sm font-medium text-gray-400 border-b-2 border-transparent">
                            Income
                        </button>
                    </div>

                    <input type="hidden" name="type" id="typeField" value="{{ old('type', 'expense') }}">

                    <div class="p-6 space-y-5">

                        {{-- Amount --}}
                        <div>
                            <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">Amount</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-mono">$</span>
                                <input type="number"
                                       name="amount"
                                       value="{{ old('amount') }}"
                                       step="0.01"
                                       min="0.01"
                                       placeholder="0.00"
                                       class="w-full pl-8 pr-4 py-3 font-mono text-xl border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">Description</label>
                            <input type="text"
                                   name="description"
                                   value="{{ old('description') }}"
                                   placeholder="e.g. Grocery run, Freelance payment…"
                                   class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                            @error('description')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date & Category --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">Date</label>
                                <input type="date"
                                       name="occurred_at"
                                       value="{{ old('occurred_at', date('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                                @error('occurred_at')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">Category</label>
                                <select name="category_id"
                                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                                    <option value="">— None —</option>
                                    @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 py-3 rounded-lg font-medium text-sm hover:opacity-80 transition">
                                Save Transaction
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const buttons = document.querySelectorAll('.toggle-btn');
        const typeField = document.getElementById('typeField');

        // Restore state on page load (e.g. after validation failure)
        buttons.forEach(btn => {
            if (btn.dataset.type === typeField.value) {
                setActive(btn);
            }
        });

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                typeField.value = btn.dataset.type;
                buttons.forEach(b => setInactive(b));
                setActive(btn);
            });
        });

        function setActive(btn) {
            const isExpense = btn.dataset.type === 'expense';
            btn.classList.remove('text-gray-400', 'border-transparent');
            btn.classList.add(
                isExpense ? 'text-red-500' : 'text-emerald-600',
                isExpense ? 'border-red-500' : 'border-emerald-500'
            );
        }

        function setInactive(btn) {
            const isExpense = btn.dataset.type === 'expense';
            btn.classList.remove(
                'text-red-500', 'text-emerald-600',
                'border-red-500', 'border-emerald-500'
            );
            btn.classList.add('text-gray-400', 'border-transparent');
        }
    </script>
</x-app-layout>