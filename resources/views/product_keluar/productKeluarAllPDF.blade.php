<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('main.product_out_report') }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", "Trebuchet MS", Arial, Helvetica, sans-serif;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .report-header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 15px;
        }

        .report-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .report-subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .report-date {
            font-style: sans-serif;
            color: #666;
            font-size: 14px;
        }

        #product-out {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        #product-out td, #product-out th {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        #product-out tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        #product-out th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
            font-style: sans-serif;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .company-logo {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
<div class="report-header">
    @if(config('app.logo'))
        <img src="{{ config('app.logo') }}" class="company-logo" alt="Company Logo">
    @endif
    <div class="report-title">{{ __('main.product_out_report') }}</div>
    <div class="report-date">{{ __('main.generated_on') }}: {{ now()->format('F j, Y') }}</div>
</div>

<table id="product-out">
    <thead>
    <tr>
        <th>{{ __('main.id') }}</th>
        <th>{{ __('main.product') }}</th>
        <th>{{ __('main.customer') }}</th>
        <th>{{ __('main.quantity') }}</th>
        <th>{{ __('main.date') }}</th>z
    </tr>
    </thead>
    <tbody>
    @foreach($product_keluar as $index => $p)
        @if($index > 0 && $index % 25 == 0)
    </tbody>
</table>
<div class="page-break"></div>
<table id="product-out">
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
    @endif
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
        <td>{{ number_format($p->qty) }}</td>
        <td>{{ \Carbon\Carbon::parse($p->date)->format('M d, Y') }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    {{ __('main.total_records') }}: {{ count($product_keluar) }} |
    {{ __('main.page') }} <span class="page-number"></span>
</div>

<script type="text/php">
    if (isset($pdf)) {
        $text = "{{ __('main.page') }} {PAGE_NUM} {{ __('main.of') }} {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("DejaVu Sans");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</body>
</html>