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
                                <h1 class="title is-2 mb-50">Reset Password</h1>
                                <img src="{{ asset('images/logo144x144.png') }}" width="144" alt="">
                                <span class="has-text-info" style="font-size: 50pt">+</span>
                                <span class="title is-2 has-text-primary" style="font-size: 50pt"><i class="fa fa-user-astronaut"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="column is-5">
                        <div class="card">
                            <header class="card-header">
                                <p class="card-header-title">Reset Password</p>
                            </header>

                            <div class="card-content">
                                @if (session('status'))
                                    <div class="notification">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form class="forgot-password-form" method="POST" action="{{ route('password.email') }}">

                                    {{ csrf_field() }}

                                    <div class="field is-horizontal">
                                        <div class="field-label">
                                            <label class="label">E-Mail Address</label>
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
                                        <div class="field-label"></div>

                                        <div class="field-body">
                                            <div class="field is-grouped">
                                                <div class="control">
                                                    <button type="submit" class="button is-primary">Send Password Reset
                                                        Link
                                                    </button>
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
