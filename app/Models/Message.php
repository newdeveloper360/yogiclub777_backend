<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'chat_id', 'user_id', 'message', 'type', 'is_read', 'type', 'file_url', 'file_type'
    ];


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('msg-media')
            ->singleFile();
    }

    protected $appends = ['is_mine'];

    public function scopeUnreadMessages($query)
    {
        $query->where('is_read', false);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function getIsMineAttribute()
    {
        return $this->user_id === auth()->id();
    }
}
