<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Add Transaction') }}</h2>
    </x-slot>
    
    <div class="py-12" x-data="{
        type: '{{ old('type', 'expense') }}',
        isRecurring: {{ old('is_recurring') ? 'true' : 'false' }},
        frequency: '{{ old('frequency', 'monthly') }}',
        selectedCurrency: '{{ old('currency', Auth::user()->currency ?? 'INR') }}',
        scanning: false,
        scanError: '',
        scanFile(e) {
            let file = e.target.files[0];
            if (!file) return;
            
            this.scanning = true;
            this.scanError = '';
            
            let formData = new FormData();
            formData.append('receipt', file);
            
            fetch('{{ route('transactions.scan') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.scanning = false;
                if (data.success) {
                    // Populate fields
                    if (data.data.amount) document.getElementById('amount').value = data.data.amount;
                    if (data.data.description) document.getElementById('description').value = data.data.description;
                    if (data.data.date) document.getElementById('date').value = data.data.date;
                    if (data.data.type) this.type = data.data.type;
                    if (data.data.category_details) {
                        let select = document.getElementById('category_id');
                        let exists = Array.from(select.options).some(opt => opt.value === data.data.category_details.id);
                        if (!exists) {
                            let newOption = new Option(data.data.category_details.name, data.data.category_details.id);
                            select.add(newOption);
                        }
                        select.value = data.data.category_details.id;
                    } else if (data.data.category_id) {
                        document.getElementById('category_id').value = data.data.category_id;
                    }
                } else {
                    this.scanError = data.message || 'Scanning failed. Try again.';
                }
            })
            .catch(err => {
                this.scanning = false;
                this.scanError = 'Network error scanning receipt.';
                console.error(err);
            });
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Panel: OCR Scanner Widget -->
            <div class="animate-fade-in-up bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-6 shadow-xl border border-slate-100 dark:border-gray-700/50 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                <div>
                    <h3 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-2">Smart Scan Receipt</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">Upload a receipt image. Gemini AI will read it and automatically fill out the transaction form for you.</p>
                    
                    <div class="border-2 border-dashed border-slate-200 dark:border-gray-700 rounded-2xl p-6 text-center hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors cursor-pointer relative group flex flex-col items-center justify-center min-h-[200px]">
                        <input type="file" @change="scanFile" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        
                        <div x-show="!scanning" class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-full bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Click to upload image</span>
                            <span class="text-xs text-gray-400 mt-1">PNG, JPG up to 5MB</span>
                        </div>
                        
                        <!-- Scanning Status -->
                        <div x-show="scanning" class="flex flex-col items-center" style="display: none;">
                            <div class="relative w-16 h-16 mb-4">
                                <div class="absolute inset-0 rounded-full border-4 border-indigo-200 dark:border-indigo-900"></div>
                                <div class="absolute inset-0 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
                            </div>
                            <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 animate-pulse">Reading receipt with AI...</span>
                        </div>
                    </div>
                    
                    <div x-show="scanError" x-text="scanError" class="mt-4 p-3 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 rounded-xl text-xs font-semibold" style="display: none;"></div>
                </div>
                
                <div class="mt-6 p-4 bg-slate-50 dark:bg-gray-900/50 rounded-xl">
                    <h4 class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Supported Currencies</h4>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($currencies as $code => $symbol)
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-white dark:bg-gray-800 text-xs font-semibold text-gray-600 dark:text-gray-400 border border-slate-100 dark:border-gray-700">
                                {{ $code }} ({{ $symbol }})
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Panel: Transaction Form -->
            <div class="animate-fade-in-up anim-delay-2 lg:col-span-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-xl sm:rounded-3xl border border-slate-100 dark:border-gray-700/50 relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-500 via-indigo-500 to-emerald-500"></div>
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl">
                        <ul class="text-sm text-red-600 dark:text-red-400 list-disc list-inside">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data">
                        @csrf
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
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Currency & Amount Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="currency">Currency</label>
                                    <select x-model="selectedCurrency" class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="currency" name="currency">
                                        @foreach($currencies as $code => $symbol)
                                            <option value="{{ $code }}" {{ old('currency', Auth::user()->currency ?? 'INR') === $code ? 'selected' : '' }}>
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
                                        <input class="w-full pl-14 bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="amount" type="number" step="0.01" name="amount" value="{{ old('amount') }}" required placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Date selection -->
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="date">Date</label>
                                <input class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="date" type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2" for="description">Description</label>
                                <input class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" id="description" type="text" name="description" value="{{ old('description') }}" placeholder="What was this transaction for?">
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
                                <button class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg transition-all hover:scale-105 active:scale-95" type="submit">Save Transaction</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
