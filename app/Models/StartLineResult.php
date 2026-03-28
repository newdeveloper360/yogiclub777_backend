<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartLineResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'startline_market_id',
        'open_pana',
        'open_digit',
        'result_date',
        'date'
    ];

    protected $appends = ["result"];

    public function market()
    {
        return $this->belongsTo(StartLineMarket::class, "startline_market_id");
    }

    public function getResultAttribute()
    {
        return $this->open_pana . '-' . $this->open_digit;
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i A');
    }
}
