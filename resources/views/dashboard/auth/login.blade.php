@extends('layouts.auth')

@section('content')
    <div class="card card-primary">
        <div class="card-header"><h4>Login</h4></div>

        <div class="card-body">
            <form class="card" action="{{ route('dashboard.login') }}" method="post">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email address</label>
                    <input type="text" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           id="exampleInputEmail1" aria-describedby="emailHelp"
                           placeholder="Enter email">
                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Password
                    </label>
                    <input type="password" name="password"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                           id="exampleInputPassword1" placeholder="Password">
                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember"
                               class="custom-control-input" {{ old('remember') ? 'checked' : '' }}  tabindex="3" id="remember-me"/>
                        <label  class="custom-control-label"  for="remember-me">Remember me</label>
                    </div>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                </div>
            </form>
        </div>
    </div>
@endsection
