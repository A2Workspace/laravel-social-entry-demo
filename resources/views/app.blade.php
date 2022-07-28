<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel Social Entry Demo</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

    </style>

    {{-- <link href="{{ mix('assets/app.css') }}" rel="stylesheet" /> --}}
    <script defer src="{{ mix('assets/app.js') }}"></script>
</head>

<body>
    <div id="app"></div>
</body>

</html>
