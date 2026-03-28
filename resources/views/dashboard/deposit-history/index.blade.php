@extends('layouts.app')
@section('title', 'Admin | Deposit History')
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
                                    <h4>Deposit History </h4>
                                    <form action="{{ route('deposit-history.index') }}" method="GET"
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
                                            data-order='[0, "desc"]]' id="myTable" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Amount</th>
                                                    <th>Request Type</th>
                                                    <th>Deposit Mode</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-body">
                                                @foreach ($depositHistories as $deposit)
                                                    <tr>
                                                        <td>{{ $deposit->id }}</td>
                                                        <td>{{ $deposit->user->name }}</td>
                                                        <td>
                                                            @if (!env('APP_DEBUG'))
                                                                {{ $deposit->user->phone }}
                                                            @else
                                                                {{ substr($deposit->user->phone, 0, 3) . '****' . substr($deposit->user->phone, 7, 10) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $deposit->amount }}</td>
                                                        <td>{{ $deposit->request_type }}</td>
                                                        <td>{{ $deposit->deposit_mode }}</td>
                                                        <td @class([
                                                            'text-info' => $deposit->status == 'pending',
                                                            'text-success' => $deposit->status == 'success',
                                                            'text-danger' => $deposit->status == 'failed',
                                                        ])>{{ $deposit->status }}</td>
                                                        @if ($deposit->status == 'pending')
                                                            <td>
                                                                <a href="{{ route('deposit-request.accept', ['id' => $deposit->id]) }}"
                                                                    class="btn btn-outline-primary">Accept</a>|
                                                                <a href="{{ route('deposit-request.reject', ['id' => $deposit->id]) }}"
                                                                    class="btn btn-outline-primary">Reject</a>
                                                            </td>
                                                        @else
                                                            <td> No Action </td>
                                                        @endif
                                                        <td>{{ $deposit->created_at }}</td>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $depositHistories->links('pagination.custom') }}
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
