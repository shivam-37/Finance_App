<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Auth::user()->budgets()->with('category')->orderBy('month', 'desc')->get();
        
        // Calculate progress for each budget
        $budgets = $budgets->map(function($budget) {
            // Convert 'YYYY-MM' to a Carbon date for month checking
            $budgetDate = Carbon::createFromFormat('Y-m', $budget->month);
            
            $spent = Auth::user()->transactions()
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('date', $budgetDate->month)
                ->whereYear('date', $budgetDate->year)
                ->sum('amount');
                
            $budget->spent = $spent;
            $budget->progress = $budget->amount > 0 ? min(100, ($spent / $budget->amount) * 100) : 0;
            return $budget;
        });

        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->where('type', 'expense')->orderBy('name')->get();
        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,_id',
            'amount' => 'required|numeric|min:0.01',
            'month' => 'required|date_format:Y-m',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        if ($category->user_id !== Auth::id()) abort(403);

        // Check if budget already exists for this category and month
        $existing = Auth::user()->budgets()
            ->where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->first();

        if ($existing) {
            return back()->withErrors(['month' => 'A budget for this category and month already exists.']);
        }

        Auth::user()->budgets()->create($validated);

        return redirect()->route('budgets.index')->with('success', 'Budget created successfully.');
    }

    public function edit(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) abort(403);
        $categories = Auth::user()->categories()->where('type', 'expense')->orderBy('name')->get();
        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,_id',
            'amount' => 'required|numeric|min:0.01',
            'month' => 'required|date_format:Y-m',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        if ($category->user_id !== Auth::id()) abort(403);

        // Check if updating to a budget that already exists
        $existing = Auth::user()->budgets()
            ->where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->where('id', '!=', $budget->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['month' => 'A budget for this category and month already exists.']);
        }

        $budget->update($validated);

        return redirect()->route('budgets.index')->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) abort(403);
        $budget->delete();
        return redirect()->route('budgets.index')->with('success', 'Budget deleted successfully.');
    }
}
