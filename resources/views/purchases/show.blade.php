@extends('layouts.app')
@section('title', 'تفاصيل فاتورة شراء')
@section('page-title', 'فاتورة شراء رقم: ' . $purchase->invoice_number)

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="content-card">
                {{-- رأس الفاتورة --}}
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6 text-start">
                        <h5 class="fw-bold text-dark opacity-75 mb-1">تفاصيل فاتورة شراء</h5>
                        <div class="badge bg-light text-dark border p-2 fw-normal">
                            <span class="text-muted">الرقم:</span> #{{ $purchase->invoice_number }}
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                        <div class="d-flex align-items-center gap-2 mb-1 justify-content-sm-end">
                            <i class="fas fa-cube text-primary fa-2x"></i>
                            <h4 class="fw-bold mb-0 text-primary">FourSW <span class="text-dark">System</span></h4>
                        </div>
                        <p class="text-muted small mb-0 fw-medium">حلول إدارة الأعمال الذكية</p>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded-4 h-100 border text-start">
                            <small class="text-muted d-block mb-1 text-uppercase fw-bold"
                                style="font-size: 0.65rem; letter-spacing: 0.5px">المورد</small>
                            <h6 class="fw-bold mb-1">{{ $purchase->supplier->name ?? 'مورد عام' }}</h6>
                            <p class="mb-0 small text-muted"><i class="fas fa-phone-alt me-1"></i>
                                {{ $purchase->supplier->phone ?? 'بدون هاتف' }}</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded-4 h-100 border text-start">
                            <small class="text-muted d-block mb-1 text-uppercase fw-bold"
                                style="font-size: 0.65rem; letter-spacing: 0.5px">تاريخ الفاتورة</small>
                            <h6 class="fw-bold mb-0"><i class="far fa-calendar-alt me-1 text-muted"></i>
                                {{ $purchase->purchase_date->format('Y-m-d') }}</h6>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded-4 h-100 border text-start">
                            <small class="text-muted d-block mb-1 text-uppercase fw-bold"
                                style="font-size: 0.65rem; letter-spacing: 0.5px">حالة الدفع</small>
                            @if($purchase->payment_status === 'paid')
                                <span
                                    class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">تم
                                    الدفع بالكامل</span>
                            @elseif($purchase->payment_status === 'partial')
                                <span
                                    class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">دفع
                                    جزئي</span>
                            @else
                                <span
                                    class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">بانتظار
                                    الدفع</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- جدول المنتجات --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-light text-center small">
                            <tr>
                                <th>#</th>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>سعر الشراء</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="fw-bold">{{ $item->product->name }}</td>
                                    <td class="text-center">{{ $item->quantity }} {{ $item->product->unit }}</td>
                                    <td class="text-center">{{ (float) $item->unit_price }}</td>
                                    <td class="text-center fw-bold">{{ (float) $item->total_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="4" class="text-start fw-bold">إجمالي الفاتورة:</td>
                                <td class="text-start fw-bold">
                                    {{ (float) ($purchase->total_amount + $purchase->discount) }} ج.م</td>
                            </tr>
                            @if($purchase->discount > 0)
                                <tr>
                                    <td colspan="4" class="text-start fw-bold text-danger">الخصم:</td>
                                    <td class="text-start fw-bold text-danger">- {{ (float) $purchase->discount }}
                                        ج.م</td>
                                </tr>
                            @endif
                            <tr class="table-info">
                                <td colspan="4" class="text-start fw-bold fs-5">الصافي:</td>
                                <td class="text-start fw-bold fs-5">{{ (float) $purchase->total_amount }} ج.م
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-start fw-bold">المسدد للمورد:</td>
                                <td class="text-start fw-bold">{{ (float) $purchase->paid_amount }} ج.م</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-start fw-bold">المتبقي للمورد:</td>
                                <td class="text-start fw-bold text-danger">
                                    {{ (float) ($purchase->total_amount - $purchase->paid_amount) }} ج.م</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($purchase->notes)
                    <div class="mt-3">
                        <h6 class="fw-bold text-muted small">ملاحظات:</h6>
                        <p class="mb-0 bg-light p-2 rounded small border">{{ $purchase->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-3 text-center">
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-arrow-right me-2"></i> العودة لقائمة المشتريات
                </a>
            </div>
        </div>
    </div>
@endsection