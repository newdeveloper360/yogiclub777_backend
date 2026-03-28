<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\DesawarMarket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DesawarRecord;
use App\Services\DesawarRecordChartService;
use Illuminate\Support\Facades\DB;

class DesawarMarketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $desawarMarkets = DesawarMarket::where('name', 'LIKE', '%' . $searchValue . '%')
                ->latest()->paginate(250);
            return view("dashboard.desawar-markets.index", compact('desawarMarkets', 'searchValue'));
        }
        $desawarMarkets = DesawarMarket::latest()->paginate(25);
        return view("dashboard.desawar-markets.index", compact('desawarMarkets'));
    }
    public function create()
    {
        return view('dashboard.desawar-markets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'api_key_name' => 'required|max:255',
            'disable_game' => 'required|boolean',
            'previous_day_check' => 'required|boolean',
            'open_time' => 'required',
            'close_time' => 'required',
            'result_time' => 'required',

            'is_bet_time_limit' => 'required|boolean',
            'bet_time_limit' => 'required_if:is_bet_time_limit,1|before:result_time',
            'choti_jodi_bet_amount_limit' => 'required_if:is_bet_time_limit,1|numeric|lt:moti_jodi_bet_amount_limit',
            'moti_jodi_bet_amount_limit' => 'required_if:is_bet_time_limit,1|numeric',

            //criteria 1 (their timings & max bet amount)
            'c_time_start' => 'required',
            'c_time_end' => 'required|after:c_time_start',
            'c_max_bet_amount' => 'required|numeric',

            //criteria 2 (their timings & max bet amount)
            'c2_time_start' => 'required',
            'c2_time_end' => 'required|after:c2_time_start',
            'c2_max_bet_amount' => 'required|numeric',

            //criteria 3 (their timings & max bet amount)
            'c3_time_start' => 'required',
            'c3_time_end' => 'required|after:c3_time_start',
            'c3_max_bet_amount' => 'required|numeric',
        ]);
        DesawarMarket::create($request->all());
        return redirect()->route('desawar-markets.index')->with('success', 'Market Created successfully');
    }

    public function edit($id)
    {
        $market = DesawarMarket::findOrFail($id);
        return view('dashboard.desawar-markets.create', compact('market'));
    }

    public function chart()
    {
        $chartData = DesawarRecordChartService::getChartData();
        $desawarMarketLimit = \App\Models\DesawarMarketLimit::find(1);

        return view('dashboard.desawar-markets.chart', [...$chartData, 'desawarMarketLimit' => $desawarMarketLimit]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'api_key_name' => 'required|max:255',
            'previous_day_check' => 'required|boolean',
            'open_time' => 'required',
            'close_time' => 'required',
            'result_time' => 'required',

            'is_bet_time_limit' => 'required|boolean',
            'bet_time_limit' => 'required_if:is_bet_time_limit,1|before:result_time',
            'choti_jodi_bet_amount_limit' => 'required_if:is_bet_time_limit,1|numeric|lt:moti_jodi_bet_amount_limit',
            'moti_jodi_bet_amount_limit' => 'required_if:is_bet_time_limit,1|numeric',

            //criteria 1 (their timings & max bet amount)
            'c_time_start' => 'required',
            'c_time_end' => 'required',
            'c_max_bet_amount' => 'required|numeric',

            //criteria 2 (their timings & max bet amount)
            'c2_time_start' => 'required',
            'c2_time_end' => 'required',
            'c2_max_bet_amount' => 'required|numeric',

            //criteria 3 (their timings & max bet amount)
            'c3_time_start' => 'required',
            'c3_time_end' => 'required',
            'c3_max_bet_amount' => 'required|numeric',

        ]);
        $market = DesawarMarket::findOrFail($id);
        $market->name = $request->name;
        $market->disable_game = $request->disable_game;
        $market->api_key_name = $request->api_key_name;
        $market->previous_day_check = $request->previous_day_check;
        $market->open_time = $request->open_time;
        $market->close_time = $request->close_time;
        $market->result_time = $request->result_time;
        $market->auto_result = $request->auto_result;

        $market->is_bet_time_limit = $request->is_bet_time_limit;
        $market->bet_time_limit = $request->bet_time_limit;
        $market->choti_jodi_bet_amount_limit = $request->choti_jodi_bet_amount_limit;
        $market->moti_jodi_bet_amount_limit = $request->moti_jodi_bet_amount_limit;

        //criteria 1 (their timings & max bet amount)
        $market->c_time_start = $request->c_time_start;
        $market->c_time_end = $request->c_time_end;
        $market->c_max_bet_amount = $request->c_max_bet_amount;

        //criteria 2 (their timings & max bet amount)
        $market->c2_time_start = $request->c2_time_start;
        $market->c2_time_end = $request->c2_time_end;
        $market->c2_max_bet_amount = $request->c2_max_bet_amount;

        //criteria 3 (their timings & max bet amount)
        $market->c3_time_start = $request->c3_time_start;
        $market->c3_time_end = $request->c3_time_end;
        $market->c3_max_bet_amount = $request->c3_max_bet_amount;


        $market->update();
        return redirect()->route('desawar-markets.index')->with('success', 'Market Update successfully');
    }

    public function destroy($id)
    {
        $market = DesawarMarket::findOrFail($id);
        foreach ($market->records as $record) {
            $record->delete();
        }
        foreach ($market->results as $result) {
            $result->delete();
        }
        $market->delete();
        return redirect()->route('desawar-markets.index')->with('success', 'Market Deleted successfully');
    }

    public function downloadExcel(Request $request)
    {
        $chartData = DesawarRecordChartService::getChartData();
        $jodiData = $chartData['jodiData'];
        $andarCounts = $chartData['andarCounts'];
        $baharCounts = $chartData['baharCounts'];
        
        $date = $request->has('date') ? $request->query('date') : date('Y-m-d');
        $marketId = $request->query('market_id');
        
        // Get market name
        $marketName = 'All Markets';
        if ($marketId) {
            $market = DesawarMarket::find($marketId);
            $marketName = $market ? $market->name : 'All Markets';
        }
        
        $filename = 'desawar_data_' . $date . '_' . str_replace(' ', '_', $marketName) . '.xls';
        
        // Calculate totals
        $jodiTotal = 0;
        foreach ($jodiData as $key => $item) {
            if ($key == 0) continue;
            $jodiTotal += $item['total_amount'] ?? 0;
        }
        
        $andarTotal = array_sum($andarCounts);
        $baharTotal = array_sum($baharCounts);
        $grandTotal = $jodiTotal + $andarTotal + $baharTotal;
        
        // Create HTML table that Excel can open
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        
        // Header information
        $html .= '<tr><td colspan="2" style="font-weight:bold;text-align:center;background-color:#E6E6FA;">Desawar Market Data Export</td></tr>';
        $html .= '<tr><td><strong>Date:</strong></td><td>' . $date . '</td></tr>';
        $html .= '<tr><td><strong>Market:</strong></td><td>' . $marketName . '</td></tr>';
        $html .= '<tr><td><strong>Generated At:</strong></td><td>' . now()->format('Y-m-d H:i:s') . '</td></tr>';
        $html .= '<tr><td colspan="2"></td></tr>';
        
        // Jodi Data Section
        $html .= '<tr><td colspan="2" style="font-weight:bold;background-color:#E6E6FA;">JODI DATA</td></tr>';
        $html .= '<tr><td style="font-weight:bold;background-color:#D3D3D3;">Number</td><td style="font-weight:bold;background-color:#D3D3D3;">Amount</td></tr>';
        
        foreach ($jodiData as $key => $item) {
            if ($key == 0) continue;
            $amount = $item['total_amount'] ?? 0;
            $html .= '<tr><td>' . $key . '</td><td>' . $amount . '</td></tr>';
        }
        $html .= '<tr><td style="font-weight:bold;background-color:#90EE90;">TOTAL</td><td style="font-weight:bold;background-color:#90EE90;">' . $jodiTotal . '</td></tr>';
        $html .= '<tr><td colspan="2"></td></tr>';
        
        // Andar Data Section
        $html .= '<tr><td colspan="2" style="font-weight:bold;background-color:#E6E6FA;">ANDAR DATA</td></tr>';
        $html .= '<tr><td style="font-weight:bold;background-color:#D3D3D3;">Number</td><td style="font-weight:bold;background-color:#D3D3D3;">Amount</td></tr>';
        
        foreach ($andarCounts as $key => $value) {
            $html .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
        }
        $html .= '<tr><td style="font-weight:bold;background-color:#90EE90;">TOTAL</td><td style="font-weight:bold;background-color:#90EE90;">' . $andarTotal . '</td></tr>';
        $html .= '<tr><td colspan="2"></td></tr>';
        
        // Bahar Data Section
        $html .= '<tr><td colspan="2" style="font-weight:bold;background-color:#E6E6FA;">BAHAR DATA</td></tr>';
        $html .= '<tr><td style="font-weight:bold;background-color:#D3D3D3;">Number</td><td style="font-weight:bold;background-color:#D3D3D3;">Amount</td></tr>';
        
        foreach ($baharCounts as $key => $value) {
            $html .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
        }
        $html .= '<tr><td style="font-weight:bold;background-color:#90EE90;">TOTAL</td><td style="font-weight:bold;background-color:#90EE90;">' . $baharTotal . '</td></tr>';
        $html .= '<tr><td colspan="2"></td></tr>';
        
        // Grand Total
        $html .= '<tr><td style="font-weight:bold;background-color:#FFB6C1;font-size:14px;">GRAND TOTAL</td><td style="font-weight:bold;background-color:#FFB6C1;font-size:14px;">' . $grandTotal . '</td></tr>';
        
        $html .= '</table></body></html>';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];
        
        return response($html, 200, $headers);
    }
}
