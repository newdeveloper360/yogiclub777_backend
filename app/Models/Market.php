<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Log;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key_name',
        'disable_game',
        'saturday_open',
        'sunday_open',
        'auto_result',
        'previous_day_check',
        'open_time',
        'close_time',
        'open_result_time',
        'close_result_time',
    ];

    protected $appends = [
        'game_on',
        'open_game_status',
        'close_game_status',
        'last_result'
    ];

    public function getLastResultAttribute()
    {
        $seven_am_systesm = false;


        if (!$seven_am_systesm) {
            $last_result = $this->results()->orderBy('result_date', 'desc')->first();
            // !$this->previous_day_check removed
            if ($last_result !== NULL && !$this->previous_day_check && $last_result->result_date == date('Y-m-d')) {
                return $last_result;
            } elseif ($last_result !== NULL && $this->previous_day_check && $last_result->result_date == date('Y-m-d', strtotime('-1 day'))) {
                return $last_result;
            } else {
                $last_result = NULL;
            }
        } else {
            $seven_am = Carbon::parse('07:00:00');
            $results = $this->results()->orderBy('result_date', 'desc')->latest();
            if ($results->count() > 1 && $results->first() !== NULL && $results->first()->result_date == date('Y-m-d', strtotime('-1 day'))) {
                if (Carbon::now()->lte($seven_am))
                    $last_result = $results->first();
                else $last_result = NULL;
            } elseif ($results->count() > 1 && $results->first() !== NULL && $results->first()->result_date == date('Y-m-d')) {
                $last_result = $results->first();
                if (Carbon::now()->lte($seven_am))
                    $last_result =  $results->skip(1)->first();
                else $last_result = $results->first();
            } else {
                $last_result = NULL;
            }
        }
        return $last_result;
    }

    protected function getGameOnAttribute()
    {
        $now = Carbon::now();
        $saturdayOn = $this->saturday_open;
        $sundayOn = $this->sunday_open;
        $openTime = Carbon::parse($this->open_time);
        $closeTime = Carbon::parse($this->close_time);

        //if close time is less than open time, it means close time is on next day
        if ($closeTime->lt($openTime)) {
            $openTime->subDay();
            $closeTime->addDay();
        }

        if ($this->disable_game) {
            return false;
        }

        if ($now->isSaturday() && !$saturdayOn) {
            return false;
        }

        if ($now->isSunday() && !$sundayOn) {
            return false;
        }

        if (!$now->lte($closeTime)) {
            return false;
        }

        return true;
    }
    protected function getOpenGameStatusAttribute()
    {
        $now = Carbon::now();
        $openTime = Carbon::parse($this->open_time);
        if (!$now->lte($openTime)) {
            return false;
        }
        return true;
    }
    protected function getCloseGameStatusAttribute()
    {
        $now = Carbon::now();
        $closeTime = Carbon::parse($this->close_time);
        if (!$now->lte($closeTime)) {
            return false;
        }
        return true;
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i A');
    }

    public function getOpenTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public function getCloseTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public function getOpenResultTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public function getCloseResultTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }


    public function records()
    {
        return $this->hasMany(MarketRecord::class);
    }

    public function results()
    {
        return $this->hasMany(MarketResult::class);
    }
}
