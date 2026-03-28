<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Market;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $markets = Market::where('name', 'LIKE', '%' . $searchValue . '%')
                ->latest()->paginate(250);
            return view('dashboard.markets.index', compact('markets', 'searchValue'));
        }
        $markets = Market::latest()->paginate(25);
        return view('dashboard.markets.index', compact('markets'));
    }

    public function create()
    {
        return view('dashboard.markets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'api_key_name' => 'required|max:255',
            'disable_game' => 'required|boolean',
            'saturday_open' => 'required|boolean',
            'sunday_open' => 'required|boolean',
            'auto_result' => 'required|boolean',
            'previous_day_check' => 'required|boolean',
            'open_time' => 'required',
            'close_time' => 'required',
            'open_result_time' => 'required',
            'close_result_time' => 'required',
        ]);
        Market::create($request->all());
        return redirect()->route('markets.index')->with('success', 'Market Created successfully');
    }

    public function edit($id)
    {
        $market = Market::findOrFail($id);
        return view('dashboard.markets.create', compact('market'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'api_key_name' => 'required|max:255',
            'disable_game' => 'required|boolean',
            'saturday_open' => 'required|boolean',
            'sunday_open' => 'required|boolean',
            'auto_result' => 'required|boolean',
            'open_time' => 'required',
            'close_time' => 'required',
            'open_result_time' => 'required',
            'close_result_time' => 'required',
        ]);
        $market = Market::findOrFail($id);
        $market->name = $request->name;
        $market->api_key_name = $request->api_key_name;
        $market->disable_game = $request->disable_game;
        $market->saturday_open = $request->saturday_open;
        $market->sunday_open = $request->sunday_open;
        $market->previous_day_check = $request->previous_day_check;
        $market->auto_result = $request->auto_result;
        $market->open_time = $request->open_time;
        $market->close_time = $request->close_time;
        $market->open_result_time = $request->open_result_time;
        $market->close_result_time = $request->close_result_time;
        $market->update();
        return redirect()->route('markets.index')->with('success', 'Market Update successfully');
    }

    public function destroy($id)
    {
        $market = Market::findOrFail($id);
        foreach ($market->records as $record) {
            $record->delete();
        }
        foreach ($market->results as $result) {
            $result->delete();
        }
        $market->delete();
        return redirect()->route('markets.index')->with('success', 'Market Deleted successfully');
    }
}
