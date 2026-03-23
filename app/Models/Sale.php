<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToShop;

class Sale extends Model
{
    use BelongsToShop;

    protected $fillable = [
        'shop_id',
        'invoice_number',
        'customer_id',
        'sale_date',
        'total_amount',
        'paid_amount',
        'discount',
        'profit',
        'payment_status',
        'notes'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // المبلغ المتبقي
    public function getRemainingAmountAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    // توليد رقم فاتورة تلقائي
    public static function generateInvoiceNumber(): string
    {
        $last = self::withoutGlobalScopes()->latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        return 'SAL-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}
