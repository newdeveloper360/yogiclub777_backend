<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'request_type',
        'withdraw_mode',
        'withdrawal_method',
        'status',
        'transaction_id',
        'current_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i A');
    }
}
