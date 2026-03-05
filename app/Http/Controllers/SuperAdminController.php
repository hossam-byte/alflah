<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        // عرض المحلات ما عدا شركة المطور نفسها
        $shops = Shop::where('slug', '!=', 'foursw')->withCount('users')->latest()->get();

        $stats = [
            'total' => Shop::where('slug', '!=', 'foursw')->count(),
            'active' => Shop::where('slug', '!=', 'foursw')->where('is_active', true)->count(),
            'inactive' => Shop::where('slug', '!=', 'foursw')->where('is_active', false)->count(),
            'expiring_soon' => Shop::where('slug', '!=', 'foursw')
                ->whereNotNull('subscription_end')
                ->where('subscription_end', '>=', now()->startOfDay())
                ->where('subscription_end', '<=', now()->addDays(7)->endOfDay())
                ->count(),
        ];
        return view('super_admin.index', compact('shops', 'stats'));
    }

    public function toggleStatus(Shop $shop)
    {
        $shop->update(['is_active' => !$shop->is_active]);
        return back()->with('success', 'تم تعديل حالة المحل بنجاح');
    }

    public function editShop(Shop $shop)
    {
        return view('super_admin.edit_shop', compact('shop'));
    }

    public function updateShop(Request $request, Shop $shop)
    {
        $data = $request->validate([
            'name' => 'required',
            'owner_name' => 'required',
            'primary_color' => 'required',
            'accent_color' => 'required',
            'subscription_start' => 'nullable|date',
            'subscription_end' => 'nullable|date',
        ]);

        $shop->update($data);
        return redirect()->route('super_admin.index')->with('success', 'تم تحديث بيانات المحل والاشتراك');
    }

    // إعدادات المطور نفسه (FourSW)
    public function developerSettings()
    {
        $shop = Shop::where('slug', 'foursw')->firstOrFail();
        return view('super_admin.edit_shop', compact('shop'));
    }
}
