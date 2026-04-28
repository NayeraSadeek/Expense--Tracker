<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
class TransactionController extends Controller
{
    private function authorizeUser(Request $request, Transaction $transaction): void
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index(Request $request)
    {
        $transactions = $request->user()
            ->transactions()
            ->with('category')
            ->orderBy('occurred_at', 'desc')  
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        $categories = $request->user()->categories()->pluck('name', 'id');  // ✅ BUG 2 FIX: was $request->iser (typo)
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validated();

            $amount = $validated['type'] === 'expense'
                ? -abs($validated['amount'])
                : abs($validated['amount']);

            $request->user()->transactions()->create([
                'category_id' => $validated['category_id'] ?? null,
                'description' => $validated['description'] ?? null,
                'amount'      => $amount,
                'type'        => $validated['type'],
                'occurred_at' => $validated['occurred_at'],
            ]);

            return redirect()->route('transactions.index')
                ->with('success', 'Transaction added successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Request $request, Transaction $transaction)
    {
        $this->authorizeUser($request, $transaction);
        $categories = $request->user()->categories()->pluck('name', 'id');
        return view('transactions.edit', compact('transaction', 'categories'));  // ✅ BUG 5 FIX: was 'transcation' (typo)
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validated();

            $amount = $validated['type'] === 'expense'
                ? -abs($validated['amount'])
                : abs($validated['amount']);

            $transaction->update([
                'category_id' => $validated['category_id'] ?? null,
                'description' => $validated['description'] ?? null,
                'amount'      => $amount,
                'type'        => $validated['type'],
                'occurred_at' => $validated['occurred_at'],
            ]);

            return redirect()->route('transactions.index')
                ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $this->authorizeUser($request, $transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}