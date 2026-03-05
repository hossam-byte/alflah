<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%');
        }
        $customers = $query->latest()->paginate(15)->withQueryString();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'phone', 'phone2', 'address', 'notes');
        $data['is_active'] = $request->has('is_active');
        Customer::create($data);
        return redirect()->route('customers.index')->with('success', 'تم إضافة العميل بنجاح');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate(['name' => 'required|string|max:200']);
        $data = $request->only('name', 'phone', 'phone2', 'address', 'notes');
        $data['is_active'] = $request->has('is_active');
        $customer->update($data);
        return redirect()->route('customers.index')->with('success', 'تم تعديل العميل بنجاح');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'تم حذف العميل');
    }

    public function show(Customer $customer)
    {
        $sales = $customer->sales()->latest()->paginate(10);
        return view('customers.show', compact('customer', 'sales'));
    }
}
