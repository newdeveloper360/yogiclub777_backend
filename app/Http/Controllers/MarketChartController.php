<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketChartController extends Controller
{
    public function index($id, $app = null)
    {
        if ($app != null) {
            $app = true;
        }

        $market = Market::findOrFail($id);
        $results = DB::table('market_results')
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
                 MAX(CASE WHEN WEEKDAY(result_date) = 6 THEN open_digit END) as sunday_open_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 0 THEN close_pana END) as monday_close_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 1 THEN close_pana END) as tuesday_close_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 2 THEN close_pana END) as wednesday_close_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 3 THEN close_pana END) as thursday_close_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 4 THEN close_pana END) as friday_close_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 5 THEN close_pana END) as saturday_close_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 6 THEN close_pana END) as sunday_close_pana,
                 MAX(CASE WHEN WEEKDAY(result_date) = 0 THEN close_digit END) as monday_close_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 1 THEN close_digit END) as tuesday_close_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 2 THEN close_digit END) as wednesday_close_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 3 THEN close_digit END) as thursday_close_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 4 THEN close_digit END) as friday_close_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 5 THEN close_digit END) as saturday_close_digit,
                 MAX(CASE WHEN WEEKDAY(result_date) = 6 THEN close_digit END) as sunday_close_digit
                 '

            )
            ->where('market_id', $id)
            ->groupBy('week_number')
            ->get();
        return view("webapp.market-chart", compact('results', 'market', 'app'));
    }

    public function jodiChart($id)
    {
        $market = Market::findOrFail($id);
        $results = DB::table('market_results')
            ->selectRaw(
                'WEEK(result_date, 1) as week_number, MIN(result_date) as start_date, MAX(result_date) as end_date,
             MAX(CASE WHEN WEEKDAY(result_date) = 0 THEN open_digit END) as monday_open_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 1 THEN open_digit END) as tuesday_open_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 2 THEN open_digit END) as wednesday_open_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 3 THEN open_digit END) as thursday_open_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 4 THEN open_digit END) as friday_open_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 5 THEN open_digit END) as saturday_open_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 6 THEN open_digit END) as sunday_open_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 0 THEN close_digit END) as monday_close_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 1 THEN close_digit END) as tuesday_close_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 2 THEN close_digit END) as wednesday_close_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 3 THEN close_digit END) as thursday_close_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 4 THEN close_digit END) as friday_close_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 5 THEN close_digit END) as saturday_close_digit,
             MAX(CASE WHEN WEEKDAY(result_date) = 6 THEN close_digit END) as sunday_close_digit
             '

            )
            ->where('market_id', $id)
            ->groupBy('week_number')
            ->get();

        return view("webapp.market-jodi-chart", compact('results', 'market'));
    }
}
