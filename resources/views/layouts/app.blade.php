<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - FourSW</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --green-dark:
                {{ Auth::user()->shop->secondary_color ?? '#1a5c0a' }}
            ;
            --green-main:
                {{ Auth::user()->shop->primary_color ?? '#2d7a18' }}
            ;
            --green-light: #4caf50;
            --green-pale: #e8f5e9;
            --gold:
                {{ Auth::user()->shop->accent_color ?? '#c8a000' }}
            ;
            --gold-light:
                {{ Auth::user()->shop->accent_color ?? '#f0c30f' }}
            ;
            --sidebar-width: 260px;
            --header-height: 60px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f0f4f0;
            color: #2c2c2c;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR (Desktop) ===== */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(170deg, var(--green-dark) 0%, var(--green-main) 60%, #3d8b28 100%);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .sidebar-logo {
            padding: 16px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-logo .logo-icon {
            font-size: 2.2rem;
            color: var(--gold-light);
            margin-bottom: 5px;
            display: block;
        }

        .sidebar-logo h4 {
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.4;
            margin: 0;
        }

        .sidebar-logo span {
            color: var(--gold-light);
            font-size: 0.72rem;
            font-weight: 500;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 8px 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .nav-section-title {
            padding: 8px 18px 4px;
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.45);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.87rem;
            font-weight: 500;
            transition: all 0.2s;
            border-right: 3px solid transparent;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            border-right-color: var(--gold-light);
        }

        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.18);
            font-weight: 700;
        }

        .sidebar-nav a i {
            width: 18px;
            text-align: center;
            font-size: 0.9rem;
        }

        .sidebar-footer {
            padding: 12px 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            flex-shrink: 0;
        }

        /* ===== OFFCANVAS (Mobile Sidebar) ===== */
        .offcanvas-sidebar {
            width: var(--sidebar-width) !important;
            background: linear-gradient(170deg, var(--green-dark) 0%, var(--green-main) 60%, #3d8b28 100%) !important;
        }

        .offcanvas-sidebar .offcanvas-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding: 14px;
        }

        .offcanvas-sidebar .btn-close {
            filter: invert(1);
        }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin 0.3s;
        }

        /* ===== HEADER ===== */
        .top-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-menu {
            display: none;
            background: var(--green-pale);
            border: none;
            color: var(--green-dark);
            width: 38px;
            height: 38px;
            border-radius: 8px;
            font-size: 1.1rem;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .page-title h5 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--green-dark);
            margin: 0;
            white-space: nowrap;
        }

        .page-title small {
            font-size: 0.73rem;
            color: #888;
            display: block;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px;
            background: var(--green-pale);
            border-radius: 50px;
        }

        .user-badge .avatar {
            width: 32px;
            height: 32px;
            background: var(--green-main);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-badge .name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--green-dark);
            white-space: nowrap;
        }

        .user-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            background: #fff;
        }

        .user-badge:hover .avatar {
            background: var(--green-dark);
        }
        .page-content {
            padding: 20px;
            flex: 1;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border-radius: 14px;
            padding: 18px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
            border: none;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18) !important;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -25px;
            left: -25px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .stat-card .icon {
            font-size: 1.9rem;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .stat-card .value {
            font-size: 1.55rem;
            font-weight: 900;
            line-height: 1.1;
        }

        .stat-card .label {
            font-size: 0.78rem;
            opacity: 0.85;
            margin-top: 3px;
        }

        .card-green {
            background: linear-gradient(135deg, #1a6b09 0%, #3fa828 100%);
        }

        .card-gold {
            background: linear-gradient(135deg, #a07800 0%, #d4a800 100%);
        }

        .card-blue {
            background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%);
        }

        .card-red {
            background: linear-gradient(135deg, #b71c1c 0%, #e53935 100%);
        }

        .card-teal {
            background: linear-gradient(135deg, #00695c 0%, #00897b 100%);
        }

        .card-orange {
            background: linear-gradient(135deg, #e65100 0%, #fb8c00 100%);
        }

        .card-purple {
            background: linear-gradient(135deg, #4527a0 0%, #7b1fa2 100%);
        }

        /* ===== CONTENT CARD ===== */
        .content-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            margin-bottom: 20px;
        }

        .content-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--green-pale);
            flex-wrap: wrap;
            gap: 8px;
        }

        .content-card-header h6 {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--green-dark);
            margin: 0;
        }

        /* ===== TABLE ===== */
        .custom-table {
            font-size: 0.85rem;
        }

        .custom-table thead th {
            background: var(--green-dark);
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 10px 14px;
            white-space: nowrap;
        }

        .custom-table tbody td {
            padding: 10px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .custom-table tbody tr:hover {
            background: var(--green-pale);
        }

        /* ===== BADGES ===== */
        .badge-paid {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .badge-partial {
            background: #fff8e1;
            color: #f57f17;
        }

        .badge-unpaid {
            background: #ffebee;
            color: #c62828;
        }

        /* ===== FORM ===== */
        .form-label {
            font-size: 0.87rem;
            font-weight: 600;
            color: #444;
        }

        .form-control,
        .form-select {
            font-family: 'Cairo', sans-serif;
            font-size: 0.88rem;
            border: 2px solid #e0e0e0;
            border-radius: 9px;
            padding: 9px 13px;
            transition: border-color 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(45, 122, 24, 0.1);
        }

        /* ===== BUTTONS ===== */
        .btn-green {
            background: var(--green-main);
            color: #fff;
            border: none;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
        }

        .btn-green:hover {
            background: var(--green-dark);
            color: #fff;
        }

        .btn {
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            font-size: 0.87rem;
        }

        /* ===== ALERT ===== */
        .alert {
            border-radius: 10px;
            font-size: 0.87rem;
            border: none;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none !important;
            }

            .main-wrapper {
                margin-right: 0;
            }

            .btn-menu {
                display: flex !important;
            }

            .user-badge .name {
                display: none;
            }

            .page-content {
                padding: 14px;
            }

            .stat-card .value {
                font-size: 1.3rem;
            }

            .stat-card {
                padding: 14px;
            }
        }

        @media (max-width: 575.98px) {
            .top-header {
                padding: 0 12px;
            }

            .page-content {
                padding: 10px;
            }

            .content-card {
                padding: 14px;
            }

            .custom-table {
                font-size: 0.8rem;
            }

            .custom-table thead th {
                padding: 8px 10px;
            }

            .custom-table tbody td {
                padding: 8px 10px;
            }
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--green-light);
            border-radius: 3px;
        }

    </style>

    @stack('styles')
</head>

<body>

    {{-- ===== DESKTOP SIDEBAR ===== --}}
    <aside class="sidebar d-none d-lg-flex">
        <div class="sidebar-logo">
            <i class="fas fa-cube logo-icon"></i>
            <h4>FourSW System</h4>
            <span>نظام إدارة المحلات</span>
        </div>

        <nav class="sidebar-nav">
            @include('partials.nav-links')
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn w-100 text-white fw-bold"
                    style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);font-family:'Cairo',sans-serif;font-size:0.85rem;">
                    <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MOBILE OFFCANVAS SIDEBAR ===== --}}
    <div class="offcanvas offcanvas-end offcanvas-sidebar" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <div class="text-center w-100">
                <i class="fas fa-cube fa-2x" style="color:var(--gold-light)"></i>
                <h6 class="text-white mt-2 mb-0 fw-bold" style="font-size:0.85rem">FourSW System</h6>
                <small style="color:var(--gold-light);font-size:0.72rem">إدارة الأعمال الذكية</small>
            </div>
            <button type="button" class="btn-close ms-0" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0" style="overflow-y:auto">
            <nav class="sidebar-nav">
                @include('partials.nav-links')
            </nav>
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn w-100 text-white fw-bold"
                        style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);font-family:'Cairo',sans-serif;font-size:0.85rem;">
                        <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="main-wrapper">

        {{-- Header --}}
        <header class="top-header">
            <div class="header-left">
                <button class="btn-menu" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title">
                    <h5>@yield('page-title', 'لوحة التحكم')</h5>
                    <small class="d-none d-sm-block">@yield('page-subtitle', '')</small>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="user-badge shadow-sm text-decoration-none">
                <div class="avatar">{{ mb_substr(Auth::user()->shop->owner_name ?? Auth::user()->name, 0, 1) }}</div>
                <span class="name">{{ Auth::user()->shop->owner_name ?? Auth::user()->name }}</span>
            </a>
        </header>

        {{-- Content --}}
        <main class="page-content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        document.addEventListener('submit', function (e) {
            if (e.target.classList.contains('delete-form')) {
                e.preventDefault();
                const form = e.target;
                const message = form.getAttribute('data-confirm') || 'هل أنت متأكد من الحذف؟';

                Swal.fire({
                    title: 'تأكيد الحذف',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3d8b28',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>