@extends('layouts.app')
@section('title', 'المصروفات')
@section('page-title', 'المصروفات')
@section('page-subtitle', 'إدارة مصروفات المحل (إيجار، كهرباء، مرتبات، إلخ)')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card card-red shadow">
                <div class="icon"><i class="fas fa-receipt"></i></div>
                <div class="value">{{ (float) $totalAmount }}</div>
                <div class="label">إجمالي مصروفات الفلترة (ج.م)</div>
            </div>
        </div>
    </div>

    <div class="content-card mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label small">فلترة بالشهر</label>
                <input type="month" name="month" class="form-control" value="{{ request('month', now()->format('Y-m')) }}">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label small">الفئة</label>
                <select name="category" class="form-select">
                    <option value="">كل الفئات</option>
                    @foreach($expenseCategories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button class="btn btn-green flex-fill">تطبيق</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">إعادة تعيين</a>
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h6><i class="fas fa-list me-2" style="color:var(--green-main)"></i> سجل المصروفات</h6>
            <a href="{{ route('expenses.create') }}" class="btn btn-green btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> إضافة مصروف
            </a>
        </div>

        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>الفئة</th>
                        <th>المبلغ</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $e)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $e->title }}</td>
                            <td><span class="badge bg-light text-dark px-2">{{ $e->category ?? '-' }}</span></td>
                            <td class="text-danger fw-bold">{{ (float) $e->amount }} ج.م</td>
                            <td>{{ $e->expense_date->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('expenses.edit', $e) }}"
                                        class="btn btn-sm btn-warning rounded-pill px-2"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('expenses.destroy', $e) }}" method="POST" class="delete-form"
                                        data-confirm="هل تريد حذف هذا المصروف؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-pill px-2"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">لا توجد سجلات مصروفات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
@endsection