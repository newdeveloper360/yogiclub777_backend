<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\StartLineMarket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StartLineMarketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $startLineMarkets = StartLineMarket::where('name', 'LIKE', '%' . $searchValue . '%')->latest()->paginate(250);
            return view('dashboard.start-line-markets.index', compact('startLineMarkets', 'searchValue'));
        }

        $startLineMarkets = StartLineMarket::all();
        return view("dashboard.start-line-markets.index", compact('startLineMarkets'));
    }
    public function create()
    {
        return view('dashboard.start-line-markets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'disable_game' => 'required|boolean',
            'open_time' => 'required',
        ]);
        StartLineMarket::create($request->all());
        return redirect()->route('start-line-markets.index')->with('success', 'Market Created successfully');
    }

    public function edit($id)
    {
        $market = StartLineMarket::findOrFail($id);
        return view('dashboard.start-line-markets.create', compact('market'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'open_time' => 'required',
        ]);
        $market = StartLineMarket::findOrFail($id);
        $market->name = $request->name;
        $market->open_time = $request->open_time;
        $market->disable_game = $request->disable_game;
        $market->update();
        return redirect()->route('start-line-markets.index')->with('success', 'Market Update successfully');
    }

    public function destroy($id)
    {
        $market = StartLineMarket::findOrFail($id);
        foreach ($market->records as $record) {
            $record->delete();
        }
        foreach ($market->results as $result) {
            $result->delete();
        }
        $market->delete();
        return redirect()->route('start-line-markets.index')->with('success', 'Market Deleted successfully');
    }
}
