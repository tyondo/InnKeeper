<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{env('APP_NAME')}} | Innkeeper Dashboard</title>

    <link rel="stylesheet" href="{{innkeeperAssets()}}assets/extensions/choices.js/public/assets/styles/choices.css" />
    <link rel="stylesheet" href="{{innkeeperAssets()}}assets/css/main/app.css" />
    <link rel="stylesheet" href="{{innkeeperAssets()}}assets/css/main/app-dark.css" />
    <link
      rel="shortcut icon"
      href="{{innkeeperAssets()}}assets/images/logo/favicon.svg"
      type="image/x-icon"
    />
    <link
      rel="shortcut icon"
      href="{{innkeeperAssets()}}assets/images/logo/favicon.png"
      type="image/png"
    />

    <link rel="stylesheet" href="{{innkeeperAssets()}}assets/css/shared/iconly.css" />
    <link rel="stylesheet" href="{{innkeeperAssets()}}assets/extensions/sweetalert2/sweetalert2.min.css" />
    @yield('styles')
  </head>

  <body>
    <script src="{{innkeeperAssets()}}assets/js/initTheme.js"></script>
    <div id="app">
        @include('innkeeper::layouts.partials._sidebar')

      <div id="main">
        <header class="mb-3">
          <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
          </a>
        </header>
          @yield('content')



        <footer>
          <div class="footer clearfix mb-0 text-muted">
            <div class="float-start">
              <p>{{date('Y')}} &copy; Innkeeper</p>
            </div>
            <div class="float-end">
              <p>
                Crafted with
                <span class="text-danger"><i class="bi bi-heart"></i></span> from Africa by
                <a href="https://tyondo.com">Tyondo</a>
              </p>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <script src="{{innkeeperAssets()}}assets/js/bootstrap.js"></script>
    <script src="{{innkeeperAssets()}}assets/js/app.js"></script>
    <script src="{{innkeeperAssets()}}assets/extensions/jquery/jquery.js"></script>
    <script src="{{innkeeperAssets()}}assets/extensions/choices.js/public/assets/scripts/choices.js"></script>
    <script src="{{innkeeperAssets()}}assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{innkeeperAssets()}}assets/js/pages/form-element-select.js"></script>


  @yield('scripts')
  </body>
</html>
