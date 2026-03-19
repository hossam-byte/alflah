@extends('layouts.app')
@section('title', 'تقرير المخزون')
@section('page-title', 'تقرير حالة المخزون')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="stat-card card-teal">
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <div class="value">{{ (float) $totalStockValue }} ج.م</div>
                <div class="label">إجمالي قيمة البضاعة بالمحل (بسعر الشراء)</div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="stat-card card-orange">
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="value">{{ $lowStockCount }}</div>
                <div class="label">عدد المنتجات التي أوشكت على النفاذ</div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-8">
                <label class="form-label small">فلترة بالتصنيف</label>
                <select name="category_id" class="form-select">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <button class="btn btn-green w-100">فلترة</button>
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h6><i class="fas fa-warehouse me-2"></i> جرد المخزون الحالي</h6>
            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary d-print-none"><i
                    class="fas fa-print me-1"></i> طباعة الجرد</button>
        </div>
        <div class="table-responsive">
            <table class="table custom-table mb-0 border">
                <thead>
                    <tr class="text-center">
                        <th>المنتج</th>
                        <th>التصنيف</th>
                        <th>سعر الشراء</th>
                        <th>الكمية الحالية</th>
                        <th>قيمة المخزون</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="{{ $product->stock <= $product->min_stock ? 'table-warning' : '' }}">
                            <td class="fw-bold">{{ $product->name }}</td>
                            <td class="text-center">{{ $product->category->name ?? '-' }}</td>
                            <td class="text-center">{{ (float) $product->purchase_price }}</td>
                            <td class="text-center fw-bold">
                                {{ $product->stock }} {{ $product->unit }}
                                @if($product->stock <= $product->min_stock)
                                    <br><small class="text-danger fw-normal">⚠️ منخفض</small>
                                @endif
                            </td>
                            <td class="text-center fw-bold text-success">
                                {{ (float) ($product->stock * $product->purchase_price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('styles')
        <style>
            @media print {

                .top-header,
                .sidebar,
                .btn,
                .d-print-none,
                .content-card-header form {
                    display: none !important;
                }

                .main-wrapper {
                    margin: 0 !important;
                }

                .content-card {
                    border: none !important;
                    box-shadow: none !important;
                }
            }
        </style>
    @endpush
@endsection