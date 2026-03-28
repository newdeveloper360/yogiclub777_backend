<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'message'
    ];

    protected $appends = ['is_mine'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsMineAttribute()
    {
        return $this->user_id === auth()->id();
    }
}
