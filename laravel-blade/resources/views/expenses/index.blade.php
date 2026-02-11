@extends('layouts.app')

@section('content')
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-2xl font-bold leading-none text-gray-900 dark:text-white">Expenses Management</h3>
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Track your spending across different categories.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button data-modal-target="expense-modal" data-modal-toggle="expense-modal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Record Expense
            </button>
        </div>
    </div>

    <!-- Expense History Table -->
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <h3 class="mb-4 text-xl font-bold dark:text-white">Expense History</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Category</th>
                        <th scope="col" class="px-6 py-3">Paid From</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactions as $tx)
                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">{{ $tx->transaction_date }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300 capitalize">
                                {{ str_replace('_', ' ', $tx->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $tx->source->source_name }}</td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $tx->description ?: '-' }}</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600 dark:text-red-500">-${{ number_format($tx->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">No expenses tracked yet. Better start spending! (Just kidding)</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Expense Modal -->
<div id="expense-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Record Expense</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="expense-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="{{ route('expenses.store') }}" method="POST" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="expense_source_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Paid From</label>
                        <select id="expense_source_id" name="source_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray_500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @foreach($sources as $s) <option value="{{ $s->id }}">{{ $s->source_name }} (${{ number_format($s->current_balance, 2) }})</option> @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                        <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @foreach(['food_dining', 'transportation', 'utilities', 'rent_mortgage', 'healthcare_medical', 'entertainment', 'shopping_retail', 'education', 'insurance', 'phone_internet', 'personal_care', 'savings_investments', 'debt_payments', 'gifts_donations', 'miscellaneous'] as $cat)
                                <option value="{{ $cat }}">{{ str_replace('_', ' ', $cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="expense_amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                        <input type="number" step="0.01" name="amount" id="expense_amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$0.00" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="expense_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                        <input type="date" name="transaction_date" id="expense_date" value="{{ date('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    </div>
                    <div class="col-span-2">
                        <label for="expense_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">What was this for?</label>
                        <input type="text" name="description" id="expense_description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g. Weekly Groceries">
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Save Expense
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
