<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Auth::user()->goals()->orderBy('deadline', 'asc')->get();
        return view('goals.index', compact('goals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0.01',
            'current_amount' => 'nullable|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'color' => 'required|string',
        ]);

        $validated['current_amount'] = $validated['current_amount'] ?? 0;

        Auth::user()->goals()->create($validated);

        return redirect()->route('goals.index')->with('success', 'Savings goal created successfully.');
    }

    public function update(Request $request, Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0.01',
            'deadline' => 'required|date|after:today',
            'color' => 'required|string',
        ]);

        $goal->update($validated);

        return redirect()->route('goals.index')->with('success', 'Savings goal updated successfully.');
    }

    public function adjust(Request $request, Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'type' => 'required|in:deposit,withdraw',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = (float) $validated['amount'];

        if ($validated['type'] === 'deposit') {
            $goal->current_amount += $amount;
            $msg = 'Deposited $' . number_format($amount, 2) . ' to your goal.';
        } else {
            if ($goal->current_amount < $amount) {
                return back()->with('error', 'Insufficient funds in savings goal to withdraw.');
            }
            $goal->current_amount -= $amount;
            $msg = 'Withdrew $' . number_format($amount, 2) . ' from your goal.';
        }

        $goal->save();

        return redirect()->route('goals.index')->with('success', $msg);
    }

    public function destroy(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) abort(403);
        $goal->delete();
        return redirect()->route('goals.index')->with('success', 'Savings goal deleted successfully.');
    }
}
