<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getUserBalance()
    {
        $user = User::find(auth()->id());
        $balance = $user->balance;
        return response()->success("Data Sent!", compact('balance'));
    }

    //update Profile: name & dob
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'nullable|sometimes|string',
            'dob' => 'nullable|sometimes|date',
            //email optional
            'email' => 'email|nullable|sometimes',
        ]);
        /** @var User $user */
        $user = Auth::user();
        if ($request->name) {
            $user->name = $request->name;
        }
        if ($request->dob) {
            $user->dob = $request->dob;
        }
        if ($request->email) {
            $user->email = $request->email;
        }
        $user->save();
        return response()->success('Profile updated!', NULL);
    }

    public function changeNotification(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['general', 'startLine', 'desawar'])],
        ]);
        $type = match ($request->type) {
            "general" => "general_noti",
            "startLine" => "startline_noti",
            "desawar" => "desawar_noti",
        };
        /** @var User $user */
        $user = Auth::user();
        $user->$type = !$user->$type;
        $user->save();
        return response()->success('Notification status changed!', NULL);
    }

    public function withdrawBalance(Request $request)
    {
        Log::info("========= withdrawBalance ========== " . Auth::user());
        Log::info($request->all());

        $request->validate([
            'amount' => 'required|numeric',
            'withdraw_mode' => 'required|in:bank,upi'
        ]);


        if ($request->withdraw_mode == 'bank') {
            $request->validate([
                'bank_name' => 'required_if:withdraw_mode,bank|string',
                'account_holder_name' => 'required_if:withdraw_mode,bank|string',
                'account_number' => 'required_if:withdraw_mode,bank|string',
                'account_ifsc_code' => 'required_if:withdraw_mode,bank|string',
            ]);
        } else {
            $request->validate([
                'upi_id' => 'required_if:withdraw_mode,upi|string',
            ]);
        }


        /** @var User $user */
        $user = Auth::user();

        //save bank details if withdraw mode is bank ($user->withdrawDetails()->updateOrCreate)
        if ($request->withdraw_mode == 'bank') {
            $user->withdrawDetails()->updateOrCreate([
                'user_id' => $user->id
            ], [
                'bank_name' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'account_ifsc_code' => $request->account_ifsc_code,
            ]);
        }

        //save upi details if withdraw mode is upi ($user->withdrawDetails()->updateOrCreate)
        if ($request->withdraw_mode == 'upi') {
            $user->withdrawDetails()->updateOrCreate([
                'user_id' => $user->id
            ], [
                'upi_name' => $request->upi_name,
                'upi_id' => $request->upi_id,
            ]);
        }

        if ($user->balance < $request->amount || $user->withdrawal_balance < $request->amount) {
            $message = "Balance or Withdrawal Balance is insufficient";
            return response()->failed($message);
        }


        // Withdrawal Request Opening Time
        $withdraw_open_time_session_one  = Carbon::parse('08:00 AM');
        $withdraw_close_time_session_one = Carbon::parse('12:59 PM');
        
        $withdraw_open_time_session_two  = Carbon::parse('06:00 PM');
        $withdraw_close_time_session_two = Carbon::parse('09:59 PM');

        $now = Carbon::now();

        // ---- Check If Now Is Inside Either Session ---- //
        $isMorning = $now->between($withdraw_open_time_session_one, $withdraw_close_time_session_one);
        $isEvening = $now->between($withdraw_open_time_session_two, $withdraw_close_time_session_two);

        if (!$isMorning && !$isEvening) {
            $message = "WITHDRAW TIMINGS MORNING - "
                . $withdraw_open_time_session_one->format('h:i A') . " TO "
                . $withdraw_close_time_session_one->format('h:i A')
                . " And EVENING - "
                . $withdraw_open_time_session_two->format('h:i A') . " TO "
                . $withdraw_close_time_session_two->format('h:i A') . ".";
            return response()->failed($message);
        }

        // ---- Check History ---- //
        $withdrawHistorySessionOne = $user->withdrawHistory()
            ->whereDate('created_at', now())
            ->whereBetween('created_at', [$withdraw_open_time_session_one, $withdraw_close_time_session_one])
            ->first();

        $withdrawHistorySessionTwo = $user->withdrawHistory()
            ->whereDate('created_at', now())
            ->whereBetween('created_at', [$withdraw_open_time_session_two, $withdraw_close_time_session_two])
            ->first();

        // If currently morning session
        if ($isMorning && $withdrawHistorySessionOne) {
            return response()->failed(
                "You have already made a withdrawal in the morning session (" .
                $withdraw_open_time_session_one->format('h:i A') . " to " .
                $withdraw_close_time_session_one->format('h:i A') . "). Please wait for the evening session." .
                "You can withdraw between " . $withdraw_open_time_session_two->format('h:i A') . " to " . $withdraw_close_time_session_two->format('h:i A') . "."
            );
        }

        // If currently evening session
        if ($isEvening && $withdrawHistorySessionTwo) {
            return response()->failed(
                "You have already made a withdrawal in the evening session (" .
                $withdraw_open_time_session_two->format('h:i A') . " to " .
                $withdraw_close_time_session_two->format('h:i A') . "). Please wait for the morning session tomorrow." .
                "You can withdraw between " . $withdraw_open_time_session_one->format('h:i A') . " to " . $withdraw_close_time_session_one->format('h:i A') . "."
            );
        }
        

        $appData = AppData::first();
        // //carbon check if time is between two times
        // $now = now();
        // // $now = Carbon::parse('2024-01-28 02:30:00');
        // $withdraw_open_time = Carbon::parse($appData->withdraw_open_time);
        // $withdraw_close_time = Carbon::parse($appData->withdraw_close_time);

        // //check conditions from 8am today to 2am tomorrow
        // if ($withdraw_close_time->lessThan($withdraw_open_time)) {
        //     //this part is being called
        //     if ($now->lt($withdraw_close_time)) {
        //         $withdraw_open_time->subDay();
        //     } else {
        //         $withdraw_close_time->addDay();
        //     }
        //     if (!$now->between($withdraw_open_time, $withdraw_close_time)) {
        //         $message = "Withdraw is not available now, Please Withdraw between " . $withdraw_open_time->format('h:i A') . " to " . $withdraw_close_time->format('h:i A') . "";
        //         return response()->failed($message);
        //     }
        // } else {
        //     if (!$now->between($withdraw_open_time, $withdraw_close_time)) {
        //         $message = "Withdraw is not available now, Please Withdraw between " . $withdraw_open_time->format('h:i A') . " to " . $withdraw_close_time->format('h:i A') . "";
        //         return response()->failed($message);
        //     }
        // }

        $balance_before = $user->balance;
        $current_amount = $user->balance - $request->amount;
        $user->withdrawal_balance -= $request->amount;
        $user->balance = $current_amount;
        $user->save();
        $user->withdrawHistory()->create([
            'amount' => $request->amount,
            'current_amount' => $current_amount, // $user->balance - $request->amount,
            'withdraw_mode' => $request->withdraw_mode,
            'status' => 'pending',
            'withdrawal_method' => $appData->withdrawal_method,
            'transaction_id' => Str::random(12)
        ]);
        $user->transactions()->create([
            'previous_amount' => $balance_before,
            'amount' => $request->amount,
            'current_amount' =>  $current_amount,
            'type' => "withdraw",
            "details" => "Withdraw a balance"
        ]);
        $balance_left = $user->balance;
        $withrawDetails = $user->withdrawDetails;

        return response()->success('Withdraw request sent!', compact('balance_left', 'withrawDetails'));
    }

    public function getReferralDetails()
    {
        $user = User::with('referralUsers')->find(auth()->id());
        $referralUsers = $user->referralUsers;

        // $total_earned = count($referralUsers) * AppData::first()->invite_bonus;
        $lossBonusRevertAmt = Transaction::where('user_id', $user->id)->where('type', 'lossBonusRevert')->sum('amount');
        $lossBonusAmt = Transaction::where('user_id', $user->id)->where('type', 'lossBonus')->sum('amount');
        $total_earned = $lossBonusAmt - $lossBonusRevertAmt;
        $total_invited = count($referralUsers);

        return response()->success("Data Sent!", compact('total_earned', 'total_invited', 'referralUsers'));
    }

    function oneSignalDubscriptionId(Request $request){   
        $user = User::find($request->user_id);
        if($request->one_signalsubscription_id){
            User::where('id', $user->id)->update([
                'one_signalsubscription_id' => $request->one_signalsubscription_id
            ]);

            return response()->json(['message' => 'Subscription ID received successfully!']);
        }
    }
}
