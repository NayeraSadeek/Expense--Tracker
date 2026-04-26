@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg">
    <h1 class="text-xl font-bold mb-4">Edit Transaction</h1>

    <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium mb-1">Description</label>
            <input type="text" name="description" value="{{ old('description', $transaction->description) }}" 
                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block font-medium mb-1">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}" 
                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
            <small class="text-gray-500">Positive = Income, Negative = Expense</small>
        </div>

        <div>
            <label class="block font-medium mb-1">Date</label>
            <input type="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" 
                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block font-medium mb-1">Category</label>
            <select name="category_id" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
