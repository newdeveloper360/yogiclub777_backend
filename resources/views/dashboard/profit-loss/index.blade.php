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
                                    <h4>Profit And Loss</h4>
                                    <form action="{{ route('profit-loss.index') }}" method="GET"
                                        class="form-inline mr-auto">
                                        <div class="search-element">
                                            <input name="date" class="form-control" @if (isset($date)) value="{{ $date }}" @endif type="date" aria-label="Date" data-width="200">
                                        </div>
                                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                                    </form>
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive">
                                        <table data-searching="false" data-paging="false" data-info="false"
                                            data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                            class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Name</th>
                                                    <th>Bid Amount</th>
                                                    <th>Win Amount</th>
                                                    <th>Profit and Loss</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($desawarRecords as $record)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $record->market->name }}</td>
                                                        <td>Rs. <b>{{ $record->total_amount ?? '0' }}</b></td>
                                                        <td class="{{ $record->total_win_amount > 0  ? 'text-success' : ''}}">Rs. <b>{{ $record->total_win_amount ?? '0' }}</b></td>
                                                        <td>
                                                            @if ($record->total_amount - $record->total_win_amount > 0)
                                                                <span class="text-success">Rs. <b>{{ $record->total_amount - $record->total_win_amount }}</b></span>
                                                            @else   
                                                                <span class="text-danger">Rs. <b>{{ $record->total_amount - $record->total_win_amount }}</b></span>
                                                            @endif                                                            
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
