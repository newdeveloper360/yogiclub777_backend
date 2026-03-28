@extends('layouts.app')
@section('title', 'Admin | Desawar Market')
@section('content')
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="row">
                        <div class="col-12">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-header">
                                    <h4>Desawar Markets</h4>
                                    <form action="{{ route('desawar-markets.index') }}" method="GET"
                                        class="form-inline mr-auto">
                                        <div class="search-element">
                                            <input name="searchValue" id="myInput" class="form-control"
                                                @if (isset($searchValue)) value="{{ $searchValue }}" @endif
                                                type="search" placeholder="Search" aria-label="Search" data-width="200">
                                            <button class="btn" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <div class="card-header-form">
                                        <form>
                                            <div class="input-group">
                                                <div class="ml-5 form-group">
                                                    <a href="{{ route('desawar-markets.create') }}"
                                                        class="btn btn-outline-primary">Create</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive">
                                        <table data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                            class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Current Status</th>
                                                    <th>Game On</th>
                                                    <th>Open Time</th>
                                                    <th>Close Time</th>
                                                    <th> Time Status</th>
                                                    <th> Result Time</th>
                                                    <th> Created At</th>
                                                    @canany(['edit-desawar', 'delete-desawar'])
                                                        <th class="col-2">Action</th>
                                                    @endcanany
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($desawarMarkets as $market)
                                                    <tr>
                                                        <td>{{ $market->id }}</td>
                                                        <td>{{ $market->name }}</td>
                                                        <td @class([
                                                            'text-success' => $market->gameOn,
                                                            'text-danger' => !$market->gameOn,
                                                        ])>
                                                            {{ $market->gameOn ? 'OPEN NOW' : 'CLOSED NOW' }}
                                                        </td>
                                                        </td>
                                                        <td>{{ $market->game_on ? 'On' : 'Off' }}</td>
                                                        <td>{{ $market->open_time }}</td>
                                                        <td>{{ $market->close_time }}</td>
                                                        <td>{{ $market->time_status ? 'On' : 'Off' }}</td>
                                                        <td>{{ $market->result_time }}</td>
                                                        <td>{{ $market->created_at }}</td>
                                                        @canany(['edit-desawar', 'delete-desawar'])
                                                            <td>
                                                                @can('edit-desawar')
                                                                    <a href="{{ route('desawar-markets.edit', ['market' => $market->id]) }}"
                                                                        class="btn btn-outline-primary">Edit</a>|
                                                                @endcan
                                                                @can('delete-desawar')
                                                                    <a href="{{ route('desawar-markets.destroy', ['market' => $market->id]) }}"
                                                                        class="btn btn-outline-primary">Delete</a>
                                                                @endcan
                                                            </td>
                                                        @endcanany
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $desawarMarkets->links('pagination.custom') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
