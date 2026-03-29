<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 60px 70px 50px 70px;
            font-size: 11px;
            color: #333;
        }

        h2 {
            font-size: 16px;
            font-weight: bold;
        }

        p {
            font-size: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .doc-header h2 {
            font-size: 15px;
        }

        .doc-header p {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }

        .filter-info {
            margin: 6px 0;
        }

        .filter-info p {
            margin: 2px 0;
            font-size: 12px;
            color: #595c63;
        }

        table {
            width: 100%;
            margin: 10px auto;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead tr {
            background-color: #f3f4f6;
        }

        th {
            padding: 7px 10px;
            text-align: left;
            border: 1px solid #d1d5db;
            font-weight: bold;
            font-size: 11px;
        }

        td {
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .footer {
            margin-top: 15px;
            font-size: 9px;
            color: #9ca3af;
            text-align: right;
        }

        .badge-success {
            color: #1a7f4b;
            font-weight: bold;
        }

        .badge-pending {
            color: #d8c65e;
            font-weight: bold;
        }

        .badge-danger {
            color: #c0392b;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header class="header">
        <h2>{{ config('app.name') }}</h2>
        <p>@yield('subtitle')</p>
    </header>

    @yield('content')

    <footer class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>

</html>
