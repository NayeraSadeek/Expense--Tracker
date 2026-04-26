<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     private function authorizeUser(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }
    }
    public function index(Request $request)
    {
        //
        $transactions=$request->user()->
        transactions()->
        with('category')
        ->orderBy('occured_at','desc')->
        paginate(10);

        return view('transactions.index',compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $categories=$request->iser->categories()->pluck('name','id');
        return view('transactions.create',compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         $validated = $request->validate([
            'category_id' => ['nullable','exists:categories,id'],
            'description' => ['nullable','string','max:255'],
            'amount'      => ['required','numeric','min:0.01'],
            'type'        => ['required','in:income,expense'],
            'occurred_at' => ['required','date'],
        ]);
        //This user owns category 
        if($validated['category_id'])
        {
            $request->user()->categories()->findOrFail($validated['category_id']);
        }
        //Amount 
        //Income ++
        //expense --
        $amount=$validated['type']==='expense'?-abs($validated['amount']):abs($validated['amount']);

        $request->user->transactions()->create([
            'category_id' => $validated['category_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'amount'      => $amount,
            'type'        => $validated['type'],
            'occurred_at' => $validated['occurred_at'],
        ]);
        return redirect()->route('transactions.index')->with('sucess','Transactions Added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,Transaction $transaction)
    {
        //
        //Transaction belong to this user
        $this->authorizeUser($request,$transaction);
        $categories=$request->user()->categories()->pluck('name','id');
        return view ('transactions.edit',compact('transcation','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeUser($request, $transaction);

        $validated = $request->validate([
            'category_id' => ['nullable','exists:categories,id'],
            'description' => ['nullable','string','max:255'],
            'amount'      => ['required','numeric','min:0.01'],
            'type'        => ['required','in:income,expense'],
            'occurred_at' => ['required','date'],
        ]);

        if ($validated['category_id']) {
            $request->user()->categories()->findOrFail($validated['category_id']);
        }

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
            ->with('success','Transaction Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
   
        public function destroy(Request $request, Transaction $transaction)
    {
        $this->authorizeUser($request, $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success','Transaction Deleted successfully.');
    }
}
