<?php

namespace App\Providers;

use App\Models\AppData;
use App\Models\Chat;
use App\Models\DepositHistory;
use App\Models\DesawarMarket;
use App\Models\WithdrawHistory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // URL::forceScheme('https');

        Gate::before(function ($user) {
            return $user->role == "admin" ? true : null;
        });

        Response::macro('success', function ($message, $data) {
            return response([
                'message' => $message,
                'error' => false,
                'response' => $data
            ], 200);
        });
        Response::macro('failed', function ($error) {
            return response([
                'error' => true,
                'message' => $error,
                'response' => NULL
            ], 200);
        });

        View::composer('layouts.navbar', function ($view) {
            $pendingWithdrawsCount = WithdrawHistory::where('status', 'pending')->count();
            $view->with('pendingWithdrawsCount', $pendingWithdrawsCount);
        });

        View::composer('layouts.navbar', function ($view) {
            $pendingDepositsCount = DepositHistory::where('status', 'pending')->count();
            $view->with('pendingDepositsCount', $pendingDepositsCount);
        });

        View::composer('layouts.navbar', function ($view) {
            $enableDesawar = AppData::pluck('enable_desawar')->first();
            $view->with('enableDesawar', $enableDesawar);
        });

        View::composer('layouts.navbar', function ($view) {
            $enableDesawarOnly = AppData::pluck('enable_desawar_only')->first();
            $view->with('enableDesawarOnly', $enableDesawarOnly);
        });

        View::composer('layouts.navbar', function ($view) {
            $countUnreadChats = Chat::whereHas('messages', function ($query) {
                $query->whereNot('user_id', auth()->id())
                    ->where('is_read', false);
            })
                ->whereNot('user_id', auth()->id())
                ->count();

            $view->with('countUnreadChats', $countUnreadChats);
        });
    }
}
