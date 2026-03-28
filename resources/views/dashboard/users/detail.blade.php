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
                                    <h4>User Profile</h4>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <div class="col-12 border border-success rounded py-3 px-1">
                                                <h5 class="font-15 px-3">User Details</h5>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="mb-0">User Status</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="selectgroup ">
                                                                <label class="selectgroup-item">
                                                                    <input type="radio" value="1"
                                                                        name="blocked{{ $user->id }}"
                                                                        class="selectgroup-input-radio"
                                                                        @if ($user->blocked == 1) checked @endif
                                                                        onchange="toogleBlock({{ $user->id }},1)">
                                                                    <span style="padding-right: 0.5rem;padding-left: 0.5rem"
                                                                        class="selectgroup-button btn-sm">Blocked </span>
                                                                </label>
                                                                <label class="selectgroup-item">
                                                                    <input type="radio" name="blocked{{ $user->id }}"
                                                                        value="0" class="selectgroup-input-radio"
                                                                        @if ($user->blocked == 0) checked @endif
                                                                        onchange="toogleBlock({{ $user->id }},0)">
                                                                    <span style="padding-right: 0.5rem;padding-left: 0.5rem"
                                                                        class="selectgroup-button">Unblock</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="mb-0">Wallet Balance</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0" id="user_wallet_balance">{{ $user->balance }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 flex">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#exampleModal">Add or Withdraw Points</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <div class="col-12 border border-success rounded py-3 px-1">
                                                <h5 class="font-15 px-3">Profile Information</h5>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="mb-0">Full Name</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p id="user_name" class="mb-0">{{ $user->name }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="mb-0">Phone</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0" id="user_phone">{{ $user->phone }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="mb-0">Registered On</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0" id="user_created_on">{{ $user->created_at }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-1">
                                            <div class="col-12 border border-success rounded py-3 px-1">

                                                <h5 class="font-15 px-3">Payment Information</h5>
                                                <div class="row px-3">
                                                    <div class="col-6">
                                                        <p class="mb-0">Account Holder Name</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0" id="account_name">
                                                            @if (isset($user->withdrawDetails->account_holder_name))
                                                                {{ $user->withdrawDetails->account_holder_name }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="col-6">
                                                        <p class="mb-0">Bank Name</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0" id="bank_name">
                                                            @if (isset($user->withdrawDetails->bank_name))
                                                                {{ $user->withdrawDetails->bank_name }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="col-6">
                                                        <p class="mb-0">Account Number</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0" id="account_number">
                                                            @if (isset($user->withdrawDetails->account_number))
                                                                {{ $user->withdrawDetails->account_number }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="col-6">
                                                        <p class="mb-0">IFSC Code</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0" id="ifsc_code">
                                                            @if (isset($user->withdrawDetails->account_ifsc_code))
                                                                {{ $user->withdrawDetails->account_ifsc_code }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="col-6">
                                                        <p class="mb-0">UPI ID</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0" id="upi_id">
                                                            @if (isset($user->withdrawDetails->upi_id))
                                                                {{ $user->withdrawDetails->upi_id }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                         <div class="col-12 mb-1">
                                            <div class="col-12 border border-success rounded py-3 px-1">
                                                <h5 class="font-15 px-3">Balance Information</h5>
                                                <div class="row px-3">
                                                    <div class="col-6">
                                                        <p class="mb-0">Total Recharge</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0" id="account_name">
                                                            @if (isset($userTransaction['totalRecharge']))
                                                                Rs. {{ $userTransaction['totalRecharge'] }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="col-6">
                                                        <p class="mb-0">Total Withdraw</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0" id="bank_name">
                                                            @if (isset($userTransaction['totalWithdraw']))
                                                                Rs. {{ $userTransaction['totalWithdraw'] }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 my-3">
                                            <h5>Withdraw Points Request</h5>
                                            <div class="table-responsive">
                                                <table data-searching="false" data-paging="false" data-info="false"
                                                    id="myTablee" class="table table-striped">
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
                                                    <tbody>
                                                        @if (count($withdrawHistory) > 0)
                                                            @foreach ($withdrawHistory as $history)
                                                                <tr>
                                                                    <td>{{ $history->id }}</td>
                                                                    <td>{{ $history->amount }}</td>
                                                                    <td>{{ $history->request_type }}</td>
                                                                    <td>{{ $history->created_at }}</td>
                                                                    <td>{{ $history->status }}</td>
                                                                    <td>
                                                                        @if ($history->status == 'pending')
                                                                            <a href="{{ url('/withdraw-history/accept-request/' . $history->id) }}"
                                                                                class="btn btn-outline-primary">Accept</a>
                                                                            <a href="{{ url('/withdraw-history/reject-request/' . $history->id) }}"
                                                                                class="btn btn-outline-primary">Reject</a>
                                                                        @else
                                                                            No Action
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr class="odd">
                                                                <td colspan="6" class="text-center">No data available
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-12 my-3">
                                            <h5>Bid History</h5>
                                            <div class="table-responsive">
                                                <table data-searching="false" data-paging="false" data-info="false"
                                                    class="table table-striped">
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
                                                        @if (count($bidHistory) > 0)
                                                            @foreach ($bidHistory as $history)
                                                                <tr>
                                                                    <td>{{ $history->id }}</td>
                                                                    <td>{{ $history->market->name }}</td>
                                                                    <td>{{ $history->gametype->name }}</td>
                                                                    <td>{{ $history->number }}</td>
                                                                    <td>{{ $history->amount }}</td>
                                                                    <td>{{ $history->created_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr class="odd">
                                                                <td colspan="6" class="text-center">No data available
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-12 my-3">
                                            <h5>Transactions</h5>
                                            <div class="table-responsive">
                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="home-tab" data-toggle="tab"
                                                            href="#home" role="tab" aria-controls="home"
                                                            aria-selected="true">All</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="profile-tab" data-toggle="tab"
                                                            href="#profile" role="tab" aria-controls="profile"
                                                            aria-selected="false">Credit</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="contact-tab" data-toggle="tab"
                                                            href="#contact" role="tab" aria-controls="contact"
                                                            aria-selected="false">Debit</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                                        aria-labelledby="home-tab">
                                                        <div class="table-responsive">
                                                            <table data-searching="false" data-paging="false"
                                                                data-info="false" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>S.No</th>
                                                                        <th>Points</th>
                                                                        <th>Transaction Note</th>
                                                                        <th>Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="all_transactions_table">
                                                                    @if (count($transactionHistory) > 0)
                                                                        @foreach ($transactionHistory as $history)
                                                                            <tr>
                                                                                <td>{{ $history->id }}</td>
                                                                                <td>{{ $history->amount }}</td>
                                                                                <td>{{ $history->details }}</td>
                                                                                <td>{{ $history->created_at }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr class="odd">
                                                                            <td colspan="4" class="text-center">No data
                                                                                available</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="profile" role="tabpanel"
                                                        aria-labelledby="profile-tab">
                                                        <div class="table-responsive">
                                                            <table data-searching="false" data-paging="false"
                                                                data-info="false" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>S.No</th>
                                                                        <th>Points</th>
                                                                        <th>Transaction Note</th>
                                                                        <th>Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="all_credits_table">
                                                                    @if (count($creditHistory) > 0)
                                                                        @foreach ($creditHistory as $history)
                                                                            <tr>
                                                                                <td>{{ $history->id }}</td>
                                                                                <td>{{ $history->amount }}</td>
                                                                                <td>{{ $history->details }}</td>
                                                                                <td>{{ $history->created_at }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr class="odd">
                                                                            <td colspan="4" class="text-center">No data
                                                                                available</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="contact" role="tabpanel"
                                                        aria-labelledby="contact-tab">
                                                        <div class="table-responsive">
                                                            <table data-searching="false" data-paging="false"
                                                                data-info="false" class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>S.No</th>
                                                                        <th>Points</th>
                                                                        <th>Transaction Note</th>
                                                                        <th>Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="all_debit_table">
                                                                    @if (count($debitHistory) > 0)
                                                                        @foreach ($debitHistory as $history)
                                                                            <tr>
                                                                                <td>{{ $history->id }}</td>
                                                                                <td>{{ $history->amount }}</td>
                                                                                <td>{{ $history->details }}</td>
                                                                                <td>{{ $history->created_at }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr class="odd">
                                                                            <td colspan="4" class="text-center">No data
                                                                                available</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (!request()->is('users/detail/' . $user->id . '/all'))
                                                <a class="btn btn-info" href='/users/detail/{{ $user->id }}/all'>View
                                                    All</a>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Add | Deduct Balance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="text-success">Balance {{ $user->balance }}</h4>
                    <form method="post" action="{{ route('users.change-balance.store', ['user' => $user->id]) }}">
                        @csrf <div class="card-body">
                            <div class="form-group">
                                <label>Currency</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            $
                                        </div>
                                    </div>
                                    <input type="number" min="1" name="balance" class="form-control currency"
                                        value="{{ old('balance') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Choose Action </label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="action" value="+"
                                            class="selectgroup-input-radio" checked>
                                        <span class="selectgroup-button">Add </span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="action" value="-"
                                            class="selectgroup-input-radio">
                                        <span class="selectgroup-button">Deduct</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-primary">Submit</button>
                            </div>
                        </div>
                    </form>
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
