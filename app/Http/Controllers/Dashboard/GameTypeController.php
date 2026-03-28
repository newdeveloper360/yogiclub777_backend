<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\GameType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameTypeController extends Controller
{
    public function index()
    {
        $gameTypes = GameType::all();
        return view('dashboard.game-types.index', compact('gameTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'multiply_by' => 'required|numeric'
        ]);

        $gameType = GameType::findOrFail($id);
        $gameType->multiply_by = $request->multiply_by;
        $gameType->save();
        return back()->with('success', 'Updated Successfully');
    }
}
