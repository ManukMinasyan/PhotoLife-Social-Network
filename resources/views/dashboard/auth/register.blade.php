@extends('layouts.auth')

@section('content')
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-marginless is-centered">
                    <div class="column is-5">
                        <div class="column">
                            <a href="{{ route('login') }}" class="button is-white is-small">
                                <i class="fa fa-arrow-left mr-5"></i> Sign In
                            </a>
                            <div class="mt-30">
                                <h1 class="title is-2 mb-50">Welcome to PixelPhoto registration page!</h1>
                                <img src="{{ asset('images/logo144x144.png') }}" width="144" alt="">
                                <span class="has-text-info" style="font-size: 50pt">+</span>
                                <span class="title is-2 has-text-purple" style="font-size: 100pt"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="column is-5">
                        <div class="card mt-50">
                            <header class="card-header">
                                <p class="card-header-title">Register</p>
                            </header>

                            <div class="card-content">
                                <form class="register-form" method="POST" action="{{ route('register') }}">

                                    {{ csrf_field() }}

                                    <div class="field is-horizontal">
                                        <div class="field-label">
                                            <label class="label">Username</label>
                                        </div>

                                        <div class="field-body">
                                            <div class="field">
                                                <p class="control">
                                                    <input class="input" id="username" type="text" name="username"
                                                           value="{{ old('username') }}"
                                                           required autofocus>
                                                </p>

                                                @if ($errors->has('username'))
                                                    <p class="help is-danger">
                                                        {{ $errors->first('username') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="field is-horizontal">
                                        <div class="field-label">
                                            <label class="label">E-mail Address</label>
                                        </div>

                                        <div class="field-body">
                                            <div class="field">
                                                <p class="control">
                                                    <input class="input" id="email" type="email" name="email"
                                                           value="{{ old('email') }}" required autofocus>
                                                </p>

                                                @if ($errors->has('email'))
                                                    <p class="help is-danger">
                                                        {{ $errors->first('email') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="field is-horizontal">
                                        <div class="field-label">
                                            <label class="label">Password</label>
                                        </div>

                                        <div class="field-body">
                                            <div class="field">
                                                <p class="control">
                                                    <input class="input" id="password" type="password" name="password"
                                                           required>
                                                </p>

                                                @if ($errors->has('password'))
                                                    <p class="help is-danger">
                                                        {{ $errors->first('password') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="field is-horizontal">
                                        <div class="field-label">
                                            <label class="label">Confirm Password</label>
                                        </div>

                                        <div class="field-body">
                                            <div class="field">
                                                <p class="control">
                                                    <input class="input" id="password-confirm" type="password"
                                                           name="password_confirmation" required>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="field is-center mt-20">
                                        <div class="field-body">
                                            <div class="field">
                                                <div class="control has-text-centered">
                                                    <button type="submit" class="button is-primary">Register</button>
                                                </div>
                                            </div>
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
@endsection
