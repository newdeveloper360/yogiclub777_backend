<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\FuncCall;

class DesawarMarket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key_name',
        'previous_day_check',
        'open_time',
        'auto_result',
        'close_time',
        'result_time',
        'disable_game',
        'c_time_start',
        'c_time_end',
        'c_max_bet_amount',
        'c2_time_start',
        'c2_time_end',
        'c2_max_bet_amount',
        'c3_time_start',
        'c3_time_end',
        'c3_max_bet_amount',
        'is_bet_time_limit',
        'bet_time_limit',
        'choti_jodi_bet_amount_limit',
        'moti_jodi_bet_amount_limit',
    ];

    protected $appends = ['game_on', 'last_result', 'max_bet_amount', 'second_last_result', 'formatted_open_time', 'formatted_close_time', 'formatted_c_time_start', 'formatted_c_time_end', 'formatted_c2_time_start', 'formatted_c2_time_end', 'formatted_c3_time_start', 'formatted_c3_time_end', 'formatted_bet_time_limit'];








    // For testing purpose started
    public function getFormattedBetTimeLimitAttribute()
    {
        $now = Carbon::now();
        $open_time = Carbon::parse($this->open_time);
        $bet_time_limit = Carbon::parse($this->bet_time_limit);
        
        if ($bet_time_limit->lt($open_time)) {
            if ($now->gte(Carbon::parse('12:00 PM'))) {
                $bet_time_limit->addDay();
            }
        }
        
        // $bet_time_limit = Carbon::parse($this->bet_time_limit)->setDate($now->year, $now->month, $now->day);

        return $bet_time_limit->format('Y-m-d h:i A');
    }
    
    public function getFormattedCTimeStartAttribute()
    {
        // $now = Carbon::parse('2024-01-05 08:30:00');
        // $now = Carbon::parse('2024-01-05 14:06:00');
        // $now = Carbon::parse('2024-01-06 02:30:00');
        //$now = Carbon::parse('2024-01-06 04:02:00');
        $now = Carbon::now();

        $c_time_start = Carbon::parse($this->c_time_start)->setDate($now->year, $now->month, $now->day);
        $c_time_end = Carbon::parse($this->c_time_end)->setDate($now->year, $now->month, $now->day);

        if ($c_time_end->lt($c_time_start)) {
            $c_time_start->addDay();
        }

        return $c_time_start->format('Y-m-d h:i A');
    }

    public function getFormattedCTimeEndAttribute()
    {
        // $now = Carbon::parse('2024-01-05 08:30:00');
        // $now = Carbon::parse('2024-01-05 14:06:00');
        // $now = Carbon::parse('2024-01-06 02:30:00');
        //$now = Carbon::parse('2024-01-06 04:02:00');
        $now = Carbon::now();

        $c_time_start = Carbon::parse($this->c_time_start)->setDate($now->year, $now->month, $now->day);
        $c_time_end = Carbon::parse($this->c_time_end)->setDate($now->year, $now->month, $now->day);

        if ($c_time_end->lt($c_time_start)) {
            $c_time_end->addDay();
        }

        return $c_time_end->format('Y-m-d h:i A');
    }

    public function getFormattedC2TimeStartAttribute()
    {
        // $now = Carbon::parse('2024-01-05 08:30:00');
        // $now = Carbon::parse('2024-01-05 14:06:00');
        // $now = Carbon::parse('2024-01-06 02:30:00');
        //$now = Carbon::parse('2024-01-06 04:02:00');
        $now = Carbon::now();

        $c2_time_start = Carbon::parse($this->c2_time_start)->setDate($now->year, $now->month, $now->day);
        $c2_time_end = Carbon::parse($this->c2_time_end)->setDate($now->year, $now->month, $now->day);

        if ($c2_time_end->lt($c2_time_start)) {
            return $c2_time_start->format('Y-m-d h:i A');
        }

        return $c2_time_start->format('Y-m-d h:i A');
    }


    public function getFormattedC2TimeEndAttribute()
    {
        // $now = Carbon::parse('2024-01-05 08:30:00');
        // $now = Carbon::parse('2024-01-05 14:06:00');
        // $now = Carbon::parse('2024-01-06 02:30:00');
        //$now = Carbon::parse('2024-01-06 04:02:00');
        $now = Carbon::now();

        $c2_time_start = Carbon::parse($this->c2_time_start)->setDate($now->year, $now->month, $now->day);
        $c2_time_end = Carbon::parse($this->c2_time_end)->setDate($now->year, $now->month, $now->day);
        if ($c2_time_end->lt($c2_time_start)) {
            $c2_time_end->addDay();
        }

        return $c2_time_end->format('Y-m-d h:i A');
    }

    public function getFormattedC3TimeStartAttribute()
    {
        // $now = Carbon::parse('2024-01-05 08:30:00');
        // $now = Carbon::parse('2024-01-05 14:06:00');
        // $now = Carbon::parse('2024-01-06 02:30:00');
        //$now = Carbon::parse('2024-01-06 04:02:00');
        $now = Carbon::now();

        $c3_time_start = Carbon::parse($this->c3_time_start)->setDate($now->year, $now->month, $now->day);
        $c3_time_end = Carbon::parse($this->c3_time_end)->setDate($now->year, $now->month, $now->day);
        if ($c3_time_end->lt($c3_time_start)) {
            $c3_time_start->addDay();
        }
        return $c3_time_start->format('Y-m-d h:i A');
    }

    public function getFormattedC3TimeEndAttribute()
    {
        // $now = Carbon::parse('2024-01-05 08:30:00');
        // $now = Carbon::parse('2024-01-05 14:06:00');
        // $now = Carbon::parse('2024-01-06 02:30:00');
        //$now = Carbon::parse('2024-01-06 04:02:00');
        $now = Carbon::now();

        $c3_time_end = Carbon::parse($this->c3_time_end)->setDate($now->year, $now->month, $now->day);
        $c3_time_start = Carbon::parse($this->c3_time_start)->setDate($now->year, $now->month, $now->day);
        if ($c3_time_end->lt($c3_time_start)) {
            $c3_time_end->addDay();
        }
        return $c3_time_end->format('Y-m-d h:i A');
    }
    // For testing purpose ended













    public function getFormattedOpenTimeAttribute()
    {
        $now = Carbon::now();

        $open_time = Carbon::parse($this->open_time);
        $close_time = Carbon::parse($this->close_time);
        if ($close_time->lt($open_time)) {
            if ($now->lte(Carbon::parse('12:00 PM'))) {
                $open_time->subDay();
            }
        }

        return $open_time->format('Y-m-d h:i A');
    }

    public function getFormattedCloseTimeAttribute()
    {
        $now = Carbon::now();

        $open_time = Carbon::parse($this->open_time);
        $close_time = Carbon::parse($this->close_time);
        if ($close_time->lt($open_time)) {
            if ($now->gte(Carbon::parse('12:00 PM'))) {
                $close_time->addDay();
            }
        }

        return $close_time->format('Y-m-d h:i A');
    }

    //get current max bet amount based on current time
    public function getMaxBetAmountAttribute()
    {
        $now = Carbon::now();
        $c_time_start = Carbon::parse($this->c_time_start);
        $c_time_end = Carbon::parse($this->c_time_end);
        $c2_time_start = Carbon::parse($this->c2_time_start);
        $c2_time_end = Carbon::parse($this->c2_time_end);
        $c3_time_start = Carbon::parse($this->c3_time_start);
        $c3_time_end = Carbon::parse($this->c3_time_end);

        //if c time end is less than c time start, it means c time end is on next day
        if ($c_time_end->lt($c_time_start)) {
            if ($now->gte(Carbon::parse('12:00 PM'))) {
                $c_time_end->subDay();
            }
            $c_time_end->addDay();
        }
        if ($c2_time_end->lt($c2_time_start)) {
            $c2_time_end->addDay();
            if ($now->gte(Carbon::parse('12:00 PM'))) {
                $c2_time_end->subDay();
            }
        }
        if ($c3_time_end->lt($c3_time_start)) {
            $c3_time_end->addDay();
            if ($now->gte(Carbon::parse('12:00 PM'))) {
                $c3_time_end->subDay();
            }
        }

        if ($now->gte($c_time_start) && $now->lte($c_time_end)) {
            return $this->c_max_bet_amount;
        } elseif ($now->gte($c2_time_start) && $now->lte($c2_time_end)) {
            return $this->c2_max_bet_amount;
        } elseif ($now->gte($c3_time_start) && $now->lte($c3_time_end)) {
            return $this->c3_max_bet_amount;
        } else {
            return 6000;
        }
    }

    public function getLastResultAttribute()
    {
        $last_result = $this->results()->orderBy('result_date', 'desc')->first();
        if ($last_result !== NULL && $last_result->result_date == date('Y-m-d')) {
            return $last_result;
        }
        return NULL;
    }

    public function getSecondLastResultAttribute()
    {

        $last_result = $this->results()->orderBy('result_date', 'desc')->first();
        $second_last_result = $this->results()->orderBy('result_date', 'desc')->skip(1)->first();

        if ($last_result !== NULL && $last_result->result_date != date('Y-m-d')) {
            return $last_result;
        } elseif ($second_last_result !== NULL) {
            return $second_last_result;
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

    public function getCloseTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public function getResultTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public function records()
    {
        return  $this->hasMany(DesawarRecord::class);
    }

    public function results()
    {
        return $this->hasMany(DesawarResult::class);
    }

    public function getGameOnAttribute()
    {
        if ($this->disable_game) {
            return false;
        }
        if ($this->id == 8) {
            $now = Carbon::now();
            // $now = Carbon::parse('2024-01-05 08:30:00');
            // $now = Carbon::parse('2024-01-05 14:06:00');
            // $now = Carbon::parse('2024-01-06 02:30:00');
            // $now = Carbon::parse('2024-01-06 04:02:00'); //working game showing coff
        } else {
            $now = Carbon::now();
        }

        //so all times are based on $now for proper testing & comparison
        // $twelvePm = $now->copy()->setTime(12, 0, 0);
        $openTime = Carbon::parse($this->open_time)->setDate($now->year, $now->month, $now->day);
        $closeTime = Carbon::parse($this->close_time)->setDate($now->year, $now->month, $now->day);


        //if close time is less than open time, it means close time is on next day
        if ($closeTime->lt($openTime)) {
            if ($now->lt($closeTime)) {
                $openTime->subDay();
            } else {
                $closeTime->addDay();
            }
        }

        if (!$now->between($openTime, $closeTime)) {
            return false;
        }

        return true;
    }
}
