<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Models\DesawarMarketLimit;
use Illuminate\Http\Request;

class DesawarMarketLimitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $desawarMarketLimit = DesawarMarketLimit::find(1);
        if($desawarMarketLimit){
            DesawarMarketLimit::where('id', $desawarMarketLimit->id)->update([
                'jodiAmount' => $request->jodiAmount,
                'andarAmount' => $request->andarAmount,
                'baharAmount' => $request->baharAmount,
            ]);
        }else{
            DesawarMarketLimit::create([
                'jodiAmount' => $request->jodiAmount,
                'andarAmount' => $request->andarAmount,
                'baharAmount' => $request->baharAmount,
            ]);
        }

        return redirect()->back()->with(['success' => 'Limit amount update successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(DesawarMarketLimit $desawarMarketLimit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DesawarMarketLimit $desawarMarketLimit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DesawarMarketLimit $desawarMarketLimit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DesawarMarketLimit $desawarMarketLimit)
    {
        //
    }
}
