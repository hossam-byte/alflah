@extends('layouts.app')
@section('title', 'الموردون')
@section('page-title', 'الموردون')
@section('page-subtitle', 'إدارة الموردين والشركات الموردة')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <h6 class="mb-0"><i class="fas fa-truck me-2" style="color:var(--green-main)"></i> قائمة الموردين
                    ({{ $suppliers->total() }})</h6>
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="بحث..."
                        value="{{ request('search') }}" style="width:160px">
                    <button class="btn btn-sm btn-green">بحث</button>
                    @if(request('search'))<a href="{{ route('suppliers.index') }}"
                    class="btn btn-sm btn-secondary">×</a>@endif
                </form>
            </div>
            <a href="{{ route('suppliers.create') }}" class="btn btn-green btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> إضافة مورد
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
                    @forelse($suppliers as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $s->name }}</td>
                            <td>{{ $s->phone ?? '-' }}</td>
                            <td><small>{{ $s->address ?? '-' }}</small></td>
                            <td>
                                @if($s->balance > 0)
                                    <span class="text-danger fw-bold">{{ number_format($s->balance, 2) }} ج.م</span>
                                @else
                                    <span class="text-muted">٠</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('suppliers.show', $s) }}" class="btn btn-sm btn-info rounded-pill px-2"><i
                                            class="fas fa-eye"></i></a>
                                    <a href="{{ route('suppliers.edit', $s) }}"
                                        class="btn btn-sm btn-warning rounded-pill px-2"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('suppliers.destroy', $s) }}" method="POST" class="delete-form"
                                        data-confirm="هل تريد حذف هذا المورد؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-pill px-2"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">لا يوجد موردون</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
            <div class="mt-3">{{ $suppliers->links() }}</div>
        @endif
    </div>
@endsection