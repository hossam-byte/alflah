<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckShopStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // المطور دائماً يدخل
            if ($user->is_super_admin) {
                return $next($request);
            }

            // التأكد من حالة المحل والاشتراك
            if ($user->shop) {
                $shop = $user->shop;

                // 1. تحقق من انتهاء التاريخ
                if ($shop->subscription_end && now()->greaterThan($shop->subscription_end)) {
                    $shop->update(['is_active' => false]);
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'انتهت فترة اشتراكك أو الفترة التجريبية للمحل. يرجى التواصل مع الإدارة للتجديد.');
                }

                // 2. تحقق من التفعيل اليدوي
                if (!$shop->is_active) {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'حساب المحل الخاص بك غير مفعل حالياً. يرجى التواصل مع الإدارة.');
                }
            }
        }

        return $next($request);
    }
}
