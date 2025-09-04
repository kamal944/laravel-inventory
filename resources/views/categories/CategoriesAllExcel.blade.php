<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('main.export_title')</title>
    <style>
        body {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        #categories {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #categories td, #categories th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        #categories tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #categories tr:hover {
            background-color: #ddd;
        }

        #categories th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #4CAF50;
            color: white;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="title">@lang('main.export_title')</div>

<table id="categories" width="100%">
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
</body>
</html>