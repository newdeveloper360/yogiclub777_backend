<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\DesawarMarket;
use App\Models\DesawarRecord;
use App\Models\DesawarResult;
use App\Models\GameType;
use App\Models\Market;
use App\Models\MarketRecord;
use App\Models\MarketResult;
use App\Models\StartLineMarket;
use App\Models\StartLineRecord;
use App\Models\StartLineResult;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function deletePlay(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['general', 'startLine', 'desawar'])],
            'game_id' => 'required|numeric',
        ]);
        $market = match ($request->type) {
            "general" => MarketRecord::class,
            "startLine" => StartLineRecord::class,
            "desawar" => DesawarRecord::class,
        };
        $marketRecord = $market::find($request->game_id);
        if (!$marketRecord) {
            return response()->failed("Game not found!");
        }
        if ($marketRecord->user_id != auth()->user()->id) {
            $marketRecord->delete();
            return response()->success("This is not your Game!", NULL);
        }

        //delete only if record is created 5 minutes ago
        if (Carbon::parse($marketRecord->created_at)->diffInMinutes(Carbon::now()) > 5) {
            return response()->failed("You can't delete Bet older than 5 Mins!");
        }

        //add bet amount to user balance
        /** @var User $user  */
        $user = auth()->user();
        $user->balance += $marketRecord->amount;
        $user->save();

        $marketRecord->delete();
        $balance_left = $user->balance;
        return response()->success("Game deleted successfully!", compact('balance_left'));
    }

    public function submitGame(Request $request)
    {
        // Log::info($request->all());
        $request->validate([
            'type' => ['required', Rule::in(['general', 'startLine', 'desawar'])],
            'market_id' => 'required|numeric',
            'games' => [
                'required',
                'array',
                'min:1',
                function (string $attribute, mixed $value, Closure $fail) {
                    foreach ($value as $game) {
                        if (!is_array($game) || !isset($game['number']) || !isset($game['amount']) || !isset($game['session']) || !isset($game['game_type_id'])) {
                            $fail("{$attribute} must be an array of games with number, amount, game_type_id and session.");
                        }
                    }
                },
            ],
            'games.*.number' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!ctype_digit($value)) {
                        $fail('The ' . $attribute . ' must be a numeric string.');
                    }
                }
            ],
            'games.*.amount' => ['required', 'numeric'],
            'games.*.session' => ["required", Rule::in(['open', 'close', 'null'])],
            'games.*.game_type_id' => 'required|exists:game_types,id',
        ]);

        $appData = AppData::first();
        // Check if any amount in games array exceeds 100
        foreach ($request->games as $key => $game) {
            if ($game['amount'] > $appData->max_bid_amount) {
                $message = "Amount in Game Number $key is More Than " . $appData->max_bid_amount . "! Maximum Bid Amount is " . $appData->max_bid_amount;
                return response()->failed($message);
            }
        }
        
        $market = match ($request->type) {
            "general" => Market::class,
            "startLine" => StartLineMarket::class,
            "desawar" => DesawarMarket::class,
        };
        if (!$market::where('id', $request->market_id)->exists()) {
            $message = "The selected market id is invalid.";
            return response()->failed($message);
        }

        if (!$market::where('id', $request->market_id)->first()->game_on) {
            return response()->failed("Game Is Not Open at The Moment!");
        }

        $amountSum = array_sum(array_column($request->games, 'amount'));
        /** @var User $user  */
        $user = auth()->user();
        if ($user->balance < $amountSum) {
            return response()->failed("User's balance is insufficient");
        }

        $marketRecord = match ($request->type) {
            "general" => MarketRecord::class,
            "startLine" => StartLineRecord::class,
            "desawar" => DesawarRecord::class,
        };
        $property_name = match ($request->type) {
            "general" => "market_id",
            "startLine" => "startline_market_id",
            "desawar" => "desawar_market_id",
        };

        foreach ($request->games as $game) {

            $market = $market::find($request->market_id);

            //check if game type is desawar then check max bet amount
            if ($request->type == 'desawar' &&  $game['amount'] > $market->max_bet_amount) {
                continue;
            }

            // check if game type is open, if open then check open_game_status
            if (($game['session'] == 'open' || $game['game_type_id'] == 5 || $game['game_type_id'] == 9 || $game['game_type_id'] == 10 || $game['game_type_id'] == 11) && $request->type != 'startLine') {
                if (!$market->open_game_status) {
                    return response()->failed("Open Game is not open at the moment!");
                }
            }



            //check if game type is close, if close then check close_game_status
            if ($game['session'] == 'close' && $request->type != 'startLine' && $request->type != 'desawar') {
                if (!$market->close_game_status) {
                    return response()->failed("Close Game is not open at the moment!");
                }
            }

            // if is_bet_time_limit is true 
            $betLimitTime = $market->previous_day_check 
                ? Carbon::tomorrow()->setTimeFromTimeString($market->bet_time_limit)
                : Carbon::today()->setTimeFromTimeString($market->bet_time_limit);

            if ($market->is_bet_time_limit && Carbon::now()->greaterThan($betLimitTime) && $request->type == 'desawar' && $game['game_type_id'] != 17 && $game['game_type_id'] != 18) {

                $userTotalBetAmtBeforeTimeLimit = DesawarRecord::where('user_id', $user->id)->where('desawar_market_id', $request->market_id)->where('number', $game['number'])->where('created_at', '<', $betLimitTime)->sum('amount');
                $userTotalBetAmtAfterTimeLimit = DesawarRecord::where('user_id', $user->id)->where('desawar_market_id', $request->market_id)->where('number', $game['number'])->where('created_at', '>', $betLimitTime)->sum('amount') + $game['amount']; 
                $chotiJotiLimit = $market->choti_jodi_bet_amount_limit;
                $motiJotiLimit = $market->moti_jodi_bet_amount_limit;

                // $formattedBetTime = Carbon::parse($market->bet_time_limit)->format('h:i A');
                $formattedBetTime = $betLimitTime->format('d M Y h:i A');
                
                // Condition 1: user already bet before limit time
                // if ($userTotalBetAmtBeforeTimeLimit > 0) {
                //     return response()->failed("You already placed a bet for number {$game['number']} in this market before {$formattedBetTime}.");
                // }

                // Condition 2: total after limit is less than min (choti)
                if($game['amount'] < $chotiJotiLimit) {
                    // return response()->failed("Minimum bet limit for number {$game['number']} is ₹{$chotiJotiLimit}. ". "You entered ₹{$game['amount']}, which is below the allowed limit.");
                    return response()->failed("Bid amount must be between ₹{$chotiJotiLimit} and ₹{$motiJotiLimit}.");
                }
                
                // Condition 3: total after limit is more than max (moti)
                if ($userTotalBetAmtAfterTimeLimit > $motiJotiLimit) {
                    // return response()->failed("You’ve exceeded the maximum bet limit ({$motiJotiLimit}) for number {$game['number']}. Your total bet amount is {$userTotalBetAmtAfterTimeLimit}.");
                    return response()->failed("Bid amount must be between ₹{$chotiJotiLimit} and ₹{$motiJotiLimit}.");
                }                
            }
            
            $marketRecord::create([
                "market_id" => $request->market_id,
                'game_type_id' => $game['game_type_id'],
                $property_name => $request->market_id,
                'number' => $game['number'],
                'amount' => $game['amount'],
                'session' => $game['session'],
                'status' => "pending",
                'user_id' => $user->id,
                'game_string' => Str::random(12),
                'date' => Carbon::today()
            ]);            

            $game_type = GameType::find($game['game_type_id']);
            //if $game_type->game_type is single_digit then check if number is single digit
            //if $game_type->game_type is jodi_digit then check if number is double digit else return error
            //if $game_type->game_type is single_pana or double_pana or triple_pana then check if number is 3 digit
            if ($game_type->game_type == 'single_digit' && strlen($game['number']) != 1) {
                return response()->failed("Single Digit Game Type Only Accepts Single Digit Number!");
            } elseif ($game_type->game_type == 'jodi' && strlen($game['number']) != 2) {
                return response()->failed("Jodi Game Type Only Accepts Double Digit Number!");
            } elseif (
                in_array($game_type->game_type, ['single_pana', 'double_pana', 'triple_pana']) && strlen($game['number']) != 3
            ) {
                return response()->failed("Pana Game Type Only Accepts Three Digit Number!");
            }

            $user->transactions()->create([
                "previous_amount" => $user->balance,
                "amount" => $game["amount"],
                "current_amount" => $user->balance - $game["amount"],
                "type" => "play",
                "details" => "Game ( " . Carbon::now() . " : $market->name : $game_type->name : $game[session] ) : $game[number]",
            ]);

            $user->balance -= $game["amount"];
            $user->save();
            $user->refresh();
        }

        $balance_left = $user->balance;
        $message = "Games successfully added";

        $bid_pdf_download = env('BID_PDF_DOWNLOAD');
        if($bid_pdf_download) {
            $pdf_url = $this->bidPdf($request);
            return response()->success($message, compact('balance_left', 'pdf_url', 'bid_pdf_download'));
        } else {
            return response()->success($message, compact('balance_left', 'bid_pdf_download'));
        }

    }

    public function getGameDetails(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['general', 'startLine', 'desawar'])],
            'market_id' => 'required|numeric',
        ]);
        $market = match ($request->input('type')) {
            "general" => Market::class,
            "startLine" => StartLineMarket::class,
            "desawar" => DesawarMarket::class,
        };
        $market = $market::find($request->market_id);
        if (!$market) {
            return response()->failed("Market not found!");
        }
        return response()->success("Data Sent!", compact('market'));
    }

    function getStatusText($status)
    {
        switch ($status) {
            case NULL:
                return NULL;
            case 1:
                return 'success';
            case 2:
                return 'failed';
            case 3:
                return 'pending';
            default:
                return NULL; // Or throw an exception if an invalid status is provided
        }
    }

    public function getGameHistory(Request $request)
    {
        // Log::info($request->all());

        if ($request->has('status'))
            $status = $this->getStatusText($request->input('status'));
        else $status = NULL;

        //if status is NULL, then set $request->status to nullable
        $request->merge(['status' => $status]);
        // $request->merge(['page' => 1]);

        $request->validate([
            'page' => 'required|numeric',
            'type' => ['required', Rule::in(['general', 'startLine', 'desawar'])],
            //market_id exists in market table & not required
            // 'market_id' => 'numeric|exists:markets,id|nullable|sometimes',
            //game_type exists in game_type table & not required
            'game_type_id' => 'numeric|exists:game_types,id|nullable|sometimes',
            //session is in enum & not required
            'session' => ["nullable", Rule::in(['open', 'close'])],
            //status is in enum & not required
            'status' => ["nullable", Rule::in(['pending', 'success', 'failed'])],
            //date is in date format & not required
            'date' => 'date|nullable|sometimes',
        ]);
        $market = match ($request->input('type')) {
            "general" => MarketRecord::class,
            "startLine" => StartLineRecord::class,
            "desawar" => DesawarRecord::class,
        };
        $market_id = match ($request->input('type')) {
            "general" => "market_id",
            "startLine" => "startline_market_id",
            "desawar" => "desawar_market_id",
        };
        $user = Auth::user();
        $market = $market::query();

        if ($request->has('market_id') && $request->market_id !== NULL) {
            $market->where($market_id, $request->market_id);
        }
        if ($request->has('game_type_id') && $request->game_type_id !== NULL) {
            $market->where('game_type_id', $request->game_type_id);
        }
        if ($request->has('session') && $request->session !== NULL) {
            $market->where('session', $request->session);
        }
        if ($request->has('status') && $request->status !== NULL) {
            $market->where('status', $request->status);
        }
        if ($request->has('date') && $request->date !== NULL) {
            $desawarMarket = DesawarMarket::find($request->market_id);
            
            // For DISAWAR market, get records between market open time and close time
            if ($desawarMarket && $desawarMarket->name == 'DISAWAR') {
                $inputDate = Carbon::parse($request->date);
                
                // Get raw open and close times
                $openTime = Carbon::parse($desawarMarket->getRawOriginal('open_time'));
                $closeTime = Carbon::parse($desawarMarket->getRawOriginal('close_time'));
                
                // Create full datetime with input date + market open time
                $marketOpenDateTime = $inputDate->copy()->setTime($openTime->hour, $openTime->minute, $openTime->second);
                
                // Create full datetime for close time
                $marketCloseDateTime = $inputDate->copy()->setTime($closeTime->hour, $closeTime->minute, $closeTime->second);
                
                // If close time is before open time, market spans midnight - add 1 day to close
                if ($closeTime->lt($openTime)) {
                    $marketCloseDateTime->addDay();
                }
                
                // Get records where created_at is between market open and close time
                $market->whereBetween('created_at', [$marketOpenDateTime, $marketCloseDateTime]);
            } else {
                $market->where('date', $request->date);
            }
        }


        $gameHistory = $market->where('user_id', $user->id)
            ->with('gameType', 'market')
            ->orderBy('id', 'desc')
            ->paginate(10, ['*'], 'game_history', $request->page);

        return response()->success("Data Sent", compact('gameHistory'));
    }

    public function getGameResults(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['general', 'startLine', 'desawar'])],
            'date' => 'date|nullable|sometimes'
        ]);
        $market = match ($request->input('type')) {
            "general" => MarketResult::class,
            "startLine" => StartLineResult::class,
            "desawar" => DesawarResult::class,
        };

        $date = $request->input('date') ?? Carbon::today();
        $gameResults = $market::with('market')
            ->where('result_date', $date)
            ->get();
        return response()->success("Data Sent!", compact('gameResults'));
    }

    public function getGameRates()
    {
        $gameTypesGeneral = GameType::where('type', 'general')->get();
        $gameTypesStartLine = GameType::where('type', 'start_line')->get();
        $gameTypesDesawar = GameType::where('type', 'desawar')->get();

        $data = [
            $data1 = [
                'title' => 'For Kalyan Games',
                'list' => $gameTypesGeneral
            ],
            // $data2 = [
            //     'title' => 'King Starline Game Win Ration',
            //     'list' => $gameTypesStartLine
            // ],
            $data3 = [
                'title' => 'For Desawar Games',
                'list' => $gameTypesDesawar
            ],
        ];

        return response()->success("Data Sent!", compact('data'));
    }

    // PDF
    static function bidPdf($request)
    {
        // Delete file old folder
        // Storage::disk('public')->deleteDirectory('bids');

        $marketRecord = match ($request->type) {
            "general" => Market::class,
            "startLine" => StartLineMarket::class,
            "desawar" => DesawarMarket::class,
        };
        $market = $marketRecord::findOrFail($request->market_id)->name;

        $data = [
            'title' => 'Your Bids',
            'date' => Carbon::now()->format('d-M-Y, h:i:s A'),
            'bids' => $request['games'],
            'market' => $market,
        ];
        $pdf = Pdf::loadView('dashboard.desawar-markets.bid-pdf', $data);
        $fileName = 'bid_' . time() . '.pdf';
        Storage::disk('public')->put('bids/' . $fileName, $pdf->output());
        // return asset('storage/bids/' . $fileName);
        return route('download.bid.pdf', ['fileName' => $fileName]);
    }
}
