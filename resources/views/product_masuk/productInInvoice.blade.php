<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('main.invoice') }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
            line-height: 1.2;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }
        .invoice-box {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            flex-direction: {{ app()->getLocale() == 'ar' ? 'row-reverse' : 'row' }};
        }
        .company-logo {
            height: 80px;
        }
        .invoice-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .parentheses {
            letter-spacing: 30px; /* Adjust spacing as needed */
        }
        .info-table td {
            padding: 3px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 60px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            flex-direction: {{ app()->getLocale() == 'ar' ? 'row-reverse' : 'row' }};
        }
        .signature-box {
            width: 100%;
        }
        .left-signature {
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }
        .right-signature {
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 25px 0 3px;
            width: 80%;
        }
        .footer {
            font-size: 8px;
            text-align: center;
            margin-top: 5px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>
<div class="invoice-box">
    <div class="header">
        <div>
            <img src="{{ asset('logo-dummy.png') }}" class="company-logo">
            <div style="margin-top: 2px;">
                <div>{{ config('app.name', 'Your Company') }}</div>
                <div>{{ __('main.company_address') }}</div>
                <div>{{ __('main.company_city') }}</div>
            </div>
        </div>
        <div>
            <div class="invoice-title">{{ __('main.product_in_invoice') }}</div>
            <div style="text-align: center;">
                <div>{{ __('main.date') }}: {{ date('m/d/Y') }}</div>
                <div>{{ __('main.invoice_number') }}: PIN-{{ now()->format('YmdHis') }}</div>
            </div>
        </div>
    </div>

    @if($product_masuk->isNotEmpty())
        @php
            $firstProduct = $product_masuk->first();
            $supplier = $firstProduct->supplier; // Assuming all products have same supplier
        @endphp

        <table class="info-table">
            <tr>
                <td class="info-label">{{ __('main.supplier') }}:</td>
                <td>
                    <strong>{{ __('main.name') }}: {{ $supplier->name ?? 'N/A' }}</strong><br>
                    {{ __('main.phone') }}: {{ $supplier->phone ?? 'N/A' }}<br>
                    {{ __('main.address') }}: {{ $supplier->address ?? 'N/A' }}<br>
                    {{ __('main.email') }}: {{ $supplier->email ?? 'N/A' }}
                </td>
                <td class="info-label">{{ __('main.process_date') }}:</td>
                <td>{{ $firstProduct->date ?? now()->format('m/d/Y') }}</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
            <tr>
                <th width="10%" style="text-align: center;">#</th>
                <th width="20%" style="text-align: center;">{{ __('main.product_id') }}</th>
                <th width="50%" style="text-align: center;">{{ __('main.name') }}</th>
                <th width="20%" style="text-align: center;">{{ __('main.quantity') }}</th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalAmount = 0;
            @endphp

            @foreach($product_masuk as $index => $product)
                @php
                    $amount = $product->qty * $product->price;
                    $totalAmount += $amount;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $product->id }}</td>
                    <td style="text-align: center;">
                        @if(app()->getLocale() === 'ar')
                            {{ $product->product->name_ar ?? $product->product->name_en ?? __('main.N/A') }}
                        @else
                            {{ $product->product->name_en ?? $product->product->name_ar ?? __('main.N/A') }}
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $product->qty }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="signature-container">
            <div class="signature-box left-signature">
                <div>{{ __('main.supplier_signature') }}</div>
                <div>{{ $supplier->name ?? 'N/A' }}<span class="parentheses">(  )</span></div>
            </div>
            <div class="signature-box right-signature">
                <div>{{ __('main.authorized_signature') }}</div>
                <div>{{ auth()->user()->name }}<span class="parentheses">(  )</span></div>
            </div>
        </div>

        <div class="footer">
            {{ __('main.thank_you_message') }} • {{ __('main.payment_terms') }}
        </div>
    @else
        <div style="text-align: center; padding: 20px; color: #666;">
            {{ __('main.no_products_found') }}
        </div>
    @endif
</div>
</body>
</html>