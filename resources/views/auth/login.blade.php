@extends('layouts.app')

@section('content')
    <body style="background-color: #202020">
    <div class="m-auto">
        <div class="card" style="background-color: #202020;">
            <form class="box" method="post" action="{{ route('login') }}" style="box-shadow: 0 0 20px rgba(0,0,0, 1);">
                @csrf
                <h1>Login</h1>
                <p class="text-muted"> Please enter your login and password!</p>
                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Username or Email">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password: ">

                @error('password')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                </span>
                @enderror
                <a class="forgot text-muted" href="{{ route('register') }}">Don't have an account?</a>
                @if($errors->has('email') || $errors->has('username'))
                    <br>
                    <br>
                    <span style="color: red">{{$errors->first('email') }} {{ $errors->first('username')}}</span>
                @endif
                <input type="submit" name="" value="Login">
                <div class="col-md-12">
                    <ul class="social-network social-circle">
                        <li><a href="#" class="icoFacebook" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" class="icoTwitter" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#" class="icoGoogle" title="Google +"><i class="fab fa-google-plus"></i></a></li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
    </body>
@endsection
