<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('withdraw_histories', function (Blueprint $table) {
            $table->enum('withdrawal_method', ['manual', 'ibr_pay', 'upi_money', 'i_online_pay', 'cub_pay', 'planet_c', 'sonic_pe', 'run_paisa', 'click_pay', 'vagon_pay', 'rudrax_pay', 'payinfintech'])->nullable()->after('withdraw_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdraw_histories', function (Blueprint $table) {
            $table->dropColumn('withdrawal_method');
        });
    }
};
