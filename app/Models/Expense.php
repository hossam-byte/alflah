<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToShop;

class Expense extends Model
{
    use BelongsToShop;

    protected $fillable = ['shop_id', 'title', 'category', 'amount', 'expense_date', 'notes'];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];
}
