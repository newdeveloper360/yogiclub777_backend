@extends('layouts.app')
@section('title','Admin | Users Create')
@section('content')
<div class="loader"></div>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        @include('layouts.navbar')
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Create User </h4>
                                </div>
                                @if (isset($user))
                                <form method="post" action="{{ route('users.update', ['id' => $user->id]) }}">
                                    @else
                                    <form method="post" action="{{ route('users.store') }}">
                                        @endif
                                        @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <div class="input-group">
                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
                                                </div>
                                            </div>
                                            @error('name')
                                            <div class="alert alert-danger">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <div class="input-group">
                                                    <input type="number" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
                                                </div>
                                            </div>
                                            @error('phone')
                                            <div class="alert alert-danger">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                            <div class="form-group">
                                                <label>Password</label>
                                                <div class="input-group">
                                                    <input type="password" name="password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <div class="input-group">
                                                    <input type="password" name="password_confirmation" class="form-control">
                                                </div>
                                            </div>
                                            @error('password')
                                            <div class="alert alert-danger">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-outline-primary">Create</button>
                                            </div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection