<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Helpers\CurrencyHelper;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->transactions()->with('category');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('from')) {
            $query->where('date', '>=', \Carbon\Carbon::parse($request->from)->startOfDay());
        }
        if ($request->filled('to')) {
            $query->where('date', '<=', \Carbon\Carbon::parse($request->to)->endOfDay());
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();
        $categories = Auth::user()->categories()->orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->orderBy('name')->get();
        $currencies = CurrencyHelper::getSymbols();
        return view('transactions.create', compact('categories', 'currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,_id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'is_recurring' => 'nullable|boolean',
            'frequency' => 'required_if:is_recurring,1,true|nullable|in:daily,weekly,monthly,yearly',
            'currency' => 'required|string|in:' . implode(',', array_keys(CurrencyHelper::getSymbols())),
            'receipt' => 'nullable|file|image|max:5120',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        if ($category->user_id !== Auth::id()) abort(403);

        $userCurrency = Auth::user()->currency ?? 'INR';
        $inputAmount = (float) $validated['amount'];
        $txnCurrency = $validated['currency'];

        // Convert transaction currency amount to user's preferred currency amount
        $convertedAmount = CurrencyHelper::convert($inputAmount, $txnCurrency, $userCurrency);

        $transactionData = [
            'category_id' => $validated['category_id'],
            'amount' => $convertedAmount,
            'original_amount' => $inputAmount,
            'currency' => $txnCurrency,
            'description' => $validated['description'],
            'date' => Carbon::parse($validated['date']),
            'type' => $validated['type'],
        ];

        // Process recurring fields
        if ($request->boolean('is_recurring')) {
            $transactionData['is_recurring'] = true;
            $transactionData['frequency'] = $request->input('frequency');
            
            $startDate = Carbon::parse($validated['date']);
            switch ($transactionData['frequency']) {
                case 'daily':
                    $transactionData['next_due_date'] = $startDate->copy()->addDay();
                    break;
                case 'weekly':
                    $transactionData['next_due_date'] = $startDate->copy()->addWeek();
                    break;
                case 'monthly':
                    $transactionData['next_due_date'] = $startDate->copy()->addMonth();
                    break;
                case 'yearly':
                    $transactionData['next_due_date'] = $startDate->copy()->addYear();
                    break;
            }
        }

        // Receipt Upload
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $transactionData['receipt_path'] = $path;
        }

        Auth::user()->transactions()->create($transactionData);

        // Auto-create budget if it doesn't exist for this expense's category in its month
        if ($transactionData['type'] === 'expense') {
            $monthStr = $transactionData['date']->format('Y-m');
            $existingBudget = Auth::user()->budgets()
                ->where('category_id', $validated['category_id'])
                ->where('month', $monthStr)
                ->first();
            
            if (!$existingBudget) {
                Auth::user()->budgets()->create([
                    'category_id' => $validated['category_id'],
                    'month' => $monthStr,
                    'amount' => 10000 // default budget amount
                ]);
            }
        }

        // Clear cached AI advice
        Cache::forget('ai_advice_' . Auth::id());

        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully.');
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) abort(403);
        $categories = Auth::user()->categories()->orderBy('name')->get();
        $currencies = CurrencyHelper::getSymbols();
        return view('transactions.edit', compact('transaction', 'categories', 'currencies'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,_id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'is_recurring' => 'nullable|boolean',
            'frequency' => 'required_if:is_recurring,1,true|nullable|in:daily,weekly,monthly,yearly',
            'currency' => 'required|string|in:' . implode(',', array_keys(CurrencyHelper::getSymbols())),
            'receipt' => 'nullable|file|image|max:5120',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        if ($category->user_id !== Auth::id()) abort(403);

        $userCurrency = Auth::user()->currency ?? 'INR';
        $inputAmount = (float) $validated['amount'];
        $txnCurrency = $validated['currency'];

        // Convert transaction currency amount to user's preferred currency amount
        $convertedAmount = CurrencyHelper::convert($inputAmount, $txnCurrency, $userCurrency);

        $transactionData = [
            'category_id' => $validated['category_id'],
            'amount' => $convertedAmount,
            'original_amount' => $inputAmount,
            'currency' => $txnCurrency,
            'description' => $validated['description'],
            'date' => Carbon::parse($validated['date']),
            'type' => $validated['type'],
        ];

        // Process recurring fields
        if ($request->boolean('is_recurring')) {
            $transactionData['is_recurring'] = true;
            $transactionData['frequency'] = $request->input('frequency');
            
            $startDate = Carbon::parse($validated['date']);
            switch ($transactionData['frequency']) {
                case 'daily':
                    $transactionData['next_due_date'] = $startDate->copy()->addDay();
                    break;
                case 'weekly':
                    $transactionData['next_due_date'] = $startDate->copy()->addWeek();
                    break;
                case 'monthly':
                    $transactionData['next_due_date'] = $startDate->copy()->addMonth();
                    break;
                case 'yearly':
                    $transactionData['next_due_date'] = $startDate->copy()->addYear();
                    break;
            }
        } else {
            $transactionData['is_recurring'] = false;
            $transactionData['frequency'] = null;
            $transactionData['next_due_date'] = null;
        }

        // Receipt Upload
        if ($request->hasFile('receipt')) {
            // Delete old receipt if it exists
            if ($transaction->receipt_path) {
                Storage::disk('public')->delete($transaction->receipt_path);
            }
            $path = $request->file('receipt')->store('receipts', 'public');
            $transactionData['receipt_path'] = $path;
        }

        $transaction->update($transactionData);

        // Auto-create budget if it doesn't exist for this expense's category in its month
        if ($transactionData['type'] === 'expense') {
            $monthStr = $transactionData['date']->format('Y-m');
            $existingBudget = Auth::user()->budgets()
                ->where('category_id', $validated['category_id'])
                ->where('month', $monthStr)
                ->first();
            
            if (!$existingBudget) {
                Auth::user()->budgets()->create([
                    'category_id' => $validated['category_id'],
                    'month' => $monthStr,
                    'amount' => 10000 // default budget amount
                ]);
            }
        }

        // Clear cached AI advice
        Cache::forget('ai_advice_' . Auth::id());

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) abort(403);
        
        // Delete receipt file
        if ($transaction->receipt_path) {
            Storage::disk('public')->delete($transaction->receipt_path);
        }

        $transaction->delete();

        // Clear cached AI advice
        Cache::forget('ai_advice_' . Auth::id());

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'receipt' => 'required|file|image|max:5120',
        ]);

        $gemini = new GeminiService();
        $path = $request->file('receipt')->store('temp', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $parsedData = $gemini->parseReceipt($fullPath);

        // Cleanup temp file
        Storage::disk('public')->delete($path);

        if ($parsedData) {
            // Auto map or create category if possible
            if (isset($parsedData['category'])) {
                $categoryName = trim($parsedData['category']);
                // Try to find matching category by name
                $category = Auth::user()->categories()
                    ->where('name', 'like', '%' . $categoryName . '%')
                    ->first();
                
                if (!$category) {
                    // Generate a random pleasant color
                    $colors = ['#10b981', '#f43f5e', '#8b5cf6', '#f59e0b', '#3b82f6', '#ec4899', '#14b8a6', '#f97316'];
                    $randomColor = $colors[array_rand($colors)];
                    
                    $category = Auth::user()->categories()->create([
                        'name' => ucfirst($categoryName),
                        'color' => $randomColor,
                        'type' => $parsedData['type'] ?? 'expense'
                    ]);
                }
                
                $parsedData['category_id'] = $category->id;
                // Also pass the name and color to frontend to dynamically update the select box
                $parsedData['category_details'] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'color' => $category->color
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $parsedData
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Could not scan receipt. Please add details manually or ensure GEMINI_API_KEY is configured.'
        ], 422);
    }

    public function export(Request $request)
    {
        $query = Auth::user()->transactions()->with('category')->orderBy('date', 'desc');

        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('from')) $query->where('date', '>=', \Carbon\Carbon::parse($request->from)->startOfDay());
        if ($request->filled('to')) $query->where('date', '<=', \Carbon\Carbon::parse($request->to)->endOfDay());

        $transactions = $query->get();
        $userCurrency = Auth::user()->currency ?? 'INR';

        $csv = "Date,Type,Category,Original Amount,Currency,Converted Amount ({$userCurrency}),Description\n";
        foreach ($transactions as $t) {
            $csv .= sprintf("%s,%s,%s,%.2f,%s,%.2f,\"%s\"\n",
                $t->date->format('Y-m-d'), 
                $t->type, 
                $t->category->name, 
                $t->original_amount ?? $t->amount, 
                $t->currency ?? $userCurrency,
                $t->amount,
                str_replace('"', '""', $t->description ?? '')
            );
        }

        return response($csv)->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="transactions_' . date('Y-m-d') . '.csv"');
    }
}
