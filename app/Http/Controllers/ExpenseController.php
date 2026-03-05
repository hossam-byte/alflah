<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query();
        if ($request->filled('month')) {
            $query->where('expense_date', 'like', $request->month . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $expenses = $query->latest('expense_date')->paginate(15)->withQueryString();
        $totalAmount = $query->sum('amount');
        $expenseCategories = Expense::select('category')->distinct()->pluck('category');
        return view('expenses.index', compact('expenses', 'totalAmount', 'expenseCategories'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
        ], [
            'title.required' => 'عنوان المصروف مطلوب',
            'amount.required' => 'المبلغ مطلوب',
            'expense_date.required' => 'التاريخ مطلوب',
        ]);

        Expense::create($request->only('title', 'category', 'amount', 'expense_date', 'notes'));
        return redirect()->route('expenses.index')->with('success', 'تم إضافة المصروف بنجاح');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
        ]);
        $expense->update($request->only('title', 'category', 'amount', 'expense_date', 'notes'));
        return redirect()->route('expenses.index')->with('success', 'تم تعديل المصروف بنجاح');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'تم حذف المصروف');
    }

    public function show(Expense $expense)
    {
        return redirect()->route('expenses.index');
    }
}
