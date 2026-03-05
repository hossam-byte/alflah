<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function profit(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $sales = Sale::where('sale_date', 'like', $month . '%')
            ->with(['customer', 'items.product'])
            ->latest()
            ->get();

        $totalSales = $sales->sum('total_amount');
        $totalProfit = $sales->sum('profit');
        $totalDiscount = $sales->sum('discount');

        $expenses = Expense::where('expense_date', 'like', $month . '%')->sum('amount');
        $netProfit = $totalProfit - $expenses;

        $purchases = Purchase::where('purchase_date', 'like', $month . '%')->sum('total_amount');

        return view('reports.profit', compact(
            'sales',
            'totalSales',
            'totalProfit',
            'totalDiscount',
            'expenses',
            'netProfit',
            'purchases',
            'month'
        ));
    }

    public function stock(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('stock', 'asc')->get();
        $lowStockCount = $products->filter(fn($p) => $p->stock <= $p->min_stock)->count();
        $totalStockValue = $products->sum(fn($p) => $p->stock * $p->purchase_price);

        $categories = \App\Models\Category::where('is_active', true)->get();

        return view('reports.stock', compact(
            'products',
            'lowStockCount',
            'totalStockValue',
            'categories'
        ));
    }
}
