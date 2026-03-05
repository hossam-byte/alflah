@extends('layouts.app')
@section('title', 'إضافة منتج')
@section('page-title', 'إضافة منتج جديد')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h6><i class="fas fa-plus-circle me-2" style="color:var(--green-main)"></i> بيانات المنتج</h6>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary rounded-pill px-3">
                        <i class="fas fa-arrow-right me-1"></i> رجوع
                    </a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                                placeholder="اسم المنتج">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">التصنيف <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- اختر --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">وحدة القياس <span class="text-danger">*</span></label>
                            <select name="unit" class="form-select" required id="product-unit">
                                @foreach(['شكارة', 'كيلو', 'جرام', 'لتر', 'مل', 'عبوة', 'كيس', 'صندوق', 'دزينة', 'قطعة', 'طن', 'جركن', 'علبة'] as $u)
                                    <option value="{{ $u }}" {{ old('unit') == $u ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">الباركود</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}"
                                placeholder="اختياري">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">الكمية الابتدائية <span class="text-danger">*</span></label>
                            <input type="number" step="0.001" name="stock" class="form-control"
                                value="{{ old('stock', 0) }}" required min="0">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">الحد الأدنى للتنبيه</label>
                            <input type="number" step="0.001" name="min_stock" class="form-control"
                                value="{{ old('min_stock', 5) }}" min="0">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">سعر الشراء (ج.م) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="purchase_price" class="form-control"
                                value="{{ old('purchase_price', 0) }}" required min="0">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">سعر البيع (ج.م) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="sale_price" class="form-control"
                                value="{{ old('sale_price', 0) }}" required min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="description" class="form-control" rows="2"
                                placeholder="ملاحظات اختيارية">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="card bg-light border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" name="has_sub_units" id="has_sub_units"
                                            class="form-check-input" {{ old('has_sub_units') ? 'checked' : '' }}>
                                        <label for="has_sub_units" class="form-check-label fw-bold text-dark">تفعيل بيع
                                            التجزئة (مثلاً بيع بالكيلو من الشكارة)</label>
                                    </div>
                                    <div id="sub-unit-fields" class="{{ old('has_sub_units') ? '' : 'd-none' }}">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="form-label small">اسم وحدة التجزئة (مثلاً: كيلو)</label>
                                                <input type="text" name="sub_unit_name" class="form-control form-control-sm"
                                                    value="{{ old('sub_unit_name', 'كيلو') }}" placeholder="كيلو / قطعة">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">الكمية داخل الوحدة الكبيرة</label>
                                                <input type="number" step="0.001" name="items_per_unit"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('items_per_unit', 1) }}" placeholder="مثلاً: 50">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">سعر بيع التجزئة (ج.م)</label>
                                                <input type="number" step="0.01" name="sub_unit_sale_price"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('sub_unit_sale_price', 0) }}"
                                                    placeholder="سعر القطعة/الكيلو">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" checked>
                                <label for="is_active" class="form-check-label">منتج فعّال</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-green w-100">
                                <i class="fas fa-save me-2"></i> حفظ المنتج
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('has_sub_units').addEventListener('change', function () {
                const fields = document.getElementById('sub-unit-fields');
                if (this.checked) {
                    fields.classList.remove('d-none');
                } else {
                    fields.classList.add('d-none');
                }
            });
        </script>
    @endpush
@endsection