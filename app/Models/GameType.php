<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'game_type',
        'multiply_by',
    ];

    public function marketRecords()
    {
        return $this->hasMany(MarketRecord::class);
    }

    public function startLineRecords()
    {
        return $this->hasMany(StartLineRecord::class);
    }

    public function desawarRecords()
    {
        return $this->hasMany(DesawarRecord::class);
    }
}
