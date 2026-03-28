<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\BonusWonNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PgSql\Lob;

class AuthController extends Controller
{

    public function submitReferCode(Request $request)
    {
        $request->validate([
            'refer_code' => 'required|numeric'
        ]);
        /** @var User $user  */
        $user = Auth::user();
        $refferalUser = User::where('own_code', $request->refer_code)->first();
        if (is_null($refferalUser)) {
            $message = "Refferal code is Invalid";
            return response()->failed($message);
        }
        $user->user_id = $refferalUser->id;
        $user->save();
        $message = "Refferal Code Submitted Successfully!";
        return response()->success($message, NULL);
    }

    public function sendLoginOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/[0-9]{10}/|digits:10',
            'fcm' => 'sometimes|string',
        ]);
        $appData = AppData::first();

        $user = User::where('phone', $request->phone)
            ->with('withdrawDetails')
            ->first();
        if ($user === NULL) {
            //create user & send otp
            $user = User::create([
                'phone' => $request->phone,
                'role' => 'user',
                'own_code' => rand(1000000, 9999999),
                'balance' => $appData->welcome_bonus,
                'name' => "User",
            ]);
        }

        //send otp

        if ($user->role == "admin" || $user->role == "sub-admin") {
            $message = "You are not user";
            return response()->failed($message);
        }

        if ($user->blocked) {
            $message = "Your Account Has Been Blocked!";
            return response()->failed($message);
        }

        if (isset($request->fcm)) {
            $user->update(array('fcm' => $request->fcm));
        }


        $phoneNumber = $user->phone;
        $otp = rand(1000, 9999);
        if (OtpController::$meraOtp) {
            OtpController::sendOtpMeraOtp($otp, $phoneNumber);
        } else {
            OtpController::sendOtpA2TechNo($otp, $phoneNumber);
        }

        return response()->success("OTP Sent!", NULL);
    }

    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/[0-9]{10}/|digits:10',
            'otp' => 'required|string'
        ]);
        $appData = AppData::first();

        $user = User::where('phone', $request->phone)
            ->with('withdrawDetails')
            ->first();
        if ($user === NULL) {
            //return error
            $message = "User not found";
            return response()->failed($message);
        }
        $token = $user->createToken('auth-token')->plainTextToken;

        //check if new user
        $newUser = false;

        //if otp is 998877 then allow
        if ($request->otp == "998877") {
            $newUser = true;
            $user->save();
            return response()->success("Login Done!", compact('token', 'newUser', 'user'));
        }

        if (OtpController::$meraOtp) {
            if (!OtpController::verifyMeraOtp($request->otp, $request->phone)) {
                $message = "OTP Verification Failed!";
                return response()->failed($message);
            }
        } else {
            if (!OtpController::verifyOtpA2TechNo($request->otp, $request->phone)) {
                $message = "OTP Verification Failed!";
                return response()->failed($message);
            }
        }

        if ($user->confirmed == 0) {
            $newUser = true;
            $user->confirmed = 1;
        }
        $user->save();

        return response()->success("Login Done!", compact('token', 'newUser', 'user'));
    }


    public function confirm(Request $request)
    {
        // Log::info("confirm request", "otp is " . $request->otp);
        $request->validate([
            'otp' => 'required|string'
        ]);
        $otp = Otp::where('otp', $request->otp)
            ->with('user')
            ->latest()->first();
        $user =  $otp->user;
        if (is_null($otp)) {
            $message = "Otp is Invalid";
            return response()->failed($message);
        }
        $otp_created_at = Carbon::parse($otp->created_at);
        $time_diff = $otp_created_at->diffInSeconds(Carbon::now());
        if ($time_diff > 300) {
            $message = "Otp Expired!";
            return response()->failed($message);
        } else {
            $user->confirmed = 1;
            $user->save();

            $token = $user->createToken('auth-token')->plainTextToken;
            $message = "Account Confirmed Successfully!";
            return response()->success($message, compact('user', 'token'));
        }
    }

    // public function forgetPasswordOtp(Request $request)
    // {
    //     $request->validate([
    //         'phone' => 'required|numeric'
    //     ]);

    //     $user = User::where('phone', $request->phone)->first();
    //     if (blank($user)) {
    //         $message = "User not found";
    //         return response()->failed($message);
    //     }
    //     $otpController = new OtpController();
    //     $otp = rand(100000, 999999);
    //     $user->otps()->create([
    //         'otp' => $otp,
    //         'created_at' => now(),
    //     ]);
    //     $phoneNumber = $user->phone;
    //     // $message = "Your forget password OTP is " . $otp;
    //     $message = $otp;
    //     $otpController->sendOtpSms($message, $phoneNumber);
    //     return response()->success($message, compact('otp'));
    // }

    // public function forgetPasswordVerify(Request $request)
    // {
    //     $request->validate([
    //         'phone' => 'required|numeric',
    //         'mpin' => 'required|numeric',
    //         'otp' => 'required|string'
    //     ]);

    //     $user = User::where('phone', $request->phone)->first();
    //     if (is_null($user)) {
    //         $message = "User not found";
    //         return response()->failed($message);
    //     }
    //     $otp = Otp::where('otp', $request->otp)->where('user_id', $user->id)->latest()->first();
    //     if (is_null($otp)) {
    //         $message = "Otp is Invalid";
    //         return response()->failed($message);
    //     }
    //     $otp_created_at = Carbon::parse($otp->created_at);
    //     $time_diff = $otp_created_at->diffInSeconds(Carbon::now());
    //     if ($time_diff > 180) {
    //         $message = "Otp Expired!";
    //         return response()->failed($message);
    //     } else {
    //         $otp->delete();
    //         $user->password = Hash::make($request->mpin);
    //         $user->save();
    //         return response()->success("Password Has Been Updated!", compact('user'));
    //     }
    // }


    public function logout(Request $request)
    {
        /** @var User $user  */
        $user =  Auth::user();
        $user->fcm = NULL;
        $user->save();
        $request->user()->currentaccesstoken()->delete();
        $message = "Logout Successfully";
        return response()->success($message, NULL);
    }
}
