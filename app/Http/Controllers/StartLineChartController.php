<?php

namespace App\Http\Controllers;

use App\Models\StartLineMarket;
use Illuminate\Support\Facades\DB;

class StartLineChartController extends Controller
{
    public function index($id)
    {
        $market = StartLineMarket::findOrFail($id);

        $results = DB::table('start_line_results')
            ->selectRaw(
                'WEEK(result_date, 1) as week_number, MIN(result_date) as start_date, MAX(result_date) as end_date, 
                 MAX(CASE WHEN WEEKDAY(result_date) = 0 THEN open_pana END) as monday_open_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 1 THEN open_pana END) as tuesday_open_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 2 THEN open_pana END) as wednesday_open_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 3 THEN open_pana END) as thursday_open_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 4 THEN open_pana END) as friday_open_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 5 THEN open_pana END) as saturday_open_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 6 THEN open_pana END) as sunday_open_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 0 THEN open_digit END) as monday_open_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 1 THEN open_digit END) as tuesday_open_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 2 THEN open_digit END) as wednesday_open_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 3 THEN open_digit END) as thursday_open_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 4 THEN open_digit END) as friday_open_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 5 THEN open_digit END) as saturday_open_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 6 THEN open_digit END) as sunday_open_digit'
            )
            ->where('startline_market_id', $id)
            ->groupBy('week_number')
            ->get();
        return view("webapp.start-line-chart", compact('results', 'market'));
    }
}
