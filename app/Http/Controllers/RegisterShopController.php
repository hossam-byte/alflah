<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterShopController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register_shop');
    }

    public function register(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'email' => 'nullable|string|email|max:255',
            'password' => 'required|string|min:4|confirmed',
        ], [
            'phone.required' => 'رقم الهاتف مطلوب لاستخدامه في تسجيل الدخول',
            'phone.unique' => 'رقم الهاتف مسجل مسبقاً',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
        ]);

        // 1. إنشاء المحل
        $shop = Shop::create([
            'name' => $request->shop_name,
            'slug' => Str::slug($request->shop_name) . '-' . rand(100, 999),
            'owner_name' => $request->owner_name,
            'phone' => $request->phone,
            'is_active' => false, // يحتاج تفعيل من المطور
        ]);

        // 2. إنشاء المستخدم المدير للمحل
        User::create([
            'name' => $request->owner_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'shop_id' => $shop->id,
            'is_super_admin' => false,
        ]);

        return redirect()->route('login')->with('success', 'تم تسجيل المحل بنجاح! يرجى التواصل مع الإدارة لتفعيل الحساب بعد الدفع.');
    }
}
