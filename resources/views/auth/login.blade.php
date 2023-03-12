@extends('layouts.app')

@section('content')
    <div class="section-hero-home">
        <div class="container center">
            <div class="row">
                <div class="col s1 m4 l3"></div>
                <div class="col s10 m4 l6">
                    <img src="{{ asset('img/logo.png') }}" alt="">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col s12">
                                    <div class="card-title black-text">
                                        Inloggen
                                    </div>
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="input-field">
                                            <label for="email">E-mail adres</label>
                                            <input id="email" type="email" onkeyup="checkLogin()" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="input-field">
                                            <label for="password">Wachtwoord</label>
                                            <input id="password" type="password" onkeyup="checkLogin()" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
{{--                                        @if (Route::has('password.request'))--}}
{{--                                            <a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--                                                {{ __('Forgot Your Password?') }}--}}
{{--                                            </a>--}}
{{--                                        @endif--}}
                                        <button class="btn-floating btn-large right blue" id="loginButton"><i class="material-icons" id="locked">lock</i><i class="material-icons" id="unlocked">lock_open</i></button>
                                        <button class="btn-floating btn-large right orange mr-10 tooltipped" data-position="left" data-tooltip="Wachtwoord vergeten?"><i class="material-icons">password</i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s1 m4 l3"></div>
            </div>

        </div>
    </div>
    <script>
        $('#password').on('blur input', function() {
            checkLogin();
        });

        $('#email').on('blur input', function() {
            checkLogin();
        });

        const login = false;
        function checkLogin() {
            let email = $('#email').val();
            let password = $('#password').val();
            if(email != '' && password != '') {
                $.get('{{ url('/login/check') }}', {email:email, password:password}, function(data) {
                    if(data === true) {
                        $('#loginButton').removeClass('blue').css('background-color', '#4caf50');
                        $('#locked').hide();
                        $('#unlocked').show();
                        window.location.href='{{ url('/home') }}';
                    } else {
                        $('#loginButton').addClass('blue');
                        $('#unlocked').hide();
                        $('#locked').show();
                    }
                });
            } else {
                $('#loginButton').addClass('blue');
                $('#unlocked').hide();
                $('#locked').show();
            }
        }
    </script>
    <style>
        html, body {margin: 0; height: 100%; overflow: hidden}

        body {
            height: 100%;
            padding-top: 8rem;
            padding-bottom: 18rem;
            background-image: url("{{ asset('img/websitebouwen.jpg') }}");
            background-repeat: no-repeat;
            background-size: cover;
            color: #fff;
        }
    </style>
@endsection
