@extends('layouts.app')
@section('title', 'Admin | General Win History')
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
                                <div class="card-header" style="display: block;">
                                    <form action="{{ url('/markets/data') }}" method="get">
                                        <div class="row">
                                            <h4>General Markets Record ({{ isset($date) ? $date : date('Y-m-d') }})</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card-content">
                                                    <div class="form-group mb-3">
                                                        <label>Date</label>
                                                        <input type="date" name="date" class="form-control datepicker"
                                                            value="{{ isset($date) ? $date : date('Y-m-d') }}"
                                                            placeholder="YYYY/MM/DD">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card-content">
                                                    <div class="form-group mb-3">
                                                        <label>Market Name</label>
                                                        <select class="form-control" required name="market_id">
                                                            @foreach ($markets as $type)
                                                                <option value="{{ $type->id }}"
                                                                    {{ request()->get('market_id') == $type->id ? 'selected' : '' }}>
                                                                    {{ $type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card-content">
                                                    <div class="form-group mb-3">
                                                        <label>Market Time</label>
                                                        <select class="form-control" name="market_time">
                                                            <option value="open"
                                                                {{ request()->get('market_time') == 'open' ? 'selected' : '' }}>
                                                                Open</option>
                                                            <option value="close"
                                                                {{ request()->get('market_time') == 'close' ? 'selected' : '' }}>
                                                                Close</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="card-content">
                                                    <button type="submit" class="btn btn-primary">GET</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="card-body px-5 py-3 mb-5">


                                        <div style="display: flex; justify-content: space-between; overflow-x: scroll;">
                                            <div>
                                                <div class="bold text-center">Sr No.</div>
                                                @for ($i = 1; $i <= $count; $i++)
                                                    <div class="text-center">
                                                        {{ $i }}
                                                    </div>
                                                @endfor
                                                <div class="bold text-center">Total.</div>
                                            </div>

                                            @foreach ($data as $record)
                                                @php
                                                    $total = 0;
                                                @endphp
                                                <div class="flex-column">
                                                    <div>
                                                        <div class="bold text-center">{{ $record->gameType->name }}</div>
                                                        @foreach ($record as $rec)
                                                            @php
                                                                $total += $rec->total_amount;
                                                            @endphp
                                                            <div class="text-center">{{ $rec->number }} =
                                                                {{ $rec->total_amount }}</div>
                                                        @endforeach
                                                    </div>
                                                    <div class="bold text-center">
                                                        {{ $total }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                    <button class="btn btn-primary" data-text="{!! $dataForClipboard !!}" id="copyButton">Copy
                                        to
                                        Clipboard</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Add click event listener to the button
            $('#copyButton').click(function() {
                console.log('clicked');
                var textToCopy = $(this).data('text');
                if (!textToCopy) {
                    alert('No data to copy, Please Select Market');
                    return;
                }
                var tempInput = $('<textarea>');
                $('body').append(tempInput);
                tempInput.val(textToCopy);
                tempInput.select();
                document.execCommand('copy');
                tempInput.remove();
            });
        });
    </script>

@endsection
