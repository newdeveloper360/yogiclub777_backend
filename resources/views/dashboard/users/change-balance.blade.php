@extends('layouts.app')
@section('title','Admin | User Change Balance')
@section('content')
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Add | Deduct Balance </h4>
                                        <h4 class="text-success">{{ $user->balance }}</h4>
                                    </div>
                                    <form method="post"
                                        action="{{ route('users.change-balance.store', ['user' => $user->id]) }}">
                                        @csrf <div class="card-body">
                                            <div class="form-group">
                                                <label>Currency</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            $
                                                        </div>
                                                    </div>
                                                    <input type="number" min="1" name="balance"
                                                        class="form-control currency" value="{{ old('balance') }}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Choose Action </label>
                                                <div class="selectgroup w-100">
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="action" value="+"
                                                            class="selectgroup-input-radio" checked>
                                                        <span class="selectgroup-button">Add </span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="action" value="-"
                                                            class="selectgroup-input-radio">
                                                        <span class="selectgroup-button">Deduct</span>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('lowBalance'))
                                                <div class="alert alert-danger">
                                                    {{ $errors->first('lowBalance') }}
                                                </div>
                                            @endif
                                            @if (session('success'))
                                                <div class="alert alert-success">
                                                    {{ session('success') }}
                                                </div>
                                            @endif
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
