<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToShop;

class Supplier extends Model
{
    use BelongsToShop;

    protected $fillable = ['shop_id', 'name', 'phone', 'phone2', 'address', 'notes', 'balance', 'is_active'];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
