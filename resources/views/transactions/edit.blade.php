<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index') }}"
               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition text-sm">
                ← Back
            </a>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                Edit Transaction
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Type Toggle (button-based, same as create) --}}
                    <div class="grid grid-cols-2 border-b border-gray-200 dark:border-gray-700">
                        <button type="button" data-type="expense"
                                class="toggle-btn py-4 text-sm font-medium border-b-2 border-transparent text-gray-400">
                            Expense
                        </button>
                        <button type="button" data-type="income"
                                class="toggle-btn py-4 text-sm font-medium border-b-2 border-transparent text-gray-400">
                            Income
                        </button>
                    </div>

                    <input type="hidden" name="type" id="typeField"
                           value="{{ old('type', $transaction->type) }}">

                    <div class="p-6 space-y-5">

                        {{-- Amount — show absolute value, controller handles sign --}}
                        <div>
                            <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                                Amount
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-mono">$</span>
                                <input type="number"
                                       name="amount"
                                       value="{{ old('amount', abs($transaction->amount)) }}"
                                       step="0.01"
                                       min="0.01"
                                       class="w-full pl-8 pr-4 py-3 font-mono text-xl border rounded-lg
                                              bg-gray-50 dark:bg-gray-900
                                              border-gray-200 dark:border-gray-600
                                              text-gray-900 dark:text-gray-100
                                              focus:outline-none focus:ring-2 focus:ring-gray-400
                                              {{ $errors->has('amount') ? 'border-red-400' : '' }}">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                                Description
                            </label>
                            <input type="text"
                                   name="description"
                                   value="{{ old('description', $transaction->description) }}"
                                   placeholder="e.g. Grocery run, Freelance payment…"
                                   class="w-full px-4 py-3 border rounded-lg text-sm
                                          bg-gray-50 dark:bg-gray-900
                                          border-gray-200 dark:border-gray-600
                                          text-gray-900 dark:text-gray-100
                                          focus:outline-none focus:ring-2 focus:ring-gray-400
                                          {{ $errors->has('description') ? 'border-red-400' : '' }}">
                            @error('description')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date & Category --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                                    Date
                                </label>
                                <input type="date"
                                       name="occurred_at"
                                       value="{{ old('occurred_at', $transaction->occurred_at->format('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border rounded-lg text-sm
                                              bg-gray-50 dark:bg-gray-900
                                              border-gray-200 dark:border-gray-600
                                              text-gray-900 dark:text-gray-100
                                              focus:outline-none focus:ring-2 focus:ring-gray-400
                                              {{ $errors->has('occurred_at') ? 'border-red-400' : '' }}">
                                @error('occurred_at')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                                    Category
                                </label>
                                <select name="category_id"
                                        class="w-full px-4 py-3 border rounded-lg text-sm
                                               bg-gray-50 dark:bg-gray-900
                                               border-gray-200 dark:border-gray-600
                                               text-gray-900 dark:text-gray-100
                                               focus:outline-none focus:ring-2 focus:ring-gray-400
                                               {{ $errors->has('category_id') ? 'border-red-400' : '' }}">
                                    <option value="">— None —</option>
                                    @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}"
                                                {{ old('category_id', $transaction->category_id) == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit"
                                    class="flex-1 bg-gray-900 dark:bg-white text-white dark:text-gray-900
                                           py-3 rounded-lg font-medium text-sm hover:opacity-80 transition">
                                Update Transaction
                            </button>
                            <a href="{{ route('transactions.index') }}"
                               class="px-5 py-3 rounded-lg border border-gray-200 dark:border-gray-600
                                      text-gray-500 dark:text-gray-400 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Cancel
                            </a>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-red-100 dark:border-red-900/30 p-5">
                <p class="text-xs text-red-400 uppercase tracking-widest font-medium mb-3">Danger Zone</p>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Permanently delete this transaction.</p>
                    <form action="{{ route('transactions.destroy', $transaction) }}"
                          method="POST"
                          onsubmit="return confirm('This cannot be undone. Delete?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm text-red-500 hover:text-red-700 font-medium transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const buttons = document.querySelectorAll('.toggle-btn');
        const typeField = document.getElementById('typeField');

        // Set initial active state from hidden field value (handles edit pre-fill + validation failures)
        buttons.forEach(btn => {
            if (btn.dataset.type === typeField.value) setActive(btn);
            else setInactive(btn);

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
                isExpense ? 'text-red-500'   : 'text-emerald-600',
                isExpense ? 'border-red-500' : 'border-emerald-500'
            );
        }

        function setInactive(btn) {
            btn.classList.remove('text-red-500', 'text-emerald-600', 'border-red-500', 'border-emerald-500');
            btn.classList.add('text-gray-400', 'border-transparent');
        }
    </script>
</x-app-layout>