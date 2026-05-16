<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $currentMonth = Carbon::now()->format('Y-m');

        // Total income/expense for the current month
        $income = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');
            
        $expense = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');
            
        $balance = $income - $expense;

        // Recent transactions
        $recentTransactions = $user->transactions()
            ->with('category')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
            
        // Budgets progress
        $budgets = $user->budgets()
            ->with('category')
            ->where('month', $currentMonth)
            ->get()
            ->map(function($budget) use ($user) {
                $spent = $user->transactions()
                    ->where('category_id', $budget->category_id)
                    ->where('type', 'expense')
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year)
                    ->sum('amount');
                    
                $budget->spent = $spent;
                $budget->progress = $budget->amount > 0 ? min(100, ($spent / $budget->amount) * 100) : 0;
                return $budget;
            });

        return view('dashboard', compact('income', 'expense', 'balance', 'recentTransactions', 'budgets'));
    }
}
