<?php

namespace App\Services;

use App\Models\AppData;
use App\Models\Market;
use App\Models\MarketRecord;
use App\Models\User;
use App\Notifications\GameResultNotification;
use App\Notifications\GameWinNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MarketResultSetService
{

    public function setResult($request, $fromApi = false)
    {
        $market = Market::with(['results' => function ($query) use ($request) {
            $query->where('result_date', $request->date)->first();
        }])->findOrFail($request->market);

        $result = $market->results->first();
        if ($market->previous_day_check && $fromApi) {
            $date = Carbon::parse($request->date);
            $date = $date->subDay();
        } else {
            $date = $request->date;
        }
        $win_numbers = [
            (string) $request->pana,
            (string) $request->digit,
        ];

        if (is_null($result)) {
            $market->results()->create([
                "result_date" => $request->date,
                "open_pana" => $request->session == "open" ? $request->pana : null,
                "open_digit" => $request->session == "open" ? $request->digit : null,
                "close_pana" => $request->session == "close" ? $request->pana : null,
                "close_digit" => $request->session == "close" ? $request->digit : null
            ]);

            $this->checkUsersWonOrLost($market->id, $win_numbers, $request->date, $request->session, false);

            return back()->with("success", "Market result created successfully");
        } else {
            $field = $request->session == "open" ? "open" : "close";

            if (!is_null($result->{$field . '_pana'})) {
                return back()->with("error", ucfirst($field) . " pana is already set");
            }

            $result->{$field . '_pana'} = $request->pana;
            $result->{$field . '_digit'} = $request->digit;
            $result->save();

            if ($request->session == "open") {
                $bothSession = false;
                if (filled($result->close_pana)) {
                    array_push($win_numbers, (string) $result->close_pana);
                    array_push($win_numbers, (string) $result->close_digit);
                    array_push($win_numbers, (string) ($result->open_digit . $result->close_pana));
                    array_push($win_numbers, (string) ($result->open_digit . $result->close_digit));
                    array_push($win_numbers, (string) ($result->open_pana . $result->close_digit));
                    array_push($win_numbers, (string) ($result->open_pana . $result->close_pana));
                    $bothSession = true;
                }
                $session = $request->session;
                $this->checkUsersWonOrLost($market->id, $win_numbers, $request->date, $session, $bothSession);
                return back()->with("success", "Open pana is set");
            }


            $bothSession = false;
            if (filled($result->open_pana)) {
                // array_push($win_numbers, (string) $result->open_pana);
                // array_push($win_numbers, (string) $result->open_digit);
                array_push($win_numbers, (string) ($result->open_digit . $result->close_pana));
                array_push($win_numbers, (string) ($result->open_pana . $result->close_digit));
                array_push($win_numbers, (string) ($result->open_digit . $result->close_digit));
                array_push($win_numbers, (string) ($result->open_pana . $result->close_pana));
                $bothSession = true;
            }

            $session = $request->session;

            $this->checkUsersWonOrLost(
                $market->id,
                $win_numbers,
                $request->date,
                $session,
                $bothSession
            );
            return back()->with("success", "Close pana is set");
        }
    }

    // Checking user win or lost
    private function checkUsersWonOrLost($marketId, $win_numbers, $date, $session, $bothSession)
    {
        Log::info('market ' . $marketId);
        $market = Market::find($marketId);
        $records =
            MarketRecord::with(["market", "gameType", "user"])
            ->where(function ($query) use ($session, $date, $marketId, $bothSession) {
                $query->where("date", $date)
                    ->where("market_id", $marketId)
                    ->where('status', 'pending');

                if ($bothSession) {
                    $query->where(function ($subQuery) use ($session) {
                        $subQuery->where("session", 'null')
                            ->orWhere("session", 'close');
                    });
                } else {
                    $query->where("session", $session);
                }
            })
            ->get();
        if (filled($records)) {
            foreach ($records as $record) {
                $user = $record->user;
                if (in_array(strval($record->number), $win_numbers, true)) {
                    //log record number and win numbrs
                    // Log::info("Record number: " . $record->number . " Win numbers: " . json_encode($win_numbers));
                    $record["status"] = "success";
                    $record["win_amount"] = $record->amount * $record->gameType->multiply_by;
                    $record->save();
                    $user->balance += $record["win_amount"];
                    $user->save();
                    $user->transactions()->create([
                        'previous_amount' =>  $user->balance - $record["win_amount"],
                        'amount' => $record["win_amount"],
                        'current_amount' => $user->balance,
                        "type" => "win",
                        // ( current_time : game_name : game_type : session ) : number
                        "details" => "Win (" . now() . $record->market->name .
                            " :" . $record->gameType->name . ": " . " : $session ) : $record->number"
                    ]);
                    if ($user->general_noti) {
                        $user->notify(new GameWinNotification($record['win_amount'], $user->fcm, $user->one_signalsubscription_id));
                    }
                } else {
                    $record["status"] = "failed";
                    $record["win_amount"] = 0;
                    $record->save();
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
            }
        }
        $usersFcms = User::where('general_noti', true)->pluck('fcm')->toArray();
        $market->refresh();
        $last_result = $market->results()->latest()->first();
        if ($last_result !== NULL) {
            $result = $last_result->result;
            Log::info('Result found ' . $result);
        } else {
            Log::info('Result not found XXX');
            $result = 'XXX-XX-XXX';
        }
        //FascadeNotification
        Notification::send(NULL, new GameResultNotification($market->name, $result, $usersFcms));
    }
}
