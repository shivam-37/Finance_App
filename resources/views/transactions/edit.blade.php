<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Edit Transaction') }}</h2>
    </x-slot>
    
    <div class="py-12" x-data="{
        type: '{{ old('type', $transaction->type) }}',
        isRecurring: {{ old('is_recurring', $transaction->is_recurring) ? 'true' : 'false' }},
        frequency: '{{ old('frequency', $transaction->frequency ?? 'monthly') }}',
        selectedCurrency: '{{ old('currency', $transaction->currency ?? Auth::user()->currency ?? 'INR') }}'
    }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-3xl border border-slate-100 dark:border-gray-700/50 relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-500"></div>
                <div class="p-10 text-gray-900 dark:text-gray-100">
                    
                    @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl">
                        <ul class="text-sm text-red-600 dark:text-red-400 list-disc list-inside">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <form method="POST" action="{{ route('transactions.update', $transaction) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            
                            <!-- Type selection -->
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-3">Transaction Type</label>
                                <div class="flex p-1 bg-slate-100 dark:bg-gray-900 rounded-xl">
                                    <label class="flex-1 text-center cursor-pointer">
                                        <input type="radio" name="type" value="expense" x-model="type" class="sr-only">
                                        <div class="py-2.5 rounded-lg text-sm font-medium transition-all duration-200" :class="type === 'expense' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 hover:text-gray-700'">Expense</div>
                                    </label>
                                    <label class="flex-1 text-center cursor-pointer">
                                        <input type="radio" name="type" value="income" x-model="type" class="sr-only">
                                        <div class="py-2.5 rounded-lg text-sm font-medium transition-all duration-200" :class="type === 'income' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 hover:text-gray-700'">Income</div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Category selection -->
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="category_id">Category</label>
                                <select class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Currency & Amount Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="currency">Currency</label>
                                    <select x-model="selectedCurrency" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="currency" name="currency">
                                        @foreach($currencies as $code => $symbol)
                                            <option value="{{ $code }}" {{ old('currency', $transaction->currency ?? Auth::user()->currency ?? 'INR') === $code ? 'selected' : '' }}>
                                                {{ $code }} ({{ $symbol }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="amount">Amount</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 font-medium" x-text="selectedCurrency"></span>
                                        </div>
                                        <input class="w-full pl-14 bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="amount" type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->original_amount ?? $transaction->amount) }}" required placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Date selection -->
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="date">Date</label>
                                <input class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="date" type="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required>
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="description">Description</label>
                                <input class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="description" type="text" name="description" value="{{ old('description', $transaction->description) }}" placeholder="What was this transaction for?">
                            </div>
                            
                            <!-- Recurring Settings -->
                            <div class="p-5 bg-slate-50 dark:bg-gray-900/40 rounded-2xl border border-slate-100 dark:border-gray-700/30">
                                <label class="flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="is_recurring" value="1" x-model="isRecurring" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                    <span class="ml-3 text-sm font-bold text-gray-800 dark:text-gray-200">Make this a Recurring Transaction</span>
                                </label>
                                
                                <div x-show="isRecurring" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 animate-scale-in" style="display: none;">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5" for="frequency">Frequency</label>
                                        <select x-model="frequency" class="w-full bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl py-2 px-3 text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" id="frequency" name="frequency">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                    </div>
                                    <div class="flex items-end">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-normal">
                                            The system will automatically duplicate this transaction on each cycle start. Future copies are added as normal historical items.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="pt-4 flex items-center justify-end gap-4 border-t border-gray-100 dark:border-gray-700/50">
                                <a href="{{ route('transactions.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium px-4 py-2 transition-colors">Cancel</a>
                                <button class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg transition-all hover:scale-105 active:scale-95" type="submit">Update Transaction</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
