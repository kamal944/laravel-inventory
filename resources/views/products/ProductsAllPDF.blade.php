<style>
    body {
        font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        margin: 20px;
        color: #333;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #4CAF50;
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .subtitle {
        font-size: 16px;
        color: #7f8c8d;
    }

    .print-date {
        text-align: right;
        font-size: 14px;
        color: #7f8c8d;
        margin-bottom: 20px;
    }

    #products {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    #products td, #products th {
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 14px;
    }

    #products tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    #products tr:hover {
        background-color: #e9ecef;
    }

    #products th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
    }

    .footer {
        margin-top: 30px;
        padding-top: 10px;
        border-top: 1px solid #eee;
        font-size: 12px;
        color: #95a5a6;
        text-align: center;
    }

    /* RTL support */
    .rtl {
        direction: rtl;
        text-align: right;
    }
    .rtl #products th,
    .rtl #products td {
        text-align: right;
    }
    .rtl .print-date,
    .rtl .footer {
        text-align: left;
    }
</style>

<div class="{{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
    <div class="header">
        <div class="title">{{ __('main.Product Inventory Report') }}</div>
        <div class="subtitle">{{ __('main.Complete list of all products in stock') }}</div>
    </div>

    <div class="print-date">
        {{ __('main.Printed on') }}: {{ date('F j, Y, g:i a') }}
    </div>

    <table id="products" width="100%">
        <thead>
        <tr>
            <th>{{ __('main.ID') }}</th>
            <th>{{ __('main.Product Name') }}</th>
            <th>{{ __('main.SKU') }}</th>
            <th>{{ __('main.Quantity') }}</th>
            <th>{{ __('main.Category name') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if(app()->getLocale() === 'ar')
                        {{ $product->name_ar ?? $product->name_en ?? __('main.N/A') }}
                    @else
                        {{ $product->name_en ?? $product->name_ar ?? __('main.N/A') }}
                    @endif
                </td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->qty }}</td>
                <td>
                    @if($product->category)
                        {{ $product->category->name }}
                    @else
                        {{ __('main.N/A') }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5" style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}; font-style: sans-serif; color: #666;">
                {{ __('main.product_count') }} : {{ $products->count() }}
            </td>
        </tr>
        </tfoot>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} {{ __('main.Your Company Name') }}. {{ __('main.All rights reserved') }}.
    </div>
</div>