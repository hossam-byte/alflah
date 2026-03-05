@if(Auth::user()->is_super_admin)
    {{-- روابط المطور فقط --}}
    <p class="nav-section-title">المطور</p>
    <a href="{{ route('super_admin.index') }}" class="{{ request()->routeIs('super_admin.index') ? 'active' : '' }}">
        <i class="fas fa-user-shield"></i> لوحة تحكم المطور
    </a>
    <a href="{{ route('super_admin.settings') }}" class="{{ request()->routeIs('super_admin.settings') ? 'active' : '' }}">
        <i class="fas fa-user-cog"></i> إعدادات حسابي
    </a>
@else
    {{-- روابط أصحاب المحلات فقط --}}
    <p class="nav-section-title">الرئيسية</p>
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> لوحة التحكم
    </a>

    <p class="nav-section-title">المخزون</p>
    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
        <i class="fas fa-boxes"></i> المنتجات
    </a>
    <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <i class="fas fa-tags"></i> التصنيفات
    </a>

    <p class="nav-section-title">العمليات</p>
    <a href="{{ route('purchases.index') }}" class="{{ request()->routeIs('purchases.*') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i> المشتريات
    </a>
    <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
        <i class="fas fa-cash-register"></i> المبيعات
    </a>
    <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}">
        <i class="fas fa-receipt"></i> المصروفات
    </a>

    <p class="nav-section-title">الأطراف</p>
    <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
        <i class="fas fa-truck"></i> الموردون
    </a>
    <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i> العملاء
    </a>

    <p class="nav-section-title">التقارير</p>
    <a href="{{ route('reports.profit') }}" class="{{ request()->routeIs('reports.profit') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i> تقرير الأرباح
    </a>
    <a href="{{ route('reports.stock') }}" class="{{ request()->routeIs('reports.stock') ? 'active' : '' }}">
        <i class="fas fa-warehouse"></i> تقرير المخزون
    </a>
@endif