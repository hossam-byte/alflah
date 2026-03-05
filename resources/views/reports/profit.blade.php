@extends('layouts.app')
@section('title', 'تقرير الأرباح')
@section('page-title', 'تقرير الأرباح والمصاريف')

@section('content')
    <div class="content-card mb-4">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-8">
                <label class="form-label small">اختر الشهر للتقرير</label>
                <input type="month" name="month" class="form-control" value="{{ $month }}">
            </div>
            <div class="col-12 col-md-4">
                <button class="btn btn-green w-100">عرض التقرير</button>
            </div>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card card-green">
                <div class="icon"><i class="fas fa-cash-register"></i></div>
                <div class="value">{{ number_format($totalSales, 2) }}</div>
                <div class="label">إجمالي المبيعات (ج.م)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-gold">
                <div class="icon"><i class="fas fa-funnel-dollar"></i></div>
                <div class="value">{{ number_format($totalProfit, 2) }}</div>
                <div class="label">إجمالي أرباح البيع (ج.م)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-red">
                <div class="icon"><i class="fas fa-wallet"></i></div>
                <div class="value">{{ number_format($expenses, 2) }}</div>
                <div class="label">إجمالي المصاريف (ج.م)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card card-blue" style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%)">
                <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="value">{{ number_format($netProfit, 2) }}</div>
                <div class="label">صافي الربح النهائي (ج.م)</div>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h6><i class="fas fa-history me-2"></i> تفاصيل مبيعات الشهر ({{ $month }})</h6>
        </div>
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>التاريخ</th>
                        <th>العميل</th>
                        <th>إجمالي الفاتورة</th>
                        <th>ربح الفاتورة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                            <td>{{ $sale->customer->name ?? 'كاش' }}</td>
                            <td class="fw-bold">{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-success fw-bold">+ {{ number_format($sale->profit, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">لا توجد عمليات بيع في هذا الشهر</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection