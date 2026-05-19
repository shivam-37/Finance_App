<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Savings Goals') }}</h2>
            <button @click="openCreateModal = true" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Savings Goal
            </button>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        openCreateModal: false,
        openAdjustModal: false,
        openEditModal: false,
        adjustGoalId: '',
        adjustGoalName: '',
        adjustType: 'deposit',
        editGoalId: '',
        editGoalName: '',
        editGoalTarget: 0,
        editGoalDeadline: '',
        editGoalColor: '#6366f1',
        setAdjustGoal(id, name, type) {
            this.adjustGoalId = id;
            this.adjustGoalName = name;
            this.adjustType = type;
            this.openAdjustModal = true;
        },
        setEditGoal(id, name, target, deadline, color) {
            this.editGoalId = id;
            this.editGoalName = name;
            this.editGoalTarget = target;
            this.editGoalDeadline = deadline;
            this.editGoalColor = color;
            this.openEditModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($goals->isEmpty())
                <div class="animate-fade-in-up bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-16 shadow-sm border border-slate-100 dark:border-gray-700/50 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-gray-700 text-gray-400 mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">No savings goals yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Create virtual piggy banks for future purchases, trips, or emergency funds.</p>
                    <button @click="openCreateModal = true" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg transition-all hover:scale-105 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Your First Goal
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($goals as $i => $goal)
                    @php
                        $progress = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0;
                    @endphp
                    <div class="animate-fade-in-up bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-gray-700/50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group relative overflow-hidden" style="animation-delay: {{ $i * 0.05 }}s;">
                        <div class="absolute top-0 left-0 w-full h-1.5 rounded-t-2xl" style="background-color: {{ $goal->color }};"></div>
                        
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800 dark:text-gray-200 text-lg">{{ $goal->name }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Target Date: {{ $goal->deadline->format('M d, Y') }}</p>
                            </div>
                            
                            {{-- Admin actions popup on hover --}}
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="setEditGoal('{{ $goal->id }}', '{{ $goal->name }}', {{ $goal->target_amount }}, '{{ $goal->deadline->format('Y-m-d') }}', '{{ $goal->color }}')" class="p-2 rounded-xl text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ route('goals.destroy', $goal) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="document.querySelector('[x-data=\'confirmModal\']').__x.$data.show('Delete Savings Goal', 'Are you sure? This will remove this piggy bank and all virtual funds in it.', this.closest('form'))" class="p-2 rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Balance Metrics --}}
                        <div class="mt-6 flex justify-between items-baseline">
                            <div>
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Saved</span>
                                <span class="text-2xl font-extrabold text-gray-800 dark:text-gray-100">₹{{ number_format($goal->current_amount, 2) }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Target</span>
                                <span class="text-sm font-bold text-gray-600 dark:text-gray-300">₹{{ number_format($goal->target_amount, 2) }}</span>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mt-4">
                            <div class="flex justify-between items-center text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5">
                                <span>Progress</span>
                                <span style="color: {{ $goal->color }}">{{ round($progress, 1) }}%</span>
                            </div>
                            <div class="w-full h-2.5 bg-gray-100 dark:bg-gray-700/50 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $progress }}%; background-color: {{ $goal->color }};"></div>
                            </div>
                        </div>

                        {{-- Money Actions --}}
                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/50 flex gap-2">
                            <button @click="setAdjustGoal('{{ $goal->id }}', '{{ $goal->name }}', 'deposit')" class="flex-1 py-2 px-3 bg-slate-50 dark:bg-gray-700/40 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-emerald-600 text-sm font-semibold rounded-xl text-gray-600 dark:text-gray-300 transition-colors flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Deposit
                            </button>
                            <button @click="setAdjustGoal('{{ $goal->id }}', '{{ $goal->name }}', 'withdraw')" class="flex-1 py-2 px-3 bg-slate-50 dark:bg-gray-700/40 hover:bg-rose-50 dark:hover:bg-rose-950/20 hover:text-rose-600 text-sm font-semibold rounded-xl text-gray-600 dark:text-gray-300 transition-colors flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                Withdraw
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ================= MODALS ================= --}}

        {{-- 1. Create Goal Modal --}}
        <div x-show="openCreateModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="openCreateModal = false">
            <div @click.away="openCreateModal = false" class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 dark:border-gray-700 animate-scale-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">New Savings Goal</h3>
                    <button @click="openCreateModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('goals.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Goal Name</label>
                        <input type="text" name="name" required placeholder="e.g. Electric Scooter, Vacation" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Amount (₹)</label>
                            <input type="number" name="target_amount" step="0.01" min="0.01" required placeholder="10000" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Starting Amount (₹)</label>
                            <input type="number" name="current_amount" step="0.01" min="0" placeholder="0" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Date</label>
                        <input type="date" name="deadline" required class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color Theme</label>
                        <div class="flex gap-2.5 mt-1.5">
                            @foreach (['#6366f1', '#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#ef4444', '#8b5cf6'] as $color)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="color" value="{{ $color }}" {{ $loop->first ? 'checked' : '' }} class="sr-only peer">
                                <span class="block w-7 h-7 rounded-full border border-gray-200/50 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-indigo-500" style="background-color: {{ $color }};"></span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg transition-all mt-4">
                        Create Goal
                    </button>
                </form>
            </div>
        </div>

        {{-- 2. Adjust Balance Modal (Deposit/Withdraw) --}}
        <div x-show="openAdjustModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="openAdjustModal = false">
            <div @click.away="openAdjustModal = false" class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 dark:border-gray-700 animate-scale-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100" x-text="adjustType === 'deposit' ? 'Add Funds' : 'Withdraw Funds'"></h3>
                    <button @click="openAdjustModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form :action="'/goals/' + adjustGoalId + '/adjust'" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" :value="adjustType">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Adjusting virtual piggy bank balance for <strong class="text-gray-800 dark:text-gray-200" x-text="adjustGoalName"></strong>.
                    </p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount (₹)</label>
                        <input type="number" name="amount" step="0.01" min="0.01" required autofocus placeholder="0.00" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <button type="submit" :class="adjustType === 'deposit' ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700' : 'bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700'" class="w-full py-3 px-4 text-white font-semibold rounded-xl shadow-lg transition-all mt-4">
                        Confirm <span x-text="adjustType === 'deposit' ? 'Deposit' : 'Withdrawal'"></span>
                    </button>
                </form>
            </div>
        </div>

        {{-- 3. Edit Goal Modal --}}
        <div x-show="openEditModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="openEditModal = false">
            <div @click.away="openEditModal = false" class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 dark:border-gray-700 animate-scale-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Edit Savings Goal</h3>
                    <button @click="openEditModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form :action="'/goals/' + editGoalId" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Goal Name</label>
                        <input type="text" name="name" x-model="editGoalName" required class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Amount (₹)</label>
                        <input type="number" name="target_amount" step="0.01" min="0.01" x-model="editGoalTarget" required class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Date</label>
                        <input type="date" name="deadline" x-model="editGoalDeadline" required class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-2.5 px-4 text-sm text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color Theme</label>
                        <div class="flex gap-2.5 mt-1.5">
                            @foreach (['#6366f1', '#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#ef4444', '#8b5cf6'] as $color)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="color" value="{{ $color }}" x-model="editGoalColor" class="sr-only peer">
                                <span class="block w-7 h-7 rounded-full border border-gray-200/50 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-indigo-500" style="background-color: {{ $color }};"></span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg transition-all mt-4">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
