<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeSource;
use App\Models\IncomeTransaction;
use App\Models\ExpenseTransaction;
use App\Models\Asset;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $startDate = Carbon::now()->startOfMonth();

        $totalIncome = IncomeTransaction::where('user_id', $user->id)
            ->where('transaction_date', '>=', $startDate)
            ->sum('amount');

        $totalExpenses = ExpenseTransaction::where('user_id', $user->id)
            ->where('transaction_date', '>=', $startDate)
            ->sum('amount');

        $totalSources = IncomeSource::where('user_id', $user->id)->sum('current_balance');
        $totalAssets = Asset::where('user_id', $user->id)->sum('current_value');

        $recentTransactions = collect()
            ->concat(IncomeTransaction::where('user_id', $user->id)->latest()->take(5)->get()->map(fn($t) => [...$t->toArray(), 'type' => 'income']))
            ->concat(ExpenseTransaction::where('user_id', $user->id)->latest()->take(5)->get()->map(fn($t) => [...$t->toArray(), 'type' => 'expense']))
            ->sortByDesc('transaction_date')
            ->take(10);

        return view('dashboard', compact('totalIncome', 'totalExpenses', 'totalSources', 'totalAssets', 'recentTransactions'));
    }
}
