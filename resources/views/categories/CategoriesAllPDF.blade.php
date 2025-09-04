<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('main.categories_export')</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
            background-color: #f9f9f9;
        }

        /* Header Styles */
        .report-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4CAF50;
        }

        .report-title {
            color: #2c3e50;
            margin: 0 0 5px 0;
            font-size: 24px;
            font-weight: 600;
        }

        .report-date {
            color: #666;
            font-size: 14px;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .data-table th {
            background-color: #4CAF50;
            color: white;
            padding: 12px 15px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .data-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #e0e0e0;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .data-table tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .data-table tr:hover {
            background-color: #f1f1f1;
        }

        /* Footer Styles */
        .report-footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #777;
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
        }

        /* Responsive Adjustments */
        @media print {
            body {
                padding: 0;
                background-color: white;
            }
            .data-table {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
<div class="report-header">
    <h1 class="report-title">@lang('main.categories_export')</h1>
    <div class="report-date">
        @lang('main.generated_on'): {{ now()->format(app()->getLocale() == 'ar' ? 'd/m/Y' : 'm/d/Y') }}
    </div>
</div>

<table class="data-table">
    <thead>
    <tr>
        <th>@lang('main.id')</th>
        <th>@lang('main.name')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($categories as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="report-footer">
    @lang('main.total_records'): {{ count($categories) }}
</div>
</body>
</html>