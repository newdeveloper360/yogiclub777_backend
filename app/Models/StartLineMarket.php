<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartLineMarket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'open_time',
        'disable_game'
    ];

    protected $appends = ["game_on", 'last_result'];

    public function getLastResultAttribute()
    {
        $last_result = $this->results()->orderBy('result_date', 'desc')->first();
        if ($last_result !== NULL && $last_result->result_date == date('Y-m-d')) {
            return $last_result;
        }
        return NULL;
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i A');
    }

    public function getOpenTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }


    public function getOpenResultTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }


    public function records()
    {
        return $this->hasMany(StartLineRecord::class, "startline_market_id");
    }

    public function results()
    {
        return $this->hasMany(StartLineResult::class, "startline_market_id");
    }

    public function getGameOnAttribute()
    {
        $now = Carbon::now();
        $openTime = Carbon::parse($this->open_time);

        if (!$now->lte($openTime)) {
            return false;
        }

        if ($this->disable_game) {
            return false;
        }

        return true;
    }
}
