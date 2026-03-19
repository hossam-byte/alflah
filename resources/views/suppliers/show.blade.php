@extends('layouts.app')
@section('title', 'تفاصيل المورد')
@section('page-title', 'ملف المورد')

@section('content')
    <div class="row g-3">
        {{-- بيانات المورد --}}
        <div class="col-12 col-md-4">
            <div class="content-card h-100 shadow-sm border-0">
                <div class="content-card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-1 text-primary"></i> معلومات المورد</h6>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                        <i class="fas fa-edit me-1"></i> تعديل
                    </a>
                </div>
                <div class="text-center py-4 px-3">
                    <div class="avatar bg-primary text-white mx-auto mb-3 shadow"
                        style="width:72px; height:72px; font-size:2rem; display:flex; align-items:center; justify-content:center; border-radius:50%">
                        {{ mb_substr($supplier->name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $supplier->name }}</h5>
                    <p class="text-muted small mb-4">
                        <i class="fas fa-phone-alt me-1"></i> {{ $supplier->phone ?? 'بدون رقم هاتف' }}
                        @if($supplier->phone2) | <i class="fas fa-phone-alt me-1"></i> {{ $supplier->phone2 }} @endif
                    </p>
                    
                    <div class="p-3 rounded-4 bg-light border">
                        <small class="d-block text-muted mb-1 text-uppercase fw-semibold" style="letter-spacing: 0.5px">الرصيد المستحق له</small>
                        <span class="fw-bold fs-4 {{ $supplier->balance > 0 ? 'text-danger' : 'text-success' }}">
                            {{ (float) $supplier->balance }} ج.م
                        </span>
                    </div>
                </div>
                <hr class="mx-3 my-0 opacity-10">
                <div class="p-4 pt-3">
                    <div class="mb-3 d-flex align-items-start gap-3">
                        <div class="bg-light p-2 rounded-3"><i class="fas fa-map-marker-alt text-primary"></i></div>
                        <div>
                            <small class="text-muted d-block">العنوان</small>
                            <span class="small fw-medium">{{ $supplier->address ?? '-' }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3 d-flex align-items-start gap-3">
                        <div class="bg-light p-2 rounded-3"><i class="fas fa-toggle-on text-primary"></i></div>
                        <div>
                            <small class="text-muted d-block">حالة المورد</small>
                            @if($supplier->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">نشط</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">متوقف</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-light p-2 rounded-3"><i class="fas fa-sticky-note text-primary"></i></div>
                        <div class="w-100">
                            <small class="text-muted d-block">ملاحظات إضافية</small>
                            <p class="small text-muted mb-0">{{ $supplier->notes ?? 'لا يوجد ملاحظات' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- آخر مشتريات من المورد --}}
        <div class="col-12 col-md-8">
            <div class="content-card h-100 shadow-sm border-0">
                <div class="content-card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-history me-1 text-success"></i> سجل فواتير الشراء</h6>
                    <a href="{{ route('purchases.create', ['supplier_id' => $supplier->id]) }}"
                        class="btn btn-sm btn-green rounded-pill px-4 shadow-sm">
                        <i class="fas fa-plus me-1"></i> فاتورة جديدة
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">رقم الفاتورة</th>
                                <th class="border-0">التاريخ</th>
                                <th class="border-0">الإجمالي</th>
                                <th class="border-0">الحالة</th>
                                <th class="border-0 text-center">عرض</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $purchase)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $purchase->invoice_number }}</td>
                                    <td>{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                                    <td class="fw-bold">{{ (float) $purchase->total_amount }}</td>
                                    <td>
                                        @if($purchase->payment_status === 'paid')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">مدفوع</span>
                                        @elseif($purchase->payment_status === 'partial')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">دفع جزئي</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">غير مدفوع</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-light rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0; line-height: 32px">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <div class="mb-2"><i class="fas fa-folder-open fa-2x opacity-25"></i></div>
                                        <p class="small mb-0">لا توجد عمليات شراء من هذا المورد بعد</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($purchases->hasPages())
                    <div class="d-flex justify-content-center mt-3 p-3 border-top">
                        {{ $purchases->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
