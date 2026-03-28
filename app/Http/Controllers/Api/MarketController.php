<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DesawarMarket;
use App\Models\DesawarResult;
use App\Models\Market;
use App\Models\MarketResult;
use App\Models\StartLineMarket;
use App\Models\StartLineResult;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['general', 'startLine', 'desawar'])],
        ]);

        $market = match ($request->type) {
            "general" => Market::class,
            "startLine" => StartLineMarket::class,
            "desawar" => DesawarMarket::class,
        };

        $marketResult = match ($request->type) {
            "general" => MarketResult::class,
            "startLine" => StartLineResult::class,
            "desawar" => DesawarResult::class,
        };

        $property_name = match ($request->type) {
            "general" => "markets",
            "startLine" => "markets",
            "desawar" => "markets",
        };

        $orderByField = match ($request->type) {
            "general" => 'open_time',
            "startLine" => 'open_time',
            "desawar" => 'result_time',
        };

        $current_result_card = $marketResult::with('market')->orderBy('id', 'desc')->first();

        // $markets = $market::orderBy($orderByField, 'asc')->get();
        $markets = $market::orderByRaw("CASE WHEN name = 'DISAWAR' THEN 1 ELSE 0 END")
                    ->orderBy('close_time', 'asc')->get();

        // Keep the market with id = 8 below id = 7
        // $market_8 = $markets->where('id', 8)->first();
        // $markets = $markets->where('id', '!=', 8);
        // $markets = $markets->values();
        // $markets->push($market_8);

        return response()->success("Data Sent!", [
            $property_name => $markets,
            'current_result_card' => $current_result_card,
        ]);
    }
}
