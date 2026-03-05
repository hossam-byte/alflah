<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToShop;

class Customer extends Model
{
    use BelongsToShop;

    protected $fillable = ['shop_id', 'name', 'phone', 'phone2', 'address', 'notes', 'balance', 'is_active'];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
