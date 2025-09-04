<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('main.product_out_export_title') }}</title>
    <style>
        body {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        #product-out {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        #product-out td, #product-out th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        #product-out tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #product-out th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #4CAF50;
            color: white;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="header">
    <h2>{{ __('main.product_out_report') }}</h2>
    <p>{{ __('main.generated_on') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
</div>

<table id="product-out" width="100%">
    <thead>
    <tr>
        <th>{{ __('main.id') }}</th>
        <th>{{ __('main.product') }}</th>
        <th>{{ __('main.customer') }}</th>
        <th>{{ __('main.quantity') }}</th>
        <th>{{ __('main.date') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($product_keluar as $p)
        <tr>
            <td>{{ $p->id }}</td>

            <td>
                @if(app()->getLocale() === 'ar')
                    {{ $p->product->name_ar ?? $p->product->name_en ?? __('main.N/A') }}
                @else
                    {{ $p->product->name_en ?? $p->product->name_ar ?? __('main.N/A') }}
                @endif
            </td>
            <td>{{ $p->customer->name }}</td>
            <td>{{ $p->qty }}</td>
            <td>{{ $p->date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    {{ __('main.report_footer') }}
</div>
</body>
</html>