<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Market;
use App\Models\MarketRecord;
use App\Http\Controllers\Controller;
use App\Models\DesawarRecord;
use App\Models\GameType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function GuzzleHttp\Promise\all;

class MarketRecordController extends Controller
{

    public function update(Request $request)
    {
        $request->validate([
            'market_type' => 'required|in:desawar,market',
            'id' => 'required|numeric',
            'number' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);
        if ($request->market_type == 'desawar') {
            $record = DesawarRecord::find($request->id);
        } else {
            $record = MarketRecord::find($request->id);
        }

        // update transaction details
        if (isset($record->transaction)) {
            $transaction = $record->transaction;
            $market_name = $record->market->name;
            $game_type_name = $record->gameType->name;
            $session = isset($record->session) ? $record->session : 'null';
            $number = $request->number;
            $transaction->details = "Game ( " . Carbon::now() . " : $market_name : $game_type_name : $session ) : $number";
            $transaction->save();
        }

        // update record
        $record->number = $request->number;
        $record->amount = $request->amount;
        $record->save();
        return redirect()->back()->with('success', 'Bid Updated Successfully');
    }

    public function index(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $marketRecords = MarketRecord::with('market')
                ->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view('dashboard.markets.records', compact('marketRecords', 'searchValue'));
        }
        $marketRecords = MarketRecord::with('market')->latest()->paginate(25);
        return view('dashboard.markets.records', compact('marketRecords'));
    }

