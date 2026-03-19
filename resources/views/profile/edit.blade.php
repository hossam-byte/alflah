@extends('layouts.app')
@section('title', 'تعديل الملف الشخصي')
@section('page-title', 'تعديل الملف الشخصي')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-6">
        <div class="content-card shadow-sm border-0">
            <div class="content-card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2 text-success"></i> بيانات الحساب</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold small text-muted">الاسم الكامل</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="name" id="name" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label fw-semibold small text-muted">رقم الهاتف (اسم المستخدم)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                            <input type="text" name="phone" id="phone" class="form-control bg-light border-start-0 @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4 opacity-50">
                    <h6 class="fw-bold mb-3 small text-primary"><i class="fas fa-lock me-1 text-muted"></i> تغيير كلمة المرور (اختياري)</h6>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold small text-muted">كلمة المرور الجديدة</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-key text-muted"></i></span>
                            <input type="password" name="password" id="password" class="form-control bg-light border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="اتركها فارغة إذا لم ترد التغيير">
                            <span class="input-group-text bg-light border-start-0 cursor-pointer" onclick="togglePassword()">
                                <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <script>
                        function togglePassword() {
                            const pwd = document.getElementById('password');
                            const icon = document.getElementById('toggleIcon');
                            if (pwd.type === 'password') {
                                pwd.type = 'text';
                                icon.classList.replace('fa-eye', 'fa-eye-slash');
                            } else {
                                pwd.type = 'password';
                                icon.classList.replace('fa-eye-slash', 'fa-eye');
                            }
                        }
                    </script>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-green py-2 fw-bold shadow-sm">
                            <i class="fas fa-save me-1"></i> حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
