<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PanaNumberController;
use App\Models\Market;
use App\Models\MarketRecord;
use App\Models\MarketResult;
use App\Services\MarketResultSetService;
use App\Services\PanaNumbersService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MarketResultController extends Controller
{
    public function index(Request $request)
    {
        $panaNumberService = new PanaNumbersService();
        $panaNumbers = $panaNumberService->getPanaNumbersList();
        $markets = Market::latest()->get();

        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $results = MarketResult::with('market')
                ->whereHas('market', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view("dashboard.markets.results", compact("results", "markets", "panaNumbers", "searchValue"));
        }

        $results = MarketResult::latest()->paginate(25);
        return view("dashboard.markets.results", compact("results", "markets", "panaNumbers"));
    }


    public function store(Request $request)
    {
        $request->validate([
            "date" => "required|date",
            "market" => "required|exists:markets,id",
            "session" => ["required", Rule::in(["open", "close"])],
            "percentage_check" => 'required|boolean',
            "percentage" => 'required_if:percentage_check,1|nullable|integer',
            "pana" => 'required_if:percentage_check,0|nullable|string',
            "digit" => 'required_if:percentage_check,0|nullable|string',
        ]);

        // Retrieve the market details
        $market = Market::find($request->market);

        // Check if the given date is Saturday or Sunday
        $dayOfWeek = Carbon::parse($request->date)->format('l');

        if (($dayOfWeek === 'Saturday' && !$market->saturday_open) ||
            ($dayOfWeek === 'Sunday' && !$market->sunday_open)
        ) {
            return back()->with('error', 'Market is closed on the selected date (Either Saturday or Sunday)');
        }

        if ($request->percentage_check) {
            $number = $this->getNumberAccordingToPercentage(
                $request->market,
                $request->date,
                $request->percentage,
                $request->session
            );
            if (is_null($number)) {
                return back()->with('error', 'No records found');
            }

            $panaNumberService = new PanaNumbersService();
            $digit = $number;
            if ($number < 100) {

                $digit = ($number < 9) ? $number : intval($number / 10);
                $pana = $panaNumberService->getPanaNumber($digit);
            } else {

                $pana = intval(($number > 999 && $number < 9999) ? $number / 10
                    : (($number > 9999 && $number < 99999) ? $number / 100
                        : (($number > 99999) ? $number : $number)));
            }
            $digit = $panaNumberService->getDigitFromPana($pana);
            $pana = $panaNumberService->getPanaNumber($digit);
            $request->offsetSet("pana", $pana);
            $request->offsetSet("digit", $digit);
        }

        $marketResultSetService = new MarketResultSetService();
        return $marketResultSetService->setResult($request);
    }

    public function getNumberAccordingToPercentage($market_id, $date, $percentage, $session)
    {
        $records = MarketRecord::where("date", $date)
            ->where("market_id", $market_id)
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

        $numbers = MarketRecord::select('number', DB::raw('SUM(amount) as totalAmount'))
            ->where('date', $date)
            ->where('market_id', $market_id)
            ->where("session", $session)
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
        Log::info("Reverting Market Result with ID: " . $id);
        $result = MarketResult::with("market")->findOrFail($id);
        $records = MarketRecord::with("user")
            ->where("date", $result->result_date)
            ->where("market_id", $result->market->id)
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

        return back()->with("success", "Market Result has been reverted");
    }
}
