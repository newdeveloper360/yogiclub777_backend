<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\DepositHistory;
use App\Models\DesawarRecord;
use App\Models\GameRecord;
use App\Models\Market;
use App\Models\MarketRecord;
use App\Models\StartLineRecord;
use App\Models\User;
use App\Models\WithdrawHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\Chat;
use App\Models\DesawarMarket;
use App\Models\GameType;
use App\Models\Message;
use App\Models\StartLineMarket;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    //construct fuction
    public function __construct()
    {
        new verify_payments();
    }

    public function index()
    {
        $today = Carbon::today();
        $markets = Market::count() + StartLineMarket::count() + DesawarMarket::count();
        $users = User::where('role', 'user')->count();
        $todayUsers = User::where('role', 'user')->whereDate('created_at', $today)->count();

        //  Today Game Amount
        $todayGameAmount = MarketRecord::whereDate('created_at', $today)->sum('amount') + StartLineRecord::whereDate('created_at', $today)->sum('amount') + DesawarRecord::whereDate('created_at', $today)->sum('amount');

        //  Today Win Amount
        $todayWinAmount = MarketRecord::whereDate('created_at', $today)->where('status', 'success')->sum('win_amount')
            + StartLineRecord::whereDate('created_at', $today)->where('status', 'success')->sum('win_amount')
            + DesawarRecord::whereDate('created_at', $today)->where('status', 'success')->sum('win_amount');

        $profitAndLoss = $todayGameAmount - $todayWinAmount;    

        //  Deposit
        $deposit = DepositHistory::where('status', 'success')
            ->sum('amount');
        //  Today Deposit
        $todayDeposit = DepositHistory::whereDate('created_at', $today)
            ->where('status', 'success')
            ->sum('amount');
        //  WithDraw
        $withdraw = WithdrawHistory::where('status', 'success')
            ->sum('amount');
        //  Today Withdraw
        $todayWithdraw = WithdrawHistory::whereDate('created_at', $today)
            ->where('status', 'success')
            ->sum('amount');
        //Wallet Blance
        $walletBalance = User::where('role', 'user')->sum('balance');
        $gameType = Market::all();
        $appData = AppData::first();

        return view("dashboard.index", [
            'markets' => $markets,
            'users' => $users,
            'todayUsers' => $todayUsers,
            'todayGameAmount' => $todayGameAmount,
            'todayWinAmount' => $todayWinAmount,
            'deposit' => $deposit,
            'todayDeposit' => $todayDeposit,
            'withdraw' => $withdraw,
            'todayWithdraw' => $todayWithdraw,
            'walletBalance' => $walletBalance,
            'gameType' => $gameType,
            'appData' => $appData,
            'profitAndLoss' => $profitAndLoss
        ]);
    }

    public function getBidsDetail(Request $request)
    {
        $request->validate([
            'game_id' => 'required',
            'market_time' => 'required'
        ]);
        $today = Carbon::today();
        $bids = MarketRecord::where(['market_id' => $request->game_id, 'game_type_id' => 4, 'session' => $request->market_time])->whereDate('created_at', $today)
            ->select(
                DB::raw('count(*) as ank_bids, SUM(amount) as ank_amount')
            )->groupBy('number')->orderBy('number', 'ASC')->get();
        return response()->json(['status' => 'success', 'bids' => $bids]);
    }

    public function deleteChetDepositWithdrawl() {
        $today = Carbon::now()->subHours(72); 

        // Delete all deposit history before today
        DepositHistory::whereDate('created_at', '<', $today)->delete();

        // Delete all withdraw history before today
        WithdrawHistory::whereDate('created_at', '<', $today)->delete();
        Chat::whereDate('created_at', '<', $today)->delete();
        Message::whereDate('created_at', '<', $today)->delete();
        DesawarRecord::whereDate('created_at', '<', $today)->delete();

        // Delete file old folder
        Storage::disk('public')->deleteDirectory('bids');

        return redirect()->back()->with(['success' => 'Old records deleted successfully.']);
    }
}
