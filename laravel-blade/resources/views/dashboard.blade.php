@extends('layouts.app')

@section('content')
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-2xl font-bold leading-none text-gray-900 dark:text-white">Dashboard Overview</h3>
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Welcome back, {{ auth()->user()->name }}. Here's your financial summary.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('income.index') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Add Income
            </a>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid w-full grid-cols-1 gap-4 mt-4 xl:grid-cols-4 2xl:grid-cols-4">
        <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="w-full">
                <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">Total Income</h3>
                <span class="text-2xl font-bold leading-none text-green-600 dark:text-green-400">${{ number_format($totalIncome, 2) }}</span>
                <p class="flex items-center text-sm font-normal text-gray-500 dark:text-gray-400 mt-2">
                    <span class="flex items-center mr-1 text-sm font-medium text-green-500 dark:text-green-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        All time
                    </span>
                </p>
            </div>
        </div>
        <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="w-full">
                <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">Total Expenses</h3>
                <span class="text-2xl font-bold leading-none text-red-600 dark:text-red-500">${{ number_format($totalExpenses, 2) }}</span>
                 <p class="flex items-center text-sm font-normal text-gray-500 dark:text-gray-400 mt-2">
                    <span class="flex items-center mr-1 text-sm font-medium text-red-500">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        All time
                    </span>
                </p>
            </div>
        </div>
         <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="w-full">
                <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">Net Savings</h3>
                <span class="text-2xl font-bold leading-none text-blue-600 dark:text-blue-500">${{ number_format($totalIncome - $totalExpenses, 2) }}</span>
                <p class="mt-2 text-sm text-gray-400">Calculated Balance</p>
            </div>
        </div>
        <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="w-full">
                <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">Total Portfolio</h3>
                <span class="text-2xl font-bold leading-none text-purple-600 dark:text-purple-500">${{ number_format($totalSources + $totalAssets, 2) }}</span>
                <p class="mt-2 text-sm text-gray-400">Sources + Assets</p>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="p-4 mt-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <h3 class="mb-4 text-xl font-bold dark:text-white">Recent Transactions</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($recentTransactions as $tx)
                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $tx['description'] ?: 'Transaction' }}</td>
                        <td class="px-6 py-4">
                            <span class="{{ $tx['type'] == 'income' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }} text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ ucfirst($tx['type']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $tx['transaction_date'] }}</td>
                        <td class="px-6 py-4 font-bold {{ $tx['type'] == 'income' ? 'text-green-500' : 'text-slate-400' }}">
                            {{ $tx['type'] == 'income' ? '+' : '-' }}${{ number_format($tx['amount'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
