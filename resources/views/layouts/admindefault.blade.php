
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://bootadmin.org/style/vendor/library.min.css">
    <link rel="stylesheet" href="https://bootadmin.org/style/vendor/jqueryui-flat/jquery-ui.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://bootadmin.org/style/core/style.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/images/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="msapplication-config" content="/images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
</head>
<body id="landing" class="sidebar-open">
<div class="loading">
    <div class="loading-center"><img src="{{ asset('profile_picture/lg.gif') }}" alt="Loading" /></div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    // assets/js/app.js (assuming jQuery is already included and initialized)

$(document).ready(function() {
    // $('.page-container').css('visibility', 'hidden');
    var loader = $('.loading');

    loader.show();

    setTimeout(function() {
        loader.hide();

        $('.page-container').css('visibility', 'visible');
    }, 2000);
});

</script>
    <div class="page-container animsition">
        
        <div id="dashboardPage">
            

            @include('layouts.adminnavbar')

            @include('layouts.adminsidebar')

            <main>
                @yield('content')

                @include('layouts.adminfooter')

            </main>
        </div>

    </div>
    <!-- <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://bootadmin.org/scripts/vendor/bootstrap.min.js"></script>
    <script src="https://bootadmin.org/scripts/vendor/library.min.js"></script>
    <script src="https://bootadmin.org/scripts/core/main.js"></script>
</body>
</html>
