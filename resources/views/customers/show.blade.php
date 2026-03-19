@extends('layouts.app')
@section('title', 'تفاصيل العميل')
@section('page-title', 'ملف العميل')

@section('content')
    <div class="row g-3">
        {{-- بيانات العميل --}}
        <div class="col-12 col-md-4">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h6><i class="fas fa-info-circle me-1"></i> معلومات العميل</h6>
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning rounded-pill"><i
                            class="fas fa-edit"></i></a>
                </div>
                <div class="text-center py-3">
                    <div class="avatar bg-success text-white mx-auto mb-2"
                        style="width:64px; height:64px; font-size:1.8rem; display:flex; align-items:center; justify-content:center; border-radius:50%">
                        {{ mb_substr($customer->name, 0, 1) }}</div>
                    <h5 class="fw-bold mb-1">{{ $customer->name }}</h5>
                    <p class="text-muted small mb-3">{{ $customer->phone ?? 'بدون رقم هاتف' }}</p>
                    <div class="alert {{ $customer->balance >= 0 ? 'alert-success' : 'alert-danger' }} p-2 mb-0">
                        <small class="d-block text-muted">الرصيد الحالي</small>
                        <span class="fw-bold fs-5">{{ number_format($customer->balance, 2) }} ج.م</span>
                    </div>
                </div>
                <hr class="mt-0">
                <ul class="list-unstyled small">
                    <li class="mb-2"><strong>العنوان:</strong> {{ $customer->address ?? '-' }}</li>
                    <li class="mb-2"><strong>حالة العميل:</strong>
                        <span
                            class="badge {{ $customer->is_active ? 'badge-paid' : 'badge-unpaid' }} rounded-pill">{{ $customer->is_active ? 'فعّال' : 'متوقف' }}</span>
                    </li>
                    <li><strong>ملاحظات:</strong><br><span class="text-muted">{{ $customer->notes ?? '-' }}</span></li>
                </ul>
            </div>
        </div>

        {{-- آخر مبيعات العميل --}}
        <div class="col-12 col-md-8">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h6><i class="fas fa-history me-1"></i> آخر فواتير البيع</h6>
                    <a href="{{ route('sales.create', ['customer_id' => $customer->id]) }}"
                        class="btn btn-sm btn-green rounded-pill">فاتورة جديدة</a>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table mb-0">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>التاريخ</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th>عرض</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="fw-bold text-success">{{ $sale->invoice_number }}</td>
                                    <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                                    <td class="fw-bold">{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        @if($sale->payment_status === 'paid')
                                            <span class="badge badge-paid rounded-pill">مدفوع</span>
                                        @elseif($sale->payment_status === 'partial')
                                            <span class="badge badge-partial rounded-pill">جزئي</span>
                                        @else
                                            <span class="badge badge-unpaid rounded-pill">غير مدفوع</span>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info rounded-pill"><i
                                                class="fas fa-eye"></i></a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">لا توجد عمليات بيع لهذا العميل بعد
                                    </td>
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
        </div>
    </div>
@endsection