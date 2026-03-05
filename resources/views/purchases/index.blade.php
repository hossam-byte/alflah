@extends('layouts.app')
@section('title', 'المشتريات')
@section('page-title', 'فواتير المشتريات')
@section('page-subtitle', 'إدارة المشتريات من الموردين وزيادة المخزون')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card card-blue shadow">
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="value">{{ number_format($totalAmount, 2) }}</div>
                <div class="label">إجمالي المشتريات (ج.م)</div>
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
                <label class="form-label small">الشهر</label>
                <input type="month" name="month" class="form-control" value="{{ request('month') }}">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>جزئي</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button class="btn btn-green flex-fill">بحث</button>
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">×</a>
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h6><i class="fas fa-file-invoice me-2" style="color:var(--green-main)"></i> سجل فواتير الشراء</h6>
            <a href="{{ route('purchases.create') }}" class="btn btn-green btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> فاتورة شراء جديدة
            </a>
        </div>

        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>المورد</th>
                        <th>التاريخ</th>
                        <th>الإجمالي</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $p)
                        <tr>
                            <td class="fw-bold text-primary">{{ $p->invoice_number }}</td>
                            <td class="fw-bold">{{ $p->supplier->name ?? 'مورد عام' }}</td>
                            <td>{{ $p->purchase_date->format('Y-m-d') }}</td>
                            <td class="fw-bold">{{ number_format($p->total_amount, 2) }}</td>
                            <td>
                                @if($p->payment_status === 'paid')
                                    <span class="badge badge-paid rounded-pill">مدفوع</span>
                                @elseif($p->payment_status === 'partial')
                                    <span class="badge badge-partial rounded-pill">جزئي</span>
                                @else
                                    <span class="badge badge-unpaid rounded-pill">غير مدفوع</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('purchases.show', $p) }}" class="btn btn-sm btn-info rounded-pill px-2"><i
                                            class="fas fa-eye"></i></a>
                                    <form action="{{ route('purchases.destroy', $p) }}" method="POST" class="delete-form"
                                        data-confirm="حذف الفاتورة سيؤدي لخصم المنتجات من المخزون، هل أنت متأكد؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-pill px-2"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">لا توجد فواتير شراء</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($purchases->hasPages())
            <div class="mt-3">{{ $purchases->links() }}</div>
        @endif
    </div>
@endsection