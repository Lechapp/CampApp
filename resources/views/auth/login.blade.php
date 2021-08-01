@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>
                    <div class="card-body">
                        <form id="loginForm" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row mt-3">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-3">
                                <div class="text-center col-md-8 offset-md-4 d-none" id="loaderImage">
                                    <div class="lds-ring">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="col-md-8 offset-md-4" id="loginButton">
                                    <div id="errorMessage" class="mb-2 font-weight-bold invalid-feedback" role="alert">
                                        Wystąpił błąd, spróbuj ponownie później
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        $(() => {
            const loginBtn = $("#loginButton");
            const loaderImg = $("#loaderImage");
            const errorMessage = $("#errorMessage");

            $("#loginForm").on('submit', (e) => {
                e.preventDefault();
                const dataString = $(e.target).serialize();
                const endPoint = $(e.target).attr('action');

                $.ajax({
                    method: 'POST',
                    url: endPoint,
                    data: dataString,
                    headers: {
                        'accept': 'application/json'
                    },
                    beforeSend: () => {
                        errorMessage.hide();
                        loginBtn.hide();
                        loaderImg.removeClass('d-none').show();
                    },
                    success: (response) => {
                        const token = response.token;
                        localStorage.setItem("token", token);
                        document.location.href = "/home";
                    },
                    error: (error) => {
                        loginBtn.show();
                        errorMessage.html(error.responseJSON.message).show();
                    },
                    complete:()=>{
                        loaderImg.hide();
                    }
                });

            });

        });
    </script>
@endsection
