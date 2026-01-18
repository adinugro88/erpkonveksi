<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots" content="noindex">

    <!-- Simplebar -->
    <link type="text/css" href="assetsbaru/vendor/simplebar.min.css" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="assetsbaru/css/app.css" rel="stylesheet">
    <link type="text/css" href="assetsbaru/css/app.rtl.css" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="assetsbaru/css/vendor-material-icons.css" rel="stylesheet">
    <link type="text/css" href="assetsbaru/css/vendor-material-icons.rtl.css" rel="stylesheet">

    <!-- Font Awesome FREE Icons -->
    <link type="text/css" href="assetsbaru/css/vendor-fontawesome-free.css" rel="stylesheet">
    <link type="text/css" href="assetsbaru/css/vendor-fontawesome-free.rtl.css" rel="stylesheet">




</head>

<body class="layout-login">





    <div class="layout-login__overlay"></div>
    <div class="layout-login__form bg-white" data-simplebar>
        <div class="d-flex justify-content-center mt-2 mb-5 navbar-light">
            <a href="index.html" class="navbar-brand" style="min-width: 0">
                <img class="navbar-brand-icon" src="assetsbaru/images/stack-logo-blue.svg" width="25" alt="Stack">
                <span>ERP Konveksi JRW</span>
            </a>
        </div>

        <h4 class="m-0">Selamat Datang</h4>
        <p class="mb-5">Login untuk masuk ke dalam sistem </p>

        <form action="{{ route('login') }}" method="post">
             @csrf
            <div class="form-group">
                <label class="text-label" for="email_2">Email Address:</label>
                <div class="input-group input-group-merge">
                    <input id="email_2" type="email" name="email" :value="old('email')" required class="form-control form-control-prepended" placeholder="john@doe.com">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="far fa-envelope"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="text-label" for="password_2">Password:</label>
                <div class="input-group input-group-merge">
                    <input id="password_2" type="password" name="password" required class="form-control form-control-prepended" placeholder="Enter your password">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-key"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-5">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" checked="" id="remember">
                    <label class="custom-control-label" for="remember">Remember me</label>
                </div>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-primary mb-5" type="submit">Login</button><br>
            </div>
        </form>
    </div>


    <!-- jQuery -->
    <script src="assetsbaru/vendor/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="assetsbaru/vendor/popper.min.js"></script>
    <script src="assetsbaru/vendor/bootstrap.min.js"></script>

    <!-- Simplebar -->
    <script src="assetsbaru/vendor/simplebar.min.js"></script>

    <!-- DOM Factory -->
    <script src="assetsbaru/vendor/dom-factory.js"></script>

    <!-- MDK -->
    <script src="assetsbaru/vendor/material-design-kit.js"></script>

    <!-- App -->
    <script src="assetsbaru/js/toggle-check-all.js"></script>
    <script src="assetsbaru/js/check-selected-row.js"></script>
    <script src="assetsbaru/js/dropdown.js"></script>
    <script src="assetsbaru/js/sidebar-mini.js"></script>
    <script src="assetsbaru/js/app.js"></script>

    <!-- App Settings (safe to remove) -->
    <script src="assetsbaru/js/app-settings.js"></script>





</body>

</html>