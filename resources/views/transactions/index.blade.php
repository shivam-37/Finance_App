<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Transactions') }}</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('transactions.export', request()->query()) }}" class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium py-2.5 px-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export CSV
                </a>
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Transaction
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Search & Filter Bar -->
            <form method="GET" action="{{ route('transactions.index') }}" class="animate-fade-in-up bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-gray-700/50">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description..." class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <select name="type" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Types</option>
                            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>
                    <div>
                        <select name="category_id" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <input type="date" name="from" value="{{ request('from') }}" placeholder="From" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-3 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500">
                        <input type="date" name="to" value="{{ request('to') }}" placeholder="To" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-3 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 px-4 rounded-xl transition-all">Filter</button>
                        <a href="{{ route('transactions.index') }}" class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">Clear</a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="animate-fade-in-up anim-delay-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-3xl border border-slate-100 dark:border-gray-700/50">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-sm font-semibold tracking-wide text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                    <th class="pb-4 px-4">Date</th>
                                    <th class="pb-4 px-4">Description</th>
                                    <th class="pb-4 px-4">Category</th>
                                    <th class="pb-4 px-4 text-right">Amount</th>
                                    <th class="pb-4 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                @foreach ($transactions as $i => $transaction)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-700/30 transition-colors group animate-fade-in-up" style="animation-delay: {{ 0.1 + $i * 0.03 }}s;">
                                    <td class="py-4 px-4 whitespace-nowrap text-gray-600 dark:text-gray-300 text-sm">
                                        {{ $transaction->date->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-4 text-gray-800 dark:text-gray-200 font-medium text-sm">{{ $transaction->description ?: '—' }}</td>
                                    <td class="py-4 px-4">
                                        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            <span class="w-2 h-2 rounded-full mr-2" style="background-color: {{ $transaction->category->color }}"></span>
                                            {{ $transaction->category->name }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-right whitespace-nowrap font-bold text-sm tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-500' : 'text-gray-800 dark:text-gray-200' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                            <a href="{{ route('transactions.edit', $transaction) }}" class="p-2 rounded-xl text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="document.querySelector('[x-data=\'confirmModal\']').__x.$data.show('Delete Transaction', 'This action cannot be undone.', this.closest('form'))" class="p-2 rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($transactions->isEmpty())
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-gray-700 text-gray-400 mb-6">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">No transactions found</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Try adjusting your filters or add a new transaction.</p>
                            <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg transition-all hover:scale-105 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Transaction
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if($transactions->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $transactions->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
