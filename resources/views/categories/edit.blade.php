<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Edit Category') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="animate-fade-in-up bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-3xl border border-slate-100 dark:border-gray-700/50 relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-500"></div>
                <div class="p-10 text-gray-900 dark:text-gray-100">
                    @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl">
                        <ul class="text-sm text-red-600 dark:text-red-400 list-disc list-inside">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('categories.update', $category) }}">
                        @csrf @method('PUT')
                        <div class="space-y-6" x-data="{ type: '{{ $category->type }}' }">
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-3">Category Type</label>
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
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Name</label>
                                <input class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" type="text" name="name" value="{{ $category->name }}" required>
                            </div>
                            <div x-data="{ color: '{{ $category->color }}' }">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Color</label>
                                <div class="flex items-center gap-4">
                                    <input type="color" name="color" x-model="color" class="w-14 h-14 rounded-xl border-2 border-slate-200 dark:border-gray-700 cursor-pointer p-1">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['#6366f1','#8b5cf6','#ec4899','#f43f5e','#f97316','#eab308','#22c55e','#14b8a6','#06b6d4','#3b82f6'] as $c)
                                        <button type="button" @click="color = '{{ $c }}'" class="w-8 h-8 rounded-lg border-2 transition-all hover:scale-110" :class="color === '{{ $c }}' ? 'border-gray-800 dark:border-white scale-110' : 'border-transparent'" style="background-color:{{ $c }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 flex items-center justify-end gap-4">
                                <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium px-4 py-2 transition-colors">Cancel</a>
                                <button class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-indigo-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95" type="submit">Update Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
