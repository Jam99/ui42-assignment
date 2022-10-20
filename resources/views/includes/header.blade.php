<!DOCTYPE html>
<html lang="sk">
    <head>
        <title>UI42 Assignment</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @if (config('app.env') == 'local')
            <link rel="stylesheet" href="{{asset('css/app.css')}}">
        @else
            <link rel="stylesheet" href="{{asset(mix('css/app.css'), true)}}">
        @endif
        @if (config('app.env') == 'local')
            <script src="{{asset('js/app.js')}}"></script>
        @else
            <script src="{{asset(mix('js/app.js'), true)}}"></script>
        @endif
    </head>
    <body>

