@extends('layouts.app')
@section('title', 'فاتورة مبيعات')
@section('page-title', 'إنشاء فاتورة بيع')

@section('content')
    <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
        @csrf
        <div class="row g-3">
            {{-- بيانات الفاتورة --}}
            <div class="col-12 col-lg-4">
                <div class="content-card">
                    <div class="content-card-header">
                        <h6><i class="fas fa-cash-register me-2"></i> بيانات البيع</h6>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الفاتورة</label>
                        <input type="text" name="invoice_number" class="form-control" value="{{ $invoiceNumber }}" readonly
                            style="background:#f9f9f9">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">العميل</label>
                        <select name="customer_id" class="form-select">
                            <option value="">-- عميل نقدي (كاش) --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ البيع <span class="text-danger">*</span></label>
                        <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}" required>
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
                        <span>الإجمالي:</span>
                        <span id="subtotal" class="fw-bold">0.00</span>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">خصم للفاتورة (ج.م)</label>
                        <input type="number" step="0.01" name="discount" id="discount" class="form-control form-control-sm"
                            value="0">
                    </div>
                    <div class="alert alert-success d-flex justify-content-between p-2 mb-2">
                        <span class="fw-bold text-success-emphasis">الصافي المطلوب:</span>
                        <span id="grand-total" class="fw-bold fs-5 text-success-emphasis">0.00</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">المبلغ المدفوع</label>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount"
                            class="form-control form-control-sm" placeholder="اتركه فارغاً للدفع الكامل">
                    </div>
                    <button type="submit" class="btn btn-green w-100 py-3 fw-bold">
                        <i class="fas fa-print me-2"></i> حفظ وطباعة الفاتورة
                    </button>
                </div>
            </div>

            {{-- البنود --}}
            <div class="col-12 col-lg-8">
                <div class="content-card">
                    <div class="content-card-header">
                        <h6><i class="fas fa-shopping-basket me-2"></i> بنود المبيعات</h6>
                        <button type="button" class="btn btn-sm btn-green px-3 rounded-pill" onclick="addRow()">
                            <i class="fas fa-plus me-1"></i> إضافة منتج
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="items-table">
                            <thead class="bg-light">
                                <tr class="small text-center">
                                    <th style="width:30%">المنتج</th>
                                    <th style="width:15%">الوحدة</th>
                                    <th style="width:12%">المتوفر</th>
                                    <th style="width:12%">الكمية</th>
                                    <th style="width:14%">سعر الوحدة</th>
                                    <th style="width:12%">الإجمالي</th>
                                    <th style="width:5%">×</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div id="no-items-msg" class="text-center py-4 text-muted small">
                        <i class="fas fa-cash-register d-block fs-2 mb-2 opacity-25"></i>
                        ابدأ بإضافة المنتجات للفاتورة
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
                        <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}"
                            data-stock="{{ $product->stock }}" data-has-sub-units="{{ $product->has_sub_units ? 1 : 0 }}"
                            data-main-unit-name="{{ $product->unit }}" data-sub-unit-name="{{ $product->sub_unit_name }}"
                            data-items-per-unit="{{ $product->items_per_unit }}"
                            data-sub-unit-price="{{ $product->sub_unit_sale_price }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="items[INDEX][is_sub_unit]" class="form-select form-select-sm unit-select"
                    onchange="toggleUnit(this)">
                    <option value="0">الوحدة الأساسية</option>
                </select>
            </td>
            <td class="text-center small text-muted"><span class="stock-display">0</span></td>
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
                if (document.querySelectorAll('.item-row').length === 0) document.getElementById('no-items-msg').classList.remove('d-none');
                calculateGrandTotal();
            }
            function updateRowData(select) {
                const option = select.options[select.selectedIndex];
                const row = select.closest('tr');
                const unitSelect = row.querySelector('.unit-select');

                if (option.value) {
                    const hasSubUnits = option.dataset.hasSubUnits == "1";

                    // تحديث قائمة الوحدات
                    unitSelect.innerHTML = `<option value="0">${option.dataset.mainUnitName}</option>`;
                    if (hasSubUnits) {
                        unitSelect.innerHTML += `<option value="1">${option.dataset.subUnitName}</option>`;
                    }

                    // تعيين السعر والوحدة الافتراضية
                    row.querySelector('.price-input').value = option.dataset.price;
                    row.querySelector('.stock-display').textContent = option.dataset.stock + " " + option.dataset.mainUnitName;

                    calculateRow(row.querySelector('.qty-input'));
                }
            }

            function toggleUnit(unitSelect) {
                const row = unitSelect.closest('tr');
                const productSelect = row.querySelector('.product-select');
                const option = productSelect.options[productSelect.selectedIndex];

                const isSubUnit = unitSelect.value == "1";
                if (isSubUnit) {
                    row.querySelector('.price-input').value = option.dataset.subUnitPrice;
                    const subStock = (parseFloat(option.dataset.stock) * parseFloat(option.dataset.itemsPerUnit)).toFixed(2);
                    row.querySelector('.stock-display').textContent = subStock + " " + option.dataset.subUnitName;
                } else {
                    row.querySelector('.price-input').value = option.dataset.price;
                    row.querySelector('.stock-display').textContent = option.dataset.stock + " " + option.dataset.mainUnitName;
                }

                calculateRow(row.querySelector('.qty-input'));
            }

            function calculateRow(input) {
                const row = input.closest('tr');
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                row.querySelector('.row-total').textContent = (qty * price).toFixed(2);
                calculateGrandTotal();
            }
            function calculateGrandTotal() {
                let subtotal = 0;
                document.querySelectorAll('.row-total').forEach(td => subtotal += parseFloat(td.textContent) || 0);
                const discount = parseFloat(document.getElementById('discount').value) || 0;
                document.getElementById('subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('grand-total').textContent = (subtotal - discount).toFixed(2);
            }
            document.getElementById('discount').oninput = calculateGrandTotal;
            addRow();
        </script>
    @endpush
@endsection