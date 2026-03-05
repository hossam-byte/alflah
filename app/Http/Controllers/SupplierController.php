<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%');
        }
        $suppliers = $query->latest()->paginate(15)->withQueryString();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'phone', 'phone2', 'address', 'notes');
        $data['is_active'] = $request->has('is_active');
        Supplier::create($data);
        return redirect()->route('suppliers.index')->with('success', 'تم إضافة المورد بنجاح');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate(['name' => 'required|string|max:200']);
        $data = $request->only('name', 'phone', 'phone2', 'address', 'notes');
        $data['is_active'] = $request->has('is_active');
        $supplier->update($data);
        return redirect()->route('suppliers.index')->with('success', 'تم تعديل المورد بنجاح');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'تم حذف المورد');
    }

    public function show(Supplier $supplier)
    {
        $purchases = $supplier->purchases()->latest()->paginate(10);
        return view('suppliers.show', compact('supplier', 'purchases'));
    }
}
