<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Finance App — Smart personal finance tracker with budgeting, expense analytics, and beautiful dashboards.">

        <title>{{ config('app.name', 'Finance App') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 relative overflow-hidden">

            {{-- Animated background orbs --}}
            <div class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-indigo-400/15 rounded-full blur-3xl pointer-events-none animate-float"></div>
            <div class="absolute bottom-[-15%] right-[-5%] w-[400px] h-[400px] bg-purple-400/15 rounded-full blur-3xl pointer-events-none animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/3 w-[300px] h-[300px] bg-emerald-400/10 rounded-full blur-3xl pointer-events-none animate-float" style="animation-delay: 4s;"></div>

            {{-- Left side — Brand Panel (hidden on mobile) --}}
            <div class="hidden lg:flex lg:w-1/2 xl:w-[55%] relative items-center justify-center p-12">
                <div class="relative z-10 max-w-lg animate-fade-in-up">
                    {{-- Logo --}}
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-2xl font-bold gradient-text">Finance App</span>
                    </div>

                    {{-- Headline --}}
                    <h1 class="text-4xl xl:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                        Take control of your
                        <span class="gradient-text">finances</span>
                    </h1>
                    <p class="text-lg text-gray-500 leading-relaxed mb-10">
                        Track your income, expenses, and budgets with beautiful charts and smart insights. Your personal finance journey starts here.
                    </p>

                    {{-- Feature highlights --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 animate-fade-in-up anim-delay-2">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Real-time Analytics</p>
                                <p class="text-sm text-gray-500">Beautiful charts and insights at a glance</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 animate-fade-in-up anim-delay-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Smart Budgeting</p>
                                <p class="text-sm text-gray-500">Set budgets and track spending effortlessly</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 animate-fade-in-up anim-delay-6">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Secure & Private</p>
                                <p class="text-sm text-gray-500">Your data stays yours, always encrypted</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right side — Form Panel --}}
            <div class="w-full lg:w-1/2 xl:w-[45%] flex flex-col items-center justify-center px-6 sm:px-12 py-12 relative">
                {{-- Mobile logo (shown only on small screens) --}}
                <div class="lg:hidden mb-8 flex items-center gap-3 animate-fade-in-down">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xl font-bold gradient-text">Finance App</span>
                </div>

                {{-- Form card --}}
                <div class="w-full max-w-md animate-scale-in">
                    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-gray-200/50 border border-white/60 p-8 sm:p-10">
                        {{ $slot }}
                    </div>

                    {{-- Footer --}}
                    <p class="text-center text-xs text-gray-400 mt-8">
                        &copy; {{ date('Y') }} Finance App. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
