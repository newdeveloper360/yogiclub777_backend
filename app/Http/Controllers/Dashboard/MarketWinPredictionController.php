<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\MarketRecord;
use App\Models\MarketResult;
use App\Services\PanaNumbersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MarketWinPredictionController extends Controller
{
    public function index()
    {
        $panaNumbersService = new PanaNumbersService();
        $panaNumbers = $panaNumbersService->getPanaNumbersList();
        $markets = Market::latest()->get();
        return view("dashboard.markets.prediction-results", compact("markets", "panaNumbers"));
    }

    public function getPrediction(Request $request)
    {

        $win_numbers = [
            (string)$request->query('pana'),
            (string)$request->query('digit')
        ];

        $market = Market::with(['results' => function ($query) use ($request) {
            $query->where('result_date', $request->date)->first();
        }])->findOrFail($request->market);
        $result = $market->results->first();

        if ($result !== NULL) {
            if (filled($result->open_pana)) {
                array_push($win_numbers, (string) ($result->open_digit . $request->pana));
                array_push($win_numbers, (string) ($result->open_pana . $request->digit));
                array_push($win_numbers, (string) ($result->open_digit . $request->digit));
                array_push($win_numbers, (string) ($result->open_pana . $request->pana));
            }
        }

        //log win numbers
        // Log::info("Win Numbers: " . json_encode($win_numbers));


        $winning_amount = 0;
        $bidding_amount = 0;
        $results = collect();
        $records = MarketRecord::with(["market", "gameType", "user"])
            ->where("session", $request->query('session'))
            ->where("market_id", $request->query('market'))
            ->whereDate("date", $request->query('date'))
            ->where("status", "pending")
            ->get();

        if (filled($records)) {
            foreach ($records as $record) {
                $recordData = collect();
                if (in_array(strval($record->number), $win_numbers, true)) {
                    $recordData->put("id", $record->id);
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
            "dashboard.markets.prediction-get-results",
            compact('results', 'bidding_amount', 'winning_amount')
        )->render();
        return response()->json([
            'view' => $view
        ]);
    }

    public function updatePredictionBid(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'number' => 'required',
            'game_id' => 'required'
        ]);
        $marketRecord = MarketRecord::where('id', $request->game_id)
            ->with('transaction', 'market', 'gameType')
            ->first();
        $marketRecord->amount = $request->amount;
        $marketRecord->number = $request->number;
        $marketRecord->save();

        //update details field in transaction
        if ($marketRecord->transaction !== NULL) {
            $marketRecord->transaction->details = "Game ( " . $marketRecord->transaction->created_at . " : " . $marketRecord->market->name . " : " . $marketRecord->gameType->name . " : " . $marketRecord->session . " ) : " . $request->number;
            $marketRecord->transaction->save();
        }

        return response()->json(['status' => 'success']);
    }
}
