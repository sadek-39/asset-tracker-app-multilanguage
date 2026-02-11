<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeSource;

class IncomeSourceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sources = $user->incomeSources;
        $transactions = $user->incomeTransactions()->with('source')->latest()->get();
        return view('income.index', compact('sources', 'transactions'));
    }

    public function create()
    {
        return redirect()->route('income.index')->with('openSource', true);
    }

    public function store(Request $request)
    {
        if (auth()->user()->incomeSources()->count() >= 5) {
            return back()->withErrors(['error' => 'Maximum 5 income sources allowed.']);
        }

        $request->validate([
            'source_name' => 'required|max:100',
            'source_type' => 'required|in:bank_account,cash,digital_wallet,other',
            'current_balance' => 'required|numeric|min:0',
        ]);

        auth()->user()->incomeSources()->create($request->all());

        return back()->with('success', 'Income source added.');
    }
}
