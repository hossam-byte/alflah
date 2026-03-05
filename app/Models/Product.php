<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToShop;

class Product extends Model
{
    use BelongsToShop;

    protected $fillable = [
        'shop_id',
        'category_id',
        'name',
        'barcode',
        'unit',
        'purchase_price',
        'sale_price',
        'stock',
        'min_stock',
        'description',
        'image',
        'has_sub_units',
        'sub_unit_name',
        'items_per_unit',
        'sub_unit_sale_price',
        'is_active'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'decimal:3',
        'min_stock' => 'decimal:3',
        'has_sub_units' => 'boolean',
        'items_per_unit' => 'decimal:3',
        'sub_unit_sale_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // هل الكمية أقل من الحد الأدنى؟
    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    // حساب هامش الربح
    public function getProfitMarginAttribute(): float
    {
        if ($this->purchase_price == 0)
            return 0;
        return round((($this->sale_price - $this->purchase_price) / $this->purchase_price) * 100, 2);
    }
}
