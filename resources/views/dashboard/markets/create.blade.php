@extends('layouts.app')
@section('title','Admin | General Create')
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
                                        {{ isset($market) ? 'Update General Market' : 'Create General Market' }}
                                </div>
                                @if (isset($market))
                                <form method="post" action="{{ route('markets.update', ['market' => $market->id]) }}">
                                    @method('PUT')
                                    @else
                                    <form method="post" action="{{ route('markets.store') }}"> @csrf
                                        @endif
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>Name</label>
                                                    <div class="input-group">
                                                        <input type="text" name="name" class="form-control" value="{{ old('name', $market->name ?? '') }}">
                                                    </div>
                                                    @error('name')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Api Key Name</label>
                                                    <div class="input-group">
                                                        <input type="text" name="api_key_name" class="form-control" value="{{ old('api_key_name', $market->api_key_name ?? '') }}">
                                                    </div>
                                                    @error('api_key_name')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>Open Time</label>
                                                    <div class="input-group">
                                                        <input type="time" name="open_time" class="form-control" value="{{ old('open_time',  date('H:i', strtotime($market->open_time ?? ''))) }}">
                                                    </div>
                                                    @error('open_time')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Close Time</label>
                                                    <div class="input-group">
                                                        <input type="time" name="close_time" class="form-control" value="{{ old('close_time',  date('H:i', strtotime($market->close_time ?? ''))) }}">
                                                    </div>
                                                    @error('close_time')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>Open Result Time</label>
                                                    <div class="input-group">
                                                        <input type="time" name="open_result_time" class="form-control" value="{{ old('open_result_time',  date('H:i', strtotime($market->open_result_time ?? ''))) }}">
                                                    </div>
                                                    @error('open_result_time')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Close Result Time</label>
                                                    <div class="input-group">
                                                        <input type="time" name="close_result_time" class="form-control" value="{{ old('close_result_time', date('H:i', strtotime( $market->close_result_time ?? ''))) }}">
                                                    </div>
                                                    @error('close_result_time')
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
                                                            <input type="radio" name="disable_game" value="1" class="selectgroup-input-radio" @if (($market->disable_game ?? false) == 1 || (old('disable_game') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">YES </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="disable_game" value="0" class="selectgroup-input-radio" @if (($market->disable_game ?? true) == 0 || (old('disable_game') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">NO</span>
                                                        </label>
                                                    </div>
                                                    @error('disable_game')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label class="form-label">Saturday Open </label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="saturday_open" value="1" class="selectgroup-input-radio" @if (($market->saturday_open ?? false) == 1 || (old('saturday_open') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="saturday_open" value="0" class="selectgroup-input-radio" @if (($market->saturday_open ?? true) == 0 || (old('saturday_open') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('saturday_open')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label class="form-label">Sunday Open </label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="sunday_open" value="1" class="selectgroup-input-radio" @if (($market->sunday_open ?? false) == 1 || (old('sunday_open') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="sunday_open" value="0" class="selectgroup-input-radio" @if (($market->sunday_open ?? true) == 0 || (old('sunday_open') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('sunday_open')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label class="form-label">Auto Result </label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="auto_result" value="1" class="selectgroup-input-radio" @if (($market->auto_result ?? false) == 1 || (old('auto_result') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="auto_result" value="0" class="selectgroup-input-radio" @if (($market->auto_result ?? true) == 0 || (old('auto_result') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('auto_result')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label class="form-label">Previous Day Check </label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="previous_day_check" value="1" class="selectgroup-input-radio" @if (($market->previous_day_check ?? false) == 1 || (old('previous_day_check') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="previous_day_check" value="0" class="selectgroup-input-radio" @if (($market->previous_day_check ?? true) == 0 || (old('previous_day_check') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('previous_day_check')
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