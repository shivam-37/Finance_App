<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Budgets') }}</h2>
            <a href="{{ route('budgets.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Budget
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($budgets->isEmpty())
                <div class="animate-fade-in-up bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-16 shadow-sm border border-slate-100 dark:border-gray-700/50 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-gray-700 text-gray-400 mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">No budgets set</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Create budgets to track your expenses against your financial goals.</p>
                    <a href="{{ route('budgets.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg transition-all hover:scale-105 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Set Your First Budget
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($budgets as $i => $budget)
                    <div class="animate-fade-in-up bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-gray-700/50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group" style="animation-delay: {{ $i * 0.05 }}s;">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm" style="background-color: {{ $budget->category->color }}">
                                    {{ substr($budget->category->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 dark:text-gray-200 text-sm">{{ $budget->category->name }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::createFromFormat('Y-m', $budget->month)->format('M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('budgets.edit', $budget) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="document.querySelector('[x-data=\'confirmModal\']').__x.$data.show('Delete Budget', 'This budget will be permanently removed.', this.closest('form'))" class="p-1.5 rounded-lg text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Progress -->
                        <div class="mb-3">
                            <div class="flex justify-between text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                <span class="tabular-nums">₹{{ number_format($budget->spent, 0) }} spent</span>
                                <span class="tabular-nums">₹{{ number_format($budget->amount, 0) }} limit</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                                <div class="h-3 rounded-full animate-progress-fill {{ $budget->progress >= 100 ? 'bg-red-500' : ($budget->progress >= 80 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ min(100, $budget->progress) }}%"></div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold {{ $budget->progress >= 100 ? 'text-red-500' : ($budget->progress >= 80 ? 'text-amber-500' : 'text-emerald-500') }}">
                                {{ number_format($budget->progress, 0) }}% used
                            </span>
                            @if($budget->progress >= 100)
                                <span class="text-xs text-red-500 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path></svg>
                                    Over by ₹{{ number_format($budget->spent - $budget->amount, 0) }}
                                </span>
                            @else
                                <span class="text-xs text-gray-500 dark:text-gray-400 tabular-nums">₹{{ number_format($budget->amount - $budget->spent, 0) }} left</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
