@extends('layouts.app')
@section('title', 'تعديل بيانات المحل')
@section('page-title', 'تخصيص المحل: ' . $shop->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="content-card shadow-sm border-0 p-4 p-md-5">
                <form action="{{ route('super_admin.update', $shop) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-4">
                        {{-- معلومات المحل --}}
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small mb-2">اسم المحل / الشركة</label>
                                <input type="text" name="name" class="form-control form-control-lg border-2"
                                    value="{{ $shop->name }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small mb-2">اسم المالك</label>
                                <input type="text" name="owner_name" class="form-control form-control-lg border-2 text-end"
                                    value="{{ $shop->owner_name }}" required>
                            </div>
                        </div>

                        {{-- الألوان --}}
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small mb-1">اللون الأساسي (Primary
                                    Color)</label>
                                <input type="color" name="primary_color"
                                    class="form-control form-control-color w-100 border-2" style="height: 48px;"
                                    value="{{ $shop->primary_color }}" required>
                                <div class="small text-muted mt-1 opacity-75">هذا اللون سيظهر في الهيدر والزوايا واللينكات.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small mb-1">اللون المميز (Accent/Gold
                                    Color)</label>
                                <input type="color" name="accent_color"
                                    class="form-control form-control-color w-100 border-2" style="height: 48px;"
                                    value="{{ $shop->accent_color }}" required>
                                <div class="small text-muted mt-1 opacity-75">هذا اللون سيظهر في الأيقونات واللمسات الذهبية.
                                </div>
                            </div>
                        </div>

                        {{-- اشتراك (لو مش Foursw) --}}
                        @if($shop->slug !== 'foursw')
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted small mb-2">بداية الاشتراك</label>
                                    <input type="date" name="subscription_start" class="form-control border-2"
                                        value="{{ $shop->subscription_start?->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted small mb-2">نهاية الاشتراك</label>
                                    <input type="date" name="subscription_end" class="form-control border-2"
                                        value="{{ $shop->subscription_end?->format('Y-m-d') }}">
                                </div>
                            </div>
                        @endif

                        {{-- أزرار التحكم --}}
                        <div class="col-12 pt-3">
                            <button type="submit" class="btn btn-green btn-lg w-100 mb-3 py-3 shadow-sm">
                                <i class="fas fa-save me-2"></i> حفظ الإعدادات
                            </button>
                            <a href="{{ route('super_admin.index') }}" class="btn btn-secondary btn-lg w-100 py-2">
                                <i class="fas fa-undo me-2"></i> رجوع
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection