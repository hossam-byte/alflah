<?php

namespace App\Traits;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToShop
{
    protected static function bootBelongsToShop()
    {
        // إضافة الـ shop_id تلقائياً عند الحفظ
        static::creating(function ($model) {
            if (Auth::check() && !isset($model->shop_id)) {
                $model->shop_id = Auth::user()->shop_id;
            }
        });

        // عزل البيانات: كل محل يشوف بياناته فقط
        // إذا كان المستخدم Super Admin ممكن نشيل الـ scope لو عاوز يشوف الكل، 
        // بس الأفضل في الـ SaaS إن كل محل يكون معزول حتى للمطور إلا في لوحة التحكم الخاصة.
        static::addGlobalScope('shop', function (Builder $builder) {
            if (Auth::check()) {
                // المطور يشوف كل الداتا في لوحة تحكم المطور، 
                // لكن هنا بنفرد الـ Scope عشان نضمن إن أي عملية بتتم بتتم على محل المستخدم الحالي
                $builder->where('shop_id', Auth::user()->shop_id);
            }
        });
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
