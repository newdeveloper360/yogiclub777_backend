@extends('layouts.app')

@section('title', 'Admin | Start Line Records')
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
                                    <h4>Start Line Markets Record</h4>
                                    <form action="{{ route('start-line-markets.records') }}" method="GET"
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
                                                    <th>User Name</th>
                                                    <th>User Phone</th>
                                                    <th>Number</th>
                                                    <th>Amount</th>
                                                    <th>Win Amount</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($startLineRecords as $record)
                                                    <tr>
                                                        <td>{{ $record->id }}</td>
                                                        <td>{{ $record->market->name }}</td>
                                                        <td>{{ $record->user->name }}</td>
                                                        <td>
                                                            @if (!env('APP_DEBUG'))
                                                                {{ $record->user->phone }}
                                                            @else
                                                                {{ substr($record->user->phone, 0, 3) . '****' . substr($record->user->phone, 7, 10) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $record->number . ' (' . $record->gameType->name . ')' }}
                                                        </td>
                                                        <td>{{ $record->amount }}</td>
                                                        <td>{{ $record->win_amount ?? 'NULL' }}</td>
                                                        <td @class([
                                                            'text-info' => $record->status == 'PENDING',
                                                            'text-success' => $record->status == 'SUCCESS',
                                                            'text-danger' => $record->status == 'FAILED',
                                                        ])>
                                                            {{ $record->status }}
                                                        </td>
                                                        <td>{{ $record->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $startLineRecords->links('pagination.custom') }}
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
