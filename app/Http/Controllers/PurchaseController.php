<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('supplier');
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('month')) {
            $query->where('purchase_date', 'like', $request->month . '%');
        }
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        $purchases = $query->latest()->paginate(15)->withQueryString();
        $totalAmount = Purchase::sum('total_amount');
        return view('purchases.index', compact('purchases', 'totalAmount'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $products = Product::where('is_active', true)->with('category')->get();
        $invoiceNumber = Purchase::generateInvoiceNumber();
        return view('purchases.create', compact('suppliers', 'products', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_date' => 'required|date',
            'invoice_number' => 'nullable|unique:purchases,invoice_number',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'items.required' => 'يجب إضافة منتج واحد على الأقل',
            'purchase_date.required' => 'تاريخ الشراء مطلوب',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = 0;

            // حساب الإجمالي
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            $discount = $request->discount ?? 0;
            $netTotal = $totalAmount - $discount;
            $paidAmount = $request->paid_amount ?? $netTotal;
            $status = $paidAmount >= $netTotal ? 'paid' : ($paidAmount > 0 ? 'partial' : 'unpaid');

            $purchase = Purchase::create([
                'invoice_number' => $request->invoice_number ?? Purchase::generateInvoiceNumber(),
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $netTotal,
                'paid_amount' => $paidAmount,
                'discount' => $discount,
                'payment_status' => $status,
                'notes' => $request->notes,
            ]);

            // حفظ البنود وتحديث المخزون
            foreach ($request->items as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $lineTotal,
                ]);

                // تحديث سعر الشراء والمخزون
                $product = Product::find($item['product_id']);
                $product->increment('stock', $item['quantity']);
                $product->update(['purchase_price' => $item['unit_price']]);
            }
        });

        return redirect()->route('purchases.index')->with('success', 'تم حفظ فاتورة الشراء بنجاح');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        return redirect()->route('purchases.show', $purchase);
    }
    public function update(Request $r, Purchase $p)
    {
        return redirect()->route('purchases.show', $p);
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            // إرجاع المخزون
            foreach ($purchase->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }
            $purchase->items()->delete();
            $purchase->delete();
        });
        return redirect()->route('purchases.index')->with('success', 'تم حذف فاتورة الشراء');
    }
}
