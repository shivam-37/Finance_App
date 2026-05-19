<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @php $hour = now()->format('H'); @endphp
            {{ $hour < 12 ? '☀️ Good Morning' : ($hour < 17 ? '🌤️ Good Afternoon' : '🌙 Good Evening') }}, {{ Auth::user()->name }}!
        </h2>
    </x-slot>

    @php
        $currencySymbol = Auth::user()->currency_symbol;
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Income Card -->
                <div class="animate-fade-in-up anim-delay-1 bg-gradient-to-br from-emerald-400 to-teal-500 dark:from-emerald-600 dark:to-teal-800 rounded-3xl p-7 text-white shadow-xl shadow-emerald-200/50 dark:shadow-none hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-20 group-hover:opacity-40 transition-opacity transform group-hover:scale-110 duration-500">
                        <svg class="w-20 h-20 -mr-4 -mt-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-emerald-50 mb-1 uppercase tracking-wider">Income (This Month)</h3>
                    <p class="text-3xl font-bold tracking-tight tabular-nums" x-data="animatedCounter({{ $income }})" x-text="'{{ $currencySymbol }}' + formatted"></p>
                    @if($incomeChange != 0)
                    <p class="text-xs mt-2 flex items-center gap-1 {{ $incomeChange > 0 ? 'text-emerald-100' : 'text-red-200' }}">
                        <svg class="w-3.5 h-3.5 {{ $incomeChange < 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        {{ abs($incomeChange) }}% vs last month
                    </p>
                    @endif
                </div>
                
                <!-- Expense Card -->
                <div class="animate-fade-in-up anim-delay-2 bg-gradient-to-br from-rose-400 to-red-500 dark:from-rose-600 dark:to-red-800 rounded-3xl p-7 text-white shadow-xl shadow-rose-200/50 dark:shadow-none hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-20 group-hover:opacity-40 transition-opacity transform group-hover:scale-110 duration-500">
                        <svg class="w-20 h-20 -mr-4 -mt-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-rose-50 mb-1 uppercase tracking-wider">Expenses (This Month)</h3>
                    <p class="text-3xl font-bold tracking-tight tabular-nums" x-data="animatedCounter({{ $expense }})" x-text="'{{ $currencySymbol }}' + formatted"></p>
                    @if($expenseChange != 0)
                    <p class="text-xs mt-2 flex items-center gap-1 {{ $expenseChange > 0 ? 'text-red-200' : 'text-emerald-200' }}">
                        <svg class="w-3.5 h-3.5 {{ $expenseChange < 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        {{ abs($expenseChange) }}% vs last month
                    </p>
                    @endif
                </div>

                <!-- Balance Card -->
                <div class="animate-fade-in-up anim-delay-3 bg-gradient-to-br from-indigo-500 to-violet-600 dark:from-indigo-600 dark:to-violet-900 rounded-3xl p-7 text-white shadow-xl shadow-indigo-200/50 dark:shadow-none hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-20 group-hover:opacity-40 transition-opacity transform group-hover:scale-110 duration-500">
                        <svg class="w-20 h-20 -mr-4 -mt-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-indigo-100 mb-1 uppercase tracking-wider">Total Balance</h3>
                    <p class="text-3xl font-bold tracking-tight tabular-nums" x-data="animatedCounter({{ $balance }})" x-text="'{{ $currencySymbol }}' + formatted"></p>
                </div>

                <!-- Savings Rate Card -->
                <div class="animate-fade-in-up anim-delay-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-7 shadow-sm border border-slate-100 dark:border-gray-700/50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 relative overflow-hidden">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Savings Rate</h3>
                    <div class="flex items-center gap-4">
                        <div class="relative w-16 h-16">
                            <svg class="w-16 h-16 -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="8" class="text-gray-200 dark:text-gray-700"/>
                                <circle cx="50" cy="50" r="45" fill="none" stroke-width="8" stroke-linecap="round"
                                    class="{{ $savingsRate >= 20 ? 'text-emerald-500' : ($savingsRate >= 0 ? 'text-amber-500' : 'text-red-500') }} animate-draw-circle"
                                    stroke-dasharray="283" stroke-dashoffset="{{ 283 - (283 * max(0, min(100, $savingsRate)) / 100) }}"/>
                            </svg>
                            <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200">{{ $savingsRate }}%</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $currencySymbol }}{{ number_format($balance, 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">saved this month</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-3 animate-fade-in-up anim-delay-5">
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Transaction
                </a>
                <a href="{{ route('budgets.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                    Set Budget
                </a>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    View Reports
                </a>
            </div>

            <!-- AI Advisor & Reminders Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- AI Advisor Widget -->
                <div class="lg:col-span-2 animate-fade-in-up anim-delay-5 bg-gradient-to-tr from-slate-900 via-slate-800 to-indigo-950 text-white rounded-3xl p-8 shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-16 -top-16 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all duration-700"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 113.536 0V21h-3.536v-4.8z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-white">Smart AI Advisor</h3>
                                <p class="text-xs text-indigo-200">Powered by Gemini</p>
                            </div>
                        </div>
                        <div class="prose prose-invert max-w-none text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                            {!! Illuminate\Support\Str::markdown($aiAdvice) !!}
                        </div>
                    </div>
                </div>

                <!-- Upcoming Bills & Savings Goals Widget -->
                <div class="space-y-6 flex flex-col justify-between">
                    <!-- Bill Reminders -->
                    <div class="animate-fade-in-up bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-gray-700/50 flex-1">
                        <h4 class="font-bold text-sm text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Upcoming Reminders
                        </h4>
                        @if($upcomingBills->isEmpty())
                            <div class="text-center py-6 text-xs text-gray-500 dark:text-gray-400">No bill reminders for the next 7 days</div>
                        @else
                            <div class="space-y-3">
                                @foreach($upcomingBills as $bill)
                                    <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-gray-700/40 rounded-xl">
                                        <div>
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $bill->description ?: 'Bill' }}</p>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500">Due {{ $bill->next_due_date->format('M d') }} ({{ ucfirst($bill->frequency) }})</p>
                                        </div>
                                        <span class="text-xs font-extrabold text-rose-500">{{ $currencySymbol }}{{ number_format($bill->amount, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Savings Goal Quick Glance -->
                    <div class="animate-fade-in-up bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-gray-700/50 flex-1">
                        <h4 class="font-bold text-sm text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Savings Goals
                        </h4>
                        @if($goals->isEmpty())
                            <div class="text-center py-6 text-xs text-gray-500 dark:text-gray-400">
                                <p class="mb-2">No active savings goals.</p>
                                <a href="{{ route('goals.index') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Create a Goal →</a>
                            </div>
                        @else
                            <div class="space-y-3 flex-1 overflow-y-auto">
                                @foreach($goals as $goal)
                                    @php
                                        $gProgress = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0;
                                    @endphp
                                    <div class="mb-2">
                                        <div class="flex justify-between items-center text-[10px] text-gray-500 dark:text-gray-400 mb-1">
                                            <span class="font-bold">{{ $goal->name }}</span>
                                            <span>{{ $currencySymbol }}{{ number_format($goal->current_amount, 0) }} / {{ $currencySymbol }}{{ number_format($goal->target_amount, 0) }}</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-100 dark:bg-gray-700/50 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-300" style="width: {{ $gProgress }}%; background-color: {{ $goal->color }};"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Monthly Trend Chart -->
                <div class="animate-fade-in-up anim-delay-6 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        6-Month Trend
                    </h3>
                    <div class="chart-container" style="height:220px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Category Spending Chart -->
                <div class="animate-fade-in-up anim-delay-7 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                        Spending by Category
                    </h3>
                    @if($categorySpending->isEmpty())
                        <div class="flex items-center justify-center h-48 text-gray-400 dark:text-gray-500 text-sm">No expenses this month</div>
                    @else
                        <div class="chart-container" style="height:220px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Budgets Progress -->
                <div class="animate-fade-in-up anim-delay-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Budgets Overview
                    </h3>
                    @if($budgets->isEmpty())
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-gray-700 text-gray-400 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4M12 20V4"></path></svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No budgets set for this month.</p>
                            <a href="{{ route('budgets.create') }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:underline mt-2 inline-block">Set a budget →</a>
                        </div>
                    @else
                        <div class="space-y-5">
                             @foreach($budgets as $budget)
                                <div>
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="flex items-center gap-2 font-medium text-gray-700 dark:text-gray-200 text-sm">
                                            <span class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $budget->category->color }}"></span>
                                            {{ $budget->category->name }}
                                        </span>
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 tabular-nums">
                                            {{ $currencySymbol }}{{ number_format($budget->spent, 0) }} <span class="text-gray-400 font-normal">/ {{ $currencySymbol }}{{ number_format($budget->amount, 0) }}</span>
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                        <div class="h-2.5 rounded-full animate-progress-fill {{ $budget->progress >= 100 ? 'bg-red-500' : ($budget->progress >= 80 ? 'bg-yellow-500' : 'bg-emerald-500') }}" style="width: {{ min(100, $budget->progress) }}%"></div>
                                    </div>
                                    @if($budget->progress >= 100)
                                        <p class="text-xs text-red-500 mt-1 text-right">Over by {{ $currencySymbol }}{{ number_format($budget->spent - $budget->amount, 0) }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Recent Transactions -->
                <div class="animate-fade-in-up anim-delay-9 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Recent Transactions
                        </h3>
                        <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 transition-colors">View All →</a>
                    </div>
                    @if($recentTransactions->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No recent transactions.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($recentTransactions as $i => $transaction)
                                <div class="flex justify-between items-center p-3 hover:bg-slate-50 dark:hover:bg-gray-700/50 rounded-xl transition-all duration-200 animate-fade-in-up" style="animation-delay: {{ 0.5 + $i * 0.05 }}s;">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm" style="background-color: {{ $transaction->category->color }}">
                                            {{ substr($transaction->category->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm text-gray-800 dark:text-gray-200">{{ $transaction->category->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->date->format('M d, Y') }} · {{ $transaction->description ?? '—' }}</p>
                                        </div>
                                    </div>
                                    <div class="font-bold text-sm tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-500' : 'text-gray-800 dark:text-gray-100' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ $currencySymbol }}{{ number_format($transaction->amount, 0) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    @endpush

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        // Trend Chart
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: @json($monthlyTrends->pluck('label')),
                    datasets: [{
                        label: 'Income',
                        data: @json($monthlyTrends->pluck('income')),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.1)',
                        fill: true, tension: 0.4, borderWidth: 2.5, pointRadius: 4, pointHoverRadius: 6,
                    }, {
                        label: 'Expenses',
                        data: @json($monthlyTrends->pluck('expense')),
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244,63,94,0.1)',
                        fill: true, tension: 0.4, borderWidth: 2.5, pointRadius: 4, pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { labels: { color: textColor, usePointStyle: true, padding: 20 } } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: textColor } },
                        y: { grid: { color: gridColor }, ticks: { color: textColor, callback: v => '{{ $currencySymbol }}' + (v/1000) + 'k' } }
                    }
                }
            });
        }

        // Category Chart
        const catCtx = document.getElementById('categoryChart');
        if (catCtx) {
            const catData = @json($categorySpending);
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: catData.map(c => c.name),
                    datasets: [{ data: catData.map(c => c.total), backgroundColor: catData.map(c => c.color), borderWidth: 0, hoverOffset: 8 }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '65%',
                    plugins: { legend: { position: 'right', labels: { color: textColor, usePointStyle: true, padding: 12, font: { size: 12 } } } }
                }
            });
        }
    });
    </script>
</x-app-layout>
