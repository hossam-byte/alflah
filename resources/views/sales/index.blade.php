@extends('layouts.app')
@section('title', 'المبيعات')
@section('page-title', 'سجل المبيعات')
@section('page-subtitle', 'إدارة فواتير المبيعات والأرباح المحققة')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card card-green shadow">
                <div class="icon"><i class="fas fa-cash-register"></i></div>
                <div class="value">{{ number_format($totalSales, 2) }}</div>
                <div class="label">إجمالي المبيعات (ج.م)</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card card-gold shadow">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <div class="value">{{ number_format($totalProfit, 2) }}</div>
                <div class="label">إجمالي الأرباح (ج.م)</div>
            </div>
        </div>
    </div>

    <div class="content-card mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label small">بحث برقم الفاتورة</label>
                <input type="text" name="search" class="form-control" placeholder="رقم الفاتورة..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">فلترة بالشهر</label>
                <input type="month" name="month" class="form-control" value="{{ request('month') }}">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">كل الحالات</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>جزئي</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button class="btn btn-green flex-fill">بحث</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">×</a>
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h6><i class="fas fa-list-ul me-2" style="color:var(--green-main)"></i> فواتير المبيعات</h6>
            <a href="{{ route('sales.create') }}" class="btn btn-green btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> فاتورة بيع جديدة
            </a>
        </div>

        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>العميل</th>
                        <th>التاريخ</th>
                        <th>الإجمالي</th>
                        <th>الربح</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $s)
                        <tr>
                            <td class="fw-bold text-success">{{ $s->invoice_number }}</td>
                            <td class="fw-bold">{{ $s->customer->name ?? 'عميل كاش' }}</td>
                            <td>{{ $s->sale_date->format('Y-m-d') }}</td>
                            <td class="fw-bold">{{ number_format($s->total_amount, 2) }}</td>
                            <td class="fw-bold">
                                <span class="{{ $s->profit >= 0 ? 'text-success' : 'text-danger' }} small">
                                    {{ $s->profit >= 0 ? '+' : '' }}{{ number_format($s->profit, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($s->payment_status === 'paid')
                                    <span class="badge badge-paid rounded-pill">مدفوع</span>
                                @elseif($s->payment_status === 'partial')
                                    <span class="badge badge-partial rounded-pill">جزئي</span>
                                @else
                                    <span class="badge badge-unpaid rounded-pill">غير مدفوع</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('sales.show', $s) }}" class="btn btn-sm btn-info rounded-pill px-2"><i
                                            class="fas fa-eye"></i></a>
                                    <form action="{{ route('sales.destroy', $s) }}" method="POST" class="delete-form"
                                        data-confirm="حذف الفاتورة سيعيد الكميات للمخزون، هل أنت متأكد؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-pill px-2"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">لا توجد مبيعات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sales->hasPages())
            <div class="mt-3">{{ $sales->links() }}</div>
        @endif
    </div>
@endsection