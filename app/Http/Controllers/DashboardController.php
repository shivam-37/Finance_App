<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\GeminiService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $currentMonth = $now->format('Y-m');

        $income = $user->transactions()->where('type', 'income')
            ->whereMonth('date', $now->month)->whereYear('date', $now->year)->sum('amount');
        $expense = $user->transactions()->where('type', 'expense')
            ->whereMonth('date', $now->month)->whereYear('date', $now->year)->sum('amount');
        
        $totalIncome = $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpense = $user->transactions()->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $prev = $now->copy()->subMonth();
        $prevIncome = $user->transactions()->where('type', 'income')
            ->whereMonth('date', $prev->month)->whereYear('date', $prev->year)->sum('amount');
        $prevExpense = $user->transactions()->where('type', 'expense')
            ->whereMonth('date', $prev->month)->whereYear('date', $prev->year)->sum('amount');

        $incomeChange = $prevIncome > 0 ? round((($income - $prevIncome) / $prevIncome) * 100, 1) : ($income > 0 ? 100 : 0);
        $expenseChange = $prevExpense > 0 ? round((($expense - $prevExpense) / $prevExpense) * 100, 1) : ($expense > 0 ? 100 : 0);
        $savingsRate = $income > 0 ? round((($income - $expense) / $income) * 100, 1) : 0;

        $categorySpending = $user->transactions()->where('type', 'expense')
            ->whereMonth('date', $now->month)->whereYear('date', $now->year)
            ->with('category')->get()->groupBy('category_id')
            ->map(fn($txns) => [
                'name' => $txns->first()->category->name,
                'color' => $txns->first()->category->color,
                'total' => $txns->sum('amount'),
            ])->sortByDesc('total')->values();

        $monthlyTrends = collect();
        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $monthlyTrends->push([
                'label' => $m->format('M'),
                'income' => $user->transactions()->where('type', 'income')->whereMonth('date', $m->month)->whereYear('date', $m->year)->sum('amount'),
                'expense' => $user->transactions()->where('type', 'expense')->whereMonth('date', $m->month)->whereYear('date', $m->year)->sum('amount'),
            ]);
        }

        $recentTransactions = $user->transactions()->with('category')->orderBy('date', 'desc')->take(5)->get();

        $budgets = $user->budgets()->with('category')->where('month', $currentMonth)->get()
            ->map(function($budget) use ($user, $now) {
                $spent = $user->transactions()->where('category_id', $budget->category_id)
                    ->where('type', 'expense')->whereMonth('date', $now->month)->whereYear('date', $now->year)->sum('amount');
                $budget->spent = $spent;
                $budget->progress = $budget->amount > 0 ? min(100, ($spent / $budget->amount) * 100) : 0;
                return $budget;
            });

        // Fetch goals
        $goals = $user->goals()->take(3)->get();

        // Fetch upcoming bills
        $upcomingBills = $user->transactions()
            ->where('is_recurring', true)
            ->whereBetween('next_due_date', [$now->copy()->startOfDay(), $now->copy()->addDays(7)->endOfDay()])
            ->with('category')
            ->orderBy('next_due_date', 'asc')
            ->get();

        // Get AI Advice (cached for 12 hours)
        $gemini = new GeminiService();
        $summary = [
            'balance' => $balance,
            'income' => $income,
            'incomeChange' => $incomeChange,
            'expense' => $expense,
            'expenseChange' => $expenseChange,
            'savingsRate' => $savingsRate,
            'categorySpending' => $categorySpending->take(5)->toArray(),
            'budgets' => $budgets->map(fn($b) => [
                'category' => $b->category->name,
                'amount' => $b->amount,
                'spent' => $b->spent,
                'progress' => $b->progress,
            ])->toArray()
        ];

        $aiAdvice = Cache::remember('ai_advice_' . $user->id, 43200, function () use ($gemini, $summary) {
            return $gemini->getFinancialAdvice($summary);
        });

        return view('dashboard', compact(
            'income', 'expense', 'balance', 'incomeChange', 'expenseChange',
            'savingsRate', 'categorySpending', 'monthlyTrends', 'recentTransactions', 'budgets',
            'goals', 'upcomingBills', 'aiAdvice'
        ));
    }
}
