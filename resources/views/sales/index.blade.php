@extends('layouts.app')
@section('title', isset($isQuotationPage) ? 'الاستعلامات' : 'المبيعات')
@section('page-title', isset($isQuotationPage) ? 'سجل الاستعلامات' : 'سجل المبيعات')
@section('page-subtitle', isset($isQuotationPage) ? 'إدارة فواتير الاستعلام والأسعار' : 'إدارة فواتير المبيعات والأرباح المحققة')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card card-green shadow">
                <div class="icon"><i class="fas fa-cash-register"></i></div>
                <div class="value">{{ (float) $totalSales }}</div>
                <div class="label">{{ isset($isQuotationPage) ? 'إجمالي الاستعلامات (ج.م)' : 'إجمالي المبيعات (ج.م)' }}</div>
            </div>
        </div>
        @if(!isset($isQuotationPage))
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card card-gold shadow">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <div class="value">{{ (float) $totalProfit }}</div>
                <div class="label">إجمالي الأرباح (ج.م)</div>
            </div>
        </div>
        @endif
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
            @if(!isset($isQuotationPage))
            <div class="col-6 col-md-3">
                <label class="form-label small">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">كل الحالات</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>جزئي</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                </select>
            </div>
            @endif
            <div class="col-12 col-md-3 d-flex gap-2">
                <button class="btn btn-green flex-fill">بحث</button>
                <a href="{{ url()->current() }}" class="btn btn-secondary">×</a>
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h6><i class="fas fa-list-ul me-2" style="color:var(--green-main)"></i> {{ isset($isQuotationPage) ? 'فواتير الاستعلام' : 'فواتير المبيعات' }}</h6>
            <a href="{{ route('sales.create', isset($isQuotationPage) ? ['type' => 'quotation'] : []) }}" class="btn btn-green btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> {{ isset($isQuotationPage) ? 'استعلام جديد' : 'فاتورة بيع جديدة' }}
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
                        @if(!isset($isQuotationPage))
                        <th>الربح</th>
                        <th>الحالة</th>
                        @endif
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $s)
                        <tr>
                            <td class="fw-bold text-success">{{ $s->invoice_number }}</td>
                            <td class="fw-bold">{{ $s->customer->name ?? 'عميل كاش' }}</td>
                            <td>{{ $s->sale_date->format('Y-m-d') }}</td>
                            <td class="fw-bold">{{ (float) $s->total_amount }}</td>
                            @if(!isset($isQuotationPage))
                            <td class="fw-bold">
                                <span class="{{ $s->profit >= 0 ? 'text-success' : 'text-danger' }} small">
                                    {{ $s->profit >= 0 ? '+' : '' }}{{ (float) $s->profit }}
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
                            @endif
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('sales.show', $s) }}" class="btn btn-sm btn-info rounded-pill px-2"
                                        title="عرض التفاصيل"><i class="fas fa-eye"></i></a>
                                    
                                    @if(isset($isQuotationPage))
                                        <a href="{{ route('sales.print', $s) }}?type=simple" target="_blank"
                                            class="btn btn-sm btn-secondary rounded-pill px-2" title="طباعة بسيطة"><i
                                                class="fas fa-receipt"></i></a>
                                    @else
                                        <a href="{{ route('sales.print', $s) }}?type=detailed" target="_blank"
                                            class="btn btn-sm btn-primary rounded-pill px-2" title="طباعة مفصلة"><i
                                                class="fas fa-print"></i></a>
                                    @endif

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
                            <td colspan="{{ isset($isQuotationPage) ? 5 : 7 }}" class="text-center py-4 text-muted">لا توجد بيانات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sales->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
@endsection