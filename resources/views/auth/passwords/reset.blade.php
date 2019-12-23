<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'PhotoLife') }}</title>
    <link rel="stylesheet" href="{{ asset('css/auth/reset.css') }}">
</head>
<body>
<nav class="navbar is-light" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ route('home') }}">
            <img src="{{ asset('images/logo144x144.png') }}" alt="{{ config('app.name', 'PhotoLife') }}">
            <h1 class="title is-5">{{ config('app.name', 'PhotoLife') }}</h1>
        </a>
    </div>
</nav>
<section class="hero">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-multiline is-vcentered is-centered">
                <div class="column is-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-title">
                                {{ __('Reset Password') }}
                            </div>
                        </div>
                        <div class="card-content">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('password.request') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="field">
                                    <label class="label">{{ __('E-Mail Address') }}</label>
                                    <div class="control ">
                                        <input class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="email" name="email" placeholder="Email input"
                                               value="{{ $email }}">
                                    </div>
                                    @if ($errors->has('email'))
                                    <p class="help is-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                                <div class="field">
                                    <label class="label">{{ __('Password') }}</label>
                                    <div class="control ">
                                        <input class="input{{ $errors->has('password') ? ' is-danger' : '' }}" type="password" name="password">
                                    </div>
                                    @if ($errors->has('password'))
                                    <p class="help is-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>
                                <div class="field">
                                    <label class="label">{{ __('Confirm Password') }}</label>
                                    <div class="control ">
                                        <input class="input{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}" type="password" name="password_confirmation">
                                    </div>
                                    @if ($errors->has('password_confirmation'))
                                    <p class="help is-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
                                </div>

                                <div class="field is-grouped">
                                    <div class="control">
                                        <button type="submit" class="button is-link">{{ __('Reset Password') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>