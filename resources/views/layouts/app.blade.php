<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots" content="noindex">

    <!-- Simplebar -->
    <link type="text/css" href="{{ asset('assetsbaru/vendor/simplebar.min.css') }}" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="{{ asset('assetsbaru/css/app.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assetsbaru/css/app.rtl.css') }}" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="{{ asset('assetsbaru/css/vendor-material-icons.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assetsbaru/css/vendor-material-icons.rtl.css') }}" rel="stylesheet">

    <!-- Font Awesome FREE Icons -->
    <link type="text/css" href="{{ asset('assetsbaru/css/vendor-fontawesome-free.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assetsbaru/css/vendor-fontawesome-free.rtl.css') }}" rel="stylesheet">


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-133433427-1"></script>
    @yield('style')
    @vite([])

    @livewireStyles

</head>

<body class="layout-fixed">

    <div class="preloader"></div>

    <!-- Header Layout -->
    <div class="mdk-header-layout js-mdk-header-layout">


        @include('layouts.header')
        <!-- Header Layout Content -->
        <div class="mdk-header-layout__content page">

            @include('layouts.aside')

            <div class="container page__heading-container">
                <div class="page__heading d-flex align-items-center">
                    <div class="flex">
                        <!-- <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#">@yield('Judul')</a></li>
                                <li class="breadcrumb-item active" aria-current="page">@yield('action')</li>
                            </ol>
                        </nav> -->
                        <h3 class="m-0">@yield('Judul')</h3>
                    </div>
                    {{-- <a href="#" class="btn btn-light ml-3"><i
                            class="material-icons icon-16pt text-muted mr-1">settings</i>
                        Settings</a> --}}
                </div>
            </div>
            {{ $slot }}
        </div>
        <!-- // END Header Layout Content -->

    </div>
    <!-- // END Header Layout -->
     <!-- Overlay -->
    <div class="overlay btn-toggle-menu" @click="sidebarOpen = false"></div>

    @stack('modals')

    @livewireScripts

    <!-- App Settings FAB -->
    {{-- <div id="app-settings">
        <app-settings layout-active="fixed" :layout-location="{
      'default': 'dashboard-quick-access.html',
      'fixed': 'fixed-dashboard-quick-access.html',
      'fluid': 'fluid-dashboard-quick-access.html',
      'mini': 'mini-dashboard-quick-access.html'
    }"></app-settings>
    </div> --}}
    <!-- jQuery -->
    <script src="{{ asset('assetsbaru/vendor/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assetsbaru/vendor/popper.min.js') }}"></script>
    <script src="{{ asset('assetsbaru/vendor/bootstrap.min.js') }}"></script>

    <!-- Simplebar -->
    <script src="{{ asset('assetsbaru/vendor/simplebar.min.js') }}"></script>

    <!-- DOM Factory -->
    <script src="{{ asset('assetsbaru/vendor/dom-factory.js') }}"></script>

    <!-- MDK -->
    <script src="{{ asset('assetsbaru/vendor/material-design-kit.js') }}"></script>

    <!-- App -->
    <script src="{{ asset('assetsbaru/js/toggle-check-all.js') }}"></script>
    <script src="{{ asset('assetsbaru/js/check-selected-row.js') }}"></script>
    <script src="{{ asset('assetsbaru/js/dropdown.js') }}"></script>
    <script src="{{ asset('assetsbaru/js/sidebar-mini.js') }}"></script>
    <script src="{{ asset('assetsbaru/js/app.js') }}"></script>

    <!-- App Settings (loaded only if #app-settings is present) -->
    <script>
        (function(){
            try {
                if (document.getElementById('app-settings')) {
                    var s = document.createElement('script');
                    s.src = "{{ asset('assetsbaru/js/app-settings.js') }}";
                    s.defer = true;
                    document.body.appendChild(s);
                }
            } catch (e) {
                console.error('Error loading app-settings:', e);
            }
        })();
    </script>
    <script>
        (function(){
            try {
                var $ = window.jQuery;
                if (!$) return;

                // Add CSS to keep modal/backdrop above template overlay
                var css = '\n.modal-hidden{display:none !important;}\n.modal-backdrop{z-index:99999 !important;}\n.modal{z-index:100000 !important;}\n';
                var style = document.createElement('style');
                style.appendChild(document.createTextNode(css));
                document.head.appendChild(style);

                // Hide the page overlay while any Bootstrap modal is open
                $(document).on('show.bs.modal', '.modal', function() {
                    $('.overlay').addClass('modal-hidden');
                });

                // Restore overlay when modal is closed
                $(document).on('hidden.bs.modal', '.modal', function() {
                    $('.overlay').removeClass('modal-hidden');
                });

                // Defensive: remove stray modal-backdrop remnants when modal hides
                $(document).on('hidden.bs.modal', function() {
                    setTimeout(function() {
                        $('.modal-backdrop').each(function() {
                            if (!document.querySelector('.modal.show')) $(this).remove();
                        });
                        if ($('body').hasClass('modal-open') && !document.querySelector('.modal.show')) {
                            $('body').removeClass('modal-open');
                        }
                    }, 50);
                });
            } catch (e) {
                console.error('Modal overlay fix error:', e);
            }
        })();

        // Fix for Livewire wire:navigate (Preloader & MDK initialization)
        document.addEventListener('livewire:navigated', function() {
            try {
                // Hide preloader if present
                var preloader = document.querySelector('.preloader');
                if (preloader) {
                    if (typeof jQuery !== 'undefined') {
                        $(preloader).fadeOut();
                    } else {
                        preloader.style.display = 'none';
                    }
                }

                // Re-initialize MDK and DOM Factory
                if (typeof domFactory !== 'undefined' && domFactory.handler) {
                    domFactory.handler.upgradeAll();
                }
                
                // Refresh MDK layout
                if (window.mdk) {
                    window.mdk.checkScroll();
                }

                console.log('Livewire navigation: Preloader hidden and MDK re-initialized');
            } catch (e) {
                console.error('Livewire navigation fix error:', e);
            }
        });
    </script>

    @yield('scripts')



</body>

</html>
