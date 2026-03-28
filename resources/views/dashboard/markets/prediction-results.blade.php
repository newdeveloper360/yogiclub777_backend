@extends('layouts.app')
@section('title', 'Admin | General Prediction')
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
                                        <h4>General Predict Result</h4>
                                    </div>
                                    @if (session('success'))
                                        <div class="m-2 alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <div class="input-group">
                                                    </div>
                                                    <input type="date" name="date" id="date"
                                                        class="form-control " value="{{ date('Y-m-d'), old('date') }}"
                                                        required>
                                                </div>
                                                @error('date')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-4">
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
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Session</label>
                                                    <div class="form-group">
                                                        <select class="form-control" id="session" name="session">
                                                            <option value="" selected>Select Session</option>
                                                            <option value="open"
                                                                {{ old('session') == 'open' ? 'selected' : '' }}>
                                                                Open
                                                            </option>
                                                            <option value="close"
                                                                {{ old('session') == 'close' ? 'selected' : '' }}>
                                                                Close</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @error('session')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Pana</label>
                                                    <div class="form-group">
                                                        <select class="form-control" id="pana-select" name="pana"
                                                            required>
                                                            <option value="" selected>Select Number</option>
                                                            @foreach ($panaNumbers as $pn)
                                                                <option value="{{ $pn }}"
                                                                    {{ old('session') == $pn ? 'selected' : '' }}>
                                                                    {{ $pn }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('pana')
                                                        <div class="alert alert-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Digit</label>
                                                    <div class="input-group">
                                                    </div>
                                                    <input type="text" name="digit" id="digit" class="form-control"
                                                        readonly value="{{ old('digit') }}">
                                                </div>
                                                @error('digit')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="">
                        <div class="form-group">
                            <label>Amount</label>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="Game Amount" name="amount">
                            </div>
                        </div>
                        <input type="hidden" name="game_id">
                        <button type="button" onclick="updateGameAmount()"
                            class="btn btn-primary m-t-15 waves-effect">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#pana-select').on('change', function() {
                var selectedValue = $(this).val();
                var digits = selectedValue.toString().split("");
                var digit = (parseInt(digits[0]) + parseInt(digits[1]) + parseInt(digits[2])) % 10;
                $('#digit').val(digit);
            });
        });

        function getResults() {
            var market = $('#market').val();
            var session = $('#session').val();
            var digit = $('#digit').val();
            if (market == '') {
                alert('Please select a valid market');
                return false;
            }
            if (session == '') {
                alert('Please select a valid session');
                return false;
            }
            if (digit == '') {
                alert('Please fill a digit');
                return false;
            }
            $.ajax({
                url: "{{ route('markets.prediction-results.show') }}",
                type: "GET",
                data: {
                    date: $('#date').val(),
                    market: $('#market').val(),
                    session: $('#session').val(),
                    pana: $('#pana-select').val(),
                    digit: $('#digit').val(),
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
            });
        }

        function update_game_id(id) {
            $("input[name='game_id']").val(id);
        }

        function updateGameAmount() {
            let amount = $("input[name='amount']").val();
            let game_id = $("input[name='game_id']").val();
            if (game_id == null || game_id == "") {
                alert("Please select a valid Bid");
                return false;
            }
            if (amount == null || amount == "") {
                alert("Please enter amount");
                return false;
            }
            $.ajax({
                url: "{{ route('markets.prediction-results.updateBid') }}",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    amount,
                    game_id,
                },
                beforeSend: function() {
                    $("#bid_amount_" + game_id).html(amount);
                    $("#bid_number_" + game_id).html(amount);
                },
                success: function(res) {
                    $("#close_button").click();
                },
            });
        }
    </script>
@endsection
