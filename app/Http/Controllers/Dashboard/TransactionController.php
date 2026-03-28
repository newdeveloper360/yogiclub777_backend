<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $transactions = Transaction::with('user')
                ->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(450);
            return view('dashboard.transactions.index', compact('transactions', 'searchValue'));
        }
        $transactions = Transaction::with('user')->latest()->paginate(25);
        return view('dashboard.transactions.index', compact('transactions'));
    }
}
