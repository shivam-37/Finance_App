<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Budget extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = ['user_id', 'category_id', 'amount', 'month'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
