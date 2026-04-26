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
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">

                {{-- Type Toggle --}}
                <div class="grid grid-cols-2 border-b border-gray-100 dark:border-gray-700">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type_toggle" value="expense" class="peer sr-only"
                               {{ old('type', $transaction->type) === 'expense' ? 'checked' : '' }}>
                        <div class="py-4 text-center text-sm font-medium text-gray-400 peer-checked:text-red-500 peer-checked:border-b-2 peer-checked:border-red-500 transition">
                            Expense
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type_toggle" value="income" class="peer sr-only"
                               {{ old('type', $transaction->type) === 'income' ? 'checked' : '' }}>
                        <div class="py-4 text-center text-sm font-medium text-gray-400 peer-checked:text-emerald-600 peer-checked:border-b-2 peer-checked:border-emerald-500 transition">
                            Income
                        </div>
                    </label>
                </div>

                <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="type" id="typeField" value="{{ old('type', $transaction->type) }}">

                    {{-- Amount --}}
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                            Amount
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-mono text-lg">$</span>
                            <input type="number"
                                   name="amount"
                                   value="{{ old('amount', $transaction->amount) }}"
                                   step="0.01"
                                   min="0.01"
                                   class="w-full pl-9 pr-4 py-3 font-mono text-2xl bg-gray-50 dark:bg-gray-900 border rounded-lg
                                          border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100
                                          focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white
                                          @error('amount') border-red-400 @enderror">
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
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border rounded-lg
                                      border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white
                                      @error('description') border-red-400 @enderror">
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
                                   name="date"
                                   value="{{ old('occurred_at', $transaction->occurred_at->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border rounded-lg
                                          border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white
                                          @error('date') border-red-400 @enderror">
                            @error('date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                                Category
                            </label>
                            <select name="category_id"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border rounded-lg
                                           border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-white
                                           @error('category_id') border-red-400 @enderror">
                                <option value="">— Select —</option>
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
                </form>
            </div>

            {{-- Danger Zone --}}
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-red-100 dark:border-red-900/30 p-5">
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
        document.querySelectorAll('input[name="type_toggle"]').forEach(radio => {
            radio.addEventListener('change', e => {
                document.getElementById('typeField').value = e.target.value;
            });
        });
    </script>
</x-app-layout>