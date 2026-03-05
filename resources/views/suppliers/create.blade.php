@extends('layouts.app')
@section('title', 'إضافة مورد')
@section('page-title', 'إضافة مورد جديد')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="content-card">
                <div class="content-card-header">
                    <h6><i class="fas fa-plus-circle me-2" style="color:var(--green-main)"></i> بيانات المورد</h6>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-secondary rounded-pill px-3"><i
                            class="fas fa-arrow-right me-1"></i> رجوع</a>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">اسم المورد / الشركة <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                                placeholder="اسم المورد">
                        </div>
                        <div class="col-6"><label class="form-label">رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                placeholder="01xxxxxxxxx">
                        </div>
                        <div class="col-6"><label class="form-label">رقم هاتف آخر</label>
                            <input type="text" name="phone2" class="form-control" value="{{ old('phone2') }}">
                        </div>
                        <div class="col-12"><label class="form-label">العنوان</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}"
                                placeholder="العنوان">
                        </div>
                        <div class="col-12"><label class="form-label">ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" checked>
                                <label for="is_active" class="form-check-label">مورد فعّال</label>
                            </div>
                        </div>
                        <div class="col-12"><button type="submit" class="btn btn-green w-100"><i
                                    class="fas fa-save me-2"></i> حفظ</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection