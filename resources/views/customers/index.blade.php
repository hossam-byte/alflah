@extends('layouts.app')
@section('title', 'العملاء')
@section('page-title', 'العملاء')
@section('page-subtitle', 'إدارة بيانات العملاء وحساباتهم')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <h6 class="mb-0"><i class="fas fa-users me-2" style="color:var(--green-main)"></i> قائمة العملاء
                    ({{ $customers->total() }})</h6>
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="بحث باسم العميل أو الهاتف..." value="{{ request('search') }}" style="width:200px">
                    <button class="btn btn-sm btn-green">بحث</button>
                    @if(request('search'))<a href="{{ route('customers.index') }}"
                    class="btn btn-sm btn-secondary">×</a>@endif
                </form>
            </div>
            <a href="{{ route('customers.create') }}" class="btn btn-green btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> إضافة عميل
            </a>
        </div>

        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>العنوان</th>
                        <th>الرصيد</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $c->name }}</td>
                            <td>{{ $c->phone ?? '-' }}</td>
                            <td><small>{{ $c->address ?? '-' }}</small></td>
                            <td>
                                @if($c->balance > 0)
                                    <span class="text-success fw-bold">{{ number_format($c->balance, 2) }} ج.م</span>
                                @elseif($c->balance < 0)
                                    <span class="text-danger fw-bold">{{ number_format(abs($c->balance), 2) }} ج.م</span>
                                @else
                                    <span class="text-muted">٠</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('customers.show', $c) }}" class="btn btn-sm btn-info rounded-pill px-2"><i
                                            class="fas fa-eye"></i></a>
                                    <a href="{{ route('customers.edit', $c) }}"
                                        class="btn btn-sm btn-warning rounded-pill px-2"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('customers.destroy', $c) }}" method="POST" class="delete-form"
                                        data-confirm="هل تريد حذف هذا العميل؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-pill px-2"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">لا يوجد عملاء مضافين بعد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="mt-3">{{ $customers->links() }}</div>
        @endif
    </div>
@endsection