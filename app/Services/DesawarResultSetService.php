<?php

namespace App\Services;

use App\Models\AppData;
use App\Models\DesawarMarket;
use App\Models\DesawarRecord;
use App\Models\User;
use App\Notifications\GameResultNotification;
use App\Notifications\GameWinNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class DesawarResultSetService
{

    public function setResult($request, $fromApi = false)
    {
        $first_digit = str_repeat(strval($request->digit)[0], 3);
        $second_digit = str_repeat(strval($request->digit)[1], 3);
        $digit = strval($request->digit);

        $market = DesawarMarket::with(['results' => function ($query) use ($request) {
            $query->where('result_date', $request->date)->first();
        }])->where('id', $request->market)
            ->firstOrFail();

        $result = $market->results->first();
        if (isset($result)) {
            return back()->with("error", "Result is already declared.");
        }
        $market->results()->create([
            "result_date" => $request->date,
            "result" => $request->digit,
            "first_digit_of_result" => $first_digit,
            "second_digit_of_result" => $second_digit
        ]);

        if ($market->previous_day_check && $fromApi) {
            $date = Carbon::parse($request->date);
            $date = $date->subDay();
        } else {
            $date = $request->date;
        }

        $this->checkUsersWonOrLost($market->id, $first_digit, $second_digit, $digit, $date);
        return back()->with("success", "Market result created successfully");
    }

    private function checkUsersWonOrLost($marketId, $first_digit, $second_digit, $digit, $date)
    {
        $market = DesawarMarket::find($marketId);
        $market_name = $market->name;
        $win_numbers = [$first_digit, $second_digit, $digit];

        //if close time is less than open time, it means close time is on next day
        $date = Carbon::parse($date);
        $now = Carbon::now()->setDate($date->year, $date->month, $date->day);

        $openTime = Carbon::parse($market->open_time)->setDate($date->year, $date->month, $date->day);

        $closeTime = Carbon::parse($market->close_time)->setDate($date->year, $date->month, $date->day);

        if ($closeTime->lt($openTime)) {
            if ($now->lt($closeTime)) {
                $openTime->subDay();
            } else {
                $closeTime->addDay();
            }

            //get all records between open time and close time
            $records = DesawarRecord::with(["market", "gameType", "user"])
                ->whereBetween('created_at', [$openTime, $closeTime])
                ->where("desawar_market_id", $marketId)
                ->where('status', 'pending')
                ->get();
        } else {
            $records = DesawarRecord::with(["market", "gameType", "user"])
                ->where('date', $date)
                ->where("desawar_market_id", $marketId)
                ->where('status', 'pending')
                ->get();
        }



        if (filled($records)) {
            foreach ($records as $record) {
                $user = $record->user;
                if ((in_array($record->number, $win_numbers, true) && $record->gameType->game_type == "jodi") ||
                    ($record->number == $first_digit && $record->gameType->game_type == "andar") ||
                    ($record->number == $second_digit && $record->gameType->game_type == "bahar")
                ) {
                    //log record number and win numbrs
                    Log::info("Record number: " . $record->number . " Win numbers: " . json_encode($win_numbers));


                    $record["status"] = "success";
                    $record["win_amount"] = $record->amount * $record->gameType->multiply_by;
                    $user->balance += $record["win_amount"];
                    $user->withdrawal_balance += $record["win_amount"];
                    $user->save();
                    
                    $user->transactions()->create([
                        'previous_amount' =>  $user->balance - $record["win_amount"],
                        'amount' => $record["win_amount"],
                        'current_amount' => $user->balance,
                        "type" => "win",
                        // ( current_time : game_name : game_type : session ) : number
                        "details" => "Win (" . now() . $record->market->name . " :" . $record->gameType->name . ": " . " ) : $record->number"
                    ]);
                    if ($user->desawar_noti) {
                        $user->notify(new GameWinNotification($record['win_amount'], $user->fcm, $user->one_signalsubscription_id));
                    }
                } else {
                    $record["status"] = "failed";
                    $record["win_amount"] = 0;
                    $appData = AppData::find(1);
                    if (env('BET_LOSE_GIVE_MONEY', false) && $appData->invite_system_enable) {
                        $amountToGive = ($appData->invite_bonus / 100) * $record->amount;
                        //give money to referredUser
                        if ($user->user_id !== NULL) {
                            $referredUser = User::find($user->user_id);
                            if ($referredUser) {
                                $referredUser->balance += $amountToGive;
                                // $referredUser->withdrawal_balance += $amountToGive;
                                $referredUser->save();
                                
                                $referredUser->transactions()->create([
                                    'previous_amount' => $referredUser->balance - $amountToGive,
                                    'amount' => $amountToGive,
                                    'current_amount' => $referredUser->balance,
                                    "type" => "lossBonus",
                                    "details" => "Bet Loss Bonus ($amountToGive)."
                                ]);
                            }
                        }
                    }
                }
                $record->save();
            }
        }
        $usersFcms = User::where('desawar_noti', true)->pluck('fcm')->toArray();
        Notification::send(NULL, new GameResultNotification($market_name, $digit, $usersFcms));
    }
}
