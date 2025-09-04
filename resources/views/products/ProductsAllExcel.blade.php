<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    th {
        text-align: center;
        background-color: #f8f9fa;
        padding: 10px;
        border: 1px solid #dee2e6;
        font-weight: bold;
    }

    td {
        padding: 8px;
        border: 1px solid #dee2e6;
        text-align: left;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tfoot td {
        text-align: right;
        font-style: italic;
        color: #666;
        border: none;
        padding-top: 15px;
    }
</style>

<table>
    <thead>
    <tr>
        <th style="text-align: center;">{{ __('main.id') }}</th>
        <th style="text-align: center;">{{ __('main.Product Name') }}</th>
        <th style="text-align: center;">{{ __('main.SKU') }}</th>
        <th style="text-align: center;">{{ __('main.Quantity') }}</th>
        <th style="text-align: center;">{{ __('main.Category name') }}</th>
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
            <td>{{ $product->category->name }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="8">
            {{ __('main.Printed') }}: {{ $printDate }}
        </td>
    </tr>
    </tfoot>
</table>