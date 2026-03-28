<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_id',
        'open_pana',
        'open_digit',
        'close_digit',
        'close_pana',
        'result_date'
    ];

    protected $appends = ['result'];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function getResultAttribute()
    {
        $close_digit = $this->close_digit === null ? "*" : $this->close_digit;
        $open_pana = $this->open_pana === null ? "***" : $this->open_pana;
        $open_digit = $this->open_digit === null ? "*" : $this->open_digit;
        $close_pana = $this->close_pana === null ? "***" : $this->close_pana;
        return $open_pana . "-" . $open_digit . $close_digit . "-" . $close_pana;
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i A');
    }
}
