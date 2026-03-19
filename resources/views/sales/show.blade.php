@extends('layouts.app')
@section('title', 'تفاصيل فاتورة البيع')
@section('page-title', 'تفاصيل فاتورة البيع')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <div id="printable-area">
                <div class="content-card shadow-sm border-0 p-4">
                    {{-- رأس الفاتورة --}}
                    <div class="row align-items-center mb-4 pb-3 border-bottom">
                        <div class="col-sm-6 text-start">
                            <h5 class="fw-bold text-dark opacity-75 mb-1">تفاصيل فاتورة بيع</h5>
                            <div class="badge bg-light text-dark border p-2 fw-normal">
                                <span class="text-muted">الرقم:</span> #{{ $sale->invoice_number }}
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

                    {{-- Top Info Cards --}}
                    <div class="row g-3 mb-4 text-start">
                        {{-- Customer --}}
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-4 h-100 border text-start shadow-xs">
                                <small class="text-muted d-block mb-1 text-uppercase fw-bold"
                                    style="font-size: 0.65rem; letter-spacing: 0.5px">العميل</small>
                                <h6 class="fw-bold mb-1">{{ $sale->customer->name ?? 'عميل كاش' }}</h6>
                                <p class="mb-0 small text-muted">
                                    <i class="fas fa-phone-alt ms-1 small"></i>
                                    {{ $sale->customer->phone ?? 'بدون تليفون' }}
                                </p>
                            </div>
                        </div>
                        {{-- Date --}}
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-4 h-100 border text-start shadow-xs">
                                <small class="text-muted d-block mb-1 text-uppercase fw-bold"
                                    style="font-size: 0.65rem; letter-spacing: 0.5px">تاريخ الفاتورة</small>
                                <h6 class="fw-bold mb-0">
                                    <i class="far fa-calendar-alt ms-1 text-muted"></i>
                                    {{ $sale->sale_date->format('Y-m-d') }}
                                </h6>
                            </div>
                        </div>
                        {{-- Status --}}
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-4 h-100 border text-start shadow-xs">
                                <small class="text-muted d-block mb-1 text-uppercase fw-bold"
                                    style="font-size: 0.65rem; letter-spacing: 0.5px">حالة الدفع</small>
                                @if($sale->payment_status === 'paid')
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">تم
                                        الدفع بالكامل</span>
                                @elseif($sale->payment_status === 'partial')
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

                    {{-- Items Table --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle border">
                            <thead class="bg-light text-center small">
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>سعر الوحدة</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                    <tr>
                                        <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                                        <td class="fw-bold">{{ $item->product->name }}</td>
                                        <td class="text-center">
                                            {{ (float) $item->quantity }}
                                            <span class="text-muted small">{{ $item->unit_name ?? $item->product->unit }}</span>
                                        </td>
                                        <td class="text-center">{{ (float) $item->unit_price }}</td>
                                        <td class="text-center fw-bold">{{ (float) $item->total_price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="4" class="text-start fw-bold">إجمالي الفاتورة:</td>
                                    <td class="text-start fw-bold">
                                        {{ (float) ($sale->total_amount + $sale->discount) }} ج.م</td>
                                </tr>
                                @if($sale->discount > 0)
                                    <tr>
                                        <td colspan="4" class="text-start fw-bold text-danger">الخصم:</td>
                                        <td class="text-start fw-bold text-danger">- {{ (float) $sale->discount }} ج.م
                                        </td>
                                    </tr>
                                @endif
                                <tr class="table-info">
                                    <td colspan="4" class="text-start fw-bold fs-5">الصافي:</td>
                                    <td class="text-start fw-bold fs-5">{{ (float) $sale->total_amount }} ج.م</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-start fw-bold">المسدد من العميل:</td>
                                    <td class="text-start fw-bold text-success">{{ (float) $sale->paid_amount }}
                                        ج.م</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-start fw-bold">المتبقي على العميل:</td>
                                    <td class="text-start fw-bold text-danger">
                                        {{ (float) $sale->remaining_amount }} ج.م</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($sale->notes)
                        <div class="mt-4 p-3 bg-light rounded small border text-start">
                            <span class="fw-bold text-muted small d-block mb-1">ملاحظات:</span>
                            <p class="mb-0 text-dark">{{ $sale->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-4 d-flex justify-content-center gap-2 d-print-none mb-5">
                <a href="{{ route('sales.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-arrow-right me-2"></i> العودة لسجل المبيعات
                </a>
                <button onclick="window.print()" class="btn btn-green px-4">
                    <i class="fas fa-print me-2"></i> طباعة الفاتورة
                </button>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .shadow-xs {
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .bg-success-soft {
                background-color: #e8f5e9;
            }

            .bg-warning-soft {
                background-color: #fff8e1;
            }

            .bg-danger-soft {
                background-color: #ffebee;
            }

            .bg-info-soft {
                background-color: #e3f2fd;
            }

            @media print {
                body {
                    background: #fff !important;
                    margin: 0;
                    padding: 0;
                }

                .top-header,
                .sidebar,
                .btn,
                .d-print-none,
                nav,
                footer {
                    display: none !important;
                }

                .main-wrapper,
                .page-content,
                .container,
                .container-fluid {
                    margin: 0 !important;
                    padding: 0 !important;
                    max-width: 100% !important;
                }

                #printable-area {
                    width: 100% !important;
                    margin: 0 !important;
                }

                .content-card {
                    border: none !important;
                    box-shadow: none !important;
                    width: 100% !important;
                }

                .border {
                    border: 1px solid #dee2e6 !important;
                }

                @page {
                    size: auto;
                    margin: 10mm;
                }
            }
        </style>
    @endpush
@endsection