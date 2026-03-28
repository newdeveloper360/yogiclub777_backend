<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DesawarMarket;
use App\Models\DesawarRecord;
use App\Models\DesawarResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesawarWinPredictionController extends Controller
{
    public function index()
    {
        $results = DesawarResult::all();
        $markets = DesawarMarket::latest()->get();
        return view(
            "dashboard.desawar-markets.prediction-results",
            compact("results", "markets")
        );
    }

    public function getPrediction(Request $request)
    {

        $first_digit = (intval($request->query('digit') / 10) * 100) +
            ((intval($request->query('digit') / 10)) * 10) + intval($request->query('digit') / 10);

        $second_digit = (intval($request->query('digit') % 10) * 100)
            + ((intval($request->query('digit') % 10)) * 10) + intval($request->query('digit') % 10);

        $digit = $request->query('digit');
        $date = $request->query('date');

        $win_numbers = [
            $first_digit,
            $second_digit,
            $digit
        ];

        Log::info("Winning Numbers: " . json_encode($win_numbers));
        Log::info($request->all());

        $winning_amount = 0;
        $bidding_amount = 0;
        $records = DesawarRecord::with(["market", "gameType", "user"])
            ->where('date', $date)
            ->where("desawar_market_id", $request->query('market'))
            ->get();

        $results = collect();

        if (filled($records)) {
            foreach ($records as $record) {
                $recordData = collect();
                if ((in_array($record->number, $win_numbers, true) && $record->gameType->game_type == "jodi") ||
                    ($record->number == $first_digit && $record->gameType->game_type == "andar") ||
                    ($record->number == $second_digit && $record->gameType->game_type == "bahar")
                ) {
                    $recordData->put("market_name", $record->market->name);
                    $recordData->put("user_name", $record->user->name);
                    $recordData->put("amount", $record->amount);
                    $recordData->put("number", $record->number);
                    $recordData->put("win_amount", $record->amount * $record->gameType->multiply_by);
                    $winning_amount += $record->amount * $record->gameType->multiply_by;
                    $bidding_amount += $record->amount;
                    $recordData->put("created_at", $record->created_at);
                    $results->push($recordData);
                }
            }
        }
        $view = view(
            "dashboard.desawar-markets.prediction-get-results",
            compact("results", "bidding_amount", "winning_amount")
        )
            ->render();
        return response()->json([
            'view' => $view
        ]);
    }
}
