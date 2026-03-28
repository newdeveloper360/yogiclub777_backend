<?php

namespace Database\Seeders;

use App\Models\AppData;
use App\Models\DepositHistory;
use App\Models\DesawarMarket;
use App\Models\DesawarRecord;
use App\Models\DesawarResult;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawDetail;
use App\Models\WithdrawHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin
        User::create([
            'name' => 'Test User',
            'phone' => '1234567890',
            'role' => 'user',
            'confirmed' => 1,
        ]);
        // user
        User::create([
            'id' => '2',
            'name' => 'Admin',
            'phone' => '1234567899',
            'password' => Hash::make('1234'),
            'role' => 'admin',
            'confirmed' => 1,
        ]);
        // sub-admin
        // User::create([
        //     'id' => '3',
        //     'name' => 'Alexa',
        //     'phone' => '1234567999',
        //     'password' => Hash::make('1234'),
        //     'role' => 'sub-admin',
        //     'confirmed' => 1,
        // ]);
    }
}
