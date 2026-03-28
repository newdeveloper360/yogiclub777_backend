<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StartLineMarket;
use App\Models\StartLineRecord;
use App\Services\PanaNumbersService;
use Illuminate\Http\Request;

class StartLineWinPredictionController extends Controller
{

    public function index()
    {
        $panaNumbersService = new PanaNumbersService();
        $panaNumbers = $panaNumbersService->getPanaNumbersList();
        $markets = StartLineMarket::all();
        return view("dashboard.start-line-markets.prediction-results", compact("markets", "panaNumbers"));
    }

    public function getPrediction(Request $request)
    {

        $win_numbers = [(int) $request->query('open_digit'), (int) $request->query('open_pana')];
        $winning_amount = 0;
        $bidding_amount = 0;
        $results = collect();
        $records = StartLineRecord::with(["market", "gameType", "user"])
            ->where("startline_market_id", $request->query('market'))
            ->where("date", $request->query('date'))
            ->get();

        if (filled($records)) {
            foreach ($records as $record) {
                $recordData = collect();
                if (in_array($record['number'], $win_numbers)) {
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
        $view  = view(
            "dashboard.start-line-markets.prediction-get-results",
            compact('results', 'bidding_amount', 'winning_amount')
        )->render();
        return response()->json([
            'view' => $view
        ]);
    }
}
