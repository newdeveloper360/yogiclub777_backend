@extends('layouts.app')
@section('title', 'Admin | Start Line Market')
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
                                    <h4>Start Line Markets</h4>
                                    <form action="{{ route('start-line-markets.index') }}" method="GET"
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
                                        <div class="input-group">
                                            @can('create-markets')
                                                <div class="ml-5 form-group">
                                                    <a href="{{ route('start-line-markets.create') }}"
                                                        class="btn btn-outline-primary">Create</a>
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive">
                                        <table data-searching="false" data-paging="false" data-info="false"
                                            data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                            class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Current Status</th>
                                                    <th>Open Time</th>
                                                    <th>Created At</th>
                                                    @canany(['edit-startLine', 'delete-startLine'])
                                                        <th>Action</th>
                                                    @endcan
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($startLineMarkets as $market)
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
                                                        <td>{{ $market->open_time }}</td>
                                                        <td>{{ $market->created_at }}</td>
                                                        <td>
                                                            @can('edit-startLine')
                                                                <a href="{{ route('start-line-markets.edit', ['market' => $market->id]) }}"
                                                                    class="btn btn-outline-primary">Edit</a>
                                                                |
                                                            @endcan
                                                            @can('delete-startLine')
                                                                <a href="{{ route('start-line-markets.destroy', ['market' => $market->id]) }}"
                                                                    class="btn btn-outline-primary">Delete</a>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
