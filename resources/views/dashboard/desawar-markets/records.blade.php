@extends('layouts.app')
@section('title', 'Admin | Desawar Records')
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
                                    <form action="{{ route('desawar-markets.records') }}" method="GET"
                                        class="form-inline mr-auto">
                                        <div class="search-element">
                                            <input name="searchValue" id="myInput" class="form-control"
                                                @if (isset($searchValue)) value="{{ $searchValue }}" @endif
                                                type="search" placeholder="Search" aria-label="Search" data-width="200">
                                        </div>

                                        <div class="search-element ml-2">
                                            <select name="searchStatus" id="searchStatus" class="form-control py-0" data-width="200" style="height: 31px; font-size: 13px;">
                                                <option value="" selected>-- Select Status --</option>
                                                <option value="PENDING" {{ request('searchStatus') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                                <option value="CANCELED" {{ request('searchStatus') == 'CANCELED' ? 'selected' : '' }}>CANCELED</option>
                                                <option value="FAILED" {{ request('searchStatus') == 'FAILED' ? 'selected' : '' }}>FAILED</option>
                                                <option value="SUCCESS" {{ request('searchStatus') == 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                                            </select>
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
                                                    <th>Name</th>
                                                    <th>User Name</th>
                                                    <th>User Phone</th>
                                                    <th>Jodi</th>
                                                    <th>Amount</th>
                                                    <th>Win Amount</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($desawarRecords as $record)
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
                                                            'text-danger' => $record->status == 'CANCELED',
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
                                        {{ $desawarRecords->links('pagination.custom') }}
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
