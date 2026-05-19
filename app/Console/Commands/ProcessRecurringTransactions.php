<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;

class ProcessRecurringTransactions extends Command
{
    protected $signature = 'finance:process-recurring';
    protected $description = 'Process all active recurring transactions whose next due date is today or in the past';

    public function handle()
    {
        $today = Carbon::today();
        
        $recurringTransactions = Transaction::where('is_recurring', true)
            ->where('next_due_date', '<=', $today)
            ->get();

        if ($recurringTransactions->isEmpty()) {
            $this->info('No recurring transactions to process.');
            return 0;
        }

        $count = 0;
        foreach ($recurringTransactions as $txn) {
            $dueDate = Carbon::parse($txn->next_due_date);
            
            // Create a historical copy of the transaction
            Transaction::create([
                'user_id' => $txn->user_id,
                'category_id' => $txn->category_id,
                'amount' => $txn->amount,
                'description' => $txn->description . ' (Recurring)',
                'date' => $dueDate,
                'type' => $txn->type,
                'is_recurring' => false,
            ]);

            // Calculate next due date
            switch ($txn->frequency) {
                case 'daily':
                    $nextDue = $dueDate->copy()->addDay();
                    break;
                case 'weekly':
                    $nextDue = $dueDate->copy()->addWeek();
                    break;
                case 'monthly':
                    $nextDue = $dueDate->copy()->addMonth();
                    break;
                case 'yearly':
                    $nextDue = $dueDate->copy()->addYear();
                    break;
                default:
                    $nextDue = null;
            }

            if ($nextDue) {
                $txn->next_due_date = $nextDue;
                $txn->save();
                $count++;
            } else {
                // If frequency invalid, disable recurring
                $txn->is_recurring = false;
                $txn->save();
            }
        }

        $this->info("Processed {$count} recurring transactions successfully.");
        return 0;
    }
}