    public function winHistory(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $winHistory = MarketRecord::with(['user', 'market', 'gameType'])
                ->where('status', 'success')
                ->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view('dashboard.markets.win-history', compact('winHistory', 'searchValue'));
        }
        $winHistory = MarketRecord::with(['user', 'market', 'gameType'])
            ->where('status', 'success')->latest()->paginate(25);
        return view('dashboard.markets.win-history', compact('winHistory'));
    }

    // if ($request->market_id && $request->market_time) {
    //     $bids = MarketRecord::where([
    //         'game_type_id' => $record->id,
    //         'market_id' => $request->market_id,
    //         'session' => $request->market_time
    //     ])
    //         ->whereDate('created_at', $date)
    //         ->select('game_type_id', 'number', DB::raw('SUM(amount) as total_amount'))
    //         ->groupBy('number')
    //         ->orderBY('number', 'ASC')
    //         ->get();
    // } else {
    //     $bids = MarketRecord::where('game_type_id', $record->id)
    //         ->whereDate('created_at', $date)
    //         ->select('game_type_id', 'number', DB::raw('SUM(amount) as total_amount'))
    //         ->groupBy('number')
    //         ->orderBY('number', 'ASC')
    //         ->get();
    // }

    // public function data(Request $request)
    // {
    //     $records = GameType::where('type', 'general')->orderBy('id', 'ASC')->get();
    //     $data = [];
    //     $count = 0;
    //     $date = ($request->date && Carbon::hasFormat($request->date, 'Y-m-d')) ? $request->date : today();
    //     foreach ($records as $record) {
    //         if ($request->market_id && $request->market_time) {
    //             $bids = MarketRecord::where([
    //                 'game_type_id' => $record->id,
    //                 'market_id' => $request->market_id,
    //                 // 'session' => $request->market_time
    //             ])
    //                 ->whereDate('created_at', $date)
    //                 ->select('game_type_id', 'number', DB::raw('SUM(amount) as total_amount'))
    //                 ->groupBy('number')
    //                 ->orderBY('number', 'ASC')
    //                 ->get();
    //         } else {
    //             $bids = MarketRecord::where('game_type_id', $record->id)
    //                 ->whereDate('created_at', $date)
    //                 ->select('game_type_id', 'number', DB::raw('SUM(amount) as total_amount'))
    //                 ->groupBy('number')
    //                 ->orderBY('number', 'ASC')
    //                 ->get();
    //         }

    //         if (count($bids) > $count) {
    //             $count = count($bids);
    //         }
    //         $bids->gameType = $record;
    //         array_push($data, $bids);
    //     }
    //     $markets = Market::all();
    //     $dataForClipboard = '';
    //     if ($request->market_time && $request->market_id) {
    //         $marketName = $markets->where('id', $request->market_id)->first()->name;
    //         $dataForClipboard = $this->makeTextDataForClipboard($data, $marketName, $request->market_time);
    //     }
    //     return view('dashboard.markets.data', compact('data', 'count', 'markets', 'date', 'dataForClipboard'));
    // }

    public function data(Request $request)
    {
        $recordsNotInSessionCheck = GameType::where('type', 'general')->whereNotIn('id', [1, 3, 4, 5])->orderBy('id', 'ASC')->get();
        $recordsInSessionCheck = GameType::where('type', 'general')->whereIn('id', [1, 3, 4, 5])->orderBy('id', 'ASC')->get();

        $data = [];
        $count = 0;
        $date = ($request->date && Carbon::hasFormat($request->date, 'Y-m-d')) ? $request->date : Carbon::today()->toDateString();
        $market_time = $request->market_time ? $request->market_time : 'open';
        $market_id = $request->market_id ? $request->market_id : Market::first()->id;

        if ($market_time == 'open') {
            foreach ($recordsNotInSessionCheck as $record) {
                $bids = MarketRecord::where([
                    'game_type_id' => $record->id,
                    'market_id' => $market_id,
                ])
                    ->whereDate('created_at', $date)
                    ->select('game_type_id', 'number', DB::raw('SUM(amount) as total_amount'))
                    ->groupBy('number')
                    ->orderBy('number', 'ASC')
                    ->get();

                // foreach ($bids as $bid) {
                //     $gameTypeId = $bid->game_type_id;
                //     $number = $bid->number;

                //     if ($gameTypeId == 6) {
                //         // Add a dash after the first digit
                //         $bid->number = substr($number, 0, 1) . '-' . substr($number, 1);
                //     } elseif ($gameTypeId == 7) {
                //         // Add a dash before the last digit
                //         $bid->number = substr($number, 0, -1) . '-' . substr($number, -1);
                //     } elseif ($gameTypeId == 8) {
                //         // Add a dash after the third digit
                //         $bid->number = substr($number, 0, 3) . '-' . substr($number, 3);
                //     }
                // }

                if (count($bids) > $count) {
                    $count = count($bids);
                }
                $bids->gameType = $record;
                array_push($data, $bids);
            }
        }

        foreach ($recordsInSessionCheck as $record) {
            $bids = MarketRecord::where([
                'game_type_id' => $record->id,
                'market_id' => $market_id,
                'session' => $market_time
            ])
                ->whereDate('created_at', $date)
                ->select('game_type_id', 'number', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('number')
                ->orderBy('number', 'ASC')
                ->get();

            if (count($bids) > $count) {
                $count = count($bids);
            }
            $bids->gameType = $record;
            array_push($data, $bids);
        }

        $markets = Market::all();
        $dataForClipboard = '';

        $marketName = $markets->where('id', $market_id)->first()->name;
        $dataForClipboard = $this->makeTextDataForClipboard(
            $data,
            $marketName,
            $market_time
        );
        return view('dashboard.markets.data', compact('data', 'count', 'markets', 'date', 'dataForClipboard'));
    }


    private function makeTextDataForClipboard($data, $marketName, $marketTime): string
    {
        $string = $marketName . " " . $marketTime . " ₹ :\nDate and Time   " . now()->format('h:m a d-m-Y') . "\n";
        $total = 0;
        $string .= "---------------------------------\n";
        foreach ($data as $record) {
            $string .= $record->gameType->name . "\n";
            foreach ($record as $rec) {
                $total += $rec->total_amount;
                $string .= $rec->number . ' - ' . $rec->total_amount . "\n";
            }
            $string .= "---------------------------------\n";
        }
        $string .= "Total  " . $total;

        return  $string;
    }
}
