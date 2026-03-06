<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Dashlite CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/css/dashlite.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/css/theme.css') }}">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @stack('style')

    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 6px 12px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px;
            right: 10px;
        }
    </style>
     @yield('styles')
</head>
<body class="nk-body bg-lighter npc-general has-sidebar">
    <div class="nk-app-root">
        <div class="nk-main">
            @include('admin.layout.sidebar')
            <div class="nk-wrap">
                @include('admin.layout.navbar')
                <div class="nk-content">
                    @yield('content')
                </div>
                @include('admin.layout.footer')
            </div>
        </div>
    </div>
    @include('admin.layout.script')
    @yield('scripts')
    @stack('scripts')
</body>


</html>
