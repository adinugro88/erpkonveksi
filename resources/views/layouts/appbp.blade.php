<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Plugins CSS -->
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet">

    <!-- Masn Styles -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dark-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/semi-dark-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/minimal-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/shadow-theme.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        .card-scroll {
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .card-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .card-scroll::-webkit-scrollbar-thumb {
            border-radius: 4px;
        }

        .card-scroll {
            border-radius: 5px;
            padding: 10px;
        }
    </style>

    @vite([])

    @livewireStyles
</head>

<body class="font-sans antialiased" x-data="{ sidebarOpen: true }">

    <!-- Header -->
    @include('layouts.header')

    <!-- Sidebar -->
    <aside x-show="sidebarOpen" class="sidebar">
        @include('layouts.aside')
    </aside>

    <!-- Main Content -->
    <main class="page-content">
        {{ $slot }} {{-- Jika ini layout komponen --}}
        {{-- @yield('content') --}} {{-- Jika layout Blade biasa --}}
    </main>

    <!-- Overlay -->
    <div class="overlay btn-toggle-menu" @click="sidebarOpen = false"></div>

    @stack('modals')

    @livewireScripts

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>
