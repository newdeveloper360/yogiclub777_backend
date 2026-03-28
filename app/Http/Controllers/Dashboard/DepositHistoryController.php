<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\DepositHistory;
use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\User;
use App\Notifications\BonusWonNotification;
use App\Notifications\DepositRequestAcceptNotification;
use App\Notifications\DepositRequestRejectNotification;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DepositHistoryController extends Controller
{
    public function index()
    {
        $depositHistories  = DepositHistory::with('user')->latest()->paginate(25);
        return view('dashboard.deposit-history.index', compact('depositHistories'));
    }

    public function giveBonusToSelf($user, $deposit)
    {
        $appData = AppData::first();
        $self_recharge_bonus = $appData->self_recharge_bonus;
        if ($self_recharge_bonus == NULL || $self_recharge_bonus == 0) {
            return;
        }
        $amount_to_give = $self_recharge_bonus == 0 ? 0 : ($deposit->amount * $self_recharge_bonus) / 100;
        if ($amount_to_give > 0) {
            $user->refresh();
            $balance_before = $user->balance;
            $user->balance = $user->balance + $amount_to_give ?? 0;
            $user->save();
            $user->transactions()->create([
                'previous_amount' => $balance_before,
                'amount' => $amount_to_give,
                'current_amount' => $user->balance,
                "type" => "recharge",
                "details" => "Recharge Bonus ($amount_to_give)"
            ]);
            $user->notify(new BonusWonNotification($amount_to_give, $user->fcm, $user->one_signalsubscription_id));
        }
    }

    public function acceptRequest($id)
    {
        $deposit = DepositHistory::findOrFail($id);
        //if already success then return error
        if ($deposit->status == "success") {
            return back()->with('error', 'Request has already been accepted');
        }
        $deposit->status = "success";
        $deposit->current_amount = User::find($deposit->user_id)->balance + $deposit->amount;   
        $deposit->save();
        $user = User::with('referredUser')->findOrFail($deposit->user->id);
        $firstDeposit =  $user->depositHistory()->orderBy('created_at', 'asc')->first();
        $appData = AppData::first();
        //On 1st recharge, give amount to the refferal user
        if ($deposit->id == $firstDeposit->id && $appData->invite_system_enable && !env('BET_LOSE_GIVE_MONEY', false)) {
            $refferredUser = $user->referredUser;
            if ($refferredUser) {
                $invite_bonus = $appData->invite_bonus;
                $balance_before = $refferredUser->balance;
                $refferredUser->balance = $refferredUser->balance + $invite_bonus ?? 0;
                $refferredUser->save();
                $refferredUser->transactions()->create([
                    'previous_amount' => $balance_before,
                    'amount' => $invite_bonus,
                    'current_amount' => $refferredUser->balance,
                    "type" => "recharge",
                    "details" => "You won ($deposit->amount) in bonus"
                ]);
                $refferredUser->notify(new BonusWonNotification($deposit->amount, $refferredUser->fcm, $refferredUser->one_signalsubscription_id));
            }
        }
        $balance_before = $user->balance;
        $user->balance = $user->balance + $deposit->amount;
        $user->save();
        $user->transactions()->create([
            'previous_amount' => $balance_before,
            'amount' => $deposit->amount,
            'current_amount' => $user->balance,
            "type" => "recharge",
            "details" => "Deposit ($deposit->amount) Accepted"
        ]);
        $user->notify(new DepositRequestAcceptNotification($deposit->amount, $user->fcm, $user->one_signalsubscription_id));
        return back()->with('success', 'Request has been accepted');
    }

    public function rejectRequest($id)
    {
        $deposit = DepositHistory::with("user")->findOrFail($id);
        //if already failed then return error
        if ($deposit->status == "failed") {
            return back()->with('error', 'Request has already been rejected');
        }
        $deposit->status = "failed";
        $deposit->save();
        $user = User::findOrFail($deposit->user->id);
        $user->notify(new DepositRequestRejectNotification($deposit->amount, $user->fcm, $user->one_signalsubscription_id));
        return back()->with('success', 'Request has been rejected');
    }
}
