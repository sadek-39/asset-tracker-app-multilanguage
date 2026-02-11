@extends('layouts.app')

@section('content')
<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-2xl font-bold leading-none text-gray-900 dark:text-white">Assets Portfolio</h3>
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Track your wealth and long-term investments.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button data-modal-target="asset-modal" data-modal-toggle="asset-modal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                New Asset
            </button>
        </div>
    </div>

    <!-- Assets Grid -->
    <div class="grid w-full grid-cols-1 gap-4 mt-4 xl:grid-cols-4 2xl:grid-cols-4 mb-8">
        @foreach($assets as $asset)
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-purple-900 dark:text-purple-300 uppercase leading-none">
                    {{ $asset->asset_type }}
                </span>
                <div class="p-2 bg-gray-100 rounded-lg dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $asset->asset_name }}</h3>
            <p class="text-2xl font-extrabold text-purple-600 dark:text-purple-500 mt-2">${{ number_format($asset->current_value, 2) }}</p>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011-1v5m-4 0h4"></path></svg>
                    {{ $asset->institution_name ?: 'Self Managed' }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Asset Modal -->
<div id="asset-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Track New Asset</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="asset-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="{{ route('assets.store') }}" method="POST" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="asset_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asset Name</label>
                        <input type="text" name="asset_name" id="asset_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g. Sanchayapatra" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="asset_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
                        <select id="asset_type" name="asset_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="dps">DPS (Deposit Pension Scheme)</option>
                            <option value="bond">Bonds / Savings Certificates</option>
                            <option value="investment">Stock / Mutual Fund</option>
                            <option value="other">Other Assets</option>
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="current_value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Valuation</label>
                        <input type="number" step="0.01" name="current_value" id="current_value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$0.00" required>
                    </div>
                    <div class="col-span-2">
                        <label for="institution_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Institution Name</label>
                        <input type="text" name="institution_name" id="institution_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray_500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="e.g. Sonali Bank">
                    </div>
                    <div class="col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Notes</label>
                        <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your notes here..."></textarea>
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Add Portfolio
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
