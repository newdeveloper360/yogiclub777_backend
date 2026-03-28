<?php

namespace App\Services;

use App\Models\AppData;
use App\Models\StartLineMarket;
use App\Models\StartLineRecord;
use App\Models\User;
use App\Notifications\GameResultNotification;
use App\Notifications\GameWinNotification;
use Illuminate\Support\Facades\Notification;

class StartLineResultSetService
{
    public function setResult($request)
    {
        $market = StartLineMarket::with(['results' => function ($query) use ($request) {
            $query->where('result_date', $request->date)->first();
        }])->findOrFail($request->market);
        $result = $market->results->first();

        if (isset($result)) {
            return back()->with("error", "Result is already declared.");
        }

        $market->results()->create([
            "result_date" => $request->date,
            "open_pana" => $request->open_pana,
            "open_digit" => $request->open_digit
        ]);

        $win_numbers = [
            (string) $request->open_pana,
            (string) $request->open_digit
        ];

        $this->checkUsersWonOrLost($market->id, $request->date, $win_numbers);

        return back()->with("success", "Market result created successfully");
    }

    private function checkUsersWonOrLost($marketId, $date, $win_numbers)
    {
        $market = StartLineMarket::find($marketId);
        $records = StartLineRecord::with(["market", "gameType", "user"])
            ->where("startline_market_id", $marketId)
            ->where("date", $date)
            ->where('status', 'pending')
            ->get();

        if (filled($records)) {
            foreach ($records as $record) {
                $user = $record->user;
                if (in_array($record->number, $win_numbers)) {
                    $record["status"] = "success";
                    $record["win_amount"] = $record->amount * $record->gameType->multiply_by;
                    $user->balance += $record["win_amount"];
                    $user->save();
                    $user->transactions()->create([
                        'previous_amount' =>  $user->balance - $record["win_amount"],
                        'amount' => $record["win_amount"],
                        'current_amount' => $user->balance,
                        "type" => "win",
                        // ( current_time : game_name : game_type : session ) : number
                        "details" => "Win (" . now() . $record->market->name .
                            " :" . $record->gameType->name . ": " . " : open ) : $record->number"
                    ]);
                    if ($user->startline_noti) {
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
                                $referredUser->save();
                                $referredUser->transactions()->create([
                                    'previous_amount' => $referredUser->balance - $amountToGive,
                                    'amount' => $amountToGive,
                                    'current_amount' => $referredUser->balance,
                                    "type" => "recharge",
                                    "details" => "You won ($amountToGive) in bonus"
                                ]);
                            }
                        }
                    }
                }
                $record->save();
            }
        }
        $last_result = $market->results()->latest()->first();
        if ($last_result !== NULL) {
            $result = $last_result->result;
        } else {
            $result = 'XXX-X';
        }
        $usersFcms = User::where('startline_noti', true)->pluck('fcm')->toArray();
        Notification::send(NULL, new GameResultNotification($market->name, $result, $usersFcms));
    }
}
