@extends('layouts.app')
@section('title', 'Admin | General Results')
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
                                        <h4>General Create Result</h4>
                                    </div>
                                    @if (session('success'))
                                        <div class="m-2 alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <form id="form_result" method="post" action="{{ route('markets.results.store') }}">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <div class="input-group">
                                                        </div>
                                                        <input type="date" id="date" name="date"
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
                                                            <select class="form-control" id="market" name="market"
                                                                required>
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
                                                <div class="form-group col-6">
                                                    <label class="form-label">
                                                        Did you want to do with percentage
                                                        ? </label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="percentage_check" value="1"
                                                                class="selectgroup-input-radio">
                                                            <span class="selectgroup-button">Yes </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="percentage_check" value="0"
                                                                class="selectgroup-input-radio" checked>
                                                            <span class="selectgroup-button">No</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="pana-section">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>Pana</label>
                                                        <div class="form-group">
                                                            <!-- Changed from select to input with datalist -->
                                                            <input list="pana-options" class="form-control" id="pana-select"
                                                                name="pana" required>
                                                            <datalist id="pana-options">
                                                                @foreach ($panaNumbers as $pn)
                                                                    <option value="{{ $pn }}"
                                                                        {{ old('session') == $pn ? 'selected' : '' }}>
                                                                        {{ $pn }}
                                                                    </option>
                                                                @endforeach
                                                            </datalist>
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
                                                        <input type="text" name="digit" id="digit"
                                                            class="form-control" readonly value="{{ old('digit') }}">
                                                    </div>
                                                    @error('digit')
                                                        <div class="alert alert-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row" id="percentage-input">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>Percentage</label>
                                                        <div class="input-group">
                                                        </div>
                                                        <input type="number" name="percentage" id="percentage"
                                                            class="form-control" value="{{ old('percentage') }}">
                                                    </div>
                                                    @error('percentage')
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
                                                <button type="button" onclick="getResults()"
                                                    class="btn btn-outline-primary">Show Winners</button>
                                                <button type="submit" class="btn btn-outline-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="section" id="results-data">

                    </section>
                    <section class="section">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Previous Results</h4>
                                        <form action="{{ route('markets.results') }}" method="GET"
                                            class="form-inline mr-auto">
                                            <div class="search-element">
                                                <input name="searchValue" id="myInput" class="form-control"
                                                    @if (isset($searchValue)) value="{{ $searchValue }}" @endif
                                                    type="search" placeholder="Search" aria-label="Search"
                                                    data-width="200">
                                                <button class="btn" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="table-responsive">
                                            <table data-searching="false" data-paging="false" data-info="false"
                                                data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                                class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Game Name</th>
                                                        <th>Result Date</th>
                                                        <th>Result</th>
                                                        <th>Open pana</th>
                                                        <th>Close pana</th>
                                                        <th>Action</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($results as $result)
                                                        <tr>
                                                            <td>{{ $result->id }}</td>
                                                            <td>{{ $result->market->name }}</td>
                                                            <td>{{ $result->result_date }}</td>
                                                            <td>{{ $result->result }}</td>
                                                            <td>{{ $result->open_pana ?? 'NULL' }}</td>
                                                            <td>{{ $result->close_pana ?? 'NULL' }}</td>
                                                            <td>
                                                                @if (env('ADVANCE_MATKA', 0))
                                                                    <a href="{{ route('markets.results.revert', ['id' => $result->id]) }}"
                                                                        class="btn btn-outline-primary">
                                                                        Revert
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>{{ $result->created_at }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="m-2" id="pagination">
                                            {{ $results->links('pagination.custom') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </section>


            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Update Bid</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="">
                        {{-- number change --}}
                        <div class="form-group">
                            <label>Bid Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Bid Number" name="number"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Bid Amount</label>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="Bid Amount" name="amount">
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
            $('#pana-select').on('input', function() { // Changed 'change' to 'input'
                var selectedValue = $(this).val();
                if (selectedValue) {
                    var digits = selectedValue.split("").map(Number);
                    var digit = (digits[0] + digits[1] + digits[2]) % 10;
                    $('#digit').val(digit);
                }
            });
        });
        $(document).ready(function() {
            $('#form_result').submit(function() {
                var selectedValue = $('select').val();
                if (selectedValue == '') {
                    alert('Please select a valid option');
                    return false;
                }
            });
        });

        $(document).ready(function() {
            // Hide the percentage input field by default
            $('#percentage-input').hide();

            // Show or hide the input fields based on the user's selection
            $('input[name="percentage_check"]').on('change', function() {
                if ($(this).val() == '1') {
                    $('#percentage-input').show();
                    $('#pana-section').hide();
                } else {
                    $('#percentage-input').hide();
                    $('#pana-section').show();
                }
            });
        });

        function getResults() {
            var market = $('#market').val();
            var session = $('#session').val();
            var digit = $('#digit').val();
            if (market == '' || market == undefined) {
                alert('Please select a valid market');
                return false;
            }
            if (session == '' || session == undefined) {
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
            let number = $("input[name='number']").val();
            if (game_id == null || game_id == "") {
                alert("Please select a valid Bid");
                return false;
            }
            if (amount == null || amount == "") {
                alert("Please enter amount");
                return false;
            }
            if (number == null || number == "") {
                alert("Please enter number");
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
                    number
                },
                beforeSend: function() {
                    $("#bid_amount_" + game_id).html(amount);
                    $("#bid_number_" + game_id).html(number);
                },
                success: function(res) {
                    $("#close_button").click();
                },
            });
        }
    </script>
@endsection
