@extends('webapp.layouts.master')

<!-- content start here -->
@section('content')

    <!-- custom css here start -->
@section('css')
    @if ($app)
        <style>
            header {
                display: none;
            }

            footer {
                display: none;
            }

            .panaChartTop {
                display: none;
            }
        </style>
    @endif
@stop
<!-- custom css here end -->

<!-- main start  -->
<main>
    <!-- RAJDHANI NIGHT Pana Chart start -->
    <div class="panaChart">
        <div class="container">
            <div class="panaChartTop">
                <h2>{{ $market->name }} </h2>
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
                                    <span class="tb">{{ $result->monday_result }}</span>
                                </td>
                                <td>
                                    <span class="tb">{{ $result->tuesday_result }} </span>
                                </td>
                                <td>
                                    <span class="tb">{{ $result->wednesday_result }}</span>
                                </td>
                                <td>
                                    <span class="tb">{{ $result->thursday_result }}</span>
                                </td>
                                <td>
                                    <span class="tb">{{ $result->friday_result }}</span>
                                </td>
                                <td>
                                    <span class="tb">{{ $result->saturday_result }} </span>
                                </td>
                                <td>
                                    <span class="tb">{{ $result->sunday_result }}</span>
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
