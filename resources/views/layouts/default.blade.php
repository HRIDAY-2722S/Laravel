<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css" />
    <link rel="stylesheet" href="{{ asset('css/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('css/simplebar.css')}}">
    <link rel="stylesheet" href="{{ asset('css/app-light.css')}}" id="lightTheme">
    <link rel="stylesheet" href="{{ asset('css/app-dark.css')}}" id="darkTheme" disabled>
  </head>
  <body class="vertical  light  ">
    <div class="wrapper">
        @include('layouts.navbar')

        @include('layouts.sidebar')
      <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              @yield('content')
            </div>
          </div>
        </div>
      </main>
    </div>
    <script src="{{ asset ('js/jquery.min.js') }}"></script>
    <script src="{{ asset ('js/tinycolor-min.js') }}"></script>
    <script src="{{ asset ('js/config.js') }}"></script>
    <script src="{{ asset ('js/simplebar.min.js') }}"></script>
    <script src="{{ asset ('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/apps.js') }}"></script>
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag()
      {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-56159088-1');
    </script>
    <footer class="fixed-footer">
  <!-- Your footer content here -->
    <p>Copyright <?php echo date('Y'); ?> Your Company Name</p>
  </footer>
  <style>
    .fixed-footer {
    /* position: fixed; */
    bottom: 0;
    width: 100%;
    background-color: #f5f5f5;
    padding: 10px;
    text-align: center;
    border-top: 1px solid #ddd;
  }
  </style>
</body>
</html>