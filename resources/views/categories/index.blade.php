@extends('layouts.app')
@section('title', 'التصنيفات')
@section('page-title', 'التصنيفات')
@section('page-subtitle', 'إدارة تصنيفات المنتجات')

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <h6><i class="fas fa-tags me-2" style="color:var(--green-main)"></i> قائمة التصنيفات</h6>
            <a href="{{ route('categories.create') }}" class="btn btn-green btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> إضافة تصنيف
            </a>
        </div>

        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم التصنيف</th>
                        <th>عدد المنتجات</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $cat->name }}</td>
                            <td><span class="badge bg-secondary rounded-pill">{{ $cat->products_count }}</span></td>
                            <td>
                                @if($cat->is_active)
                                    <span class="badge badge-paid rounded-pill px-3">فعّال</span>
                                @else
                                    <span class="badge badge-unpaid rounded-pill px-3">متوقف</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('categories.edit', $cat) }}"
                                        class="btn btn-sm btn-warning rounded-pill px-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="delete-form"
                                        data-confirm="هل تريد حذف هذا التصنيف؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-pill px-3">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">لا توجد تصنيفات بعد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="mt-3">{{ $categories->links() }}</div>
        @endif
    </div>
@endsection