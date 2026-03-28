@extends('layouts.app')
@section('title','Admin | SubAdmins Create')
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
                                        <h4>Sub Admin </h4>
                                    </div>
                                    @if (isset($subAdmin))
                                        <form method="post"
                                            action="{{ route('sub-admins.update', ['id' => $subAdmin->id]) }}">
                                        @else
                                            <form method="post" action="{{ route('sub-admins.store') }}">
                                    @endif
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name', $subAdmin->name ?? '') }}">
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
                                                <input type="number" name="phone" class="form-control"
                                                    value="{{ old('phone', $subAdmin->phone ?? '') }}">
                                            </div>
                                        </div>
                                        @error('phone')
                                            <div class="alert alert-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <div class="form-group">
                                            <label class="d-block">Permissions</label>
                                            <div class="row">
                                                @foreach ($permissions as $permission)
                                                    <div class="ml-5 col-2">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            @if (isset($subAdmin)) @if ($subAdmin->permissions->contains('id', $permission->id)) 
                                                                        checked @endif
                                                            @endif>
                                                        <label class="form-check-label" for="{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
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
                                            <button type="submit" class="btn btn-outline-primary">Submit</button>
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
