<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\WithdrawDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WithdrawDetailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $withdrawDetails = WithdrawDetail::with('user')
                ->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view('dashboard.users.withdraw-details', compact('withdrawDetails', 'searchValue'));
        }

        $withdrawDetails = WithdrawDetail::with('user')->latest()->paginate(25);
        return view('dashboard.users.withdraw-details', compact('withdrawDetails'));
    }
}
