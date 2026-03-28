<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'balance',
        'withdrawal_balance',
        'notification_active',
        'fcm',
        'blocked',
        'role',
        'password',
        'own_code',
        'one_signalsubscription_id',
    ];

    protected $hidden = [
        'password'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('m/d/Y h:i A');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isSubAdmin()
    {
        return $this->role === 'sub-dmin';
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

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

    public function withdrawHistory()
    {
        return $this->hasMany(WithdrawHistory::class);
    }

    public function withdrawDetails()
    {
        return $this->hasOne(WithdrawDetail::class);
    }

    public function depositHistory()
    {
        return $this->hasMany(DepositHistory::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function referralUsers()
    {
        return $this->hasMany(User::class, 'user_id')->latest();
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function upiTransactions()
    {
        return $this->hasMany(UpiTransaction::class);
    }

    public function groupPostings()
    {
        return $this->hasMany(GroupPosting::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
