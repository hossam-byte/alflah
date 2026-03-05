<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'owner_name',
        'phone',
        'address',
        'logo',
        'primary_color',
        'secondary_color',
        'accent_color',
        'is_active',
        'subscription_start',
        'subscription_end'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_start' => 'date',
        'subscription_end' => 'date',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
