<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\DesawarMarket;
use App\Models\DesawarRecord;
use App\Models\DesawarResult;
use App\Models\User;
use App\Services\DesawarResultSetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DesawarResultController extends Controller
{
    public function index(Request $request)
    {
        $markets = DesawarMarket::latest()->get();

        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $results = DesawarResult::with(['market', 'user'])
                ->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view("dashboard.desawar-markets.results", compact("results", "markets", "searchValue"));
        }

        $results = DesawarResult::latest()->paginate(25);
        return view("dashboard.desawar-markets.results", compact("results", "markets"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "date" => "required|date",
            "market" => "required|exists:desawar_markets,id",
            "percentage_check" => "required|boolean",
            "percentage" => 'required_if:percentage_check,1|nullable|integer',
            "digit" => 'required_if:percentage_check,0|nullable|between:00,99',

        ]);
        if ($request->percentage_check) {
            $digit = $this->getNumberAccordingToPercentage(
                $request->market,
                $request->date,
                $request->percentage
            );
            if (is_null($digit)) {
                return back()->with('error', 'No records found');
            }
            $request->offsetSet("digit", $digit);
        }
        $desawarResultSetService = new DesawarResultSetService();
        return $desawarResultSetService->setResult($request, true);
    }

    private function getNumberAccordingToPercentage($market_id, $date, $percentage)
    {
        $records = DesawarRecord::where("date", $date)
            ->where("desawar_market_id", $market_id)
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

        $numbers = DesawarRecord::select('number', DB::raw('SUM(amount) as totalAmount'))
            ->where('date', $date)
            ->where('desawar_market_id', $market_id)
            ->groupBy('number')
            ->get()
            ->toArray();
        $minDifference = PHP_INT_MAX; // Initialize with a large value

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
        Log::info("Reverting DMarket result with id: " . $id);
        $result = DesawarResult::with('market')->where('id', $id)->firstOrFail();
        $records = DesawarRecord::with("user")
            ->where('date', $result->result_date)
            ->where("desawar_market_id",   $result->market->id)->get();
        if (filled($records)) {
            foreach ($records as $record) {
                $user = $record->user;

                if (isset($record["win_amount"]) && $record["win_amount"] != 0) {
                    $user->transactions()->create([
                        'previous_amount' => $user->balance,
                        'amount' =>  $record["win_amount"],
                        'current_amount' => $user->balance -  $record["win_amount"],
                        "type" => "recharge",
                        "details" => "Win amount (" . $record["win_amount"] . ") Revert"
                    ]);

                    $user->balance -= $record["win_amount"];
                    $user->withdrawal_balance -= $record["win_amount"];
                    $user->save();
                } else {
                    $appData = AppData::find(1);
                    $refferalUser = User::find($user->user_id);

                    if ($refferalUser && env('BET_LOSE_GIVE_MONEY', false) && $appData->invite_system_enable) {
                        $amountToGive = ($appData->invite_bonus / 100) * $record->amount;
                        $refferalUser->transactions()->create([
                            'previous_amount' => $refferalUser->balance,
                            'amount' =>  $amountToGive,
                            'current_amount' => $refferalUser->balance -  $amountToGive,
                            "type" => "lossBonusRevert",
                            "details" => "Bet Loss Bonus ($amountToGive) Revert"
                        ]);
                        $refferalUser->balance -= $amountToGive;
                        // $refferalUser->withdrawal_balance -= $amountToGive;
                        $refferalUser->save();
                    }    
                }

                $record["status"] = "pending";
                $record["win_amount"] = null;
                $record->save();
            }
        }
        $result->delete();
        return back()->with("success", "Result has been reverted");
    }
}
