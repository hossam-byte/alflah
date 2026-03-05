@extends('layouts.app')
@section('title', 'إضافة مصروف')
@section('page-title', 'إضافة مصروف جديد')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="content-card">
                <div class="content-card-header">
                    <h6><i class="fas fa-plus-circle me-2" style="color:var(--green-main)"></i> بيانات المصروف</h6>
                    <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-secondary rounded-pill px-3"><i
                            class="fas fa-arrow-right me-1"></i> رجوع</a>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">عنوان المصروف <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required
                                placeholder="مثال: فاتورة كهرباء يناير">
                        </div>
                        <div class="col-12"><label class="form-label">الفئة</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}"
                                placeholder="إيجار، مرتبات، خدمات، إلخ" list="expense-cats">
                            <datalist id="expense-cats">
                                <option value="إيجار">
                                <option value="مرتبات">
                                <option value="كهرباء">
                                <option value="مياه">
                                <option value="صيانة">
                            </datalist>
                        </div>
                        <div class="col-6"><label class="form-label">المبلغ (ج.م) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}"
                                required min="0.01">
                        </div>
                        <div class="col-6"><label class="form-label">التاريخ <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control"
                                value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-12"><label class="form-label">ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                        <div class="col-12"><button type="submit" class="btn btn-green w-100"><i
                                    class="fas fa-save me-2"></i> حفظ المصروف</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection