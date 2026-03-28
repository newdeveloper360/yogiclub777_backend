@extends('layouts.app')
@section('title', 'Admin | Transactions ')
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
                                    <h4>Transactions</h4>
                                    <form action="{{ route('transactions.index') }}" method="GET"
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
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Previous amount</th>
                                                <th>Transaction amount</th>
                                                <th>Current amount</th>
                                                <th>Type</th>
                                                <th>Details</th>
                                                <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($transactions as $transaction)
                                                    <tr>
                                                        <td>{{ $transaction->id }}</td>
                                                        <td>{{ $transaction->user->name }}</td>
                                                        <td>
                                                            @if (!env('APP_DEBUG'))
                                                                {{ $transaction->user->phone }}
                                                            @else
                                                                {{ substr($transaction->user->phone, 0, 3) . '****' . substr($transaction->user->phone, 7, 10) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($transaction->previous_amount) }}</td>
                                                        <td>{{ number_format($transaction->amount) }}</td>
                                                        <td>{{ number_format($transaction->current_amount) }}</td>
                                                        <td>{{ $transaction->type }}</td>
                                                        <td>{{ $transaction->details }}</td>
                                                        <td>{{ $transaction->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $transactions->links('pagination.custom') }}
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
