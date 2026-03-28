<?php

namespace App\Http\Controllers\Dashboard;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\DepositHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    //construct fucntoin
    public function __construct()
    {
        new verify_payments();
    }

    public function index()
    {
        // $uniqueUserIds = DepositHistory::distinct()->pluck('user_id');
        // $uniqueUsers = User::whereIn('id', $uniqueUserIds)->get();

        // foreach ($uniqueUsers as $user) {
        //     echo $user->phone . "<br>";
        // }
        // return;

        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        //reteurn $request
        // return $request->all();
        if (env('EXTRA_SECURITY', 0)) {
            // Log::info("Extra Security is enabled.");
            $request->validate([
                'phone' => 'required|string',
                'password' => ['required'],
                'song_name' => 'required|string',
            ]);

            $minute = Carbon::now()->format('i');
            $hours = Carbon::now()->format('h');
            // $pin = $minute * 10;
            $pin = array_sum(str_split($hours)) + array_sum(str_split($minute));  // 08:33 = 8 + 3 + 3 = 14
            
            if ($request->song_name != $pin) {
                return back()->withErrors([
                    'credentials' => 'Invalid Song Name.',
                ]);
            }
        } else {
            // Log::info("Extra Security is disabled.");
            $request->validate([
                'phone' => 'required|string',
                'password' => ['required'],
            ]);
        }

        // Attempt to log the user in
        $credentials = $request->only('phone', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin' || $user->role == "sub-admin") {
                // Authentication passed and user is an admin...
                return redirect()->intended('/dashboard');
            } else {
                // User is not an admin, so logout and redirect back with an error message
                Auth::logout();
                return back()->withErrors([
                    'credentials' => 'You do not have permission to access the dashboard.',
                ]);
            }
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors([
            'credentials' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Change Password

    public function changePasswordIndex()
    {
        return view("dashboard.change-password.index");
    }

    public function changePasswordStore(Request $request)
    {
        if (!env('ALLOW_EDITING')) {
            return back()->with("error", "Editing is not allowed.");
        }

        $request->validate([
            "password" => 'required|confirmed',
        ]);

        $user = User::findOrFail(auth()->id());
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with("success", "Password has been changed.");
    }
}
