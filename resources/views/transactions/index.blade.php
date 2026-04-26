@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Transactions</h1>
        <a href="{{ route('transactions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Add Transaction
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-3 border-b">Description</th>
                    <th class="px-4 py-3 border-b">Category</th>
                    <th class="px-4 py-3 border-b">Amount</th>
                    <th class="px-4 py-3 border-b">Date</th>
                    <th class="px-4 py-3 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $transaction->description }}</td>
                        <td class="px-4 py-3">{{ $transaction->category->name ?? '-' }}</td>
                        <td class="px-4 py-3 font-semibold 
                            {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->amount }}
                        </td>
                        <td class="px-4 py-3">{{ $transaction->occurred_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('transactions.edit', $transaction) }}" 
                               class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" 
                                  onsubmit="return confirm('Delete this transaction?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-3 text-center text-gray-500">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
