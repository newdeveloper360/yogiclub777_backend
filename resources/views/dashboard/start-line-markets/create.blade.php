@extends('layouts.app')
@section('title','Admin | Start Line Create')
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
                                        <h4>
                                            {{ isset($market) ? 'Update Start Line Market' : 'Create Start Line Market' }}
                                        </h4>
                                    </div>
                                    @if (isset($market))
                                        <form method="post"
                                            action="{{ route('start-line-markets.update', ['market' => $market->id]) }}">
                                            @method('PUT')
                                        @else
                                            <form method="post" action="{{ route('start-line-markets.store') }}"> @csrf
                                    @endif
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-6">
                                                <label>Name</label>
                                                <div class="input-group">
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ old('name', $market->name ?? '') }}">
                                                </div>
                                                @error('name')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-6">
                                                <label>Open Time</label>
                                                <div class="input-group">
                                                    <input type="time" name="open_time" class="form-control"
                                                        value="{{ old('open_time', date('H:i', strtotime($market->open_time ?? ''))) }}">
                                                </div>
                                                @error('open_time')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-6">
                                                <label class="form-label">Disable Game </label>
                                                <div class="selectgroup w-100">
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="disable_game" value="1"
                                                            class="selectgroup-input-radio"
                                                            @if (($market->disable_game ?? false) == 1 || (old('disable_game') ?? false) == 1) checked @endif>
                                                        <span class="selectgroup-button">YES </span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="disable_game" value="0"
                                                            class="selectgroup-input-radio"
                                                            @if (($market->disable_game ?? true) == 0 || (old('disable_game') ?? true) == 0) checked @endif>
                                                        <span class="selectgroup-button">NO</span>
                                                    </label>
                                                </div>
                                                @error('disable_game')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">

                                            <button type="submit" class="btn btn-outline-primary">
                                                {{ isset($market) ? 'Update' : 'Submit' }}
                                            </button>
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
