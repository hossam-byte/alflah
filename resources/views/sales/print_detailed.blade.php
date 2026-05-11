<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة مفصلة - {{ $sale->invoice_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background: #f4f7f6;
            padding: 20px;
            color: #333;
        }
        .invoice-card {
            background: #fff;
            max-width: 850px;
            margin: 0 auto;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #eee;
            padding-bottom: 30px;
            margin-bottom: 30px;
        }
        .shop-info h2 { margin: 0; color: #2d3436; font-size: 28px; }
        .shop-info p { margin: 5px 0; color: #636e72; font-size: 14px; }
        .invoice-meta { text-align: left; }
        .invoice-meta h1 { margin: 0; color: #2ecc71; font-size: 24px; text-transform: uppercase; }
        .invoice-meta p { margin: 5px 0; font-weight: bold; }

        .client-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }
        .client-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            border-right: 4px solid #2ecc71;
        }
        .client-box h4 { margin: 0 0 10px 0; color: #7f8c8d; font-size: 12px; text-transform: uppercase; }
        .client-box p { margin: 2px 0; font-weight: bold; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #2ecc71;
            color: #fff;
            text-align: right;
            padding: 12px 15px;
            font-size: 14px;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 15px;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }

        .summary {
            display: flex;
            justify-content: flex-end;
        }
        .summary-table {
            width: 250px;
        }
        .summary-table tr td { border: none; padding: 5px 0; }
        .summary-table tr td:last-child { text-align: left; font-weight: bold; }
        .total-row { font-size: 18px; color: #2ecc71; }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .invoice-card { box-shadow: none; border: none; max-width: 100%; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #2ecc71; color: #fff; border: none; padding: 10px 25px; border-radius: 4px; cursor: pointer; font-family: 'Cairo'; font-weight: bold;">
            <i class="fas fa-print"></i> طباعة الفاتورة المفصلة
        </button>
        <a href="{{ route('sales.show', $sale) }}" style="text-decoration: none; color: #666; margin-right: 15px;">إلغاء</a>
    </div>

    <div class="invoice-card">
        <div class="header">
            <div class="shop-info">
                <h2>{{ $sale->shop->name }}</h2>
                @if($sale->shop->address) <p><i class="fas fa-map-marker-alt"></i> {{ $sale->shop->address }}</p> @endif
                @if($sale->shop->phone) <p><i class="fas fa-phone"></i> {{ $sale->shop->phone }}</p> @endif
            </div>
            <div class="invoice-meta">
                <h1>فاتورة بيع</h1>
                <p>الرقم: #{{ $sale->invoice_number }}</p>
                <p>التاريخ: {{ $sale->sale_date->format('Y-m-d') }}</p>
            </div>
        </div>

        <div class="client-section">
            <div class="client-box">
                <h4>العميل</h4>
                <p>{{ $sale->customer->name ?? 'عميل كاش' }}</p>
                <p>{{ $sale->customer->phone ?? '---' }}</p>
            </div>
            <div class="client-box" style="border-right-color: #3498db;">
                <h4>حالة الدفع</h4>
                <p>
                    @if($sale->payment_status === 'paid') تم الدفع بالكامل
                    @elseif($sale->payment_status === 'partial') دفع جزئي
                    @else بانتظار الدفع @endif
                </p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">المنتج</th>
                    <th class="text-center">الكمية</th>
                    <th class="text-center">سعر الوحدة</th>
                    <th class="text-left">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">{{ (float) $item->quantity }} {{ $item->unit_name ?? $item->product->unit }}</td>
                        <td class="text-center">{{ (float) $item->unit_price }} ج.م</td>
                        <td class="text-left">{{ (float) $item->total_price }} ج.م</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <table class="summary-table">
                <tr>
                    <td>الإجمالي:</td>
                    <td>{{ (float) ($sale->total_amount + $sale->discount) }} ج.م</td>
                </tr>
                @if($sale->discount > 0)
                    <tr style="color: #e74c3c;">
                        <td>الخصم:</td>
                        <td>- {{ (float) $sale->discount }} ج.م</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td>الصافي:</td>
                    <td>{{ (float) $sale->total_amount }} ج.م</td>
                </tr>
                <tr>
                    <td>المدفوع:</td>
                    <td>{{ (float) $sale->paid_amount }} ج.م</td>
                </tr>
                @if($sale->remaining_amount > 0)
                    <tr style="color: #e67e22;">
                        <td>المتبقي:</td>
                        <td>{{ (float) $sale->remaining_amount }} ج.م</td>
                    </tr>
                @endif
            </table>
        </div>

        @if($sale->notes)
            <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 15px;">
                <h5 style="margin: 0 0 5px 0; color: #7f8c8d;">ملاحظات:</h5>
                <p style="margin: 0; font-size: 14px;">{{ $sale->notes }}</p>
            </div>
        @endif

        <div class="footer">
            <p>شكراً لتعاملكم معنا</p>
            <p style="font-size: 10px;">صدرت بواسطة {{ config('app.name') }}</p>
        </div>
    </div>

    <script>
        // Auto print on load if requested
        if (window.location.search.includes('autoprint=1')) {
            window.onload = function() {
                window.print();
            }
        }
    </script>
</body>
</html>
