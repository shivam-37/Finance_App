<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data x-bind:class="$store.darkMode.on ? 'dark' : ''">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Finance App — Track your income, expenses, and budgets with beautiful charts and smart insights.">
        <meta name="author" content="Finance App">
        <meta property="og:title" content="{{ config('app.name', 'Finance App') }}">
        <meta property="og:description" content="Smart personal finance tracker with budgeting, expense analytics, and beautiful dashboards.">
        <meta property="og:type" content="website">

        <title>{{ config('app.name', 'Finance App') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Flatpickr for date inputs -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-slate-50 dark:bg-[#0f172a] selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 dark:from-[#0f172a] dark:via-[#1e293b] dark:to-[#0f172a] relative">
            <!-- Animated decorative background orbs -->
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-400/10 dark:bg-indigo-500/5 rounded-full blur-3xl -z-10 pointer-events-none animate-float"></div>
            <div class="absolute top-1/3 right-1/4 w-80 h-80 bg-purple-400/10 dark:bg-purple-500/5 rounded-full blur-3xl -z-10 pointer-events-none animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-0 left-1/2 w-72 h-72 bg-emerald-400/8 dark:bg-emerald-500/5 rounded-full blur-3xl -z-10 pointer-events-none animate-float" style="animation-delay: 4s;"></div>

            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-sm border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-40">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 animate-fade-in-down">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="page-enter">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="border-t border-gray-200/50 dark:border-gray-700/30 mt-16">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Finance App</span>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} Finance App. Built with Laravel & Tailwind CSS.</p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Global Toast Container -->
        <div x-data class="fixed top-4 right-4 z-[100] flex flex-col gap-3 pointer-events-none" style="max-width: 400px;">
            <template x-for="toast in $store.toasts.items" :key="toast.id">
                <div :class="toast.leaving ? 'toast-leave' : 'toast-enter'"
                     class="pointer-events-auto flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl border backdrop-blur-xl"
                     :class="{
                         'bg-emerald-50/90 dark:bg-emerald-900/80 border-emerald-200 dark:border-emerald-700 text-emerald-800 dark:text-emerald-200': toast.type === 'success',
                         'bg-red-50/90 dark:bg-red-900/80 border-red-200 dark:border-red-700 text-red-800 dark:text-red-200': toast.type === 'error',
                         'bg-amber-50/90 dark:bg-amber-900/80 border-amber-200 dark:border-amber-700 text-amber-800 dark:text-amber-200': toast.type === 'warning',
                     }">
                    <!-- Icon -->
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                    <span class="text-sm font-medium" x-text="toast.message"></span>
                    <button @click="$store.toasts.remove(toast.id)" class="ml-auto shrink-0 opacity-60 hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </template>
        </div>

        <!-- Flash Message Bridge -->
        @if(session('success'))
            <script>
                document.addEventListener('alpine:init', () => {
                    setTimeout(() => Alpine.store('toasts').add(@json(session('success')), 'success'), 300);
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('alpine:init', () => {
                    setTimeout(() => Alpine.store('toasts').add(@json(session('error')), 'error'), 300);
                });
            </script>
        @endif

        <!-- Global Confirmation Modal -->
        <div x-data="confirmModal">
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[90] modal-backdrop flex items-center justify-center p-4" style="display:none;" @keydown.escape.window="cancel()">
                <div @click.away="cancel()" x-show="open" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 p-8 max-w-md w-full">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" x-text="title"></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="message"></p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button @click="cancel()" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">Cancel</button>
                        <button @click="confirm()" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-200/50 dark:shadow-none transition-all hover:scale-105 active:scale-95">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize standard date pickers
                flatpickr('input[type="date"]', {
                    dateFormat: "Y-m-d",
                    allowInput: true
                });
                
                // Initialize month-only pickers
                flatpickr('input[type="month"]', {
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: true,
                            dateFormat: "Y-m",
                            altFormat: "F Y"
                        })
                    ]
                });
            });
        </script>
    </body>
</html>
