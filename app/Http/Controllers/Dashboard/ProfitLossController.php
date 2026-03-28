<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DesawarMarket;
use App\Models\DesawarRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{
    // public function index() {
    //     $date = request()->query('date') ?? date("Y-m-d");
        
    //     $desawarRecords = DesawarRecord::with(['market'])->select(
    //             'desawar_market_id',
    //             DB::raw('SUM(amount) as total_amount'),
    //             DB::raw('SUM(win_amount) as total_win_amount')
    //     )
    //     ->whereDate('created_at', $date)
    //     ->groupBy('desawar_market_id')
    //     ->get();

    //     return view('dashboard.profit-loss.index', compact('desawarRecords', 'date'));
    // }

    public function index()
    {
        $date = request()->query('date') ?? date("Y-m-d");

        // Get DISAWAR market
        $disawarMarket = DesawarMarket::where('name', 'DISAWAR')->first();

        $desawarRecords = DesawarRecord::with('market')
            ->select(
                'desawar_market_id',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('SUM(win_amount) as total_win_amount')
            )
            ->where(function ($query) use ($date, $disawarMarket) {

                if ($disawarMarket) {

                    $openTime = Carbon::parse($date . ' ' . $disawarMarket->open_time);
                    $closeTime = Carbon::parse($date . ' ' . $disawarMarket->close_time);

                    // Agar market next day close hota ho
                    if ($closeTime->lessThan($openTime)) {
                        $closeTime->addDay();
                    }

                    $query->whereBetween('created_at', [
                        $openTime,
                        $closeTime
                    ]);

                } else {
                    $query->whereDate('created_at', $date);
                }
            })
            ->groupBy('desawar_market_id')
            ->get();

        return view('dashboard.profit-loss.index', compact('desawarRecords', 'date'));
    }

}
