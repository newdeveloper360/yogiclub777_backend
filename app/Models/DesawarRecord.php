<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesawarRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'amount',
        'game_string',
        'win_amount',
        'date',
        'status',
        'desawar_market_id',
        'user_id',
        'game_type_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function market()
    {
        return $this->belongsTo(DesawarMarket::class, 'desawar_market_id');
    }

    public function result()
    {
        return $this->belongsTo(DesawarResult::class);
    }

    public function gameType()
    {
        return $this->belongsTo(GameType::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i:s A');
    }

    public function getStatusAttribute($value)
    {
        return strtoupper($value);
    }
}
