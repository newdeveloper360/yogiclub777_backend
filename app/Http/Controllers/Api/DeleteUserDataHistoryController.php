<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepositHistory;
use App\Models\DesawarRecord;
use App\Models\WithdrawHistory;
use Carbon\Carbon;
use Google\Service\Texttospeech\Turn;
use Illuminate\Http\Request;

class DeleteUserDataHistoryController extends Controller
{
    public function deleteHistory(){
        $today = Carbon::now()->subHours(48);  // Delete Data Before 48 hours
        $user =  auth()->user();

        DepositHistory::where('user_id', $user->id)->whereDate('created_at', '<', $today)->delete();
        WithdrawHistory::where('user_id', $user->id)->whereDate('created_at', '<', $today)->delete();
        DesawarRecord::where('user_id', $user->id)->whereDate('created_at', '<', $today)->delete();

        return response()->json([
            'message' => 'History delete successfully.',
            'status' => true
        ], 201);
    }
}
