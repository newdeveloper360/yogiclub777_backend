@extends('layouts.app')
@section('title', 'Admin | Start Line Prediction')
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
                                        <h4>Start Line Predict Result</h4>
                                    </div>
                                    @if (session('success'))
                                        <div class="m-2 alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <div class="input-group">
                                                    </div>
                                                    <input type="date" id="date" name="date"
                                                        class="form-control " value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                @error('date')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Markets</label>
                                                    <div class="form-group">
                                                        <select class="form-control" id="market" name="market" required>
                                                            <option selected value=""> Select Game </option>
                                                            @foreach ($markets as $market)
                                                                <option value="{{ $market->id }}"
                                                                    {{ old('market') == $market->id ? 'selected' : '' }}>
                                                                    {{ $market->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @error('market')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Open Pana</label>
                                                    <div class="form-group">
                                                        <select class="form-control" id="pana-select" name="open_pana"
                                                            required>
                                                            <option value="" selected>Select Number</option>
                                                            @foreach ($panaNumbers as $pn)
                                                                <option value="{{ $pn }}">
                                                                    {{ $pn }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('open_pana')
                                                        <div class="alert alert-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Open Digit</label>
                                                    <div class="input-group">
                                                    </div>
                                                    <input type="text" name="open_digit" id="open_digit"
                                                        class="form-control" readonly value="{{ old('open_digit') }}">
                                                </div>
                                                @error('open_digit')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        @if (session('error'))
                                            <div class="alert alert-danger">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <button type="submit" onclick="getResults()"
                                                class="btn btn-outline-primary">Predict</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="section" id="results-data">

                    </section>
                </section>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#pana-select').on('change', function() {
                var selectedValue = $(this).val();
                var digits = selectedValue.toString().split("");
                var digit = (parseInt(digits[0]) + parseInt(digits[1]) + parseInt(digits[2])) % 10;
                $('#open_digit').val(digit);
            });
        });

        function getResults() {
            var market = $('#market').val();
            var openDigit = $('#open_digit').val();
            if (market == '') {
                alert('Please select a valid market');
                return false;
            }
            if (openDigit == '') {
                alert('Please fill a digit');
                return false;
            }
            $.ajax({
                url: "{{ route('start-line-markets.prediction-results.show') }}",
                type: "GET",
                data: {
                    date: $('#date').val(),
                    market: $('#market').val(),
                    open_pana: $('#pana-select').val(),
                    open_digit: $('#open_digit').val(),
                },
                beforeSend: function() {
                    $('#results-data').html(
                        `<h6 class="d-flex justify-content-center">Loading...</h6>`
                    )
                },
                success: function(res) {
                    // Handle successful respons
                    $('#results-data').html(res.view)
                },
                error: function(xhr) {
                    // Handle error
                    // console.log(xhr);
                }
            });

        }
    </script>
@endsection
