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
        Schema::table('deposit_histories', function (Blueprint $table) {
            $table->enum('payment_method', ['auto', 'manual', 'direct_upi', 'ibr_pay', 'upi_money', 'i_online_pay', 'payment_karo', 'planet_c', 'sonic_pe', 'run_paisa', 'pay_from_upi', 'rudrax_pay', 'pay_o_matix'])->nullable()->after('deposit_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposit_histories', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
