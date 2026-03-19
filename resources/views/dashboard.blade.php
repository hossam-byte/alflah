@extends('layouts.app')
@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'أهلاً بك، ' . Auth::user()->name . ' 👋')

@section('content')
    {{-- نظرة عامة سريعة --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card card-green shadow-sm">
                <div class="icon"><i class="fas fa-cash-register"></i></div>
                <div class="value">{{ (float) $stats['sales_today'] }}</div>
                <div class="label">مبيعات اليوم (ج.م)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-gold shadow-sm">
                <div class="icon"><i class="fas fa-coins"></i></div>
                <div class="value">{{ (float) $stats['profit_today'] }}</div>
                <div class="label">أرباح اليوم (ج.م)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-blue shadow-sm">
                <div class="icon"><i class="fas fa-shopping-basket"></i></div>
                <div class="value">{{ (float) $stats['purchases_today'] }}</div>
                <div class="label">مشتريات اليوم (ج.م)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-red shadow-sm">
                <div class="icon"><i class="fas fa-wallet"></i></div>
                <div class="value">{{ (float) $stats['expenses_today'] }}</div>
                <div class="label">مصروفات اليوم (ج.م)</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- تنبيهات المخزون --}}
        <div class="col-12 col-md-6">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h6><i class="fas fa-exclamation-triangle text-warning me-2"></i> تنبيهات المخزون المنخفض</h6>
                    <a href="{{ route('products.index', ['low_stock' => 1]) }}"
                        class="btn btn-sm btn-outline-danger rounded-pill">عرض الكل</a>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>المتبقي</th>
                                <th>طلب</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                                <tr>
                                    <td class="fw-bold">{{ $product->name }}</td>
                                    <td class="text-danger fw-bold">{{ $product->stock }} {{ $product->unit }}</td>
                                    <td>
                                        <a href="{{ route('purchases.create', ['product_id' => $product->id]) }}"
                                            class="btn btn-sm btn-link text-success p-0">
                                            <i class="fas fa-plus-circle fs-5"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-success"><i
                                            class="fas fa-check-circle me-1"></i> كل المنتجات متوفرة بكثرة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- آخر العمليات --}}
        <div class="col-12 col-md-6">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h6><i class="fas fa-history text-primary me-2"></i> آخر فواتير البيع</h6>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">عرض الكل</a>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>الرقم</th>
                                <th>العميل</th>
                                <th>المبلغ</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td class="fw-bold text-success">#{{ $sale->invoice_number }}</td>
                                    <td>{{ $sale->customer->name ?? 'كاش' }}</td>
                                    <td class="fw-bold">{{ (float) $sale->total_amount }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $sale->payment_status === 'paid' ? 'badge-paid' : ($sale->payment_status === 'partial' ? 'badge-partial' : 'badge-unpaid') }} rounded-pill"
                                            style="font-size: 0.65rem">
                                            {{ $sale->payment_status === 'paid' ? 'مدفوع' : ($sale->payment_status === 'partial' ? 'جزئي' : 'آجل') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">لا توجد عمليات بيع اليوم</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection