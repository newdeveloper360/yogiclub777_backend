<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepositHistory;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DepositHistoryController extends Controller
{
    // old baba code
    // public function getDepositHistory(Request $request)
    // {
    //     $request->validate([
    //         'page' => 'required|numeric',
    //     ]);
    //     $depositHistory = Transaction::where('user_id', auth()->id())
    //         ->where(function ($query) {
    //             $query->where('type', 'recharge')
    //                 ->orWhere('type', 'bonus')
    //                 ->orWhere('type', 'win')
    //                 ->orWhere('type', 'transfer')
    //                 ->orWhere('type', 'play');
    //         })
    //         ->latest()->paginate(50, ['*'], 'deposit_history', $request->page);
    //     return response()->success("Data Sent!", compact('depositHistory'));
    // }

    public function getDepositHistory(Request $request)
    {
        $request->validate([
            'page' => 'required|numeric',
        ]);
        $depositHistory = DepositHistory::where('user_id', auth()->id())
            ->latest()->paginate(50, ['*'], 'deposit_history', $request->page);
        return response()->success("Data Sent!", compact('depositHistory'));
    }
}
