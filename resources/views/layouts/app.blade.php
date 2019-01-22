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
        <el-container class="wrapper">
            <el-header height="80px" :style="{ 'background-color': primaryColor }">
                <img src="{{ asset('img/logo.png') }}" alt="element-logo" class="header-logo">
                <!--ul class="header-operations">
                    <li>
                        User
                    </li>
                </ul-->
            </el-header>
            <el-container class="wrapper">
                <el-aside class="menu">
                    <el-menu default-active="1">
                        <el-menu-item index="1" v-on:click="goto('{{ route('sales.list') }}')">
                            Ordenes de Servicio
                        </el-menu-item>
                    </el-menu>
                </el-aside>
                @yield('content')
            </el-container>
        </el-container>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
