<?php

namespace App\Http\Controllers;

use App\Models\DesawarMarket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesawarChartController extends Controller
{
    // public function index($id, $app = null)
    // {
    //     if ($app != null) {
    //         $app = true;
    //     }

    //     $market = DesawarMarket::findOrFail($id);
    //     $results = DB::table('desawar_results')
    //         ->selectRaw(
    //             'WEEK(result_date, 1) as week_number,
    //          MIN(result_date) as start_date,
    //          MAX(result_date) as end_date,
    //          MAX(CASE WHEN WEEKDAY(result_date) = 0 THEN result END) as monday_result,
    //          MAX(CASE WHEN WEEKDAY(result_date) = 1 THEN result END) as tuesday_result,
    //          MAX(CASE WHEN WEEKDAY(result_date) = 2 THEN result END) as wednesday_result,
    //          MAX(CASE WHEN WEEKDAY(result_date) = 3 THEN result END) as thursday_result,
    //          MAX(CASE WHEN WEEKDAY(result_date) = 4 THEN result END) as friday_result,
    //          MAX(CASE WHEN WEEKDAY(result_date) = 5 THEN result END) as saturday_result,
    //          MAX(CASE WHEN WEEKDAY(result_date) = 6 THEN result END) as sunday_result
    //      '
    //         )
    //         ->where('desawar_market_id', $id)
    //         ->groupBy('week_number')
    //         ->get();


    //     return view("webapp.desawar-chart", compact('results', 'market', 'app'));
    // }


    public function index($id)
    {
        $market = DesawarMarket::findOrFail($id);
        $results = DB::table('desawar_results')
            ->selectRaw(
                'WEEK(result_date, 1) as week_number,
             MIN(result_date) as start_date,
             MAX(result_date) as end_date,
             MAX(CASE WHEN WEEKDAY(result_date) = 0 THEN result END) as monday_result,
             MAX(CASE WHEN WEEKDAY(result_date) = 1 THEN result END) as tuesday_result,
             MAX(CASE WHEN WEEKDAY(result_date) = 2 THEN result END) as wednesday_result,
             MAX(CASE WHEN WEEKDAY(result_date) = 3 THEN result END) as thursday_result,
             MAX(CASE WHEN WEEKDAY(result_date) = 4 THEN result END) as friday_result,
             MAX(CASE WHEN WEEKDAY(result_date) = 5 THEN result END) as saturday_result,
             MAX(CASE WHEN WEEKDAY(result_date) = 6 THEN result END) as sunday_result'
            )
            ->where('desawar_market_id', $id)
            ->groupBy('week_number', DB::raw('YEAR(result_date)')) // Group by year and week
            ->orderByRaw('YEAR(result_date) ASC, week_number ASC') // Order by year and week number
            ->get();

        // Replace null values with "**"
        $results = $results->map(function ($result) {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days as $day) {
                if (is_null($result->{$day . '_result'})) {
                    $result->{$day . '_result'} = '**';
                }
            }
            return $result;
        });

        return view("webapp.desawar-chart", compact('results', 'market'));
    }
}
