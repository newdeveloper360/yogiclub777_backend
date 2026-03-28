@extends('webapp.layouts.master')

<!-- content start here -->
@section('content')

<!-- custom css here start -->
@section('css')

@stop
<!-- custom css here end -->

<!-- main start  -->
<main>
    <!-- RAJDHANI NIGHT Pana Chart start -->
    <div class="panaChart">
        <div class="container">
            <div class="panaChartTop">
                <h2>{{$market->name}} Jodi Chart</h2>
            </div>
            <div class="table-responsive-lg">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Mon</th>
                            <th scope="col">Tue</th>
                            <th scope="col">Wed</th>
                            <th scope="col">Thu</th>
                            <th scope="col">Fri</th>
                            <th scope="col">Sat</th>
                            <th scope="col">Sun</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)
                        <tr>
                            <td>
                                <p class="tn">{{ $result->start_date }} <br> to <br> {{ $result->end_date }}</p>
                            </td>
                            <td>
                                <span class="tb">{{ $result->monday_open_digit }}{{ $result->monday_close_digit }}</span>
                            </td>
                            <td>
                                <span class="tb">{{ $result->tuesday_open_digit }}
                                    {{ $result->tuesday_close_digit }}</span>
                            </td>
                            <td>
                                <span class="tb">{{ $result->wednesday_open_digit }}
                                    {{ $result->wednesday_close_digit }}</span>
                            </td>
                            <td>
                                <span class="tb">{{ $result->thursday_open_digit }}
                                    {{ $result->thursday_close_digit }}</span>
                            </td>
                            <td>
                                <span class="tb">{{ $result->friday_open_digit }}
                                    {{ $result->friday_close_digit }}</span>
                            </td>
                            <td>
                                <span class="tb">{{ $result->saturday_open_digit }}
                                    {{ $result->saturday_close_digit }}</span>
                            </td>
                            <td>
                                <span class="tb">{{ $result->sunday_open_digit }}
                                    {{ $result->sunday_close_digit }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- RAJDHANI NIGHT Pana Chart end -->

</main>
<!-- main end  -->

@stop
<!-- content end here -->

<!-- js start here -->
@section('script')
@stop
<!-- js end here -->