<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\BelongsToShop;

class Purchase extends Model
{
    use BelongsToShop;

    protected $fillable = [
        'shop_id',
        'invoice_number',
        'supplier_id',
        'purchase_date',
        'total_amount',
        'paid_amount',
        'discount',
        'payment_status',
        'notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // المبلغ المتبقي
    public function getRemainingAmountAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    // توليد رقم فاتورة تلقائي
    public static function generateInvoiceNumber(): string
    {
        $last = self::latest()->first();
        $nextId = $last ? $last->id + 1 : 1;
        return 'PUR-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}
