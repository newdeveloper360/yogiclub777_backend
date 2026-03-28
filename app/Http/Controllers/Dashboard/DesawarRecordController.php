<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\DesawarRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use App\Helpers\OneSignalHelper;

class DesawarRecordController extends Controller
{
    public function index(Request $request)
    {
        // if ($request->has('searchValue')) {
        //     $searchValue = $request->searchValue;
        //     $desawarRecords = DesawarRecord::with(['market', 'user'])
        //         ->whereHas('user', function ($query) use ($searchValue) {
        //             $query->where('name', 'LIKE', '%' . $searchValue . '%')
        //                 ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
        //         })->latest()->paginate(250);
        //     return view('dashboard.desawar-markets.records', compact('desawarRecords', 'searchValue'));
        // }

        // $desawarRecords = DesawarRecord::with('market')->latest()->paginate(25);
        // return view('dashboard.desawar-markets.records', compact('desawarRecords'));


        $query = DesawarRecord::with(['market', 'user']);
        $searchValue = '';
        $searchStatus = '';

        // Search by user name or phone
        if ($request->has('searchValue') && !empty($request->searchValue)) {
            $searchValue = $request->searchValue;
            $query->whereHas('user', function ($q) use ($searchValue) {
                $q->where('name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
            });
        }

        // Search by status
        if ($request->has('searchStatus') && !empty($request->searchStatus)) {
            $searchStatus = $request->searchStatus;
            $query->where('status', $searchStatus);
        }

        $desawarRecords = $query->latest()->paginate(25);
        return view('dashboard.desawar-markets.records', compact('desawarRecords', 'searchValue', 'searchStatus'));
    }

    public function winHistory(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $winHistory = DesawarRecord::with(['user', 'market', 'gameType'])
                ->where('status', 'success')
                ->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view('dashboard.desawar-markets.win-history', compact('winHistory', 'searchValue'));
        }

        $winHistory = DesawarRecord::with(['user', 'market', 'gameType'])->where('status', 'success')->latest()->paginate(25);
        return view('dashboard.desawar-markets.win-history', compact('winHistory'));
    }

    public function chartNoRecords(Request $request)
    {
        $desawarMarkets = \App\Models\DesawarMarket::get();
        $query = DesawarRecord::with('market')->where('number', $request->number)->latest();

        if ($request->has('market_id')) {
            $query->where('desawar_market_id', $request->market_id);
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        $desawarRecords = $query->paginate(25);
        return view('dashboard.desawar-markets.chart-no-records', compact('desawarRecords', 'desawarMarkets'));
    }

    public function cancelBet(Request $request)
    {
        $desawarRecord = DesawarRecord::find($request->id);
        $user = User::find($desawarRecord->user_id);
        $playerId = $user->one_signalsubscription_id;

        // Update status
        DesawarRecord::where('id', $desawarRecord->id)->update([
            'status' => 'canceled'
        ]);

        // Update User Wallet
        User::where('id', $user->id)->update([
            'balance' => $user->balance + $desawarRecord->amount,
        ]);

        // Add Transaction
        Transaction::create([
            'user_id' => $user->id,
            'previous_amount' => $user->balance,
            'amount' => $desawarRecord->amount,
            'current_amount' => $user->balance + $desawarRecord->amount,
            'type' => 'canceled',
            'details' => 'canceled your bet',
        ]);

        if ($desawarRecord) {
            if ($playerId) {
                $message = "Your bet has been canceled, and your funds have been refunded.";
                OneSignalHelper::singleUserNotification($message, $playerId);
            }
            return redirect()->back();
        }
    }
}
