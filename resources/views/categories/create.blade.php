<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-3xl border border-slate-100 dark:border-gray-700/50 relative">
                <!-- Decorative Top Border -->
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-500"></div>
                
                <div class="p-10 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div class="space-y-6" x-data="{ type: 'expense' }">
                            
                            <!-- Type Selection (Radios disguised as pills) -->
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-3">
                                    Category Type
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
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="name">
                                    Category Name
                                </label>
                                <input class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" id="name" type="text" name="name" placeholder="e.g. Groceries, Salary, Utilities" required>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="color">
                                    Color Theme
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="relative w-14 h-14 rounded-full overflow-hidden shadow-inner border-2 border-slate-200 dark:border-gray-700 focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-transparent transition-all">
                                        <input class="absolute -top-2 -left-2 w-20 h-20 cursor-pointer" id="color" type="color" name="color" value="#10b981" required>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Choose a distinct color to easily identify this category on the dashboard.</span>
                                </div>
                            </div>
                            
                            <div class="pt-4 flex items-center justify-end gap-4">
                                <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium px-4 py-2 transition-colors">
                                    Cancel
                                </a>
                                <button class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-emerald-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" type="submit">
                                    Save Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
