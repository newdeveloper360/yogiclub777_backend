@extends('webapp.layouts.master')

<!-- content start here -->
@section('content')

<!-- custom css here start -->
@section('css')

@stop
<!-- custom css here end -->

<!-- main start  -->
<main>

    <!-- Available Games start  -->
    <div class="AvailableGames">
        <div class="container">
            <div class="box">
                <div class="row text-center justify-content-center">

                    @if (!$appData->enable_desawar_only)
                    <h3 style="font-weight:unset; margin-bottom:unset;" class="mt-5">StarLine Markets</h3>
                    <!-- Start Line markets  -->
                    @foreach ($startLineMarkets as $market)
                    <div class="col-lg-4">
                        <div class="box-inner">
                            <div class="item d-flex">
                                <div class="item-left">
                                    <h4>{{ $market->name }}
                                        <span><img src="{{ asset('assets/frontend/images/info.png') }}" alt="img" class="img-fluid"></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="d-flex buttom-link justify-content-center">
                                <a href="{{ route('start-line-markets.chart', ['market' => $market->id]) }}">Pana
                                    Chart</a>
                            </div>
                        </div>
                    </div>
                    <!-- single item end -->
                    @endforeach
                    {{-- Markets --}}
                    <h3 style="font-weight:unset; margin-bottom:unset;" class="mt-5">Matka Markets</h3>
                    @foreach ($markets as $market)
                    <!-- Markets -->
                    <div class="col-lg-4">
                        <div class="box-inner">
                            <div class="item d-flex">
                                <div class="item-left">
                                    <h4>{{ $market->name }}
                                        <span><img src="{{ asset('assets/frontend/images/info.png') }}" alt="img" class="img-fluid"></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="d-flex buttom-link justify-content-center">
                                <a href="{{ route('markets.chart', ['market' => $market->id]) }}">Pana
                                    Chart</a>
                                <a href="{{ route('markets.jodi-chart', ['market' => $market->id]) }}">Jodi
                                    Chart</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif


                    <!-- Desawar markets  -->
                    <h3 style="font-weight:unset; margin-bottom:unset;" class="mt-5">Satta Markets</h3>
                    @foreach ($desawarMarkets as $market)
                    <div class="col-lg-4">
                        <div class="box-inner">
                            <div class="item d-flex">
                                <div class="item-left">
                                    <h4>{{ $market->name }}
                                        <span><img src="{{ asset('assets/frontend/images/info.png') }}" alt="img" class="img-fluid"></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="d-flex buttom-link justify-content-center">
                                <a href="{{ route('desawar-markets.chart_view', ['market' => $market->id]) }}">
                                    Jodi Chart</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Available Games end  -->

</main>
<!-- main end  -->
@stop