<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-bind:class="darkMode ? 'dark' : ''">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Finance App — Your smart personal finance companion. Track income, manage expenses, set budgets, and gain insights with beautiful analytics.">

        <title>Finance App — Smart Personal Finance Tracker</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-[#0f172a] text-gray-900 dark:text-gray-100 selection:bg-indigo-500 selection:text-white overflow-x-hidden">

        <!-- Hero Section -->
        <div class="relative min-h-screen flex flex-col">
            <!-- Animated Background -->
            <div class="absolute inset-0 overflow-hidden -z-10">
                <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-[#0f172a] dark:via-[#1e293b] dark:to-[#0f172a]"></div>
                <div class="absolute top-20 left-10 w-96 h-96 bg-indigo-400/20 dark:bg-indigo-500/10 rounded-full blur-3xl animate-float"></div>
                <div class="absolute top-40 right-10 w-80 h-80 bg-purple-400/20 dark:bg-purple-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
                <div class="absolute bottom-20 left-1/3 w-72 h-72 bg-emerald-400/15 dark:bg-emerald-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 4s;"></div>
                <div class="absolute top-1/2 right-1/4 w-64 h-64 bg-rose-400/10 dark:bg-rose-500/5 rounded-full blur-3xl animate-float" style="animation-delay: 3s;"></div>
            </div>

            <!-- Nav -->
            <nav class="relative z-50 max-w-7xl mx-auto w-full px-6 py-6 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-300/50 dark:shadow-indigo-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xl font-bold">Finance App</span>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                                Get Started Free
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>

            <!-- Hero Content -->
            <div class="flex-1 flex items-center justify-center px-6 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="animate-fade-in-up">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 mb-6 border border-indigo-200/50 dark:border-indigo-700/30">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Smart Finance Tracking
                        </span>
                    </div>

                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-tight mb-6 animate-fade-in-up anim-delay-2">
                        Take Control of<br>
                        <span class="gradient-text">Your Finances</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-10 animate-fade-in-up anim-delay-4 leading-relaxed">
                        Track your income, manage expenses, set budgets, and gain powerful insights — all in one beautifully designed dashboard.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up anim-delay-6">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-2xl shadow-xl shadow-indigo-300/40 dark:shadow-indigo-500/20 transition-all hover:scale-105 active:scale-95 hover:shadow-2xl text-lg">
                                Go to Dashboard
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-2xl shadow-xl shadow-indigo-300/40 dark:shadow-indigo-500/20 transition-all hover:scale-105 active:scale-95 hover:shadow-2xl text-lg">
                                Start Free
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-4 text-gray-700 dark:text-gray-300 font-medium rounded-2xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all text-lg">
                                Sign In
                            </a>
                        @endauth
                    </div>

                    <!-- Dashboard Preview Mockup -->
                    <div class="mt-16 animate-fade-in-up anim-delay-8">
                        <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 p-4 sm:p-6 max-w-3xl mx-auto">
                            <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-4">
                                <div class="bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl p-4 text-white text-left">
                                    <p class="text-xs opacity-80 mb-1">Income</p>
                                    <p class="text-lg sm:text-2xl font-bold">₹45,200</p>
                                </div>
                                <div class="bg-gradient-to-br from-rose-400 to-red-500 rounded-2xl p-4 text-white text-left">
                                    <p class="text-xs opacity-80 mb-1">Expenses</p>
                                    <p class="text-lg sm:text-2xl font-bold">₹28,750</p>
                                </div>
                                <div class="bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl p-4 text-white text-left">
                                    <p class="text-xs opacity-80 mb-1">Saved</p>
                                    <p class="text-lg sm:text-2xl font-bold">₹16,450</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-5 gap-2 h-20">
                                @foreach([60, 80, 45, 70, 90] as $i => $height)
                                <div class="flex items-end">
                                    <div class="w-full bg-gradient-to-t from-indigo-500 to-indigo-400 rounded-lg transition-all duration-1000" style="height: {{ $height }}%; animation: fadeInUp 0.6s ease both; animation-delay: {{ 0.8 + $i * 0.1 }}s;"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <section class="py-24 px-6 relative">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-4">Everything You Need to <span class="gradient-text">Manage Money</span></h2>
                    <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Powerful features wrapped in a beautiful, intuitive interface designed to help you master your finances.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 border border-gray-200/50 dark:border-gray-700/50 hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-200/50 dark:shadow-none group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-gray-100">Budget Tracking</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Set monthly budgets per category and monitor spending with visual progress bars. Get alerts when you're nearing limits.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: 0.1s;" class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 border border-gray-200/50 dark:border-gray-700/50 hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-indigo-200/50 dark:shadow-none group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-gray-100">Expense Analytics</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Beautiful charts and reports showing where your money goes. Discover spending patterns with monthly trend analysis.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: 0.2s;" class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 border border-gray-200/50 dark:border-gray-700/50 hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-amber-200/50 dark:shadow-none group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-gray-100">Smart Categories</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Organize transactions with custom color-coded categories. Separate income and expense categories for clarity.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 px-6 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 dark:from-indigo-900 dark:via-purple-900 dark:to-indigo-900 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-30"></div>
            <div class="max-w-5xl mx-auto relative z-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                    <div x-data x-intersect.once="$el.classList.add('animate-bounce-in')">
                        <div class="text-4xl sm:text-5xl font-extrabold mb-2">100%</div>
                        <div class="text-indigo-200 text-sm font-medium">Free & Open</div>
                    </div>
                    <div x-data x-intersect.once="$el.classList.add('animate-bounce-in')" style="animation-delay: 0.1s;">
                        <div class="text-4xl sm:text-5xl font-extrabold mb-2">∞</div>
                        <div class="text-indigo-200 text-sm font-medium">Transactions</div>
                    </div>
                    <div x-data x-intersect.once="$el.classList.add('animate-bounce-in')" style="animation-delay: 0.2s;">
                        <div class="text-4xl sm:text-5xl font-extrabold mb-2">5+</div>
                        <div class="text-indigo-200 text-sm font-medium">Chart Types</div>
                    </div>
                    <div x-data x-intersect.once="$el.classList.add('animate-bounce-in')" style="animation-delay: 0.3s;">
                        <div class="text-4xl sm:text-5xl font-extrabold mb-2">24/7</div>
                        <div class="text-indigo-200 text-sm font-medium">Access Anywhere</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 px-6">
            <div class="max-w-3xl mx-auto text-center" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')">
                <h2 class="text-3xl sm:text-4xl font-bold mb-6">Ready to Master Your <span class="gradient-text">Finances?</span></h2>
                <p class="text-gray-600 dark:text-gray-400 mb-10 text-lg">Join now and start tracking your money with the most beautiful finance app.</p>
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-2xl shadow-xl shadow-indigo-300/40 dark:shadow-indigo-500/20 transition-all hover:scale-105 active:scale-95 text-lg">
                        Open Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-2xl shadow-xl shadow-indigo-300/40 dark:shadow-indigo-500/20 transition-all hover:scale-105 active:scale-95 text-lg">
                        Create Free Account →
                    </a>
                @endauth
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-gray-200/50 dark:border-gray-700/30 py-8 px-6">
            <div class="max-w-6xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Finance App</span>
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} Finance App. Built with ❤️ using Laravel & Tailwind CSS.</p>
            </div>
        </footer>
    </body>
</html>
