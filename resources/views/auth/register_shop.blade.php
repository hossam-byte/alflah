<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل محل جديد - FourSW</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --green-dark: #1a5c0a;
            --green-main: #2d7a18;
            --green-light: #4caf50;
            --gold: #c8a000;
            --gold-light: #f0c30f;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #0d3d04 0%, #1a5c0a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
            color: var(--green-dark);
        }

        .form-control {
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .btn-register {
            background: var(--green-main);
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-register:hover {
            background: var(--green-dark);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <div class="register-card">
        <div class="logo">
            <i class="fas fa-store-alt fa-3x mb-2"></i>
            <h4>انضم إلى FourSW System</h4>
            <p class="text-muted small">سجل محلك الآن وابدأ ثورة في إدارتك</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.shop.post') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">اسم المحل</label>
                    <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name') }}" required
                        placeholder="مثال: محل الأمل للزراعة">
                </div>
                <div class="col-md-6">
                    <label class="form-label">اسم المالك</label>
                    <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name') }}" required
                        placeholder="الاسم بالكامل">
                </div>
            </div>

            <label class="form-label">رقم الهاتف (سيستخدم لتسجيل الدخول)</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required
                placeholder="01xxxxxxxxx">

            <label class="form-label">البريد الإلكتروني (اختياري)</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                placeholder="email@example.com">

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <div class="col-md-6">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" class="form-control" required
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn-register mt-2">إنشاء الحساب وبدء التجربة</button>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small text-decoration-none">لديك حساب بالفعل؟ سجل دخولك</a>
            </div>
        </form>
    </div>
</body>

</html>