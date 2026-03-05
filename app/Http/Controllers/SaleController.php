<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('customer');
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('month')) {
            $query->where('sale_date', 'like', $request->month . '%');
        }
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        $sales = $query->latest()->paginate(15)->withQueryString();
        $totalSales = Sale::sum('total_amount');
        $totalProfit = Sale::sum('profit');
        return view('sales.index', compact('sales', 'totalSales', 'totalProfit'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->with('category')->get();
        $invoiceNumber = Sale::generateInvoiceNumber();
        return view('sales.create', compact('customers', 'products', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'sale_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'items.required' => 'يجب إضافة منتج واحد على الأقل',
            'sale_date.required' => 'تاريخ البيع مطلوب',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = 0;
            $totalProfit = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $isSubUnit = isset($item['is_sub_unit']) && $item['is_sub_unit'] == 1;

                $lineTotal = $item['quantity'] * $item['unit_price'];

                // حساب تكلفة القطعة/الكيلو بناء على نوع البيع
                $effectivePurchasePrice = $isSubUnit
                    ? ($product->purchase_price / max($product->items_per_unit, 1))
                    : $product->purchase_price;

                $lineProfit = ($item['unit_price'] - $effectivePurchasePrice) * $item['quantity'];

                $totalAmount += $lineTotal;
                $totalProfit += $lineProfit;
            }

            $discount = $request->discount ?? 0;
            $netTotal = $totalAmount - $discount;
            $paidAmount = $request->paid_amount ?? $netTotal;
            $status = $paidAmount >= $netTotal ? 'paid' : ($paidAmount > 0 ? 'partial' : 'unpaid');

            $sale = Sale::create([
                'invoice_number' => $request->invoice_number ?? Sale::generateInvoiceNumber(),
                'customer_id' => $request->customer_id,
                'sale_date' => $request->sale_date,
                'total_amount' => $netTotal,
                'paid_amount' => $paidAmount,
                'discount' => $discount,
                'profit' => $totalProfit,
                'payment_status' => $status,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $isSubUnit = isset($item['is_sub_unit']) && $item['is_sub_unit'] == 1;

                $lineTotal = $item['quantity'] * $item['unit_price'];

                $effectivePurchasePrice = $isSubUnit
                    ? ($product->purchase_price / max($product->items_per_unit, 1))
                    : $product->purchase_price;

                $profit = ($item['unit_price'] - $effectivePurchasePrice) * $item['quantity'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'purchase_price' => $effectivePurchasePrice,
                    'total_price' => $lineTotal,
                    'profit' => $profit,
                    'unit_name' => $isSubUnit ? $product->sub_unit_name : $product->unit,
                    'is_sub_unit' => $isSubUnit,
                    'items_per_unit' => $product->items_per_unit,
                ]);

                // خصم من المخزون
                $stockToDecrement = $isSubUnit
                    ? ($item['quantity'] / max($product->items_per_unit, 1))
                    : $item['quantity'];

                $product->decrement('stock', $stockToDecrement);
            }
        });

        return redirect()->route('sales.index')->with('success', 'تم حفظ فاتورة البيع بنجاح');
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        return redirect()->route('sales.show', $sale);
    }
    public function update(Request $r, Sale $s)
    {
        return redirect()->route('sales.show', $s);
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            // إرجاع المخزون
            foreach ($sale->items as $item) {
                $amountToReturn = $item->is_sub_unit
                    ? ($item->quantity / max($item->items_per_unit, 1))
                    : $item->quantity;

                $item->product->increment('stock', $amountToReturn);
            }
            $sale->delete(); // الـ SaleItems هيتحذفوا تلقائياً لو فيه cascade delete في الـ migration
        });
        return redirect()->route('sales.index')->with('success', 'تم حذف الفاتورة بنجاح وإعادة الكميات للمخزن');
    }
}
