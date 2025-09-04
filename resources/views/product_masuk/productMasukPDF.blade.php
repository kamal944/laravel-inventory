<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('main.invoice') }} {{ $product_masuk->id }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
            line-height: 1.2;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }
        .parentheses {
            letter-spacing: 30px;
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
            <div class="invoice-title">{{ __('main.invoice') }}</div>
            <div style="text-align: center;">
                <div>{{ __('main.date') }}: {{ date('m/d/Y') }}</div>
                <div>{{ __('main.invoice_number') }}: {{ $product_masuk->id }}</div>
            </div>
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">{{ __('main.bill_to') }}:</td>
            <td>
                <strong>{{ __('main.name') }}: {{ $product_masuk->supplier->name }}</strong><br>
                {{ __('main.phone') }}: {{ $product_masuk->supplier->phone }}<br>
                {{ __('main.address') }}: {{ $product_masuk->supplier->address }}<br>
                {{ __('main.email') }}: {{ $product_masuk->supplier->email }}
            </td>
            <td class="info-label">{{ __('main.process_date') }}:</td>
            <td>{{ $product_masuk->date }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
        <tr>
            <th width="10%">{{ __('main.id') }}</th>
            <th width="60%">{{ __('main.name') }}</th>
            <th width="30%">{{ __('main.quantity') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $product_masuk->id }}</td>
            <td>
                @if(app()->getLocale() === 'ar')
                    {{ $product_masuk->product->name_ar ?? $product_masuk->product->name_en ?? __('main.N/A') }}
                @else
                    {{ $product_masuk->product->name_en ?? $product_masuk->product->name_ar ?? __('main.N/A') }}
                @endif
            </td>
            <td>{{ $product_masuk->qty }}</td>
        </tr>
        </tbody>
    </table>

    <div class="signature-container">
        <div class="signature-box left-signature">
            <div>{{ __('main.supplier_signature') }}</div>
            <div>{{ $product_masuk->supplier->name }}<span class="parentheses">(  )</span></div>
        </div>
        <div class="signature-box right-signature">
            <div>{{ __('main.authorized_signature') }}</div>
            <div>{{ auth()->user()->name }}<span class="parentheses">(  )</span></div>
        </div>
    </div>

    <div class="footer">
        {{ __('main.thank_you_message') }} • {{ __('main.payment_terms') }}
    </div>
</div>
</body>
</html>