<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء المحل الرئيسي (الشركة المالكة للنظام)
        $shop = \App\Models\Shop::updateOrCreate(
            ['slug' => 'foursw'],
            [
                'name' => 'شركة FourSW للحلول',
                'slug' => 'foursw',
                'owner_name' => 'محمد حسن',
                'is_active' => true, // مفعل افتراضياً
                'primary_color' => '#2d7a18',
                'accent_color' => '#c8a000',
                'subscription_start' => now(),
                'subscription_end' => now()->addYear(),
            ]
        );

        // 2. إنشاء مدير النظام (المطور) وربطه بالمحل وتفعيله كـ Super Admin
        User::updateOrCreate(
            ['phone' => '01000000000'],
            [
                'name' => 'محمد حسن',
                'phone' => '01000000000',
                'email' => 'admin@alflah.com',
                'password' => Hash::make('admin123'),
                'shop_id' => $shop->id,
                'is_super_admin' => true, // لوحة تحكم المطور
            ]
        );
    }
}
