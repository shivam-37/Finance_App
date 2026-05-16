<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Income Card -->
                <div class="bg-gradient-to-br from-emerald-400 to-teal-500 dark:from-emerald-600 dark:to-teal-800 rounded-3xl p-8 text-white shadow-xl shadow-emerald-200/50 dark:shadow-none hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-30 group-hover:opacity-50 transition-opacity transform group-hover:scale-110 duration-500">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-emerald-50 mb-2 relative z-10 uppercase tracking-wider">Total Income (This Month)</h3>
                    <p class="text-4xl font-bold tracking-tight relative z-10">₹{{ number_format($income, 2) }}</p>
                </div>
                
                <!-- Expense Card -->
                <div class="bg-gradient-to-br from-rose-400 to-red-500 dark:from-rose-600 dark:to-red-800 rounded-3xl p-8 text-white shadow-xl shadow-rose-200/50 dark:shadow-none hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-30 group-hover:opacity-50 transition-opacity transform group-hover:scale-110 duration-500">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-rose-50 mb-2 relative z-10 uppercase tracking-wider">Total Expenses (This Month)</h3>
                    <p class="text-4xl font-bold tracking-tight relative z-10">₹{{ number_format($expense, 2) }}</p>
                </div>

                <!-- Balance Card -->
                <div class="bg-gradient-to-br from-indigo-500 to-violet-600 dark:from-indigo-600 dark:to-violet-900 rounded-3xl p-8 text-white shadow-xl shadow-indigo-200/50 dark:shadow-none hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-30 group-hover:opacity-50 transition-opacity transform group-hover:scale-110 duration-500">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-indigo-100 mb-2 relative z-10 uppercase tracking-wider">Balance</h3>
                    <p class="text-4xl font-bold tracking-tight relative z-10">₹{{ number_format($balance, 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Budgets Progress -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Budgets Overview
                    </h3>
                    @if($budgets->isEmpty())
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-gray-700 text-gray-400 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4M12 20V4"></path></svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">No budgets set for this month.</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($budgets as $budget)
                                <div>
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="flex items-center gap-3 font-medium text-gray-700 dark:text-gray-200">
                                            <span class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $budget->category->color }}"></span>
                                            {{ $budget->category->name }}
                                        </span>
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                                            ₹{{ number_format($budget->spent, 2) }} <span class="text-gray-400 font-normal">/ ₹{{ number_format($budget->amount, 2) }}</span>
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden shadow-inner">
                                        <div class="h-3 rounded-full {{ $budget->progress >= 100 ? 'bg-red-500' : ($budget->progress >= 80 ? 'bg-yellow-500' : 'bg-emerald-500') }} transition-all duration-1000 ease-out" style="width: {{ min(100, $budget->progress) }}%"></div>
                                    </div>
                                    @if($budget->progress >= 100)
                                        <p class="text-xs text-red-500 mt-1 text-right">Over budget by ₹{{ number_format($budget->spent - $budget->amount, 2) }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Recent Transactions
                        </h3>
                        <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">View All &rarr;</a>
                    </div>
                    
                    @if($recentTransactions->isEmpty())
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-gray-700 text-gray-400 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">No recent transactions.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($recentTransactions as $transaction)
                                <div class="flex justify-between items-center p-4 hover:bg-slate-50 dark:hover:bg-gray-700/50 rounded-2xl transition-all duration-200 border border-transparent hover:border-slate-200 dark:hover:border-gray-600">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold shadow-sm" style="background-color: {{ $transaction->category->color }}">
                                            {{ substr($transaction->category->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $transaction->category->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $transaction->date->format('M d, Y') }} &bull; {{ $transaction->description ?? 'No description' }}</p>
                                        </div>
                                    </div>
                                    <div class="font-bold text-lg {{ $transaction->type === 'income' ? 'text-emerald-500' : 'text-gray-800 dark:text-gray-100' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
