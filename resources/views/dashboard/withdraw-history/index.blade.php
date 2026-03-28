@extends('layouts.app')
@section('title', 'Admin | Withdraw History')
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
                                    <h4>Withdraw History </h4>
                                    <form action="{{ route('withdraw-history.index') }}" method="GET"
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

                                    @if ($appData->withdrawal_method == 'payinfintech')
                                        <div>
                                            <a href="{{ route('payinfintechToken') }}" class="btn btn-sm btn-primary">Get Token</a>
                                        </div> 
                                    @endif
                                    
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive">
                                        <table data-searching="false" data-paging="false" data-info="false"
                                            data-order='[[0, "desc"],[2, "desc"]]' id="myTable"
                                            class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Amount</th>
                                                    <th>Request Type</th>
                                                    <th>Withdraw Mode</th>
                                                    <th>UPI Name</th>
                                                    <th>UPI ID</th>
                                                    <th>Bank Name</th>
                                                    <th>Banking Name</th>
                                                    <th>Bank Number</th>
                                                    <th>Bank IFSC</th>
                                                    <th>Status</th>
                                                    @canany(['withdraw-request-accept', 'withdraw-request-reject'])
                                                        <th>Action</th>
                                                    @endcanany
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($withdrawHistories as $withdraw)
                                                    <tr>
                                                        <td>{{ $withdraw->id }}</td>
                                                        <td>{{ $withdraw->user->name }}</td>
                                                        <td>
                                                            <a href="{{ route('users.Detail', $withdraw->user->id) }}">
                                                                @if (!env('APP_DEBUG'))
                                                                    {{ $withdraw->user->phone }}
                                                                @else
                                                                    {{ substr($withdraw->user->phone, 0, 3) . '****' . substr($withdraw->user->phone, 7, 10) }}
                                                                @endif
                                                            </a>
                                                        </td>
                                                        <td>{{ $withdraw->amount }}</td>
                                                        <td>{{ $withdraw->request_type }}</td>
                                                        <td>{{ $withdraw->withdraw_mode }}</td>
                                                        <td>{{ $withdraw->user->withdrawDetails->upi_name }}</td>
                                                        <td>{{ $withdraw->user->withdrawDetails->upi_id }}</td>
                                                        <td>{{ $withdraw->user->withdrawDetails->bank_name }}
                                                        <td>{{ $withdraw->user->withdrawDetails->account_holder_name }}
                                                        </td>
                                                        <td>{{ $withdraw->user->withdrawDetails->account_number }}</td>
                                                        <td>{{ $withdraw->user->withdrawDetails->account_ifsc_code }}</td>
                                                        <td @class([
                                                            'text-info' => $withdraw->status == 'pending',
                                                            'text-success' => $withdraw->status == 'success',
                                                            'text-danger' => $withdraw->status == 'failed',
                                                        ])>{{ $withdraw->status }}</td>
                                                        @if ($withdraw->status == 'pending')
                                                            @canany(['withdraw-request-accept', 'withdraw-request-reject'])
                                                                <td style="white-space: nowrap;">
                                                                    @can('withdraw-request-accept')
                                                                        <a href="{{ route('withdraw-request.accept', ['id' => $withdraw->id]) }}"
                                                                            class="btn btn-outline-primary">Accept</a>
                                                                        @if ($withdraw->withdraw_mode == 'bank' && env('ENABLE_PAYOUT'))
                                                                            <a href="{{ route('withdraw.accept-api', ['id' => $withdraw->id]) }}"
                                                                                class="btn btn-outline-primary">SEND MONEY USING
                                                                                API</a>
                                                                        @endif
                                                                    @endcan
                                                                    @can('withdraw-request-reject')
                                                                        <a href="{{ route('withdraw-request.reject', ['id' => $withdraw->id]) }}"
                                                                            class="btn btn-outline-primary">Reject</a>
                                                                    @endcan
                                                                </td>
                                                            @endcanany
                                                        @else
                                                            <td> No Action </td>
                                                        @endif
                                                        <td>{{ $withdraw->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $withdrawHistories->links('pagination.custom') }}
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
