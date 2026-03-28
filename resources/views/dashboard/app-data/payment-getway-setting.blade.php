@extends('layouts.app')
@section('title', 'Admin | App Data Setting')
@section('content')
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <style>
            .colors,
            .short-info {
                display: flex;
                align-items: center;
            }

            .colors i,
            .short-info i {
                color: #088178;
                margin-right: 10px;
                font-size: 20px;
                cursor: pointer;
            }

            .ql-editor strong {
                font-weight: 700;
            }

            .ql-editor em {
                font-style: italic;
            }
        </style>
    @endpush
    <div class="loader">
    </div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="setting-tab" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" aria-selected="true">Setting</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="histroy-tab" data-toggle="tab" href="#histroy" role="tab" aria-controls="histroy" aria-selected="false">Histroy</a>
                                            </li>
                                        </ul>
                                    </div>
                                
                                    <div>
                                        <form action="{{ route('payment-getway-setting.index') }}" method="GET" class="form-inline mr-auto">
                                            <div class="search-element">
                                                <input name="searchValue" id="myInput" class="form-control" @if (isset($searchValue)) value="{{ $searchValue }}" @endif type="search" placeholder="Search" aria-label="Search" data-width="200">
                                                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="tab-content" id="myTabContent">
                                    <!-- Setting Tab -->
                                    <div class="tab-pane fade show active" id="setting" role="tabpanel" aria-labelledby="setting-tab">
                                        
                                        <div class="card px-3 pt-3">
                                            {{-- <div class="card-header mb-2">
                                                <h4>App Data Setting</h4>
                                            </div> --}}

                                            <!-- Sub Box Payin and Payout -->
                                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                                <li class="nav-item w-50" role="presentation">
                                                    <button class="nav-link active btn-sm border border-0 rounded-0 w-100" id="pills-payment-tab" data-bs-toggle="pill" data-bs-target="#pills-payment" type="button" role="tab" aria-controls="pills-payment" aria-selected="true"><b>Payment Method</b></button>
                                                </li>
                                                <li class="nav-item w-50" role="presentation">
                                                    <button class="nav-link btn-sm border border-0 rounded-0 w-100" id="pills-payOut-tab" data-bs-toggle="pill" data-bs-target="#pills-payOut" type="button" role="tab" aria-controls="pills-payOut" aria-selected="false"><b>PayOut Method</b></button>
                                                </li>
                                            </ul>
                                            <form enctype="multipart/form-data" method="post" action="{{ route('payment-getway-setting.update') }}"> 
                                                @csrf
                                                @if (session('success'))
                                                    <div class="alert alert-success mt-2">
                                                        {{ session('success') }}
                                                    </div>
                                                @endif
                                                
                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade show active" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
                                                        <div class="card-body p-0">

                                                            <div class="row my-2">
                                                                <div class="col-md-3">
                                                                    <div class="alert alert-light" role="alert">
                                                                        <b>Today : </b> Rs. {{ $depositAmount['today'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="alert alert-light" role="alert">
                                                                        <b>Yesterday : </b> Rs. {{ $depositAmount['yesterday'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="alert alert-light" role="alert">
                                                                        <b>This Month : </b> Rs. {{ $depositAmount['thisMonth'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="alert alert-light" role="alert">
                                                                        <b>Total : </b> Rs. {{ $depositAmount['total'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row">                                                                
                                                                <div class="form-group col-md-6">
                                                                    <label>Payment Method</label>
                                                                    <select id="payment_method" name="payment_method" class="form-control">
                                                                        <option value="auto"
                                                                            {{ old('payment_method') == 'auto' ? ' selected' : '' }}
                                                                            {{ $appData->payment_method == 'auto' ? ' selected' : '' }}>
                                                                            Auto</option>
                                                                        <option value="manual"
                                                                            {{ old('payment_method') == 'manual' ? ' selected' : '' }}{{ $appData->payment_method == 'manual' ? ' selected' : '' }}>
                                                                            Manual</option>
                                                                        <option value="direct_upi"
                                                                            {{ old('payment_method') == 'direct_upi' ? ' selected' : '' }}{{ $appData->payment_method == 'direct_upi' ? ' selected' : '' }}>
                                                                            Dicret UPI</option>
                                                                        <option value="ibr_pay"
                                                                            {{ old('payment_method') == 'ibr_pay' ? ' selected' : '' }}{{ $appData->payment_method == 'ibr_pay' ? ' selected' : '' }}>
                                                                            IBR Pay</option>
                                                                        <option value="upi_money"
                                                                            {{ old('payment_method') == 'upi_money' ? ' selected' : '' }}{{ $appData->payment_method == 'upi_money' ? ' selected' : '' }}>
                                                                            UPI Money</option>
                                                                        <option value="i_online_pay"
                                                                            {{ old('payment_method') == 'i_online_pay' ? ' selected' : '' }}{{ $appData->payment_method == 'i_online_pay' ? ' selected' : '' }}>
                                                                            I Online Pay</option>
                                                                        <option value="payment_karo"
                                                                            {{ old('payment_method') == 'payment_karo' ? ' selected' : '' }}{{ $appData->payment_method == 'payment_karo' ? ' selected' : '' }}>
                                                                            Payment Karo</option>
                                                                        <option value="planet_c"
                                                                            {{ old('payment_method') == 'planet_c' ? ' selected' : '' }}{{ $appData->payment_method == 'planet_c' ? ' selected' : '' }}>
                                                                            Planet C</option>
                                                                        <option value="sonic_pe"
                                                                            {{ old('payment_method') == 'sonic_pe' ? ' selected' : '' }}{{ $appData->payment_method == 'sonic_pe' ? ' selected' : '' }}>
                                                                            Sonic Pe</option>
                                                                        <option value="run_paisa"
                                                                            {{ old('payment_method') == 'run_paisa' ? ' selected' : '' }}{{ $appData->payment_method == 'run_paisa' ? ' selected' : '' }}>
                                                                            Run Paisa</option>
                                                                        <option value="pay_from_upi"
                                                                            {{ old('payment_method') == 'pay_from_upi' ? ' selected' : '' }}{{ $appData->payment_method == 'pay_from_upi' ? ' selected' : '' }}>
                                                                            Pay From UPI</option>
                                                                        <option value="rudrax_pay"
                                                                            {{ old('payment_method') == 'rudrax_pay' ? ' selected' : '' }}{{ $appData->payment_method == 'rudrax_pay' ? ' selected' : '' }}>
                                                                            Rudrax Pay</option>
                                                                        <option value="pay_o_matix"
                                                                            {{ old('payment_method') == 'pay_o_matix' ? ' selected' : '' }}{{ $appData->payment_method == 'pay_o_matix' ? ' selected' : '' }}>
                                                                            Pay O Matix</option>
                                                                    </select>
                                                                    @error('payment_method')
                                                                        <div class="alert alert-danger mt-2">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <!-- pay_from_upi_details -->
                                                                <div class="form-group col-6 pay_from_upi_details" style="display: none;">
                                                                    <label>PayFromUPI API Key</label>
                                                                    <div class="input-group">
                                                                        <input type="text" name="payfromupi_api_key" class="form-control" value="{{ old('payfromupi_api_key', $appData->payfromupi_api_key ?? '') }}">
                                                                    </div>
                                                                    @error('payfromupi_api_key')
                                                                        <div class="alert alert-danger mt-2">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                
                                                                <!-- direct_upi_details -->
                                                                <div class="form-group col-6 direct_upi_details" style="display: none;">
                                                                    <label>UPI Image</label>
                                                                    <div class="input-group">
                                                                        <img class="img-fluid" src="{{ $appData->upi_image ?? '' }}" 
                                                                        @if ($appData->upi_image)
                                                                            style="height: 150px;"
                                                                        @endif > 
                                                                        <input accept="image/*" type="file" name="upi_image" class="form-control" value="{{ old('upi_image', $appData->upi_image ?? '') }}">
                                                                    </div>
                                                                    @error('upi_image')
                                                                        <div class="alert alert-danger mt-2">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <div class="form-group col-12 text-right">
                                                                    <button type="submit" class="btn btn-outline-primary">Save</button>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-12">                                                                    
                                                                    <div class="table-responsive">
                                                                        <table data-searching="false" data-paging="false" data-info="false" data-order='[0, "desc"]]' id="myTable" class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>ID</th>
                                                                                    <th>Name</th>
                                                                                    <th>Phone</th>
                                                                                    <th>Amount</th>
                                                                                    <th>Request Type</th>
                                                                                    <th>Deposit Mode</th>
                                                                                    <th>Status</th>
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

                                                    <div class="tab-pane fade" id="pills-payOut" role="tabpanel" aria-labelledby="pills-payOut-tab">
                                                        <div class="card-body p-0">   

                                                            <div class="row my-2">
                                                                <div class="col-md-3">
                                                                    <div class="alert alert-light" role="alert">
                                                                        <b>Today : </b> Rs. {{ $withdrawAmount['today'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="alert alert-light" role="alert">
                                                                        <b>Yesterday : </b> Rs. {{ $withdrawAmount['yesterday'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="alert alert-light" role="alert">
                                                                        <b>This Month : </b> Rs. {{ $withdrawAmount['thisMonth'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="alert alert-light" role="alert">
                                                                        <b>Total : </b> Rs. {{ $withdrawAmount['total'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                                                                     
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <select id="withdrawal_method" name="withdrawal_method"
                                                                        class="form-control">
                                                                        <option value="manual"
                                                                            {{ old('withdrawal_method') == 'manual' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'manual' ? ' selected' : '' }}>
                                                                            Manual</option>
                                                                        <option value="ibr_pay"
                                                                            {{ old('withdrawal_method') == 'ibr_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'ibr_pay' ? ' selected' : '' }}>
                                                                            IBR Pay</option>
                                                                        <option value="upi_money"
                                                                            {{ old('withdrawal_method') == 'upi_money' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'upi_money' ? ' selected' : '' }}>
                                                                            UPI Money</option>
                                                                        <option value="i_online_pay"
                                                                            {{ old('withdrawal_method') == 'i_online_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'i_online_pay' ? ' selected' : '' }}>
                                                                            I Online Pay</option>
                                                                        <option value="cub_pay"
                                                                            {{ old('withdrawal_method') == 'cub_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'cub_pay' ? ' selected' : '' }}>
                                                                            Cub Pay</option>
                                                                        <option value="planet_c"
                                                                            {{ old('withdrawal_method') == 'planet_c' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'planet_c' ? ' selected' : '' }}>
                                                                            Planet C</option>
                                                                        <option value="sonic_pe"
                                                                            {{ old('withdrawal_method') == 'sonic_pe' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'sonic_pe' ? ' selected' : '' }}>
                                                                            Sonic Pe</option>
                                                                        <option value="run_paisa"
                                                                            {{ old('withdrawal_method') == 'run_paisa' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'run_paisa' ? ' selected' : '' }}>
                                                                            Run Paisa</option>
                                                                        <option value="click_pay"
                                                                            {{ old('withdrawal_method') == 'click_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'click_pay' ? ' selected' : '' }}>
                                                                            Click Pay</option>
                                                                        <option value="vagon_pay"
                                                                            {{ old('withdrawal_method') == 'vagon_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'vagon_pay' ? ' selected' : '' }}>
                                                                            Vagon Pay</option>
                                                                        <option value="rudrax_pay"
                                                                            {{ old('withdrawal_method') == 'rudrax_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'rudrax_pay' ? ' selected' : '' }}>
                                                                            Rudrax Pay</option>
                                                                        <option value="payinfintech" {{ old('withdrawal_method') == 'payinfintech' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'payinfintech' ? ' selected' : '' }}>Payin Fintech</option>
                                                                    </select>
                                                                    @error('withdrawal_method')
                                                                        <div class="alert alert-danger mt-2">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                
                                                                <div class="form-group col-12 text-right">
                                                                    <button type="submit" class="btn btn-outline-primary">Save</button>
                                                                </div>
                                                            </div>

                                                             <div class="row mb-3">
                                                                <div class="col-12">                                                                    
                                                                    <div class="table-responsive">
                                                                        <table data-searching="false" data-paging="false" data-info="false" data-order='[0, "desc"]]' id="myTable" class="table table-striped">
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
                                                                                    <th>Created At</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($withdrawHistories as $withdraw)
                                                                                    <tr>
                                                                                        <td>{{ $withdraw->id }}</td>
                                                                                        <td>{{ $withdraw->user->name }}</td>
                                                                                        <td>
                                                                                            @if (!env('APP_DEBUG'))
                                                                                                {{ $withdraw->user->phone }}
                                                                                            @else
                                                                                                {{ substr($withdraw->user->phone, 0, 3) . '****' . substr($withdraw->user->phone, 7, 10) }}
                                                                                            @endif
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
                                                </div>
                                            </form>
                                            
                                        </div>

                                    </div>

                                    <!-- History Tab -->
                                    <div class="tab-pane fade" id="histroy" role="tabpanel" aria-labelledby="histroy-tab">
                                        <div class="card px-3 pt-3">

                                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                                <li class="nav-item w-50" role="presentation">
                                                    <button class="nav-link active btn-sm border border-0 rounded-0 w-100" id="pills-depositHistory-tab" data-bs-toggle="pill" data-bs-target="#pills-depositHistory" type="button" role="tab" aria-controls="pills-depositHistory" aria-selected="true"><b>Deposit History</b></button>
                                                </li>
                                                <li class="nav-item w-50" role="presentation">
                                                    <button class="nav-link btn-sm border border-0 rounded-0 w-100" id="pills-withdrawalHistory-tab" data-bs-toggle="pill" data-bs-target="#pills-withdrawalHistory" type="button" role="tab" aria-controls="pills-withdrawalHistory" aria-selected="false"><b>Withdrawal History</b></button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-depositHistory" role="tabpanel" aria-labelledby="pills-depositHistory-tab">
                                                    <div class="card-body p-0">

                                                        <div class="row my-2">
                                                            <div class="col-md-3">
                                                                <div class="alert alert-light" role="alert">
                                                                    <b>Today : </b> Rs. {{ $depositAmount['todayAll'] }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="alert alert-light" role="alert">
                                                                    <b>Yesterday : </b> Rs. {{ $depositAmount['yesterdayAll'] }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="alert alert-light" role="alert">
                                                                    <b>This Month : </b> Rs. {{ $depositAmount['thisMonthAll'] }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="alert alert-light" role="alert">
                                                                    <b>Total : </b> Rs. {{ $depositAmount['totalAll'] }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-12">                                                                    
                                                                <div class="table-responsive">
                                                                    <table data-searching="false" data-paging="false" data-info="false" data-order='[0, "desc"]]' id="myTable" class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>Name</th>
                                                                                <th>Phone</th>
                                                                                <th>Amount</th>
                                                                                <th>Request Type</th>
                                                                                <th>Deposit Mode</th>
                                                                                <th>Status</th>
                                                                                <th>Created At</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="table-body">
                                                                            @foreach ($depositHistoryAll as $deposit)
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
                                                                                    <td>{{ $deposit->created_at }}</td>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="m-2" id="pagination">
                                                                    {{ $depositHistoryAll->links('pagination.custom') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="pills-withdrawalHistory" role="tabpanel" aria-labelledby="pills-withdrawalHistory-tab">
                                                    <div class="card-body p-0">   

                                                        <div class="row my-2">
                                                            <div class="col-md-3">
                                                                <div class="alert alert-light" role="alert">
                                                                    <b>Today : </b> Rs. {{ $withdrawAmount['todayAll'] }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="alert alert-light" role="alert">
                                                                    <b>Yesterday : </b> Rs. {{ $withdrawAmount['yesterdayAll'] }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="alert alert-light" role="alert">
                                                                    <b>This Month : </b> Rs. {{ $withdrawAmount['thisMonthAll'] }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="alert alert-light" role="alert">
                                                                    <b>Total : </b> Rs. {{ $withdrawAmount['totalAll'] }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-12">                                                                    
                                                                <div class="table-responsive">
                                                                    <table data-searching="false" data-paging="false" data-info="false" data-order='[0, "desc"]]' id="myTable" class="table table-striped">
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
                                                                                <th>Created At</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($withdrawHistoryAll as $withdraw)
                                                                                <tr>
                                                                                    <td>{{ $withdraw->id }}</td>
                                                                                    <td>{{ $withdraw->user->name }}</td>
                                                                                    <td>
                                                                                        @if (!env('APP_DEBUG'))
                                                                                            {{ $withdraw->user->phone }}
                                                                                        @else
                                                                                            {{ substr($withdraw->user->phone, 0, 3) . '****' . substr($withdraw->user->phone, 7, 10) }}
                                                                                        @endif
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
                                                                                    <td>{{ $withdraw->created_at }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="m-2" id="pagination">
                                                                    {{ $withdrawHistoryAll->links('pagination.custom') }}
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
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function (){
            var paymentMethod = document.querySelector('select[name="payment_method"]');
            paymentMethod.addEventListener('change', function() {
                // alert(this.value);
                if (this.value == 'pay_from_upi') {
                    document.querySelector('.pay_from_upi_details').style.display = 'block';
                    document.querySelector('.direct_upi_details').style.display = 'none';
                } 
                else if (this.value == 'direct_upi') {
                    document.querySelector('.direct_upi_details').style.display = 'block';
                    document.querySelector('.pay_from_upi_details').style.display = 'none';
                }
                else {
                    document.querySelector('.pay_from_upi_details').style.display = 'none';
                    document.querySelector('.direct_upi_details').style.display = 'none';
                }
            })

            
        });
    </script>
@endpush