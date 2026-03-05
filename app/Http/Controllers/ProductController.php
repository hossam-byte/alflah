<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('barcode', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('low_stock')) {
            $query->whereColumn('stock', '<=', 'min_stock');
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:30',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'has_sub_units' => 'nullable',
            'sub_unit_name' => 'required_if:has_sub_units,on|nullable|string|max:30',
            'items_per_unit' => 'required_if:has_sub_units,on|nullable|numeric|min:0.001',
            'sub_unit_sale_price' => 'required_if:has_sub_units,on|nullable|numeric|min:0',
        ], [
            'sub_unit_name.required_if' => 'يجب إدخال اسم وحدة التجزئة (مثلاً: كيلو) عند تفعيل بيع التجزئة',
            'items_per_unit.required_if' => 'يجب إدخال الكمية داخل الوحدة الكبيرة عند تفعيل بيع التجزئة',
            'sub_unit_sale_price.required_if' => 'يجب إدخال سعر بيع التجزئة عند تفعيل بيع التجزئة',
            'name.required' => 'اسم المنتج مطلوب',
            'category_id.required' => 'التصنيف مطلوب',
            'unit.required' => 'وحدة القياس مطلوبة',
            'purchase_price.required' => 'سعر الشراء مطلوب',
            'sale_price.required' => 'سعر البيع مطلوب',
            'stock.required' => 'الكمية الابتدائية مطلوبة',
        ]);

        $data = $request->except(['_token', 'is_active', 'has_sub_units']);
        $data['is_active'] = $request->has('is_active');
        $data['has_sub_units'] = $request->has('has_sub_units');
        Product::create($data);

        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:30',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'has_sub_units' => 'nullable',
            'sub_unit_name' => 'required_if:has_sub_units,on|nullable|string|max:30',
            'items_per_unit' => 'required_if:has_sub_units,on|nullable|numeric|min:0.001',
            'sub_unit_sale_price' => 'required_if:has_sub_units,on|nullable|numeric|min:0',
        ]);

        $data = $request->except(['_token', '_method', 'stock', 'is_active', 'has_sub_units']);
        $data['is_active'] = $request->has('is_active');
        $data['has_sub_units'] = $request->has('has_sub_units');
        $product->update($data);

        return redirect()->route('products.index')->with('success', 'تم تعديل المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'تم حذف المنتج');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // API: بيانات المنتج للـ Ajax في فاتورة البيع/الشراء
    public function getProductData(int $id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'unit' => $product->unit,
            'purchase_price' => $product->purchase_price,
            'sale_price' => $product->sale_price,
            'stock' => $product->stock,
            'has_sub_units' => $product->has_sub_units,
            'sub_unit_name' => $product->sub_unit_name,
            'items_per_unit' => $product->items_per_unit,
            'sub_unit_sale_price' => $product->sub_unit_sale_price,
        ]);
    }
}
