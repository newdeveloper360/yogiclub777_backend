@extends('layouts.app')
@section('title', 'Admin | Users ')
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
                                    <h4>Users</h4>
                                    <form action="{{ route('users.index') }}" method="GET" class="form-inline mr-auto">
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
                                        @can('create-markets')
                                            <div class="ml-5 form-group">
                                                <a href="{{ route('users.create') }}" class="btn btn-outline-primary">Create</a>
                                            </div>
                                        @endcan
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
                                                    <th>Phone</th>
                                                    <th>Dashboard</th>
                                                    <th>Balance</th>
                                                    <th>Total Game Amount</th>
                                                    <th>Total Won</th>
                                                    <th>Total Withdraw</th>
                                                    <th>Total Bonus</th>
                                                    @can('users.toogle-blocked.change')
                                                        <th>Blocked | Unblocked</th>
                                                    @endcan
                                                    @can('add-deduct-balance-users')
                                                        <th>Add/Deduct Balance</th>
                                                    @endcan
                                                    <th>Action</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-body">
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ $user->id }}</td>
                                                        <td>
                                                            <a
                                                                href="{{ route('users.Detail', $user->id) }}">{{ $user->name }}</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('users.Detail', $user->id) }}">
                                                                @if (!env('APP_DEBUG'))
                                                                    {{ $user->phone }}
                                                                @else
                                                                    {{ substr($user->phone, 0, 3) . '****' . substr($user->phone, 7, 10) }}
                                                                @endif
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.to.user.login', ['id'=>$user->id]) }}" class="btn btn-sm btn-outline-primary" target="_blank">Login</a>
                                                        </td>
                                                        <td>{{ $user->balance }}</td>
                                                        <td>
                                                            {{ $user->total_market_game_amount + $user->total_startLine_game_amount + $user->total_desawar_game_amount ??
                                                                'no games yet' }}
                                                        </td>
                                                        <td>
                                                            {{ $user->total_market_win_amount + $user->total_startline_win_amount + $user->total_desawar_win_amount }}
                                                        </td>
                                                        <td>
                                                            {{ $user->total_withdraw_success_amount }}
                                                        </td>
                                                        <td>{{ $user->bonus }}</td>
                                                        @can('users.toogle-blocked.change')
                                                            <td>
                                                                <div class="mt-3">
                                                                    <div class="selectgroup ">
                                                                        <label class="selectgroup-item">
                                                                            <input type="radio" value="1"
                                                                                name="blocked{{ $user->id }}"
                                                                                class="selectgroup-input-radio"
                                                                                @if ($user->blocked == 1) checked @endif
                                                                                onchange="toogleBlock({{ $user->id }},1)">
                                                                            <span class="selectgroup-button">Blocked </span>
                                                                        </label>
                                                                        <label class="selectgroup-item">
                                                                            <input type="radio"
                                                                                name="blocked{{ $user->id }}"
                                                                                value="0" class="selectgroup-input-radio"
                                                                                @if ($user->blocked == 0) checked @endif
                                                                                onchange="toogleBlock({{ $user->id }},0)">
                                                                            <span class="selectgroup-button">Unblock</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @endcan
                                                        @can('add-deduct-balance-users')
                                                            <td><a href="{{ route('users.change-balance.show', ['user' => $user->id]) }}"
                                                                    class="btn btn-outline-primary">Add | Deduct</a></td>
                                                        @endcan
                                                        <td><a target="_blank" href="https://wa.me/+91{{ $user->phone }}"
                                                                class="btn btn-outline-primary">Open WhatsApp</a></td>
                                                        <td>{{ $user->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $users->links('pagination.custom') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center w-100" id="myLargeModalLabel">User Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 mb-5">
                            <h5>User Details</h5>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <p>User Status</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="user_status">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Wallet Balance</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="user_wallet_balance"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 flex">
                                <a href="/" id="add_points" class="btn btn-primary">Add or Withdraw Points</a>
                            </div>
                        </div>
                        <div class="col-6 mb-5">
                            <h5>Profile Information</h5>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Full Name</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="user_name"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Phone</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="user_phone"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Registered On</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="user_created_on"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <h5>Payment Information</h5>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Account Holder Name</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="account_name">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Account Number</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="account_number"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6">
                                        <p>IFSC Code</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="ifsc_code"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6">
                                        <p>UPI ID</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="upi_id"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <h5>Withdraw Points Request</h5>
                            <div class="table-responsive">
                                <table data-searching="false" data-paging="false" data-info="false"
                                    data-order='[[2, "desc"], [0, "desc"]]' id="myTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Amount</th>
                                            <th>Request Type</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="history_table">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <h5>Bid History</h5>
                            <div class="table-responsive">
                                <table data-searching="false" data-paging="false" data-info="false"
                                    data-order='[[2, "desc"], [0, "desc"]]' id="myTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Game Name</th>
                                            <th>Game Type</th>
                                            <th>Digits</th>
                                            <th>Points</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bid_table">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12">
                            <h5>Transactions</h5>
                            <div class="table-responsive">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                            role="tab" aria-controls="home" aria-selected="true">All</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                                            role="tab" aria-controls="profile" aria-selected="false">Credit</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact"
                                            role="tab" aria-controls="contact" aria-selected="false">Debit</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <div class="table-responsive">
                                            <table data-searching="false" data-paging="false" data-info="false"
                                                data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                                class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Points</th>
                                                        <th>Transaction Note</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="all_transactions_table">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel"
                                        aria-labelledby="profile-tab">
                                        <div class="table-responsive">
                                            <table data-searching="false" data-paging="false" data-info="false"
                                                data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                                class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Points</th>
                                                        <th>Transaction Note</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="all_credits_table">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="contact" role="tabpanel"
                                        aria-labelledby="contact-tab">
                                        <div class="table-responsive">
                                            <table data-searching="false" data-paging="false" data-info="false"
                                                data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                                class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Points</th>
                                                        <th>Transaction Note</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="all_debit_table">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function toogleBlock(id, block) {
            $.ajax({
                url: "{{ route('users.toogle-blocked.change') }}",
                type: "POST",
                data: {
                    user_id: id,
                    blocked: block
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                        .attr('content')
                },
            })
        }
    </script>
@endsection
