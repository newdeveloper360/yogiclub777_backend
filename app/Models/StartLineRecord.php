<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartLineRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'amount',
        'game_string',
        'status',
        'date',
        'win_amount',
        'startline_market_id',
        'user_id',
        'game_type_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function market()
    {
        return $this->belongsTo(StartLineMarket::class, 'startline_market_id');
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
