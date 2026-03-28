<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawDetailsController extends Controller
{
    public function saveBankDetails(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'account_holder_name' => 'required|string',
            'account_number' => 'required|numeric',
            'account_ifsc_code' => 'required|string'
        ]);
        /** @var User $user */
        $user = Auth::user();

        $user->withdrawDetails()->updateOrCreate([
            'user_id' => $user->id
        ], [
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->account_number,
            'account_ifsc_code' => $request->account_ifsc_code,
        ]);
        $withdrawDetails = $user->withdrawDetails;
        return response()->success("Data Sent!", compact('withdrawDetails'));
    }

    public function saveUpiDetails(Request $request)
    {
        $request->validate([
            'upi_name' => 'required|string',
            'upi_id' => 'required|string',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->withdrawDetails()->updateOrCreate([
            'user_id' => $user->id
        ], [
            'upi_name' => $request->upi_name,
            'upi_id' => $request->upi_id,
        ]);
        $withdrawDetails = $user->withdrawDetails;
        return response()->success("Data Sent!", compact('withdrawDetails'));
    }
}
