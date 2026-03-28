@extends('layouts.app')
@section('title', 'Admin | Users Withdraw Details')
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
                                    <h4>Withdraw Details</h4>
                                    <form action="{{ route('users.withdraw-details.index') }}" method="GET"
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
                                                    <th>Name</th>
                                                    <th>Account Holder Name</th>
                                                    <th>Upi Name</th>
                                                    <th>Account Number</th>
                                                    <th>Account IFSC Code</th>
                                                    <th>UP ID</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($withdrawDetails as $wd)
                                                    <tr>
                                                        <td>{{ $wd->id }}</td>
                                                        <td>{{ $wd->user->name }}</td>
                                                        <td>{{ $wd->account_holder_name }}</td>
                                                        <td>{{ $wd->upi_name }}</td>
                                                        <td>{{ $wd->account_number }}</td>
                                                        <td>{{ $wd->account_ifsc_code }}</td>
                                                        <td>{{ $wd->upi_id }}</td>
                                                        <td>{{ $wd->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $withdrawDetails->links('pagination.custom') }}
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
