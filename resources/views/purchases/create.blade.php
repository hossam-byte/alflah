@extends('layouts.app')
@section('title', 'فاتورة شراء')
@section('page-title', 'إنشاء فاتورة شراء')

@section('content')
    <form action="{{ route('purchases.store') }}" method="POST" id="purchase-form">
        @csrf
        <div class="row g-3">
            {{-- بيانات الفاتورة الأساسية --}}
            <div class="col-12 col-lg-4">
                <div class="content-card">
                    <div class="content-card-header">
                        <h6><i class="fas fa-file-invoice me-2"></i> بيانات الفاتورة</h6>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الفاتورة</label>
                        <input type="text" name="invoice_number" class="form-control" value="{{ $invoiceNumber }}" readonly
                            style="background:#f9f9f9">
                    </div>
                    <div class="mb-3">
                        @php $reqSupplierId = request('supplier_id'); @endphp
                        <label class="form-label">المورد <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select" required @if($reqSupplierId) disabled @endif>
                            <option value="">-- اختر مورد --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected($reqSupplierId == $supplier->id)>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @if($reqSupplierId)
                            <input type="hidden" name="supplier_id" value="{{ $reqSupplierId }}">
                            <small class="text-muted"><i class="fas fa-lock me-1"></i> تم تحديد المورد تلقائياً من ملفه
                                الشخصي</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ الشراء <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="content-card mt-3">
                    <div class="content-card-header">
                        <h6><i class="fas fa-calculator me-2"></i> الحسابات</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>الإجمالي قبل الخصم:</span>
                        <span id="subtotal" class="fw-bold">0.00</span>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">خصم للفاتورة (ج.م)</label>
                        <input type="number" step="0.01" name="discount" id="discount" class="form-control form-control-sm"
                            value="0">
                    </div>
                    <div class="alert alert-success d-flex justify-content-between p-2 mb-2">
                        <span class="fw-bold">الصافي النهائي:</span>
                        <span id="grand-total" class="fw-bold fs-5">0.00</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">المبلغ المدفوع</label>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount"
                            class="form-control form-control-sm" placeholder="اتركه فارغاً للدفع الكامل">
                    </div>
                    <button type="submit" class="btn btn-green w-100 py-2 fs-6">
                        <i class="fas fa-check-circle me-2"></i> حفظ الفاتورة
                    </button>
                </div>
            </div>

            {{-- بنود الفاتورة --}}
            <div class="col-12 col-lg-8">
                <div class="content-card">
                    <div class="content-card-header">
                        <h6><i class="fas fa-list me-2"></i> بنود المشتريات</h6>
                        <button type="button" class="btn btn-sm btn-green px-3 rounded-pill" onclick="addRow()">
                            <i class="fas fa-plus me-1"></i> إضافة بند
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="items-table">
                            <thead class="bg-light">
                                <tr class="small text-center">
                                    <th style="width:35%">المنتج</th>
                                    <th style="width:20%">الكمية</th>
                                    <th style="width:20%">سعر الشراء</th>
                                    <th style="width:20%">الإجمالي</th>
                                    <th style="width:5%">×</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be added here -->
                            </tbody>
                        </table>
                    </div>

                    <div id="no-items-msg" class="text-center py-4 text-muted small">
                        <i class="fas fa-shopping-basket d-block fs-3 mb-2 opacity-25"></i>
                        لا توجد بنود في الفاتورة، اضغط على "إضافة بند" للبدء
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="row-template">
        <tr class="item-row">
            <td>
                <select name="items[INDEX][product_id]" class="form-select form-select-sm product-select" required
                    onchange="updateRowData(this)">
                    <option value="">اختر منتج</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}">{{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" step="0.001" name="items[INDEX][quantity]"
                    class="form-control form-control-sm text-center qty-input" value="1" required
                    oninput="calculateRow(this)">
            </td>
            <td>
                <input type="number" step="0.01" name="items[INDEX][unit_price]"
                    class="form-control form-control-sm text-center price-input" value="0" required
                    oninput="calculateRow(this)">
            </td>
            <td class="text-center fw-bold row-total">0.00</td>
            <td>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeRow(this)">
                    <i class="fas fa-times-circle fs-5"></i>
                </button>
            </td>
        </tr>
    </template>

    @push('scripts')
        <script>
            let rowIndex = 0;

            function addRow() {
                document.getElementById('no-items-msg').classList.add('d-none');
                const template = document.getElementById('row-template').innerHTML;
                const newRow = template.replace(/INDEX/g, rowIndex++);
                document.querySelector('#items-table tbody').insertAdjacentHTML('beforeend', newRow);
            }

            function removeRow(btn) {
                btn.closest('tr').remove();
                if (document.querySelectorAll('.item-row').length === 0) {
                    document.getElementById('no-items-msg').classList.remove('d-none');
                }
                calculateGrandTotal();
            }

            function updateRowData(select) {
                const option = select.options[select.selectedIndex];
                const row = select.closest('tr');
                if (option.value) {
                    row.querySelector('.price-input').value = option.dataset.price;
                    calculateRow(row.querySelector('.qty-input'));
                }
            }

            function calculateRow(input) {
                const row = input.closest('tr');
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const total = qty * price;
                row.querySelector('.row-total').textContent = total.toFixed(2);
                calculateGrandTotal();
            }

            function calculateGrandTotal() {
                let subtotal = 0;
                document.querySelectorAll('.row-total').forEach(td => {
                    subtotal += parseFloat(td.textContent) || 0;
                });

                const discount = parseFloat(document.getElementById('discount').value) || 0;
                const grandTotal = subtotal - discount;

                document.getElementById('subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
            }

            document.getElementById('discount').oninput = calculateGrandTotal;

            // البدء بصف واحد
            addRow();
        </script>
    @endpush
@endsection