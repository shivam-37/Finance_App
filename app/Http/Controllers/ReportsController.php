<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        // Monthly trends (last 6 months)
        $monthlyTrends = collect();
        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $inc = $user->transactions()->where('type', 'income')->whereMonth('date', $m->month)->whereYear('date', $m->year)->sum('amount');
            $exp = $user->transactions()->where('type', 'expense')->whereMonth('date', $m->month)->whereYear('date', $m->year)->sum('amount');
            $monthlyTrends->push(['label' => $m->format('M Y'), 'income' => $inc, 'expense' => $exp, 'savings' => $inc - $exp]);
        }

        // Current month spending by category
        $categorySpending = $user->transactions()->where('type', 'expense')
            ->whereMonth('date', $now->month)->whereYear('date', $now->year)
            ->with('category')->get()->groupBy('category_id')
            ->map(fn($txns) => [
                'name' => $txns->first()->category->name,
                'color' => $txns->first()->category->color,
                'total' => $txns->sum('amount'),
                'count' => $txns->count(),
            ])->sortByDesc('total')->values();

        // Income sources
        $incomeSources = $user->transactions()->where('type', 'income')
            ->whereMonth('date', $now->month)->whereYear('date', $now->year)
            ->with('category')->get()->groupBy('category_id')
            ->map(fn($txns) => [
                'name' => $txns->first()->category->name,
                'color' => $txns->first()->category->color,
                'total' => $txns->sum('amount'),
            ])->sortByDesc('total')->values();

        // Top 5 expenses this month
        $topExpenses = $user->transactions()->where('type', 'expense')
            ->whereMonth('date', $now->month)->whereYear('date', $now->year)
            ->with('category')->orderByDesc('amount')->take(5)->get();

        // Totals
        $totalIncome = $user->transactions()->where('type', 'income')
            ->whereMonth('date', $now->month)->whereYear('date', $now->year)->sum('amount');
        $totalExpense = $user->transactions()->where('type', 'expense')
            ->whereMonth('date', $now->month)->whereYear('date', $now->year)->sum('amount');
        $totalTransactions = $user->transactions()
            ->whereMonth('date', $now->month)->whereYear('date', $now->year)->count();

        return view('reports.index', compact(
            'monthlyTrends', 'categorySpending', 'incomeSources', 'topExpenses',
            'totalIncome', 'totalExpense', 'totalTransactions'
        ));
    }
}
