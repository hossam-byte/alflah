@extends('layouts.app')
@section('title', 'تعديل عميل')
@section('page-title', 'تعديل بيانات العميل')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h6><i class="fas fa-user-edit me-2" style="color:var(--green-main)"></i> تعديل: {{ $customer->name }}
                    </h6>
                    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-secondary rounded-pill px-3"><i
                            class="fas fa-arrow-right me-1"></i> رجوع</a>
                </div>
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}"
                                required>
                        </div>
                        <div class="col-6"><label class="form-label">الهاتف</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $customer->phone) }}">
                        </div>
                        <div class="col-6"><label class="form-label">هاتف آخر</label>
                            <input type="text" name="phone2" class="form-control"
                                value="{{ old('phone2', $customer->phone2) }}">
                        </div>
                        <div class="col-12"><label class="form-label">العنوان</label>
                            <input type="text" name="address" class="form-control"
                                value="{{ old('address', $customer->address) }}">
                        </div>
                        <div class="col-12"><label class="form-label">ملاحظات</label>
                            <textarea name="notes" class="form-control"
                                rows="2">{{ old('notes', $customer->notes) }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ $customer->is_active ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">عميل فعّال</label>
                            </div>
                        </div>
                        <div class="col-12"><button type="submit" class="btn btn-green w-100"><i
                                    class="fas fa-save me-2"></i> حفظ التعديلات</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection