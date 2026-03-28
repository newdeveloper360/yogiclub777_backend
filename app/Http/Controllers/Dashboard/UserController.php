<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\DesawarRecord;
use App\Models\MarketRecord;
use App\Models\Transaction;
use App\Models\WithdrawHistory;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $users = User::withCount('withdrawHistory')
                ->withSum('marketRecords as total_market_game_amount', 'amount')
                ->withSum('startLineRecords as total_startLine_game_amount', 'amount')
                ->withSum('desawarRecords as total_desawar_game_amount', 'amount')
                ->withSum('marketRecords as total_market_win_amount', 'win_amount')
                ->withSum('startLineRecords as total_startline_win_amount', 'win_amount')
                ->withSum('desawarRecords as total_desawar_win_amount', 'win_amount')
                ->withSum(['withdrawHistory as total_withdraw_success_amount' => function ($query) {
                    $query->where('status', 'success');
                }], 'amount')
                ->where('role', 'user')
                ->where(function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view('dashboard.users.index', compact('users', 'searchValue'));
        }

        $users = User::withCount('withdrawHistory')
            ->withSum('marketRecords as total_market_game_amount', 'amount')
            ->withSum('startLineRecords as total_startLine_game_amount', 'amount')
            ->withSum('desawarRecords as total_desawar_game_amount', 'amount')
            ->withSum('marketRecords as total_market_win_amount', 'win_amount')
            ->withSum('startLineRecords as total_startline_win_amount', 'win_amount')
            ->withSum('desawarRecords as total_desawar_win_amount', 'win_amount')
            ->withSum(['withdrawHistory as total_withdraw_success_amount' => function ($query) {
                $query->where('status', 'success');
            }], 'amount')
            ->where('role', 'user')
            ->latest()->paginate(25);
        return view("dashboard.users.index", [
            'users' => $users
        ]);
    }

    public function getUserDetail($id)
    {
        $user = User::where('id', $id)->with('withdrawDetails')->first();
        $withdrawHistory  = WithdrawHistory::where(['user_id' => $id])->latest()->limit(10)->get();

        // $bidHistory = MarketRecord::where(['user_id' => $id])->with(['market', 'gameType'])->latest()->limit(10)->get();
        $bidHistory = DesawarRecord::where(['user_id' => $id])->with(['market', 'gameType'])->latest()->limit(10)->get();

        $withdraw_request_accept = 0;
        $withdraw_request_reject = 0;
        if (request()->user()->can('withdraw-request-accept')) {
            $withdraw_request_accept = 1;
        }
        if (request()->user()->can('withdraw-request-reject')) {
            $withdraw_request_reject = 1;
        }

        $transactionHistory = Transaction::where(['user_id' => $id])->latest()->limit(10)->get();
        $creditHistory = Transaction::where(['user_id' => $id, 'type' => 'recharge'])->latest()->limit(10)->get();
        $debitHistory = Transaction::where(['user_id' => $id, 'type' => 'withdraw'])->latest()->limit(10)->get();
        $userTransaction['totalRecharge'] = Transaction::where(['user_id' => $id, 'type' => 'recharge'])->sum('amount');
        $userTransaction['totalWithdraw'] = Transaction::where(['user_id' => $id, 'type' => 'withdraw'])->sum('amount');

        return view('dashboard.users.detail', compact('user', 'withdrawHistory', 'withdraw_request_reject', 'withdraw_request_accept', 'bidHistory', 'transactionHistory', 'creditHistory', 'debitHistory', 'userTransaction'));
    }

    public function getUserAllDetail($id)
    {
        $user = User::where('id', $id)->with('withdrawDetails')->first();
        $withdrawHistory  = WithdrawHistory::where(['user_id' => $id])->latest()->get();

        $bidHistory = MarketRecord::where(['user_id' => $id])->with(['market', 'gameType'])->latest()->get();

        $withdraw_request_accept = 0;
        $withdraw_request_reject = 0;
        if (request()->user()->can('withdraw-request-accept')) {
            $withdraw_request_accept = 1;
        }
        if (request()->user()->can('withdraw-request-reject')) {
            $withdraw_request_reject = 1;
        }

        $transactionHistory = Transaction::where(['user_id' => $id])->latest()->get();
        $creditHistory = Transaction::where(['user_id' => $id, 'type' => 'recharge'])->latest()->get();
        $debitHistory = Transaction::where(['user_id' => $id, 'type' => 'withdraw'])->latest()->get();

        return view('dashboard.users.detail', compact('user', 'withdrawHistory', 'withdraw_request_reject', 'withdraw_request_accept', 'bidHistory', 'transactionHistory', 'creditHistory', 'debitHistory'));
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone|regex:/[0-9]{10}/|digits:10',
            'password' => 'required|confirmed|min:4',
        ]);

        $user =   User::create([
            'name' => $request->name,
            'phone' => "$request->phone",
            'password' => Hash::make($request->password),
            'user_id' => auth()->id(),
            'role' => 'user',
            'confirmed' => 1,
            'fcm' => null,
            'blocked' => 0,
        ]);

        $user->permissions()->sync($request->permissions);
        return redirect()->route('users.index')
            ->with('success', 'User successfully created');
    }

    public function market($id)
    {
        $user = User::with('gameRecords')->find($id);
        return view("dashboard.users.games-record", [
            'user' => $user
        ]);
    }


    public function changeBalanceView($id)
    {
        $user = User::findOrFail($id);
        return view("dashboard.users.change-balance", [
            'user' => $user
        ]);
    }



    public function changeBalance(Request $request, $id)
    {
        $request->validate([
            'balance' => 'required|numeric|min:1',
            'action' => 'required|regex:/^[+-]$/'
        ]);
        $user = User::findOrFail($id);
        if ($request->action == '+') {

            $app_data = AppData::first();
            $app_data->total_mannual_amount_added = $app_data->total_mannual_amount_added + $request->balance;
            $app_data->update();

            $user->balance = $user->balance + $request->balance;
            $user->update();

            $user->transactions()->create([
                'previous_amount' => $user->balance - $request->balance,
                'amount' =>  $request->balance,
                'current_amount' => $user->balance,
                "type" => "recharge",
                "details" => "Balance ($request->balance) Added"
            ]);
        } else {
            if ($user->balance - $request->balance >= 0) {
                $user->balance = $user->balance - $request->balance;
                $user->update();
                $user->transactions()->create([
                    'previous_amount' => $user->balance + $request->balance,
                    'amount' =>  $request->balance,
                    'current_amount' => $user->balance,
                    "type" => "recharge",
                    "details" => "Balance ($request->balance) Deducted"
                ]);
            } else {
                return back()->withErrors(['lowBalance' => 'User balance is low'])->withInput();
            }
        }
        return back()->with('success', 'User balance is updated');
    }

    public function toogleBlock(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric'
        ]);
        $user = User::findOrFail($request->user_id);
        $user->blocked = intval($request->blocked);
        $user->save();
    }

    public function adminToUserLogin($id){
        try {
            $user = User::findOrFail($id);
            if($user === null){
                return redirect()->route('users.index')->with('faild', 'User not found');
            }

            // Mark user as confirmed
            $user->confirmed = 1;
            $user->save();

            // Create token
            $token = $user->createToken('auth-token')->plainTextToken;

            if (env('APP_URL') == 'http://localhost') {
                $url = 'http://localhost:3000';
            } else {
                $url = 'https://yogiclub777.com';
            }
            return redirect()->away("$url/auth/login?token={$token}");
            
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('failed', 'User not found or login failed');
        }
    }
}
