<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WithdrawHistory;
use Illuminate\Http\Request;

class WithdrawHistoryController extends Controller
{
    public function getWithdrawlHistory(Request $request)
    {
        $request->validate([
            'page' => 'required|numeric',
        ]);

        $depositHistory = WithdrawHistory::where('user_id', auth()->id())->latest()->paginate(50, ['*'], 'withdrawl_history', $request->page);
        return response()->success("Data Sent!", compact('depositHistory'));
    }
}
