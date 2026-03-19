@extends('layouts.app')
@section('title', 'المنتجات')
@section('page-title', 'المنتجات')
@section('page-subtitle', 'إدارة مخزون المنتجات')

@section('content')
    {{-- فلتر البحث --}}
    <div class="content-card mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-sm-5">
                <input type="text" name="search" class="form-control" placeholder="🔍 بحث بالاسم أو الباركود..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-6 col-sm-3">
                <select name="category_id" class="form-select">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-2">
                <div class="form-check mt-1">
                    <input type="checkbox" name="low_stock" id="low_stock" class="form-check-input" {{ request('low_stock') ? 'checked' : '' }}>
                    <label for="low_stock" class="form-check-label small">منخفض فقط</label>
                </div>
            </div>
            <div class="col-12 col-sm-2 d-flex gap-2">
                <button type="submit" class="btn btn-green flex-fill">بحث</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">×</a>
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h6><i class="fas fa-boxes me-2" style="color:var(--green-main)"></i> قائمة المنتجات ({{ $products->total() }})
            </h6>
            <a href="{{ route('products.create') }}" class="btn btn-green btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> إضافة منتج
            </a>
        </div>

        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>التصنيف</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>المخزون</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-bold">{{ $product->name }}</span>
                                @if($product->barcode)
                                    <br><small class="text-muted">{{ $product->barcode }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark">{{ $product->category->name ?? '-' }}</span></td>
                            <td>{{ (float) $product->purchase_price }}</td>
                            <td>
                                <div>{{ (float) $product->sale_price }} <small
                                        class="text-muted">{{ $product->unit }}</small></div>
                                @if($product->has_sub_units)
                                    <div class="text-success small" style="font-size: 0.8rem;">
                                        {{ (float) $product->sub_unit_sale_price }}
                                        <small>{{ $product->sub_unit_name }}</small>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($product->isLowStock())
                                    <span class="badge badge-unpaid fw-bold">⚠️ {{ (float) $product->stock }}
                                        {{ $product->unit }}</span>
                                @else
                                    <span class="badge badge-paid">{{ (float) $product->stock }} {{ $product->unit }}</span>
                                @endif
                                @if($product->has_sub_units)
                                    <div class="text-muted small" style="font-size: 0.75rem;">
                                        = {{ (float) ($product->stock * $product->items_per_unit) }} {{ $product->sub_unit_name }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'badge-paid' : 'badge-unpaid' }} rounded-pill px-2">
                                    {{ $product->is_active ? 'فعّال' : 'متوقف' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('products.edit', $product) }}"
                                        class="btn btn-sm btn-warning rounded-pill px-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="delete-form"
                                        data-confirm="هل تريد حذف هذا المنتج؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-pill px-2"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">لا توجد منتجات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection