<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create([
            "name" => "app-data",
        ]);
        Permission::create([
            "name" => "users",
        ]);
        Permission::create([
            "name" => "users.toogle-blocked.change",
        ]);
        Permission::create([
            "name" => "sub-admins.toogle-blocked.change",
        ]);
        Permission::create([
            "name" => "add-deduct-balance-users",
        ]);
        Permission::create([
            "name" => "markets",
        ]);
        Permission::create([
            "name" => "create-markets",
        ]);
        Permission::create([
            "name" => "edit-markets",
        ]);
        Permission::create([
            "name" => "delete-markets",
        ]);
        Permission::create([
            "name" => "startLine",
        ]);
        Permission::create([
            "name" => "create-startLine",
        ]);
        Permission::create([
            "name" => "edit-startLine",
        ]);
        Permission::create([
            "name" => "delete-startLine",
        ]);
        Permission::create([
            "name" => "desawar",
        ]);
        Permission::create([
            "name" => "create-desawar",
        ]);
        Permission::create([
            "name" => "edit-desawar",
        ]);
        Permission::create([
            "name" => "delete-desawar",
        ]);
        Permission::create([
            "name" => "game-types",
        ]);
        Permission::create([
            "name" => "update-game-types",
        ]);
        Permission::create([
            "name" => "transactions",
        ]);
        Permission::create([
            "name" => "notifications",
        ]);
        Permission::create([
            "name" => "create-notifications",
        ]);
        Permission::create([
            "name" => "deposit-history",
        ]);
        Permission::create([
            "name" => "withdraw-history",
        ]);
        Permission::create([
            "name" => "withdraw-request-accept",
        ]);
        Permission::create([
            "name" => "withdraw-request-reject",
        ]);

        Permission::create([
            "name" => "dashboard-view",
        ]);
        Permission::create([
            "name" => "clear-dashboard-data",
        ]);
        Permission::create([
            "name" => "today-dashboard-record",
        ]);
        Permission::create([
            "name" => "total-wallet-balance",
        ]);
        Permission::create([
            "name" => "dashboard-total-users",
        ]);
        Permission::create([
            "name" => "dashboard-total-markets",
        ]);
        Permission::create([
            "name" => "dashboard-total-deposits",
        ]);
        Permission::create([
            "name" => "dashboard-total-withdraws",
        ]);
        Permission::create([
            "name" => "slider-images",
        ]);
        Permission::create([
            "name" => "chats-view",
        ]);
        Permission::create([
            "name" => "chats-send-message",
        ]);
        Permission::create([
            "name" => "profit-loss",
        ]);
        Permission::create([
            "name" => "change-password",
        ]);
    }
}
