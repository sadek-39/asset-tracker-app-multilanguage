<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeTransaction;
use App\Models\ExpenseTransaction;
use App\Models\IncomeSource;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function storeIncome(Request $request)
    {
        $request->validate([
            'source_id' => 'required|exists:income_sources,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $source = IncomeSource::findOrFail($request->source_id);
            $source->increment('current_balance', $request->amount);

            IncomeTransaction::create([
                'user_id' => auth()->id(),
                'source_id' => $request->source_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'transaction_date' => $request->transaction_date,
            ]);
        });

        return back()->with('success', 'Income transaction recorded.');
    }

    public function expenseIndex()
    {
        $sources = auth()->user()->incomeSources;
        $transactions = auth()->user()->expenseTransactions()->latest()->get();
        return view('expenses.index', compact('sources', 'transactions'));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'source_id' => 'required|exists:income_sources,id',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required',
            'transaction_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $source = IncomeSource::findOrFail($request->source_id);
            if ($source->current_balance < $request->amount) {
                throw new \Exception('Insufficient balance in source.');
            }
            $source->decrement('current_balance', $request->amount);

            ExpenseTransaction::create([
                'user_id' => auth()->id(),
                'source_id' => $request->source_id,
                'amount' => $request->amount,
                'category' => $request->category,
                'description' => $request->description,
                'transaction_date' => $request->transaction_date,
            ]);
        });

        return back()->with('success', 'Expense recorded.');
    }
}
