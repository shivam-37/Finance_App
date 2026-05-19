<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Reports & Analytics') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="animate-fade-in-up anim-delay-1 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Income</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 tabular-nums">₹{{ number_format($totalIncome, 2) }}</p>
                </div>
                <div class="animate-fade-in-up anim-delay-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Expenses</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 tabular-nums">₹{{ number_format($totalExpense, 2) }}</p>
                </div>
                <div class="animate-fade-in-up anim-delay-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Transactions</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $totalTransactions }}</p>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Income vs Expense Bar Chart -->
                <div class="animate-fade-in-up anim-delay-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Income vs Expenses
                    </h3>
                    <div class="chart-container" style="height:260px;"><canvas id="barChart"></canvas></div>
                </div>

                <!-- Spending by Category Doughnut -->
                <div class="animate-fade-in-up anim-delay-5 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                        Spending by Category
                    </h3>
                    @if($categorySpending->isEmpty())
                        <div class="flex items-center justify-center h-56 text-gray-400 text-sm">No expense data this month</div>
                    @else
                        <div class="chart-container" style="height:260px;"><canvas id="doughnutChart"></canvas></div>
                    @endif
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Savings Trend -->
                <div class="animate-fade-in-up anim-delay-6 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"></path></svg>
                        Monthly Savings Trend
                    </h3>
                    <div class="chart-container" style="height:260px;"><canvas id="savingsChart"></canvas></div>
                </div>

                <!-- Top 5 Expenses -->
                <div class="animate-fade-in-up anim-delay-7 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                        Top 5 Expenses This Month
                    </h3>
                    @if($topExpenses->isEmpty())
                        <div class="flex items-center justify-center h-56 text-gray-400 text-sm">No expenses this month</div>
                    @else
                        <div class="space-y-4">
                            @foreach($topExpenses as $i => $t)
                            <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors animate-fade-in-up" style="animation-delay:{{ 0.6 + $i * 0.05 }}s;">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background-color: {{ $t->category->color }}">{{ $i + 1 }}</div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $t->description ?: $t->category->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $t->date->format('M d') }} · {{ $t->category->name }}</p>
                                    </div>
                                </div>
                                <span class="font-bold text-sm text-gray-800 dark:text-gray-100 tabular-nums">₹{{ number_format($t->amount, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
        const textColor = isDark ? '#94a3b8' : '#64748b';
        const trends = @json($monthlyTrends);
        const catData = @json($categorySpending);

        // Bar Chart
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: trends.map(t => t.label),
                datasets: [
                    { label: 'Income', data: trends.map(t => t.income), backgroundColor: '#10b981', borderRadius: 8, barPercentage: 0.6 },
                    { label: 'Expenses', data: trends.map(t => t.expense), backgroundColor: '#f43f5e', borderRadius: 8, barPercentage: 0.6 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: textColor, usePointStyle: true, padding: 20 } } },
                scales: { x: { grid: { display: false }, ticks: { color: textColor } }, y: { grid: { color: gridColor }, ticks: { color: textColor, callback: v => '₹' + (v/1000) + 'k' } } }
            }
        });

        // Doughnut Chart
        if (catData.length > 0) {
            new Chart(document.getElementById('doughnutChart'), {
                type: 'doughnut',
                data: { labels: catData.map(c => c.name), datasets: [{ data: catData.map(c => c.total), backgroundColor: catData.map(c => c.color), borderWidth: 0, hoverOffset: 8 }] },
                options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { position: 'right', labels: { color: textColor, usePointStyle: true, padding: 12, font: { size: 12 } } } } }
            });
        }

        // Savings Line Chart
        new Chart(document.getElementById('savingsChart'), {
            type: 'line',
            data: {
                labels: trends.map(t => t.label),
                datasets: [{ label: 'Net Savings', data: trends.map(t => t.savings), borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.15)', fill: true, tension: 0.4, borderWidth: 2.5, pointRadius: 5, pointHoverRadius: 7, pointBackgroundColor: '#10b981' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: textColor, usePointStyle: true } } },
                scales: { x: { grid: { display: false }, ticks: { color: textColor } }, y: { grid: { color: gridColor }, ticks: { color: textColor, callback: v => '₹' + (v/1000) + 'k' } } }
            }
        });
    });
    </script>
</x-app-layout>
