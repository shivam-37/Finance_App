<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-3xl border border-slate-100 dark:border-gray-700/50 relative">
                <!-- Decorative Top Border -->
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500"></div>
                
                <div class="p-10 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('transactions.store') }}">
                        @csrf
                        <div class="space-y-6" x-data="{ type: 'expense' }">
                            
                            <!-- Type Selection (Radios disguised as pills) -->
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-3">
                                    Transaction Type
                                </label>
                                <div class="flex p-1 bg-slate-100 dark:bg-gray-900 rounded-xl">
                                    <label class="flex-1 text-center cursor-pointer">
                                        <input type="radio" name="type" value="expense" x-model="type" class="sr-only">
                                        <div class="py-2.5 rounded-lg text-sm font-medium transition-all duration-200"
                                             :class="type === 'expense' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
                                            Expense
                                        </div>
                                    </label>
                                    <label class="flex-1 text-center cursor-pointer">
                                        <input type="radio" name="type" value="income" x-model="type" class="sr-only">
                                        <div class="py-2.5 rounded-lg text-sm font-medium transition-all duration-200"
                                             :class="type === 'income' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
                                            Income
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="category_id">
                                    Category
                                </label>
                                <select class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="category_id" name="category_id" required>
                                    @if($categories->isEmpty())
                                        <option value="" disabled selected>No categories available. Please create one in Categories first.</option>
                                    @else
                                        <option value="" disabled selected>Select a category...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" x-show="type === '{{ $category->type }}' || !type">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="amount">
                                        Amount (₹)
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 font-medium">₹</span>
                                        </div>
                                        <input class="w-full pl-8 bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="amount" type="number" step="0.01" name="amount" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="date">
                                        Date
                                    </label>
                                    <input class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="date" type="date" name="date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="description">
                                    Description (Optional)
                                </label>
                                <input class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="description" type="text" name="description" placeholder="What was this transaction for?">
                            </div>
                            
                            <div class="pt-4 flex items-center justify-end gap-4">
                                <a href="{{ route('transactions.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium px-4 py-2 transition-colors">
                                    Cancel
                                </a>
                                <button class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-indigo-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" type="submit">
                                    Save Transaction
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
