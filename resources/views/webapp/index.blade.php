@extends('webapp.layouts.master')

<!-- content start here -->
@section('content')

<!-- custom css here start -->
@section('css')
<style>
    .float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 60px;
        right: 10px;
    }

    .my-float {
        margin-top: 22px;
    }
</style>
@stop
<!-- custom css here end -->

<!-- main start  -->
<main>
    <div class="download-app x">
        <div class="container">
            <a href="{{ route('download-apk') }}" class="d-block text-center">
                <svg viewBox="64 64 896 896" focusable="false" data-icon="android" width="28px" height="28px" fill="currentColor" style="margin-bottom: 6px" aria-hidden="true">
                    <i style="height: 35px;" class="fa-brands fa-download"></i>
                </svg> <strong>Download Now</strong></a>
        </div>
    </div>
    <div class="slider-img">
        <div class="container">
            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($sliderImages as $img)
                    <div class="carousel-item active" data-bs-interval="10000">
                        <img src="{{ $img->url }}" class="d-block w-100" alt="...">
                    </div>
                    @endforeach

                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    <!-- Game Rates star  -->
    <div class="game-rate">
        <div class="container">
            <h2>Game Rates</h2>
            <!-- row start  -->
            <div class="row">
                <!-- single item start  -->
                @foreach ($gameTypes as $gameType)
                <div class="col-lg-4">
                    <div class="box">
                        <div class="item">
                            <img src="{{ asset('assets/frontend/images/icon.png') }}" alt="img" class="img-fluid">
                            <h4>{{ $gameType->name }} ({{ ucfirst(str_replace('_', ' ', $gameType->type)) }})</h4>
                            <p>1 RS KA {{ $gameType->multiply_by }} Rs</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- row end -->
    </div>

    </div>
    <!-- Game Rates end -->

    <!-- Available Games start  -->
    <div class="AvailableGames">
        <div class="container">
            <h2>Available Games</h2>
            <div class="box">
                <div class="row">
                    @if (!$appData->enable_desawar_only)
                    <!-- Start Line markets  -->
                    @foreach ($startLineMarkets as $market)
                    <div class="col-lg-4">
                        <div class="box-inner">
                            <div class="item d-flex">
                                <div class="item-left">
                                    <h4>{{ $market->name }} {{ date('h:i A', strtotime($market->open_time)) }}
                                        <span><img src="{{ asset('assets/frontend/images/info.png') }}" alt="img" class="img-fluid"></span>
                                    </h4>
                                    <h3>{{ $market->last_result->open_pana ?? '' }}-{{ $market->last_result->open_digit ?? '' }}
                                    </h3>
                                </div>
                                <div class="item-end">
                                    <h6 @class(['text-success', $market->gameOn => true])>
                                        {{ $market->gameOn ? 'Open Today' : 'Closed for Today' }}
                                    </h6>
                                    <a href="{{ route('download-apk') }}" target="_blank">
                                        <svg id="video" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 31.277 31.708">
                                            <path id="Path_593" data-name="Path 593" d="M15.589,0A15.589,15.589,0,1,1,0,15.589,15.589,15.589,0,0,1,15.589,0Z" transform="translate(0.098 0)" fill="#fc0"></path>
                                            <path id="Path_590" data-name="Path 590" d="M31.179,256H0a15.589,15.589,0,1,0,31.179,0Z" transform="translate(0 -239.882)" fill="#fab028"></path>
                                            <g id="Group_1840" data-name="Group 1840" transform="translate(11.884 8.643)">
                                                <g id="Group_1786" data-name="Group 1786" transform="translate(0 0)">
                                                    <g id="Group_1785" data-name="Group 1785">
                                                        <path id="Path_591" data-name="Path 591" d="M171.741,125.324a.741.741,0,0,1-.741-.741V111.741a.741.741,0,0,1,1.175-.6l8.89,6.421a.741.741,0,0,1,0,1.2l-8.89,6.421A.74.74,0,0,1,171.741,125.324Z" transform="translate(-171 -111)" fill="#fff"></path>
                                                    </g>
                                                </g>
                                                <g id="Group_1787" data-name="Group 1787" transform="translate(0 7.162)">
                                                    <path id="Path_592" data-name="Path 592" d="M171,256v6.421a.741.741,0,0,0,1.175.6l8.89-6.421a.741.741,0,0,0,.307-.6Z" transform="translate(-171 -256)" fill="#fff"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
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
                    @foreach ($markets as $market)
                    <!-- Markets -->
                    <div class="col-lg-4">
                        <div class="box-inner">
                            <div class="item d-flex">
                                <div class="item-left">
                                    <h4>{{ $market->name }} {{ date('h:i A', strtotime($market->open_time)) }}
                                        <span><img src="{{ asset('assets/frontend/images/info.png') }}" alt="img" class="img-fluid"></span>
                                    </h4>
                                    <h3>{{ $market->last_result->result ?? '' }}</h3>
                                </div>
                                <div class="item-end">
                                    <h6 @class(['text-success', $market->gameOn => true])>
                                        {{ $market->gameOn ? 'Open Today' : 'Closed for Today' }}
                                    </h6>
                                    <a href="{{ route('download-apk') }}" target="_blank"><svg id="video" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 31.277 31.708">
                                            <path id="Path_593" data-name="Path 593" d="M15.589,0A15.589,15.589,0,1,1,0,15.589,15.589,15.589,0,0,1,15.589,0Z" transform="translate(0.098 0)" fill="#fc0"></path>
                                            <path id="Path_590" data-name="Path 590" d="M31.179,256H0a15.589,15.589,0,1,0,31.179,0Z" transform="translate(0 -239.882)" fill="#fab028"></path>
                                            <g id="Group_1840" data-name="Group 1840" transform="translate(11.884 8.643)">
                                                <g id="Group_1786" data-name="Group 1786" transform="translate(0 0)">
                                                    <g id="Group_1785" data-name="Group 1785">
                                                        <path id="Path_591" data-name="Path 591" d="M171.741,125.324a.741.741,0,0,1-.741-.741V111.741a.741.741,0,0,1,1.175-.6l8.89,6.421a.741.741,0,0,1,0,1.2l-8.89,6.421A.74.74,0,0,1,171.741,125.324Z" transform="translate(-171 -111)" fill="#fff">
                                                        </path>
                                                    </g>
                                                </g>
                                                <g id="Group_1787" data-name="Group 1787" transform="translate(0 7.162)">
                                                    <path id="Path_592" data-name="Path 592" d="M171,256v6.421a.741.741,0,0,0,1.175.6l8.89-6.421a.741.741,0,0,0,.307-.6Z" transform="translate(-171 -256)" fill="#fff"></path>
                                                </g>
                                            </g>
                                        </svg></a>
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
                    @foreach ($desawarMarkets as $market)
                    <div class="col-lg-4">
                        <div class="box-inner">
                            <div class="item d-flex">
                                <div class="item-left">
                                    <h4>{{ $market->name }} {{ date('h:i A', strtotime($market->open_time)) }}
                                        <span><img src="{{ asset('assets/frontend/images/info.png') }}" alt="img" class="img-fluid"></span>
                                    </h4>
                                    <h3>
                                        {{ $market->last_result->result ?? '' }}
                                    </h3>
                                </div>
                                <div class="item-end">

                                    <h6 @class(['text-success', $market->gameOn => true])>
                                        {{ $market->gameOn ? 'Open Today' : 'Closed for Today' }}
                                    </h6>
                                    <a href="{{ route('download-apk') }}" target="_blank">
                                        <svg id="video" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 31.277 31.708">
                                            <path id="Path_593" data-name="Path 593" d="M15.589,0A15.589,15.589,0,1,1,0,15.589,15.589,15.589,0,0,1,15.589,0Z" transform="translate(0.098 0)" fill="#fc0"></path>
                                            <path id="Path_590" data-name="Path 590" d="M31.179,256H0a15.589,15.589,0,1,0,31.179,0Z" transform="translate(0 -239.882)" fill="#fab028"></path>
                                            <g id="Group_1840" data-name="Group 1840" transform="translate(11.884 8.643)">
                                                <g id="Group_1786" data-name="Group 1786" transform="translate(0 0)">
                                                    <g id="Group_1785" data-name="Group 1785">
                                                        <path id="Path_591" data-name="Path 591" d="M171.741,125.324a.741.741,0,0,1-.741-.741V111.741a.741.741,0,0,1,1.175-.6l8.89,6.421a.741.741,0,0,1,0,1.2l-8.89,6.421A.74.74,0,0,1,171.741,125.324Z" transform="translate(-171 -111)" fill="#fff">
                                                        </path>
                                                    </g>
                                                </g>
                                                <g id="Group_1787" data-name="Group 1787" transform="translate(0 7.162)">
                                                    <path id="Path_592" data-name="Path 592" d="M171,256v6.421a.741.741,0,0,0,1.175.6l8.89-6.421a.741.741,0,0,0,.307-.6Z" transform="translate(-171 -256)" fill="#fff"></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex buttom-link justify-content-center">
                                <a href="{{ route('desawar-markets.chart', ['market' => $market->id]) }}">
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
    <!-- faq start -->
    <div class="faq">
        <div class="container">
            <div class="faq_top text-center">
                <h2>Frequently asked <strong>questions?</strong></h2>
            </div>
            <div class="box">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item shadow">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                What is {{ env('APP_NAME') }} ?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ env('APP_NAME') }} Is A Satta Matka Gamming App Where You Can Play Games And Win Jackpot.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item shadow">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                What are games are available on {{ env('APP_NAME') }} ?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>Populer Satta Matka Games Like Kalyan, Sridevi, Milan, Time Bazar, Matka &
                                    Rajdhani.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item shadow">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Is {{ env('APP_NAME') }} Have Licence ?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>Yes, Matka International N.V. Have Sub License In Isle Of Man. All Rights That
                                    Allows
                                    To Operate Software Worldwide.
                                </p>
                            </div>
                        </div>
                    </div>



                    <div class="accordion-item shadow">
                        <h2 class="accordion-header" id="headingOne1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                                What Is Minimum Deposit and Withdrawals ?
                            </button>
                        </h2>
                        <div id="collapseOne1" class="accordion-collapse collapse " aria-labelledby="headingOne1" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>In {{ env('APP_NAME') }} We Allow 100 Rs/- Deposit and 100 Rs/- Withdrawals.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item shadow">
                        <h2 class="accordion-header" id="headingTwo1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo1">
                                Why to Choose {{ env('APP_NAME') }} Among Other Providers ?
                            </button>
                        </h2>
                        <div id="collapseTwo1" class="accordion-collapse collapse" aria-labelledby="headingTwo1" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ env('APP_NAME') }} Is The Most Trusted Gaming Provider Worldwide.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- faq end -->
    <div class="footer-top text-center">
        <div class="container">
            <div class="item-top">
                <a href=""><img src="{{ asset('logo.png') }}" alt="img" class="img-fluid"></a>
            </div>
            <div class="pluse18">
                <img src="{{ asset('assets/frontend/images/18plus.svg') }}" alt="img" class="img-fluid">
                <p>Players need to be 18+ in order to register. Underage gambling is prohibited.
                </p>
            </div>
            <div class="item-brand d-sm-flex justify-content-between">
                <a href="" class="mb-sm-0 mb-4"><img src="{{ asset('assets/frontend/images/gambleaware.svg') }}" alt="img" class="img-fluid"></a>
                <a href=""><img src="{{ asset('assets/frontend/images/gamblingtherapy.svg') }}" alt="img" class="img-fluid"></a>
            </div>
            <div class="text-item">
                <p>Our website is operated by Matka International, a company established under the law of Isle of
                    Man, with registered
                    address at 1-10 Ballanoa Meadow IM4-2HT, Isle Of Man, and having its gaming sublicense issued by
                    Isle of Man e-Gaming
                    and all rights to operate the gaming software worldwide.
                </p>
            </div>
        </div>
    </div>

    @if ($appData->telegram_enable)
    <div class="justify-content-center d-flex">
        <a href="{{ $appData->telegram_link }}" target="_blank" class="float">
            <img style="width:55px;height:55px;" src="{{asset('/assets/frontend/images/telegram-logo.png')}}" class="img-fluid" alt="" srcset="">
        </a>
    </div>
    @elseif ($appData->whatsapp_enable)
    <div class="justify-content-center d-flex">
        <a href="https://wa.me/{{ $appData->whatsapp_number }}" target="_blank" class="float">
            <img style="width:55px;height:55px;" src="{{asset('/assets/frontend/images/whatsapp-logo.webp')}}" class="img-fluid" alt="" srcset="">
        </a>
    </div>
    @endif
</main>
<!-- main end  -->
@stop