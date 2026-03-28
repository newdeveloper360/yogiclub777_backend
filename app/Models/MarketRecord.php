<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'amount',
        'date',
        'game_string',
        'status',
        'session',
        'win_amount',
        'market_id',
        'user_id',
        'game_type_id',
        'transaction_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    // public function transaction()
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }


    public function gameType()
    {
        return $this->belongsTo(GameType::class);
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i A');
    }
    public function getStatusAttribute($value)
    {
        return strtoupper($value);
    }
}
