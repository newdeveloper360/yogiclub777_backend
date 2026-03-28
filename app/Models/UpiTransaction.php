<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpiTransaction extends Model
{
    use HasFactory;

    //belogns to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
