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
                                <h4 class="p-4">Number Records Filter</h4>
                                <div class="container">
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label>Date</label>
                                            <div class="input-group">
                                                <input type="date" name="date" class="form-control" value="{{ request()->has('date') ? request()->query('date') : date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-6">
                                            <label>DesawarMarkets</label>
                                            <select class="form-control" name="market_time">
                                                <option value="0">All Markets</option>
                                                @foreach ($desawarMarkets as $market)
                                                    <option value="{{ $market->id }}"
                                                        @if (request()->query('market_id') == $market->id) selected @endif>
                                                        {{ $market->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- reload page div button --}}
                                        <div class="col-12 text-right">
                                            <button class="btn mb-3 btn-primary"
                                                onclick="window.location.reload()">Reload</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="card">
                                <div class="card-header">
                                    <h4>Desawar Markets Number Records</h4>
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
                                                    <th>User Name</th>
                                                    <th>User Phone</th>
                                                    <th>Jodi</th>
                                                    <th>Amount</th>
                                                    <th>Win Amount</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($desawarRecords as $record)
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
                                                        <td>{{ $record->number . ' (' . $record->gameType->name . ')' }}
                                                        </td>
                                                        <td>{{ $record->amount }}</td>
                                                        <td>{{ $record->win_amount ?? 'NULL' }}</td>
                                                        <td @class([
                                                            'text-info' => $record->status == 'PENDING',
                                                            'text-success' => $record->status == 'SUCCESS',
                                                            'text-danger' => $record->status == 'FAILED',
                                                            'text-danger' => $record->status == 'CANCELED',
                                                        ])>
                                                            {{ $record->status }}
                                                        </td>
                                                        <td>{{ $record->created_at }}</td>
                                                        <td>
                                                            @if ($record->status != 'CANCELED' && $record->status != 'FAILED')
                                                                <a href="{{ route('cancelBet', ['id' => $record->id]) }}" onclick="return confirm('Are you sure you want to canceled? ')" class="btn btn-sm btn-danger">Cancel</a>                                                                
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="m-2" id="pagination">
                                        {{ $desawarRecords->appends(request()->query())->links('pagination.custom') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dateInput = document.querySelector('input[name="date"]');
                const marketSelect = document.querySelector('select[name="market_time"]');

                if (dateInput) {
                    dateInput.addEventListener('change', updatePageURL);
                }

                if (marketSelect) {
                    marketSelect.addEventListener('change', updatePageURL);
                }

                function updatePageURL() {
                    // Get current URL and its search parameters
                    const currentUrl = new URL(window.location);
                    const searchParams = currentUrl.searchParams;

                    // Update date parameter, if changed
                    const newDate = dateInput ? dateInput.value : null;
                    if (newDate) {
                        searchParams.set('date', newDate);
                    }

                    // Update market_id parameter, if changed
                    const newMarketId = marketSelect ? marketSelect.value : null;

                    if (newMarketId) {
                        if (newMarketId != 0) {
                            searchParams.set('market_id', newMarketId);
                        } else {
                            searchParams.delete('market_id');
                        }
                    }

                    // Construct the new URL with updated search parameters
                    const newUrl = currentUrl.pathname + '?' + searchParams.toString();                    

                    // Redirect to the new URL
                    window.location.href = newUrl;
                }
            });
        </script>
    @endpush    
@endsection
