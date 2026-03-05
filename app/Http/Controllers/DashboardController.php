<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->is_super_admin) {
            return redirect()->route('super_admin.index');
        }

        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');

        // إحصائيات اليوم
        $todaySales = Sale::whereDate('sale_date', $today)->sum('total_amount');
        $todayProfit = Sale::whereDate('sale_date', $today)->sum('profit');
        $todayPurchases = Purchase::whereDate('purchase_date', $today)->sum('total_amount');

        // إحصائيات الشهر
        $monthSales = Sale::where('sale_date', 'like', $thisMonth . '%')->sum('total_amount');
        $monthProfit = Sale::where('sale_date', 'like', $thisMonth . '%')->sum('profit');
        $monthExpenses = Expense::where('expense_date', 'like', $thisMonth . '%')->sum('amount');

        // إحصائيات عامة
        $totalProducts = Product::where('is_active', true)->count();
        $totalCustomers = Customer::where('is_active', true)->count();
        $totalSuppliers = Supplier::where('is_active', true)->count();

        // المنتجات منخفضة المخزون
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->with('category')
            ->take(5)
            ->get();

        // آخر 5 مبيعات
        $recentSales = Sale::with('customer')
            ->latest()
            ->take(5)
            ->get();

        // آخر 5 مشتريات
        $recentPurchases = Purchase::with('supplier')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'sales_today' => $todaySales,
            'profit_today' => $todayProfit,
            'purchases_today' => $todayPurchases,
            'expenses_today' => Expense::whereDate('expense_date', $today)->sum('amount'),
            'sales_month' => $monthSales,
            'profit_month' => $monthProfit,
            'expenses_month' => $monthExpenses,
            'total_products' => $totalProducts,
            'total_customers' => $totalCustomers,
            'total_suppliers' => $totalSuppliers,
        ];

        return view('dashboard', compact(
            'stats',
            'lowStockProducts',
            'recentSales',
            'recentPurchases'
        ));
    }
}
