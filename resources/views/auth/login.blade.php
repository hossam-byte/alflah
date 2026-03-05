<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - FourSW System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0d3d04 0%, #1a5c0a 35%, #2d7a18 70%, #3d9620 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* خلفية متحركة */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(240, 195, 15, 0.06) 0%, transparent 50%);
            pointer-events: none;
        }

        .floating-leaf {
            position: absolute;
            font-size: 2rem;
            opacity: 0.08;
            animation: floatLeaf 6s ease-in-out infinite;
        }

        .floating-leaf:nth-child(1) {
            top: 10%;
            left: 5%;
            animation-delay: 0s;
            font-size: 3rem;
        }

        .floating-leaf:nth-child(2) {
            top: 70%;
            left: 8%;
            animation-delay: 1s;
            font-size: 2rem;
        }

        .floating-leaf:nth-child(3) {
            top: 30%;
            right: 5%;
            animation-delay: 2s;
            font-size: 2.5rem;
        }

        .floating-leaf:nth-child(4) {
            top: 80%;
            right: 8%;
            animation-delay: 0.5s;
            font-size: 1.8rem;
        }

        .floating-leaf:nth-child(5) {
            top: 50%;
            left: 50%;
            animation-delay: 3s;
            font-size: 4rem;
            opacity: 0.04;
        }

        @keyframes floatLeaf {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        /* بطاقة تسجيل الدخول */
        .login-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 24px;
            padding: 44px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 10;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-logo .icon-wrap {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--green-dark), var(--green-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            box-shadow: 0 8px 24px rgba(45, 122, 24, 0.4);
        }

        .login-logo .icon-wrap i {
            font-size: 2.2rem;
            color: var(--gold-light);
        }

        .login-logo h2 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--green-dark);
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .login-logo p {
            font-size: 0.82rem;
            color: #888;
            margin: 0;
        }

        /* الفورم */
        .form-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 18px;
        }

        .input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--green-main);
            font-size: 0.95rem;
            z-index: 5;
        }

        .form-control {
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
            padding: 12px 42px 12px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fafafa;
        }

        .form-control:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(45, 122, 24, 0.12);
            background: #fff;
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #e53935;
        }

        .invalid-feedback {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            color: #fff;
            font-family: 'Cairo', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            padding: 13px;
            border: none;
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
            transition: all 0.25s;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(45, 122, 24, 0.35);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--green-dark), #0d3d04);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(45, 122, 24, 0.45);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .form-check-input:checked {
            background-color: var(--green-main);
            border-color: var(--green-main);
        }

        .form-check-label {
            font-size: 0.85rem;
            color: #666;
            cursor: pointer;
        }

        .login-footer {
            text-align: center;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #eee;
        }

        .login-footer .info-badges {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .info-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            color: #aaa;
        }

        .info-badge i {
            color: var(--green-main);
        }

        .alert {
            border-radius: 10px;
            font-size: 0.85rem;
            border: none;
            margin-bottom: 20px;
        }

        /* divider */
        .gold-divider {
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold-light), var(--gold), var(--gold-light), transparent);
            border-radius: 3px;
            margin: 20px 0;
        }
    </style>
</head>

<body>

    {{-- عناصر الخلفية المتحركة --}}
    <span class="floating-leaf">🌿</span>
    <span class="floating-leaf">🌱</span>
    <span class="floating-leaf">🍃</span>
    <span class="floating-leaf">🌾</span>
    <span class="floating-leaf">🌿</span>

    <div class="login-card">

        {{-- الشعار --}}
        <div class="login-logo">
            <div class="icon-wrap">
                <i class="fas fa-seedling"></i>
            </div>
            <h2>FourSW System</h2>
            <p>حلول إدارة الأعمال المتكاملة</p>
        </div>

        <div class="gold-divider"></div>

        {{-- رسائل الخطأ --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- الفورم --}}
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            {{-- رقم الهاتف --}}
            <div class="mb-1">
                <label class="form-label">رقم الهاتف</label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="fas fa-phone"></i></span>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                        placeholder="01000000000" value="{{ old('phone') }}" autocomplete="tel" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- كلمة المرور --}}
            <div class="mb-1">
                <label class="form-label">كلمة المرور</label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                        autocomplete="current-password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- تذكرني --}}
            <div class="remember-row mt-3">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">تذكرني</label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> دخول
            </button>
            <div class="text-center mt-3">
                <a href="{{ route('register.shop') }}" class="text-decoration-none small fw-bold"
                    style="color:var(--green-main)">
                    <i class="fas fa-plus-circle me-1"></i> ليس لديك محل؟ سجل الآن
                </a>
            </div>
        </form>

        {{-- Footer --}}
        <div class="login-footer">
            <div class="info-badges">
                <span class="info-badge"><i class="fas fa-shield-alt"></i> نظام آمن</span>
                <span class="info-badge"><i class="fas fa-lock"></i> بيانات محمية</span>
                <span class="info-badge"><i class="fas fa-clock"></i> {{ now()->format('Y') }}</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>