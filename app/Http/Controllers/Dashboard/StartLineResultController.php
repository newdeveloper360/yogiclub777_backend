<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PanaNumberController;
use App\Models\StartLineMarket;
use App\Models\StartLineRecord;
use App\Models\StartLineResult;
use App\Services\PanaNumbersService;
use App\Services\StartLineResultSetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StartLineResultController extends Controller
{
    public function index(Request $request)
    {
        $panaNumberService = new PanaNumbersService();
        $panaNumbers = $panaNumberService->getPanaNumbersList();
        $markets = StartLineMarket::all();

        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $results = StartLineResult::with(['market', 'gameType'])
                ->whereHas('market', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view("dashboard.start-line-markets.results", compact("results", "markets", "panaNumbers", "searchValue"));
        }

        $results = StartLineResult::latest()->paginate(25);
        return view("dashboard.start-line-markets.results", compact("results", "markets", "panaNumbers"));
    }

    public function store(Request  $request)
    {
        $request->validate([
            "date" => "required|date",
            "market" => "required|exists:start_line_markets,id",
            "percentage_check" => 'required|boolean',
            "percentage" => 'required_if:percentage_check,1|nullable|string',
            "open_pana" => 'required_if:percentage_check,0|nullable|string',
            "open_digit" => 'required_if:percentage_check,0|nullable|string',
        ]);
        if ($request->percentage_check) {
            $number = $this->getNumberAccordingToPercentage(
                $request->market,
                $request->date,
                $request->percentage
            );
            if (is_null($number)) {
                return back()->with('error', 'No records found');
            }
            $panaNumberService = new PanaNumbersService();
            if ($number < 10) {
                $digit = $number;
                $pana = $panaNumberService->getPanaNumber($digit);
            } else {
                $pana = $number;
                $digit = $panaNumberService->getDigitFromPana($pana);
            }
            $request->offsetSet("open_pana", $pana);
            $request->offsetSet("open_digit", $digit);
        }
        $startLineResultSetService = new StartLineResultSetService();


        return $startLineResultSetService->setResult($request);
    }

    public function getNumberAccordingToPercentage($market_id, $date, $percentage)
    {
        $market = StartLineMarket::findOrFail($market_id);
        if ($market->previous_day_check) {
            $date = Carbon::parse($date);
            $date = $date->subDay();
        }

        $records = StartLineRecord::where("date", $date)
            ->where("startline_market_id", $market_id)
            ->with('gameType')
            ->get();
        if (blank($records)) {
            return null;
        }
        // Loop through the records and calculate the total amount with multiplier
        $totalAmount = 0;
        foreach ($records as $record) {
            $totalAmount += $record->amount;
        }
        $desiredAmount = $totalAmount * ($percentage / 100);

        $numbers = StartLineRecord::select('number', DB::raw('SUM(amount) as totalAmount'))
            ->where('date', $date)
            ->where('startline_market_id', $market_id)
            ->groupBy('number')
            ->get()
            ->toArray();

        $minDifference = PHP_INT_MAX;
        foreach ($numbers as $number) {
            $difference = abs($number['totalAmount'] - $desiredAmount);
            if ($difference < $minDifference) {
                $minDifference = $difference;
                $closestNumber = $number['number'];
            }
        }

        return $closestNumber;
    }

    public function revert($id)
    {
        $result = StartLineResult::with('market')->where('id', $id)->firstOrFail();
        $records = StartLineRecord::with("user")
            ->where("startline_market_id",   $result->market->id)
            ->where("date", $result->result_date)
            ->get();
        if (filled($records)) {
            foreach ($records as $record) {
                $user = $record->user;

                if (filled($record["win_amount"])) {
                    $user->transactions()->create([
                        'previous_amount' => $user->balance,
                        'amount' =>  $record["win_amount"],
                        'current_amount' => $user->balance -  $record["win_amount"],
                        "type" => "recharge",
                        "details" => "Win amount (" . $record["win_amount"] . ") Revert"
                    ]);
                }

                $record["status"] = "pending";
                $user->balance -= $record["win_amount"];
                $record["win_amount"] = null;
                $user->save();
                $record->save();
            }
        }
        $result->delete();
        return back()->with("success", "StartLine Market Result has been reverted");
    }
}
