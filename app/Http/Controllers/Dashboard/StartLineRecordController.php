<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\StartLineRecord;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StartLineRecordController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $startLineRecords = StartLineRecord::with(['market', 'user'])
                ->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view('dashboard.start-line-markets.records', compact('startLineRecords', 'searchValue'));
        }

        $startLineRecords = StartLineRecord::with('market')->latest()->paginate(25);
        return view('dashboard.start-line-markets.records', compact('startLineRecords'));
    }

    public function winHistory(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $winHistory = StartLineRecord::with(['user', 'market', 'gameType'])
                ->where('status', 'success')
                ->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view('dashboard.start-line-markets.win-history', compact('winHistory', 'searchValue'));
        }

        $winHistory = StartLineRecord::with(['user', 'market', 'gameType'])->where('status', 'success')->latest()->paginate(25);
        return view('dashboard.start-line-markets.win-history', compact('winHistory'));
    }
}
