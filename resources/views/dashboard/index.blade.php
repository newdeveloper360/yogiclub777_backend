@extends('layouts.app')

@section('content')
<div>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="row ">
                        @can('clear-dashboard-data')
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 text-right d-none">
                            <a href="{{ route('deleteChetDepositWithdrawl') }}" class="btn btn-sm btn-outline-danger">Clear Dashboard Data</a>
                        </div>       
                        @endcan
                        @can('dashboard-total-markets')
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Total Markets</h5>
                                                    <h2 class="mb-3 font-18">{{ $markets }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/backend/img/banner/1.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('dashboard-total-users')
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Total Users</h5>
                                                    <h2 class="mb-3 font-18">{{ $users }}</h2>
                                                    <p class="mb-0">
                                                        <span class="col-green">{{ $todayUsers }}</span>
                                                        Users Today
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/backend/img/banner/2.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('today-dashboard-record')
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Today Bid Amount</h5>
                                                    <h2 class="mb-3 font-18">{{ $todayGameAmount }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/backend/img/banner/4.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Today Win amount</h5>
                                                    <h2 class="mb-3 font-18">{{ $todayWinAmount }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/backend/img/banner/4.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('dashboard-total-deposits')
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Today Deposit</h5>
                                                    <h2 class="mb-3 font-18">{{ $todayDeposit }}</h2>
                                                    <p class="mb-0"><span class="col-green">{{ $deposit}}</span>
                                                        Total Deposit</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/backend/img/banner/4.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('dashboard-total-withdraws')
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Today Withdraw</h5>
                                                    <h2 class="mb-3 font-18">{{ $todayWithdraw }}</h2>
                                                    <p class="mb-0"><span class="col-green">{{ $withdraw}}</span>
                                                        Total Withdraw</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/backend/img/banner/4.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan

                        @can('total-wallet-balance')
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Total Wallet Balance</h5>
                                                    <h2 class="mb-3 font-18">{{ $walletBalance }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/backend/img/banner/4.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan

                        @can('today-dashboard-record')
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Total Mannualy added Wallet Balance</h5>
                                                    <h2 class="mb-3 font-18">{{ $appData->total_mannual_amount_added }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/backend/img/banner/4.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15">Today Profit & Loss</h5>
                                                    <h2 class="mb-3 font-18 {{ $profitAndLoss > 0 ? 'text-success' : 'text-danger' }}">
                                                        @if ($profitAndLoss > 0)
                                                            {{ number_format($profitAndLoss) }}   
                                                        @else
                                                            - {{ number_format($profitAndLoss) }}                                                     
                                                        @endif
                                                    </h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/backend/img/banner/4.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan

                        @if (!$appData->enable_desawar_only)
                        <div class="col-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-12">

                                                <h5 class="font-15">Today Bids on Single Ank of Date {{ now()->format('d M,Y') }}</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card-content">
                                                    <div class="form-group">
                                                        <label>Game Name</label>
                                                        <select class="form-control" name="game_type_id">
                                                            <option value="">Select Type</option>
                                                            @foreach($gameType as $type)
                                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card-content">
                                                    <div class="form-group">
                                                        <label>Market Time</label>
                                                        <select class="form-control" name="market_time">
                                                            <option value="open">Open</option>
                                                            <option value="close">Close</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="card-content">
                                                    <button type="button" onclick="getBidsDetail()" class="btn btn-primary">GET</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_0_bids">0</span></h5>
                                                    <h2 class="text-center" id="ank_0_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 0</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_1_bid">0</span></h5>
                                                    <h2 class="text-center" id="ank_1_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 1</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_2_bids">0</span></h5>
                                                    <h2 class="text-center" id="ank_2_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 2</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_3_bids">0</span></h5>
                                                    <h2 class="text-center" id="ank_3_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 3</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_4_bids">0</span></h5>
                                                    <h2 class="text-center" id="ank_4_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 4</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_5_bids">0</span></h5>
                                                    <h2 class="text-center" id="ank_5_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 5</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_6_bids">0</span></h5>
                                                    <h2 class="text-center" id="ank_6_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 6</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_7_bids">0</span></h5>
                                                    <h2 class="text-center" id="ank_7_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 7</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_8_bids">0</span></h5>
                                                    <h2 class="text-center" id="ank_8_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 8</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <div class="card-content box_border">
                                                    <h5 class="font-15 mt-3 text-center">Total Bids <span id="ank_9_bids">0</span></h5>
                                                    <h2 class="text-center" id="ank_9_amount">0</h2>
                                                    <h5 class="font-15 text-center">Total Bid Amount</h5>
                                                    <h5 class="font-15 text-center underline">Ank 9</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection