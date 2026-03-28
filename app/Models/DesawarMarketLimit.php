<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesawarMarketLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'jodiAmount',
        'andarAmount',
        'baharAmount',
    ];
}
