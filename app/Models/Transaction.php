<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'previous_amount',
        'amount',
        'current_amount',
        'type',
        'details'
    ];

    //modify type if type="recharge" then "Deposit"
    public function getTypeAttribute($value)
    {
        if ($value == "recharge") {
            return "Deposit";
        }
        return $value;
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i A');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('m/d/Y h:i A');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
