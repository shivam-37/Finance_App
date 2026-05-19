<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'description',
        'date',
        'type',
        'is_recurring',
        'frequency',
        'next_due_date',
        'currency',
        'original_amount',
        'receipt_path'
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_recurring' => 'boolean',
            'next_due_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
