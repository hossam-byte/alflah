<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة رقم {{ $sale->invoice_number }}</title>
    <style>
        @font-face {
            font-family: 'Cairo';
            src: url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 20px;
            color: #333;
            direction: rtl;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #eee;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }

        .header p {
            margin: 5px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .info-box {
            flex: 1;
        }

        .info-box div {
            margin-bottom: 5px;
        }

        .info-box span {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .total-section {
            margin-top: 20px;
            text-align: left;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 5px;
        }

        .total-label {
            width: 150px;
            font-weight: bold;
            text-align: right;
            padding-right: 10px;
        }

        .total-value {
            width: 100px;
            text-align: center;
            border: 1px solid #333;
            padding: 5px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        @media print {
            body {
                padding: 0;
            }

            .invoice-container {
                border: none;
                max-width: 100%;
            }

            .no-print {
                display: none;
            }
        }

        .btn-print {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center;">
        <button onclick="window.print()" class="btn-print">طباعة الفاتورة</button>
        <a href="{{ route('sales.show', $sale) }}"
            style="text-decoration: none; color: #666; margin-right: 10px;">إلغاء</a>
    </div>

    <div class="invoice-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">اسم المنتج</th>
                    <th style="width: 25%;">الكمية</th>
                    <th style="width: 25%;">السعر</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>
                            {{ (float) $item->quantity }}
                            <small>{{ $item->unit_name ?? $item->product->unit }}</small>
                        </td>
                        <td>{{ (float) $item->total_price }} ج.م</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        // Auto print on load if requested
        if (window.location.search.includes('autoprint=1')) {
            window.onload = function () {
                window.print();
            }
        }
    </script>
</body>

</html>