@extends('layouts.app')
@section('title', 'Admin | Desawar Win History')
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
                            <div class="card">
                                <div class="card-header">
                                    <h4>Desawar Markets Record</h4>
                                    <form action="{{ route('desawar-markets.win-history') }}" method="GET"
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
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive">
                                        <table data-searching="false" data-paging="false" data-info="false"
                                            data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                            class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Market Name</th>
                                                    <th>Winner Name</th>
                                                    <th>Winner Phone</th>
                                                    <th>Amount</th>
                                                    <th>Number</th>
                                                    <th>Win Amount</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($winHistory as $wh)
                                                    <tr>
                                                        <td>{{ $wh->id }}</td>
                                                        <td>{{ $wh->market->name }}</td>
                                                        <td>{{ $wh->user->name }}</td>
                                                        <td>
                                                            @if (!env('APP_DEBUG'))
                                                                {{ $wh->user->phone }}
                                                            @else
                                                                {{ substr($wh->user->phone, 0, 3) . '****' . substr($wh->user->phone, 7, 10) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $wh->amount }}</td>
                                                        <td>{{ $wh->number . ' (' . $wh->gameType->name . ')' }}</td>
                                                        <td>{{ $wh->win_amount }}</td>
                                                        <td>{{ $wh->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $winHistory->links('pagination.custom') }}
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
