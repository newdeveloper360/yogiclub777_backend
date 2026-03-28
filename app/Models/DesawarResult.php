<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DesawarResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_date',
        'result',
        'first_digit_of_result',
        'second_digit_of_result'
    ];


    public function market()
    {
        return $this->belongsTo(DesawarMarket::class, "desawar_market_id");
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i A');
    }
}
