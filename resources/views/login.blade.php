@extends('layouts.app')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    console.log('Pusher');
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    console.log('Pusher 2');
    var pusher = new Pusher('d03e16e1bd0da3337dcc', {
        cluster: 'ap2',
    });

    var channel = pusher.subscribe('chats.1');
    channel.bind('message.sent', function(data) {
        console.log(data);
    });
</script>
@section('content')
    <div class="loader"></div>
    <div id="app">
        <section class="section">
            <div class="container mt-5 ">

                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="d-flex justify-content-center mb-5">
                            <h2 class="mb-5">{{ env('APP_NAME') }}</h2>
                        </div>
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Login</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="phone">Phone/Username</label>
                                        <input id="phone" type="text" class="form-control" name="phone"
                                            tabindex="1" required autofocus value="{{ old('phone') }}">
                                        @if ($errors->has('phone'))
                                            <div class="alert alert-danger mt-4" role="alert">
                                                {{ $errors->first('phone') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            <div class="float-right">
                                                <a href="auth-forgot-password.html" class="text-small">
                                                    Forgot Password?
                                                </a>
                                            </div>
                                        </div>
                                        <input id="password" type="password" class="form-control" name="password"
                                            tabindex="2" required>
                                        @if ($errors->has('password'))
                                            <div class="alert alert-danger mt-4" role="alert">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                        @if ($errors->has('credentials'))
                                            <div class="alert alert-danger mt-4" role="alert">
                                                {{ $errors->first('credentials') }}
                                            </div>
                                        @endif
                                    </div>
                                    @if (env('EXTRA_SECURITY', 0))
                                        <div class="form-group">
                                            <label for="song_name">Song Name</label>
                                            <input id="song_name" type="text" class="form-control" name="song_name"
                                                tabindex="3" required autofocus value="{{ old('song_name') }}">
                                            @if ($errors->has('song_name'))
                                                <div class="alert alert-danger mt-4" role="alert">
                                                    {{ $errors->first('song_name') }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
