<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; {{ env('APP_NAME', 'PhotoLife') }}</title>
  <link rel="stylesheet" href="{{ asset('assets/dashboard/css/app.css') }}">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="{{ asset('images/logo144x144.png') }}" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>
            @if(session()->has('info'))
            <div class="alert alert-primary">
                {{ session()->get('info') }}
            </div>
            @endif
            @if(session()->has('status'))
            <div class="alert alert-info">
                {{ session()->get('status') }}
            </div>
            @endif
            @yield('content')
            <div class="simple-footer">
              Copyright &copy; {{ env('APP_NAME', 'PhotoLife') }} {{ date('Y') }}
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <script src="{{ asset('assets/dashboard/js/manifest.js') }}"></script>
  <script src="{{ asset('assets/dashboard/js/vendor.js') }}"></script>
  <script src="{{ asset('assets/dashboard/js/app.js') }}"></script>
</body>
</html>
