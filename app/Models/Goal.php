<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Goal extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'user_id',
        'name',
        'target_amount',
        'current_amount',
        'deadline',
        'color',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'float',
            'current_amount' => 'float',
            'deadline' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
