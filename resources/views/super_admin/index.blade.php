@extends('layouts.app')

@section('title', 'إدارة المحلات المشتركة')
@section('page-title', 'إدارة المحلات المشتركة')

@section('content')
    <!-- إحصائيات المطور -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card card-blue">
                <div class="icon"><i class="fas fa-store"></i></div>
                <div class="value">{{ $stats['total'] }}</div>
                <div class="label">إجمالي المحلات</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card card-green">
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <div class="value">{{ $stats['active'] }}</div>
                <div class="label">محلات نشطة</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card card-red">
                <div class="icon"><i class="fas fa-pause-circle"></i></div>
                <div class="value">{{ $stats['inactive'] }}</div>
                <div class="label">محلات متوقفة</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card card-gold">
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="value">{{ $stats['expiring_soon'] }}</div>
                <div class="label">اشتراكات تقترب من الانتهاء</div>
            </div>
        </div>
    </div>

    @if($stats['expiring_soon'] > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning border-start border-4 border-warning shadow-sm">
                    <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> اشتراكات تنتهي قريباً (خلال 7
                        أيام):</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($shops as $shop)
                            @if($shop->subscription_end && $shop->subscription_end->isAfter(now()->subDay()) && $shop->subscription_end->isBefore(now()->addDays(8)))
                                <span class="badge bg-white text-dark border p-2">
                                    <i class="fas fa-store me-1"></i> {{ $shop->name }}:
                                    @php
                                        $diff = ceil(now()->startOfDay()->diffInDays($shop->subscription_end, false));
                                    @endphp
                                    @if($diff > 0)
                                        ينتهي في {{ $shop->subscription_end->format('Y-m-d') }} (باقي {{ $diff }} يوم)
                                    @else
                                        ينتهي اليوم!
                                    @endif
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <div class="content-card-header">
                    <h6><i class="fas fa-microchip me-2"></i> قائمة المحلات المشتركة (FourSW Client Management)</h6>
                </div>

                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>المحل</th>
                                <th>المالك / الهاتف</th>
                                <th>نهاية الاشتراك</th>
                                <th>الحالة</th>
                                <th>الألوان</th>
                                <th>التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shops as $shop)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $shop->name }}</div>
                                        <small class="text-muted">{{ $shop->slug }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $shop->owner_name }}</div>
                                        <small class="text-muted"><i class="fas fa-phone me-1"></i> {{ $shop->phone }}</small>
                                    </td>
                                    <td>
                                        @if($shop->subscription_end)
                                            <div
                                                class="{{ $shop->subscription_end->isPast() ? 'text-danger' : 'text-success' }} fw-bold">
                                                {{ $shop->subscription_end->format('Y-m-d') }}
                                            </div>
                                            @php
                                                $days = now()->diffInDays($shop->subscription_end, false);
                                            @endphp
                                            <small class="text-muted">
                                                @if($days > 0)
                                                    باقي {{ ceil($days) }} يوم
                                                @elseif($days < 0)
                                                    منتهي منذ {{ abs(floor($days)) }} يوم
                                                @else
                                                    ينتهي اليوم
                                                @endif
                                            </small>
                                        @else
                                            <span class="badge bg-secondary">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($shop->is_active && ($shop->subscription_end ? !$shop->subscription_end->isPast() : true))
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">متوقف / منتهي</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <span class="rounded-circle shadow-sm"
                                                style="width:15px;height:15px;background:{{ $shop->primary_color }};border:1px solid #ddd"
                                                title="الأساسي"></span>
                                            <span class="rounded-circle shadow-sm"
                                                style="width:15px;height:15px;background:{{ $shop->accent_color }};border:1px solid #ddd"
                                                title="الذهبي"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('super_admin.edit', $shop) }}" class="btn btn-outline-primary"
                                                title="تعديل"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('super_admin.toggle', $shop) }}" method="POST"
                                                style="display:inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn {{ $shop->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                    title="{{ $shop->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas {{ $shop->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection