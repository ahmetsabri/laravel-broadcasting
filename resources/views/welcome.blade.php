<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Hi There</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <!-- Fonts -->

        <!-- Styles -->
    </head>
    <body>
        <div id="app">
                <v-app>

            <home></home>
                </v-app>
        </div>

    <script src="{{asset('js/app.js')}}" defer></script>
        <script>

    window.Laravel = {!! json_encode([
        'user' => auth()->check() ? auth()->user() : null,
        ]) !!};
        </script>
</body>
</html>
