@extends('layouts.app')
@section('title', 'Admin | General Records')
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
                                    <h4>General Markets Record</h4>
                                    <form action="{{ route('markets.records') }}" method="GET" class="form-inline mr-auto">
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
                                                    <th>Market Name</th>
                                                    <th>User Name</th>
                                                    <th>User Phone</th>
                                                    <th>Session</th>
                                                    <th>Number</th>
                                                    <th>Amount</th>
                                                    <th>Win Amount</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                    {{-- action --}}
                                                    <th>Actions</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($marketRecords as $record)
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
                                                        <td>{{ $record->session == 'null' ? '' : $record->session }}</td>
                                                        <td>{{ $record->number . ' (' . $record->gameType->name . ')' }}
                                                        </td>
                                                        <td>{{ $record->amount }}</td>
                                                        <td>{{ $record->win_amount ?? 'NULL' }}</td>
                                                        <td @class([
                                                            'text-info' => $record->status == 'PENDING',
                                                            'text-success' => $record->status == 'SUCCESS',
                                                            'text-danger' => $record->status == 'FAILED',
                                                        ])>
                                                            {{ $record->status }}
                                                        </td>
                                                        <td>{{ $record->created_at }}</td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                                data-target="#editBidModal{{ $record->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="editBidModal{{ $record->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="editBidModalLabel{{ $record->id }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <style>
                                                                            .modal {
                                                                                z-index: 1050;
                                                                                /* Ensure the modal appears above other content */
                                                                            }

                                                                            .interfering-div {
                                                                                z-index: 1030;
                                                                            }

                                                                            .modal-backdrop {
                                                                                opacity: 0.5;
                                                                                display: none;
                                                                                z-index: 1040;
                                                                            }
                                                                        </style>
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="editBidModalLabel{{ $record->id }}">
                                                                                Edit Bid</h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <!-- Form for editing bid -->
                                                                            <form id="editBidForm{{ $record->id }}"
                                                                                action="{{ route('markets.records.update') }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                {{-- id hidden --}}
                                                                                <input type="hidden" name="id"
                                                                                    value="{{ $record->id }}">
                                                                                {{-- market type hidden --}}
                                                                                <input type="hidden" name="market_type"
                                                                                    value="market">
                                                                                <!-- Number input -->
                                                                                <div class="form-group">
                                                                                    <label for="number">Number:</label>
                                                                                    <input type="text"
                                                                                        value="{{ $record->number }}"
                                                                                        class="form-control" id="number"
                                                                                        name="number">
                                                                                </div>
                                                                                <!-- Amount input -->
                                                                                <div class="form-group">
                                                                                    <label for="amount">Amount:</label>
                                                                                    <input type="text"
                                                                                        value="{{ $record->amount }}"
                                                                                        class="form-control" id="amount"
                                                                                        name="amount">
                                                                                </div>
                                                                                <button type="submit"
                                                                                    class="btn btn-primary">Submit</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $marketRecords->links('pagination.custom') }}
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
