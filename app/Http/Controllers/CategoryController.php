<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ], ['name.required' => 'اسم التصنيف مطلوب', 'name.unique' => 'هذا التصنيف موجود بالفعل']);

        $data = $request->only('name', 'icon', 'description');
        $data['is_active'] = $request->has('is_active');
        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'تم إضافة التصنيف بنجاح');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ], ['name.required' => 'اسم التصنيف مطلوب', 'name.unique' => 'هذا التصنيف موجود بالفعل']);

        $data = $request->only('name', 'icon', 'description');
        $data['is_active'] = $request->has('is_active');
        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'تم تعديل التصنيف بنجاح');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف التصنيف لوجود منتجات مرتبطة به');
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'تم حذف التصنيف');
    }

    public function show(Category $category)
    {
        return redirect()->route('categories.index');
    }
}
