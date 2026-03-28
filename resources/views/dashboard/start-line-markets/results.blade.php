@extends('layouts.app')
@section('title', 'Admin | Start Line Results')
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
                                        <h4>Start Line Create Result</h4>
                                    </div>
                                    @if (session('success'))
                                        <div class="m-2 alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <form id="form_result" method="post"
                                        action="{{ route('start-line-markets.results.store') }}">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <div class="input-group">
                                                        </div>
                                                        <input type="date" name="date" class="form-control "
                                                            value="{{ date('Y-m-d'), old('date') }}" required>
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
                                                            <select class="form-control" name="market" required>
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
                                            <div class="row" id="pana-input">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>Open Pana</label>
                                                        <div class="form-group">
                                                            <select class="form-control" id="pana-select" name="open_pana"
                                                                required>
                                                                @foreach ($panaNumbers as $pn)
                                                                    <option value="{{ $pn }}"
                                                                        {{ old('session') == $pn ? 'selected' : '' }}>
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
                                                        <input type="text" name="open_digit" id="digit"
                                                            class="form-control" readonly value="{{ old('open_digit') }}">
                                                    </div>
                                                    @error('open_digit')
                                                        <div class="alert alert-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6" id="percentage-input">
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
                                                <button type="submit" class="btn btn-outline-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Previsous Results</h4>
                                        <form action="{{ route('start-line-markets.results') }}" method="GET"
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
                                                        <th>Open pana</th>
                                                        <th>Open Digit</th>
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
                                                            <td>{{ $result->open_pana ?? 'NULL' }}</td>
                                                            <td>{{ $result->open_digit ?? 'NULL' }}</td>
                                                            <td>
                                                                @if (env('ADVANCE_MATKA', 0))
                                                                    <a href="{{ route('start-line-markets.results.revert', ['id' => $result->id]) }}"
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
    <script>
        $(document).ready(function() {
            $('#pana-select').on('change', function() {
                var selectedValue = $(this).val();
                var digits = selectedValue.toString().split("");
                var digit = (parseInt(digits[0]) + parseInt(digits[1]) + parseInt(digits[2])) % 10;
                $('#digit').val(digit);
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
                    $('#pana-input').hide();
                } else {
                    $('#percentage-input').hide();
                    $('#pana-input').show();
                }
            });
        });
    </script>
@endsection
