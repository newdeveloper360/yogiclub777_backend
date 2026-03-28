<?php

namespace App\Jobs;

use App\Models\DepositHistory;
use App\Models\DesawarRecord;
use App\Models\MarketRecord;
use App\Models\Notification;
use App\Models\Otp;
use App\Models\StartLineRecord;
use App\Models\Transaction;
use App\Models\UpiTransaction;
use App\Models\WithdrawHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteExpiredOtps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        Log::info('deleting old data');
        Otp::whereDate('created_at', '<=', now()->subMinutes(30))->delete();
        //delete transaction older than 30 days, deposit history older than 30 days, desawar record older than 30 days, notification older than 30 days, upi transaction older than 30 days, withdraw history older than 30 days

        Transaction::whereDate('created_at', '<=', now()->subDays(4))->delete();
        DepositHistory::whereDate('created_at', '<=', now()->subDays(4))->delete();
        DesawarRecord::whereDate('created_at', '<=', now()->subDays(4))->delete();
        StartLineRecord::whereDate('created_at', '<=', now()->subDays(4))->delete();
        MarketRecord::whereDate('created_at', '<=', now()->subDays(4))->delete();
        Notification::whereDate('created_at', '<=', now()->subDays(4))->delete();
        UpiTransaction::whereDate('created_at', '<=', now()->subDays(4))->delete();
        WithdrawHistory::whereDate('created_at', '<=', now()->subDays(4))->delete();
        echo "doing";
    }
}
