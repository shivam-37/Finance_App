<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Budget') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-3xl border border-slate-100 dark:border-gray-700/50 relative">
                <!-- Decorative Top Border -->
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-500"></div>
                
                <div class="p-10 text-gray-900 dark:text-gray-100">
                    <!-- show errors -->
                    @if ($errors->any())
                        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl relative mb-6 shadow-sm">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('budgets.update', $budget) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="month">
                                        Budget Month
                                    </label>
                                    <input class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" id="month" type="month" name="month" value="{{ $budget->month }}" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="amount">
                                        Amount Limit (₹)
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 font-medium">₹</span>
                                        </div>
                                        <input class="w-full pl-8 bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" id="amount" type="number" step="0.01" name="amount" value="{{ $budget->amount }}" placeholder="0.00" required>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="category_id">
                                    Expense Category
                                </label>
                                <select class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $budget->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="pt-4 flex items-center justify-end gap-4">
                                <a href="{{ route('budgets.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium px-4 py-2 transition-colors">
                                    Cancel
                                </a>
                                <button class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-amber-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500" type="submit">
                                    Update Budget
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
