@extends('layouts.app')
@section('title', 'تعديل تصنيف')
@section('page-title', 'تعديل التصنيف')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="content-card">
                <div class="content-card-header">
                    <h6><i class="fas fa-edit me-2" style="color:var(--green-main)"></i> تعديل: {{ $category->name }}</h6>
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary rounded-pill px-3">
                        <i class="fas fa-arrow-right me-1"></i> رجوع
                    </a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">
                            <li>{{ $errors->first() }}</li>
                        </ul>
                    </div>
                @endif

                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">اسم التصنيف <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $category->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control"
                            rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="mb-3 d-flex align-items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ $category->is_active ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">تصنيف فعّال</label>
                    </div>
                    <button type="submit" class="btn btn-green w-100">
                        <i class="fas fa-save me-2"></i> حفظ التعديلات
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection