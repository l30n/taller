<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Taller Gallegos</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div id="app" v-cloak>
        <div class="loader-container">
            <div class="loader align-self-center">Loading...</div>
        </div>
        @yield('content')
    </div>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
