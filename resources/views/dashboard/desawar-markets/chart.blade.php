@extends('layouts.app')
@section('title', 'Admin | Desawar Market')
@section('content')
    @push('styles')
        <style>
            .table td,
            .table th {
                vertical-align: middle;
                text-align: center;
            }

            .number-link {
                text-decoration: none !important;
            }
            .key {
                font-weight: bold;
                color: red;
                border-radius: 4px;
                padding: 0px 8px;
                background: #ffdcdc;
            }

            .value {
                font-weight: normal;
                color: black;
            }

            .front-value {
                font-weight: bold;
                color: red;
            }

            .total-value {
                font-size: 1.5em;
                color: red;
                font-weight: bold;
                text-align: center;
            }
        </style>
    @endpush
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            <!-- Main Content -->
            <div class="main-content">
                <section class="section mb-5">
                    <div class="row">
                        <div class="col-12">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="card">
                                <h4 class="p-4">Desawar Market Chart</h4>
                                <div class="container">
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label>Date</label>
                                            <div class="input-group">
                                                <input type="date" name="date" class="form-control"
                                                    value="{{ request()->has('date') ? request()->query('date') : date('Y-m-d') }}">

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
                                            <button class="btn mb-3 btn-primary" onclick="window.location.reload()">Reload</button>
                                            <a href="{{ route('desawar-markets.download-excel', request()->query()) }}" 
                                                class="btn mb-3 btn-success">
                                                 <i class="fas fa-download"></i> Download Excel
                                            </a>
                                        </div>

                                    </div>

                                    <form action="{{ route('desawar-market-limit.store') }}" method="POST">
                                        @csrf
                                    <div class="row">
                                        <div class="form-group col-4">
                                            <label>Jodi Limit</label>
                                            <div class="input-group">
                                                <input type="number" name="jodiAmount" class="form-control" placeholder="Jodi Limit Amount" value="{{ $desawarMarketLimit ? $desawarMarketLimit->jodiAmount : 0 }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>Ander Limit</label>
                                            <div class="input-group">
                                                <input type="number" name="andarAmount" class="form-control" placeholder="Ander Limit Amount" value="{{ $desawarMarketLimit ? $desawarMarketLimit->andarAmount : 0 }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>Bahar Limit</label>
                                            <div class="input-group">
                                                <input type="number" name="baharAmount" class="form-control" placeholder="Bahar Limit Amount" value="{{ $desawarMarketLimit ? $desawarMarketLimit->baharAmount : 0 }}" required>
                                            </div>
                                        </div>
                                       
                                        {{-- reload page div button --}}
                                        <div class="col-12 text-right">
                                            <button style="submit" class="btn mb-3 btn-primary" >Save Amount</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card">
                                <h4 class="p-2">Jodi</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <!-- Example for one row -->
                                        @foreach ($jodiData as $key => $item)
                                            @if ($key == 0)
                                                @php
                                                    $totalAmountPerRow = 0;
                                                    $grandTotalAmount = 0;
                                                @endphp
                                                @continue
                                            @endif
                                            <td 
                                            class="@if ($desawarMarketLimit && $desawarMarketLimit->jodiAmount < $item['total_amount'])
                                                bg-warning
                                                @else bg-white 
                                            @endif"
                                            >
                                                <a href="{{ route('desawar-markets.chartNoRecords', ['number' => $key]) }}" class="number-link"><span class="key">{{ $key }}</span></a>
                                                <br>
                                                <span class="value">{{ $item['total_amount'] }}</span>
                                            </td>
                                            @php
                                                $totalAmountPerRow += $item['total_amount'];
                                                $grandTotalAmount += $item['total_amount'];
                                            @endphp
                                            @if ($key % 10 === 0)
                                                <td>
                                                    <span class="key">Total</span>
                                                    <br> <span class="value"> {{ $totalAmountPerRow }} </span>
                                                </td>
                                                @php
                                                    $totalAmountPerRow = 0;
                                                @endphp
                                                </tr>
                                            @endif
                                        @endforeach
                                        <!-- Repeat for the other rows -->
                                    </tbody>
                                </table>
                                <div class="total-value">Total:
                                    {{ number_format($grandTotalAmount) }}
                                </div>
                            </div>
                            <hr>
                            <div class="card">
                                <h4 class="p-2">Andar</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <!-- Example for one row -->
                                        <tr>
                                            @foreach ($andarCounts as $key => $value)
                                                <td 
                                                class="@if ($desawarMarketLimit && $desawarMarketLimit->andarAmount < $value)
                                                    bg-warning
                                                    @else bg-white
                                                @endif"
                                                >
                                                    <a href="{{ route('desawar-markets.chartNoRecords', ['number' => $key == 0 ? '000' : $key]) }}" class="number-link"><span class="key">{{ $key == 0 ? '000' : $key }}</span></a>
                                                    <br>
                                                    <span class="value">{{ $value }}</span>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <!-- Repeat for the other rows -->
                                    </tbody>
                                </table>
                                <div class="total-value">Total:
                                    {{ number_format(array_sum($andarCounts)) }}
                                </div>
                            </div>
                            <hr>
                            <div class="card">
                                <h4 class="p-2">Bahar</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <!-- Example for one row -->
                                        <tr>
                                            @foreach ($baharCounts as $key => $value)
                                                <td
                                                class="@if ($desawarMarketLimit && $desawarMarketLimit->baharAmount < $value)
                                                    bg-warning
                                                    @else bg-white
                                                @endif"
                                                >
                                                    <a href="{{ route('desawar-markets.chartNoRecords', ['number' => $key == 0 ? '000' : $key]) }}" class="number-link"><span class="key">{{ $key == 0 ? '000' : $key }}</span></a>
                                                    <br>
                                                    <span class="value">{{ $value }}</span>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <!-- Repeat for the other rows -->
                                    </tbody>
                                </table>
                                <div class="total-value">Total:
                                    {{ number_format(array_sum($baharCounts)) }}
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
