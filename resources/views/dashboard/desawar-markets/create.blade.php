@extends('layouts.app')
@section('title', 'Admin | Desawar Create ')
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
                                            {{ isset($market) ? 'Update Desawar Market' : 'Create Desawar Market' }}
                                    </div>
                                    @if (isset($market))
                                        <form method="post"
                                            action="{{ route('desawar-markets.update', ['market' => $market->id]) }}">
                                            @method('PUT')
                                        @else
                                            <form method="post" action="{{ route('desawar-markets.store') }}"> @csrf
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
                                                <label>Api Key Name</label>
                                                <div class="input-group">
                                                    <input type="text" name="api_key_name" class="form-control"
                                                        value="{{ old('api_key_name', $market->api_key_name ?? '') }}">
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
                                                    <input type="time" name="open_time" class="form-control"
                                                        value="{{ old('open_time', date('H:i', strtotime($market->open_time ?? ''))) }}">
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
                                                    <input type="time" name="close_time" class="form-control"
                                                        value="{{ old('close_time', date('H:i', strtotime($market->close_time ?? ''))) }}">
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
                                                <label>Result Time</label>
                                                <div class="input-group">
                                                    <input type="time" name="result_time" class="form-control"
                                                        value="{{ old('result_time', date('H:i', strtotime($market->result_time ?? ''))) }}">
                                                </div>
                                                @error('result_time')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
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
                                        <div class="row">
                                            <div class="form-group col-6">
                                                <label class="form-label">Auto Result </label>
                                                <div class="selectgroup w-100">
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="auto_result" value="1"
                                                            class="selectgroup-input-radio"
                                                            @if (($market->auto_result ?? false) == 1 || (old('auto_result') ?? false) == 1) checked @endif>
                                                        <span class="selectgroup-button">Enable </span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="auto_result" value="0"
                                                            class="selectgroup-input-radio"
                                                            @if (($market->auto_result ?? true) == 0 || (old('auto_result') ?? true) == 0) checked @endif>
                                                        <span class="selectgroup-button">Disable</span>
                                                    </label>
                                                </div>
                                                @error('auto_result')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label class="form-label">Previous Day Check </label>
                                                <div class="selectgroup w-100">
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="previous_day_check" value="1"
                                                            class="selectgroup-input-radio"
                                                            @if (($market->previous_day_check ?? false) == 1 || (old('previous_day_check') ?? false) == 1) checked @endif>
                                                        <span class="selectgroup-button">Enable </span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="previous_day_check" value="0"
                                                            class="selectgroup-input-radio"
                                                            @if (($market->previous_day_check ?? true) == 0 || (old('previous_day_check') ?? true) == 0) checked @endif>
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

                                        
                                        <div class="row">
                                            <div class="form-group col-6">
                                                <label class="form-label">Is Bet Time Limit </label>
                                                <div class="selectgroup w-100">
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="is_bet_time_limit" value="1" class="selectgroup-input-radio"
                                                            @if (($market->is_bet_time_limit ?? false) == 1 || (old('is_bet_time_limit') ?? false) == 1) checked @endif>
                                                        <span class="selectgroup-button">YES </span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="is_bet_time_limit" value="0"
                                                            class="selectgroup-input-radio"
                                                            @if (($market->is_bet_time_limit ?? true) == 0 || (old('is_bet_time_limit') ?? true) == 0) checked @endif>
                                                        <span class="selectgroup-button">NO</span>
                                                    </label>
                                                </div>
                                                @error('is_bet_time_limit')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label class="form-label">Bet Time Limit </label>
                                                <div class="input-group">
                                                    <input type="time" name="bet_time_limit" class="form-control"
                                                        value="{{ old('bet_time_limit', date('H:i', strtotime($market->bet_time_limit ?? ''))) }}">
                                                </div>
                                                @error('bet_time_limit')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label class="form-label">Choti Jodi Bet Amount Limit </label>
                                                <div class="input-group">
                                                    <input type="number" name="choti_jodi_bet_amount_limit" class="form-control" value="{{ old('choti_jodi_bet_amount_limit', $market->choti_jodi_bet_amount_limit ?? '') }}">
                                                </div>
                                                @error('choti_jodi_bet_amount_limit')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label class="form-label">Moti Jodi Bet Amount Limit </label>
                                                <div class="input-group">
                                                    <input type="number" name="moti_jodi_bet_amount_limit" class="form-control" value="{{ old('moti_jodi_bet_amount_limit', $market->moti_jodi_bet_amount_limit ?? '') }}">
                                                </div>
                                                @error('moti_jodi_bet_amount_limit')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <h4 class="mt-5 text-danger">Criteria 1 (Time Conditions)</h4>
                                        <div class="row ">
                                            {{-- criteria 1 inputs --}}
                                            <div class="form-group col-6">
                                                <label>Criteria 1 Time Start</label>
                                                <div class="input-group">
                                                    <input type="time" name="c_time_start" class="form-control"
                                                        value="{{ old('c_time_start', date('H:i', strtotime($market->c_time_start ?? ''))) }}">
                                                </div>
                                                @error('c_time_start')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label>Criteria 1 Time End</label>
                                                <div class="input-group">
                                                    <input type="time" name="c_time_end" class="form-control"
                                                        value="{{ old('c_time_end', date('H:i', strtotime($market->c_time_end ?? ''))) }}">
                                                </div>
                                                @error('c_time_end')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label>Criteria 1 Max Bet Amount</label>
                                                <div class="input-group">
                                                    <input type="number" min="1" name="c_max_bet_amount"
                                                        class="form-control"
                                                        value="{{ old('c_max_bet_amount', $market->c_max_bet_amount ?? '') }}">
                                                </div>
                                                @error('c_max_bet_amount')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <h4 class="mt-4 text-danger">Criteria 2 (Time Conditions)</h4>
                                        <div class="row ">
                                            {{-- criteria 1 inputs --}}
                                            <div class="form-group col-6">
                                                <label>Criteria 2 Time Start</label>
                                                <div class="input-group">
                                                    <input type="time" name="c2_time_start" class="form-control"
                                                        value="{{ old('c2_time_start', date('H:i', strtotime($market->c2_time_start ?? ''))) }}">
                                                </div>
                                                @error('c2_time_start')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label>Criteria 2 Time End</label>
                                                <div class="input-group">
                                                    <input type="time" name="c2_time_end" class="form-control"
                                                        value="{{ old('c2_time_end', date('H:i', strtotime($market->c2_time_end ?? ''))) }}">
                                                </div>
                                                @error('c2_time_end')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label>Criteria 2 Max Bet Amount</label>
                                                <div class="input-group">
                                                    <input type="number" min="1" name="c2_max_bet_amount"
                                                        class="form-control "
                                                        value="{{ old('c2_max_bet_amount', $market->c2_max_bet_amount ?? '') }}">
                                                </div>
                                                @error('c2_max_bet_amount')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <h4 class="mt-4 text-danger">Criteria 3 (Time Conditions)</h4>
                                        <div class="row ">
                                            {{-- criteria 1 inputs --}}
                                            <div class="form-group col-6">
                                                <label>Criteria 3 Time Start</label>
                                                <div class="input-group">
                                                    <input type="time" name="c3_time_start" class="form-control"
                                                        value="{{ old('c3_time_start', date('H:i', strtotime($market->c3_time_start ?? ''))) }}">
                                                </div>
                                                @error('c3_time_start')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label>Criteria 3 Time End</label>
                                                <div class="input-group">
                                                    <input type="time" name="c3_time_end" class="form-control"
                                                        value="{{ old('c3_time_end', date('H:i', strtotime($market->c3_time_end ?? ''))) }}">
                                                </div>
                                                @error('c3_time_end')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-6">
                                                <label>Criteria 3 Max Bet Amount</label>
                                                <div class="input-group">
                                                    <input type="number" min="1" name="c3_max_bet_amount"
                                                        class="form-control "
                                                        value="{{ old('c3_max_bet_amount', $market->c3_max_bet_amount ?? '') }}">
                                                </div>
                                                @error('c3_max_bet_amount')
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
